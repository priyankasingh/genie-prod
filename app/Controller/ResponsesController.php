<?php
App::uses('AppController', 'Controller');
/**
 * Responses Controller
 *
 * @property Response $Response
 */
class ResponsesController extends AppController {

	public $uses = array('Response', 'Statement', 'NetworkMember');

	public function beforeFilter(){
		$this->Auth->allow(
			array(
				'add',
				'my_network',
				'questionnaire_setup',
				'questionnaire_page'
			)
		);
		parent::beforeFilter();
	}

	public function beforeRender() {
		parent::beforeRender();
		$params = $this->Session->read('questionnaire.params');
		$this->set(compact('params'));
	}

	public function questionnaire_setup(){
		$this->Session->delete('questionnaire');

		App::uses('Folder', 'Utility');
		$questionnaireViewFolder = new Folder(APP . 'View' . DS . 'Responses');
		$pages = count($questionnaireViewFolder->find('questionnaire_page_.*\.ctp'));
		$this->Session->write('questionnaire.params.pages', $pages);
		$this->Session->write('questionnaire.params.maxProgress', 0);
		$this->redirect(array('action' => 'questionnaire_page', 1));
	}

	public function questionnaire_page($pageNumber){
		$modelToValidate = 'Response';

		// pr($this->request->data);
		// exit;
		// $session = $this->Session->read('questionnaire.data');
		// pr($session);
		/**
		 * Check if a view file for this page exists, otherwise redirect to page 1
		 */
		if (!file_exists(APP . 'View' . DS . 'Responses' . DS . 'questionnaire_page_' . $pageNumber . '.ctp')) {
			$this->redirect('/questionnaire/1');
		}

		/**
		 * Determines the max allowed step (the last completed + 1)
		 * if choosen step is not allowed (URL manually changed) the user gets redirected
		 */
		$maxAllowed = $this->Session->read('questionnaire.params.maxProgress') + 1;
		if ($pageNumber > $maxAllowed) {
		  $this->redirect('/questionnaire/' . $maxAllowed);
		} else {
			$this->Session->write('questionnaire.params.currentPage', $pageNumber);
		}

		/**
     * Check if there's an existing response
     */
		$existingResponse = $this->_existingResponse();

		/**
     * Check if some data has been submitted via POST
     * if not, set the current data to the session data,
     * to automatically populate previously saved fields
     */
		if ($this->request->is('post') || $this->request->is('put')) {

			/**
			 * Reverse geocode postcode to geo coordinates
			 */
			if ($pageNumber == 1) {
				$postcode = $this->request->data['Response']['postcode'];
				if (!empty($postcode)) {
					$coordinates = $this->_getCoordinates($postcode);
					if (is_array($coordinates)) {
						$this->request->data['Response']['lat'] = $coordinates['lat'];
						$this->request->data['Response']['lng'] = $coordinates['lng'];
					}
				}

				if($this->Auth->user('id')){
					unset($this->request->data['User']);
				}

				if (isset($this->request->data['finish'])) {
					$this->_finishQuestionnaire($this->request->data);
				}
			}

			/**
			 * Remove dummy network members
			 */
			if ($pageNumber == 2) {
				if(!empty($this->request->data['NetworkMember'])){
					if(isset($this->request->data['NetworkMember'][-1])) {
						unset($this->request->data['NetworkMember'][-1]);
					}
					foreach($this->request->data['NetworkMember'] as $key => $networkMember) {
						if(empty($networkMember['name']) || !empty($networkMember['dummy_pin'])) {
							unset($this->request->data['NetworkMember'][$key]);
						}
					}
				}

				if ($existingResponse) {

					/**
					 * Any existing network members that have been removed in this step need to be deleted
					 */
					$existingNetworkMembers = Hash::extract($existingResponse['NetworkMember'], '{n}.id');
					if (!empty($existingNetworkMembers)) {
						foreach ($existingNetworkMembers as $key => $value) {
							$existingNetworkMemberRemoved = true;
							foreach ($this->request->data['NetworkMember'] as $keyNetworkMember => $valueNetworkMember) {
								if (isset($valueNetworkMember['id']) && $valueNetworkMember['id'] == $value) {
									$existingNetworkMemberRemoved = false;
								}
							}
							if ($existingNetworkMemberRemoved) {
								unset($existingResponse['NetworkMember'][$key]);
								$this->NetworkMember->delete($value);
							}
						}
					}
					$currentNetworkMembers = Hash::merge((array)$existingResponse['NetworkMember'], $this->request->data['NetworkMember']);
					unset($this->request->data['NetworkMember']);
					$this->Session->write('questionnaire.data.NetworkMember', $currentNetworkMembers);
				} else {
					$this->request->data['NetworkMember'] = array_values($this->request->data['NetworkMember']);
					$this->Session->write('questionnaire.data.NetworkMember', $this->request->data['NetworkMember']);
				}
			}

			/**
			 * Validate top 3 interests
			 */
			if ($pageNumber == 16) {
				$modelToValidate = 'TopInterest';
				$this->loadModel('TopInterest');
				$this->TopInterest->set($this->request->data);
			}

			/**
			 * If existing response add resubmitted pages to session
			 */
			if ($existingResponse) {
				$pagesResubmitted = $this->Session->read('questionnaire.pagesResubmitted');
				$pagesResubmitted[] = $pageNumber;
				$this->Session->write('questionnaire.pagesResubmitted', $pagesResubmitted);
			}

			/**
			 * Merge previous session data with submitted data
			 */
			$prevSessionData = $this->Session->read('questionnaire.data');

			/**
			 * There's a catch with checkboxes
			 * When you uncheck one the data is still in the session and won't ever disappear from there,
			 * as we're only merging session's data with post data.
			 * We need to delete these manually
			 */
			if ($pageNumber == 1) {
				// Delete all user's conditions
				if (!empty($this->request->data['Condition'])) {
					$conditionRemoved = Hash::remove($prevSessionData['Condition'], 'Condition');
					$prevSessionData['Condition'] = $conditionRemoved;
				}
			}

			if ($pageNumber > 2) {
				if (	 isset($this->request->data['ResponseStatement'])
						&& !empty($this->request->data['ResponseStatement'])
					) {
					$statementNumber = $pageNumber - 2;
					if (isset($prevSessionData['ResponseStatement'])) {
						$dataWithCurrentResponseStatementRemoved = Hash::remove($prevSessionData['ResponseStatement'], "$statementNumber.Category");
						$prevSessionData['ResponseStatement'] = $dataWithCurrentResponseStatementRemoved;
					}
					// pr($prevSessionData);
				}
				// Delete network memebers statement
				if (	 isset($this->request->data['NetworkMember'])
						&& !empty($this->request->data['NetworkMember'])
					) {
					$statementNumber = $pageNumber - 2;
					if (isset($prevSessionData) && isset($prevSessionData['NetworkMember'])) {
						$networkMembersWithStatementRemoved = Hash::remove($prevSessionData['NetworkMember'], "{n}.Statement$statementNumber");
						$prevSessionData['NetworkMember'] = $networkMembersWithStatementRemoved;
					}
				}
			}

			if ($pageNumber == 16) {
				// Delete all user's conditions
				if (	 isset($this->request->data['TopInterest'])
						&& !empty($this->request->data['TopInterest'])
					) {
					$prevSessionData['TopInterest'] = array();
				}
			}

			// pr($prevSessionData);
			// exit;
			// pr($this->request->data);

			$currentSessionData = Hash::merge((array)$prevSessionData, $this->request->data);

			// pr($currentSessionData);
			// exit;
			/**
			 * Set passed data to the model, so we can validate against it without saving
			 */

			$this->Response->set($currentSessionData);

			/**
			 * Validate data
			 */
			// pr($currentSessionData);
			// exit;
			if ($this->{$modelToValidate}->validates()) {

				/**
				 * if this is not the last step we replace session data with the new merged array
				 * update the max progress value and redirect to the next step
				 */
				if ($pageNumber < $this->Session->read('questionnaire.params.pages')) {
				  $this->Session->write('questionnaire.data', $currentSessionData);
				  $this->Session->write('questionnaire.params.maxProgress', $pageNumber);
				  $this->redirect(
				  	array(
				  		'action' => 'questionnaire_page',
				  		$pageNumber+1
				  	)
				  );
				} else {

					/**
					 * this is the final step
					 * save to session, if user hasn't provided email address(as it's optional)
					 * the data won't be saved
					 */
					$this->Session->write('questionnaire.data', $currentSessionData);

					/**
					 * clean up network members statements and change to json
					 */
					if (!empty($currentSessionData['NetworkMember'])) {
						foreach ($currentSessionData['NetworkMember'] as $key => $value) {
							for ($i=1; $i <= 13; $i++) {
								$tmpStatement = $value['Statement' . $i];
								$currentSessionData['NetworkMember'][$key]['Statement' . $i] = json_encode($tmpStatement);
							}
						}
					}

					// Save everything
					$fieldList = array(
						'Response' => array(
							'id',
							'name',
							'age',
							'gender',
							'postcode',
							'lat',
							'lng',
							'user_id',
							'network_type',
							'network_type_id'
						),
						'Condition' => array('id'),
						'Category' => array('id'),
						'ResponseStatement' => array(
							'id',
							'weighting',
							'statement_id'
						),
						'NetworkMember' => array(
							'id',
							'name',
							'frequency',
							'network_category_id',
							'diagram_x',
							'diagram_y',
							'other',
							'Statement1',
							'Statement2',
							'Statement3',
							'Statement4',
							'Statement5',
							'Statement6',
							'Statement7',
							'Statement8',
							'Statement9',
							'Statement10',
							'Statement11',
                                                        'Statement12',
                                                        'Statement13'
						),
						'TopInterest' => array(
							'id',
							'response_id',
							'data'
						)
					);

					if(!$this->Auth->user('id')){

						// Add to field list
						$fieldList['User'] = array(
							'email',
							'password'
						);

						// Account creation is optional
						if(	 !empty($currentSessionData['User'])
							&& isset($currentSessionData['User']['email'])
							&& trim($currentSessionData['User']['email']) == ''
						){
							unset($currentSessionData['User']);
						}
						if(!empty($currentSessionData['User'])) {
							$currentSessionData['User']['password'] = '';
						}
					} else {
						// Use existing user
						$currentSessionData['Response']['user_id'] = $this->Auth->user('id');
						unset($currentSessionData['User']);
					}
					// Use existing response
					if($existingResponse) {
						$currentSessionData['Response']['id'] = $existingResponse['Response']['id'];
					}

					// Update network type field
					$currentSessionData['Response']['network_type'] = $this->Response->getNetworkType($currentSessionData);
					$networkType = $this->Response->NetworkType->find('first',
						array(
							'conditions' => array(
								'NetworkType.ruleset' => $currentSessionData['Response']['network_type']
							)
						)
					);
					$currentSessionData['Response']['network_type_id'] = $networkType['NetworkType']['id'];

					// pr($currentSessionData);
					// exit;
				  /**
				   * save the data to the database
				   */
				  // exit;
					if ($this->Response->saveAll(
								$currentSessionData,
								array(
									'fieldList' => $fieldList,
									/**
									 * No need to validate now as each individual step has already been validated
									 */
									'validate' => false
								)
					)) {
						// Remove unmentioned (deleted) network members
						$networkIds = Set::classicExtract($currentSessionData, 'NetworkMember.{n}.id');
						$this->Response->NetworkMember->deleteAll( array(
							'NOT' => array(
								'NetworkMember.id' => $networkIds,
							),
							'NetworkMember.response_id' => $this->Response->id
						));

						// Created account?
						$flashLink = '<a href="/my-network/" class="">You can also view information on your Network Type.</a>';

						if(!$this->Auth->user('id') && $this->Response->User->id){
							// Auto log in
							$user_id = $this->Response->User->id;
							$currentSessionData['User'] = array_merge($currentSessionData['User'], array('id' => $user_id));
							$this->Auth->login($currentSessionData['User']);

							// Send sigup email
							$this->_signupEmail($currentSessionData['User']['email']);

							$this->Session->setFlash(__('Your customised map is below. Your account information will arrive via email - please check your junk mail folder if you don\'t see it. '. $flashLink));
						} else {
							$this->Session->setFlash(__('Your responses have been saved. ' . $flashLink));
						}

						// Response Saved - store in session
						$this->Session->write('response', $this->Response->id);

						// Now update the top 3 interests
						$savedResponse = $this->Response->find('first',
							array(
								'conditions' => array('Response.id' => $this->Response->id),
								'contain' => array(
									'ResponseStatement' => array(
										'conditions' => array( array('ResponseStatement.weighting >' => 0 ) ),
										'fields' => array('id', 'weighting', 'statement_id'),
										'order' => array( 'ResponseStatement.weighting' => 'DESC' ),
										'Category' => array(
											'fields' => array('id', 'name', 'slug', 'parent_id'),
										),
									),
								)
							)
						);
						foreach ($savedResponse['ResponseStatement'] as $responseKey => $response) {
							foreach ($response['Category'] as $categoryKey => $category) {
								if (in_array($category['CategoriesResponsesStatement']['category_id'], $this->request->data['TopInterest']['data'])) {
									$savedResponse['ResponseStatement'][$responseKey]['Category'][$categoryKey]['CategoriesResponsesStatement']['top_interest'] = 1;
								} else {
									$savedResponse['ResponseStatement'][$responseKey]['Category'][$categoryKey]['CategoriesResponsesStatement']['top_interest'] = 0;
								}
							}
						}

						if ($this->Response->saveAssociated($savedResponse)) {
							$this->redirect(
								array(
									'controller' => 'services',
									'action' => 'index',
									'my-map'
								)
							);
						}

					}
				}
			} else {
				/**
				 * Editing current response
				 */
				if($existingResponse){
					$this->request->data = $existingResponse;

					/**
					 * After a page has been POST'ed we want to show new data
					 */
					$pagesResubmitted = $this->Session->read('questionnaire.pagesResubmitted');
					if (is_array($pagesResubmitted) && in_array($pageNumber, $pagesResubmitted)) {
						$this->request->data = $this->Session->read('questionnaire.data');

						$this->request->data['Response'] = $this->Session->read('questionnaire.data.Response');
					}

					/**
					 * After 2nd page has been POST'ed we need to show updated network members
					 */
					if (is_array($pagesResubmitted) && in_array(2, $pagesResubmitted)) {
						$this->request->data['NetworkMember'] = $this->Session->read('questionnaire.data.NetworkMember');
					}
				} else {
					$this->request->data = $this->Session->read('questionnaire.data');
				}
			}
		} else {

			/**
			 * Editing current response
			 */
			if($existingResponse){
				$this->request->data = $existingResponse;

				/**
				 * After a page has been POST'ed we want to show new data
				 */
				$pagesResubmitted = $this->Session->read('questionnaire.pagesResubmitted');
				if (is_array($pagesResubmitted) && in_array($pageNumber, $pagesResubmitted)) {
					$this->request->data = $this->Session->read('questionnaire.data');
				}

				$questionnaireDataResponse = $this->Session->read('questionnaire.data.Response');
				if (!empty($questionnaireDataResponse)) {
					$this->request->data['Response'] = $this->Session->read('questionnaire.data.Response');
				}

				/**
				 * After 2nd page has been POST'ed we need to show updated network members
				 */
				if (is_array($pagesResubmitted) && in_array(2, $pagesResubmitted)) {
					$this->request->data['NetworkMember'] = $this->Session->read('questionnaire.data.NetworkMember');
				}
			} else {
				$this->request->data = $this->Session->read('questionnaire.data');
			}

		}

		/**
		 * Data for questionnaire_page_1
		 */
		if ($pageNumber == 1) {
			$conditions = $this->Response->Condition->find('list');
			$this->set(compact('conditions'));
		}

		/**
		 * Data for questionnaire_page_2
		 */
		if ($pageNumber == 2) {
			$frequencies = $this->Response->NetworkMember->getFrequencies();
			$this->set(compact('frequencies'));

			$this->loadModel('NetworkCategory');
			$networkCategories = $this->NetworkCategory->find('all',
				array(
					'conditions' => array('NetworkCategory.parent_id <>' => null),
					'contain' => array(
						'ParentNetworkCategory',
						'ChildNetworkCategory'
					)
				)
			);

			$networkCategories = Set::combine($networkCategories, '{n}.NetworkCategory.id', '{n}');
			$this->set(compact('networkCategories'));

			$parentNetworkCategories = $this->Response->NetworkMember->NetworkCategory->find('list',
				array(
					'conditions' => array('NetworkCategory.parent_id' => null),
				)
			);
			$this->set(compact('parentNetworkCategories'));
		}


		/**
		 * Prepare for pages 3+ (Statement questions)
		 */
		$this->set('statementCount', count($this->Statement->find('all')));
		$this->set('statement', $this->Statement->getStatement($pageNumber - 2));
		$this->set(compact('pageNumber'));

		if ($pageNumber == 16) {
			// This needs to be based on the information from the session
			$data = $this->Session->read('questionnaire.data');

			// Sort definites first
			$weigting_2 = array();
			$weigting_1 = array();
			foreach ($data['ResponseStatement'] as $key => $value) {
				switch ($value['weighting']) {
					case '2':
						$weigting_2[] = $data['ResponseStatement'][$key];
						break;
					case '1':
						$weigting_1[] = $data['ResponseStatement'][$key];
						break;
				}
			}

			$responsesSortedByWeighting = array_merge($weigting_2, $weigting_1);
			$this->request->data['ResponseStatement'] = $responsesSortedByWeighting;

			// Now get Category data
			$this->loadModel('Category');
			$categories = $this->Category->find('all',
				array(
					'contain' => false
				)
			);
			$this->set(compact('categories'));
		}

		/**
		 * Set some other variables
		 */
		$this->set('title_for_layout', 'Questionnaire');
		$this->set('active_nav', 'home');

		if ($existingResponse && !empty($existingResponse)) {
			$this->set('existingResponse', true);
		}
		/**
		 * Render correct view file
		 */
		$this->render('questionnaire_page_'.$pageNumber);
	}

