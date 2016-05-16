<?php
App::uses('AppController', 'Controller');
/**
 * Responses Controller
 *
 * @property Response $Response
 */
class ResponsesController extends AppController {

	function beforeFilter(){
		$this->Auth->allow( array( 'add', 'my_network' ) );
		parent::beforeFilter();
	}

	public function add( $id = null ){
	
		// Header response
		$this->response->header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->response->header("Pragma: no-cache");
		
		// Fetch the existing response if we have it
		$existingResponse = $this->_existingResponse();
		$this->set('existingResponse', $existingResponse);
		
		// Save/validate data?
		if ($this->request->is('post')) {
			// No dummy network members
			if( !empty( $this->request->data['NetworkMember'] ) ){
				if( isset( $this->request->data['NetworkMember'][-1] ) )
					unset( $this->request->data['NetworkMember'][-1] );
				foreach( $this->request->data['NetworkMember'] as $key => $networkMember )
					if( empty( $networkMember['name'] ) || !empty( $networkMember['dummy_pin'] ) )
						unset( $this->request->data['NetworkMember'][$key] );
			}
			
			// Save everything
			$fieldList = array(
				'Response' => array( 'id','name','age','gender','postcode','lat','lng','user_id','network_type','network_type_id' ),
				'Condition' => array('id'),
				'Category' => array('id'),
				'ResponseStatement' => array('id','weighting','statement_id'),
				'NetworkMember' => array('id','name','frequency','network_category_id','diagram_x','diagram_y','other'),
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
					'NetworkMember.response_id'=>$this->Response->id
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
					$this->Session->setFlash(__('Your responses have been saved. '.$flashLink));
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
			'conditions'=>array('Response.id'=>$response_id ),
			'contain' => array(
				'NetworkMember' => array(
					'fields' => array('id','name','frequency','network_category_id','diagram_x','diagram_y','other'),
					'NetworkCategory' => array( 
						'fields' => array('id','name','parent_id'),
					),
				),
				
			),
		)) : false;	
		
		if( !$response ){
			$this->Session->setFlash(__('Please fill in the questionnaire to get your personalised network feedback.'));
			$this->redirect(array('controller'=>'responses', 'action' => 'add', '#'=>'questionnaire'));
		}

		$network_type = $this->Response->NetworkType->find('first', array(
			'conditions' => array( 'NetworkType.id', $response['Response']['network_type_id'] ),
		));
	
		// Set view vars
		$parentNetworkCategories = $this->Response->NetworkMember->NetworkCategory->find('list', array(
			'conditions'=>array('NetworkCategory.parent_id'=>null),
		));
		
		$this->set( compact('response', 'parentNetworkCategories', 'network_type' ));
		$this->set('scores', $this->Response->getScoreBreakDown( $response, true ));
		
		$this->set('title_for_layout', 'My Network');
		$this->set('active_nav', 'my_network');
	}

	protected function _existingResponse( ){
		$response_id = $this->Session->read( 'response' );
	
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
					'fields' => array('id','name','frequency','network_category_id','diagram_x','diagram_y','other'),
					'NetworkCategory' => array( 
						'fields' => array('id','name','parent_id'),
					),
				),
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