	public function add( $id = null ){

		// Header response
		$this->response->header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->response->header("Pragma: no-cache");

		// Fetch the existing response if we have it
		$existingResponse = $this->_existingResponse();
		// pr($existingResponse);
		$this->set('existingResponse', $existingResponse);
		// pr($existingResponse);
		// Save/validate data?
		if ($this->request->is('post')) {

			// pr($this->request->data);

			// No dummy network members
			if( !empty( $this->request->data['NetworkMember'] ) ){
				if( isset( $this->request->data['NetworkMember'][-1] ) ) {
					unset( $this->request->data['NetworkMember'][-1] );
				}
				foreach( $this->request->data['NetworkMember'] as $key => $networkMember ) {
					if( empty( $networkMember['name'] ) || !empty( $networkMember['dummy_pin'] ) ) {
						unset( $this->request->data['NetworkMember'][$key] );
					}
				}
			}

			// clean up network members interests and change to json
			if (!empty($this->request->data['NetworkMember'])) {
				foreach ($this->request->data['NetworkMember'] as $key => $value) {
					$tmpInterests = array();
					foreach ($value['Interests'] as $key2 => $interest) {
						if (!empty($interest)) {
							$tmpInterests[] = $interest;
						}
					}
					$this->request->data['NetworkMember'][$key]['Interests'] = json_encode($tmpInterests);
				}
			}

			// Save everything
			$fieldList = array(
				'Response' => array( 'id','name','age','gender','postcode','lat','lng','user_id','network_type','network_type_id' ),
				'Condition' => array('id'),
				'Category' => array('id'),
				'ResponseStatement' => array('id','weighting','statement_id'),
				'NetworkMember' => array('id','name','frequency','network_category_id','diagram_x','diagram_y','other', 'Interests'),
			);
			if( !$this->Auth->user('id') ){
				// Prepare to save new user
				$fieldList['User'] = array( 'email', 'password' );

				// Account creation is optional
				if( !empty($this->request->data['User']) && isset($this->request->data['User']['email']) && trim($this->request->data['User']['email']) == '' ){
					unset( $this->request->data['User'] );
				}
				if( !empty($this->request->data['User']) ) $this->request->data['User']['password'] = '';
			} else {
				// Use existing user
				$this->request->data['Response']['user_id'] = $this->Auth->user('id');
				unset( $this->request->data['User'] );
			}
			// Use existing response
			if($existingResponse) $this->request->data['Response']['id'] = $existingResponse['Response']['id'];

			// Update network type field
			$this->request->data['Response']['network_type'] = $this->Response->getNetworkType( $this->request->data );
			$networkType = $this->Response->NetworkType->find('first', array('conditions'=>array('NetworkType.ruleset' => $this->request->data['Response']['network_type'] )) );
			$this->request->data['Response']['network_type_id'] = $networkType['NetworkType']['id'];

			if( $this->Response->saveAll($this->request->data, array('fieldList' => $fieldList))){
				// Remove unmentioned (deleted) network members
				$networkIds = Set::classicExtract($this->request->data, 'NetworkMember.{n}.id');
				$this->Response->NetworkMember->deleteAll( array(
					'NOT' => array(
						'NetworkMember.id' => $networkIds,
					),
					'NetworkMember.response_id' => $this->Response->id
				));

				// Created account?
				$flashLink = '<a href="/my-network/" class="">You can also view information on your Network Type.</a>';

				if( !$this->Auth->user('id') && $this->Response->User->id ){
					// Auto log in
					$user_id = $this->Response->User->id;
					$this->request->data['User'] = array_merge($this->request->data['User'], array('id' => $user_id));
					$this->Auth->login($this->request->data['User']);

					// Send sigup email
					$this->_signupEmail();

					$this->Session->setFlash(__('Your customised map is below. Your account information will arrive via email - please check your junk mail folder if you don\'t see it. '. $flashLink));
				} else {
					$this->Session->setFlash(__('Your responses have been saved. ' . $flashLink));
				}

				// Response Saved - store in session
				$this->Session->write('response', $this->Response->id );
				$this->redirect(array('controller'=>'services', 'action' => 'index', 'my-map'));
			} else {
				$this->Session->setFlash(__('Please check your entry below - some fields aren\'t right yet. Don\'t worry, we\'ve kept all your answers.'));
			}
		} else {
			// Editing?
			if( $existingResponse ){
				$this->request->data = $existingResponse;
				// pr($this->request->data);
				// exit;
			}
		}

		// Prepare view
		$this->loadModel('Statement');

		$statements = $this->Statement->find( 'all', array(
			'order'=>array( 'Statement.order' => 'ASC' ),
			'contain'=>array('Category'),
		) );

		$conditions = $this->Response->Condition->find( 'list' );
		$frequencies = $this->Response->NetworkMember->getFrequencies();
		$networkCategories = $this->Response->NetworkMember->NetworkCategory->find('all', array(
			'conditions'=>array('NetworkCategory.parent_id <>'=>null),
		));
		$networkCategories = Set::combine( $networkCategories, '{n}.NetworkCategory.id', '{n}');

		$parentNetworkCategories = $this->Response->NetworkMember->NetworkCategory->find('list', array(
			'conditions'=>array('NetworkCategory.parent_id'=>null),
		));

		$this->set('editing', $existingResponse?true:false );
		$this->set(compact('conditions','statements','frequencies','networkCategories','parentNetworkCategories'));

		$this->set('title_for_layout', 'Questionnaire');
		$this->set('active_nav', 'home');

	}


	public function my_network(){
		// Has response?
		$response_id = $this->Session->read( 'response' );
		$response = $response_id ? $this->Response->find('first', array(
			'conditions'=>array('Response.id' => $response_id ),
			'contain' => array(
				'NetworkMember' => array(
					'fields' => array('id','name','frequency','network_category_id','diagram_x','diagram_y','other'),
					'NetworkCategory' => array(
						'fields' => array('id','name','parent_id'),
					),
				),

			),
		)) : false;

		if(!$response){
			$this->Session->setFlash(__('Please fill in the questionnaire to get your personalised network feedback.'));
			$this->redirect(
				array(
					'controller' => 'responses',
					'action' => 'questionnaire_setup',
				)
			);
		}

		$this->loadModel('NetworkType');
		$network_type = $this->Response->NetworkType->find('first',
			array(
				'conditions' => array(
					'NetworkType.id' => $response['Response']['network_type_id']
				),
				'contain' => array()
			)
		);
		// pr($network_type);
		// exit;

		// Set view vars
		$parentNetworkCategories = $this->Response->NetworkMember->NetworkCategory->find('list', array(
			'conditions'=>array('NetworkCategory.parent_id'=>null),
		));

		$this->set( compact('response', 'parentNetworkCategories', 'network_type' ));
		$this->set('scores', $this->Response->getScoreBreakDown( $response, true ));

		$this->set('title_for_layout', 'My Network');
		$this->set('active_nav', 'my_network');
	}

	protected function _getCoordinates($postcode){
		// Get lat / lon
		if ($xml = simplexml_load_file('https://maps.googleapis.com/maps/api/geocode/xml?sensor=false&region=uk&address=' . $postcode)){
			if ($xml->status == 'OK'){
				$lat = (array) $xml->result->geometry->location->lat;
				$lng = (array) $xml->result->geometry->location->lng;

				$coordinates = array();
				$coordinates['lat'] = $lat[0];
				$coordinates['lng'] = $lng[0];

				return $coordinates;
			} else {
				return false;
			}
		}
	}

	protected function _finishQuestionnaire($data){

		$this->loadModel('ConditionsResponse');
		$this->ConditionsResponse->deleteAll(array('response_id' => $data['Response']['id']), false);

		$fieldList = array(
			'Response' => array(
				'id',
				'name',
				'age',
				'gender',
				'postcode',
				'lat',
				'lng'
			),
			'Condition' => array('id')
		);

		if ($this->Response->saveAll($this->request->data,
			array(
				'fieldList' => $fieldList,
				'validate' => true
			)
		)) {
			$this->Session->setFlash(__('Your details have been updated.'));

			$this->redirect(
				array(
					'controller' => 'services',
					'action' => 'index',
					'my-map'
				)
			);
		}

		$this->redirect(
			array(
				'controller' => 'responses',
				'action' => 'questionnaire_setup'
			)
		);

	}

	protected function _existingResponse( ){
		$response_id = $this->Session->read('response');

		$response = $response_id ? $this->Response->find('first', array(
			'conditions'=>array('Response.id'=>$response_id ),
			'contain' => array(
				'ResponseStatement' => array(
					'fields' => array('id','weighting','response_id','statement_id'),
					'order' => 'ResponseStatement.id ASC',
					'Category' => array(
						'fields' => array('id','name'),
					),
				),
				'Condition' => array(
					'fields' => array('id'),
				),
				'User' => array(
					'fields' => array('id','email'),
				),
				'NetworkMember' => array(
					'fields' => array(
						'id',
						'name',
						'frequency',
						'network_category_id',
						'diagram_x',
						'diagram_y',
						'other',
						'Statement1',
						'Statement2',
						'Statement3',
						'Statement4',
						'Statement5',
						'Statement6',
						'Statement7',
						'Statement8',
						'Statement9',
						'Statement10',
						'Statement11',
                                                'Statement12',
                                                'Statement13'
					),
					'NetworkCategory' => array(
						'fields' => array(
							'id',
							'name',
							'parent_id'
						),
					),
				),
				'TopInterest' => array(
					'fields' => array(
						'id',
						'response_id',
						'data'
					)
				)
			),

		)) : false;

		// Process ResponseStatements so that they are indexed by Statement ID (VITAL FOR VIEW FUNCTIONALITY)
		if( $response && !empty( $response['ResponseStatement'] ) ){
			$response['ResponseStatement'] = Set::combine( $response['ResponseStatement'], '{n}.statement_id', '{n}');
			// Switch category key to IDs only (FormHelper fails otherwise)
			foreach( $response['ResponseStatement'] as &$responseStatement ){
				if( !empty( $responseStatement['Category'] ) ){
					$responseStatement['Category'] = Set::classicExtract( $responseStatement['Category'], '{n}.id');
				}
			}
		}

		/**
		 * json_decode network member statements
		 */
		if (!empty($response['NetworkMember'])) {
			foreach ($response['NetworkMember'] as $key => $value) {
				for ($i=1; $i <= 13; $i++) {
					$tmpStatement = $value['Statement' . $i];
					$response['NetworkMember'][$key]['Statement' . $i] = json_decode($tmpStatement);
				}
			}
		}

		// Done
		return $response;

	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Response->recursive = 0;
		$this->set('responses', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Response->exists($id)) {
			throw new NotFoundException(__('Invalid response'));
		}
		$options = array(
			'conditions' => array('Response.' . $this->Response->primaryKey => $id),
			'contain'=>array('ResponseStatement'=>array('Statement'),'Condition','User','NetworkMember'=>array('NetworkCategory')),
		);
		$this->set('response', $this->Response->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Response->create();
			if ($this->Response->save($this->request->data)) {
				$this->Session->setFlash(__('The response has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The response could not be saved. Please, try again.'));
			}
		}
		$users = $this->Response->User->find('list');
		$this->set(compact('users', 'statements'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Response->exists($id)) {
			throw new NotFoundException(__('Invalid response'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Response->save($this->request->data)) {
				$this->Session->setFlash(__('The response has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The response could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Response.' . $this->Response->primaryKey => $id));
			$this->request->data = $this->Response->find('first', $options);
		}
		$users = $this->Response->User->find('list');
		$this->set(compact('users', 'statements'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Response->id = $id;
		if (!$this->Response->exists()) {
			throw new NotFoundException(__('Invalid response'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Response->delete()) {
			$this->Session->setFlash(__('Response deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Response was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
