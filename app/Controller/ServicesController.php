<?php
App::uses('AppController', 'Controller');
/**
 * Services Controller
 *
 * @property Service $Service
 */
class ServicesController extends AppController {

	public $helpers = array('OHPinMap');
	public $components = array('RequestHandler','Twitter.Twitter', 'Paginator');

	function beforeFilter(){
		$this->Auth->allow(array('index', 'view', 'my-map', 'availability'));
		parent::beforeFilter();
	}

	public function isAuthorized($user = null) {
		// Only users with a response can use the map
		if( $this->action == 'view' && !$this->Session->read( 'response' ) ){
			return false;
		}
		return parent::isAuthorized($user);
	}

	public function connect(){
		$this->Twitter->setupApp('YOUR_CONSUMER_KEY', 'YOUR_CONSUMER_SECRET');
		$this->Twitter->connectApp('YOUR_CALLBAK_URL');
	}

	public function twitter(){
		debug($this->Twitter->accountVerifyCredentials());
		debug($this->Twitter->userTimeline(""));
		die();
	}

	public function view($selected_parent_slug = null, $selected_category_slug = null, $selected_service_slug = null){

		if($selected_service_slug){
			$selected_service_id = $this->Service->getIdFromSlug($selected_service_slug);
		}
		if($selected_category_slug){
			$selected_category_id = $this->Service->Category->getIdFromSlug($selected_category_slug);
		}
		//$results = $this->Service->find('all',array('conditions'=>array('slug =' => $selected_service_slug)));
		$results = $this->Service->find('all',array('conditions'=>array('slug =' => $selected_service_slug), 'contain' => 'Video'));
		$service = $results[0];
		$selected_parent_id = $service['Category'][0]['parent_id'];

		$sub_category_list = $this->Service->Category->getChildrenOfCategoryWithId($selected_parent_id);

		//Get list of categories
		$categories = $this->Service->Category->find('list');

		if(isset($service['Service']['twitter']) && $service['Service']['twitter'] != ''){
			$twitter = $this->_getTweetsWithUsername($service['Service']['twitter']);
			//echo "HELLO";
			//print_r($twitter);die();
		}
		//debug($service['Service']['twitter']);
		//debug($twitter);

/*		if($this->RequestHandler->isAjax()){
			Configure::write('debug',0);
			$this->autoRender = false;
			$this->layout = 'ajax';
			$this->render('index_ajax');
		}
		*/

		$this->set(compact('categories','selected_parent_id','service','selected_parent_slug','sub_category_list','selected_category_id','twitter'));

		$this->autoRender = false;
		$this->layout = 'default';
		$this->render('index');
	}


	public function index($selected_parent_slug = null, $selected_category_slug = null, $selected_service_slug = null){

		// Get network members
		// Has response?
		$this->loadModel('Response');
		$response_id = $this->Session->read( 'response' );
		$response = $response_id ? $this->Response->find('first',
			array(
				'conditions' => array('Response.id' => $response_id ),
				'contain' => array(
					'NetworkMember'
				),
			)) : false;

		// pr($network_members);
		$this->set('network_members', $response);


		if($selected_service_slug){
			// VIEW INDIVIDUAL SERVICE
			$selected_service_id = $this->Service->getIdFromSlug($selected_service_slug);
			$this->setAction('view', $selected_parent_slug, $selected_category_slug, $selected_service_slug);
		}

		$conditions = array();
		$joins = array();

		$selected_parent_id = null;
		$selected_category_id = null;

		$geo_permitted = true;

		// MY PLANS
		if( $selected_parent_slug == 'my-map' ){
			// Get list of categories
			$sub_category_list = $this->_response_categories();
			// pr($sub_category_list);

			if( !$sub_category_list ){
				if($this->Session->read('response')){
					$this->Session->setFlash(__('You have not answered yes to any survey questions. To get your personalised map you must answer yes to some questions'));
				}
				else{
					$this->Session->setFlash(__('Please fill in the questionnaire to get your personalised map.'));
				}
				$this->redirect(
					array(
						'controller' => 'responses',
						'action' => 'questionnaire_setup'
					)
				);
			}

			// Set gender and age conditions
			$conditions = array_merge( $conditions, $this->_response_age_gender_conditions() );

			// Set query and view vars
			$this->set('personalised', true);
			$this->set('active_nav','my_plans');

			if($selected_category_slug){
				if(strpos($selected_category_slug, ",")){
					$selected_category_slug = rtrim($selected_category_slug, ",");
					$selected_category_slug = explode(",", $selected_category_slug);

					$i = 0;
					$category_array = array();
					foreach($selected_category_slug as $slug){
						$category_array[$i] = $this->Service->Category->getIdFromSlug($slug);
						$i++;
					}
					$selected_category_id = $category_array;
				}
				else{
					$selected_category_id = $this->Service->Category->getIdFromSlug($selected_category_slug);
				}
			} else {
				$selected_category_id = $this->_extract_ids( $sub_category_list, 'Category' );
				$this->set("all_id", true);
			}


		} elseif( $selected_parent_slug == 'favourites' ){
		// FAVOURITES
			// Logged in only
			if( !$this->Auth->user('id') ){
				$this->Session->setFlash(__('Please log in to view favourites.'));
				$this->redirect(array('controller'=>'users', 'action' => 'login'));
			}

			// Adjust query
			$geo_permitted = false;

			$this->loadModel('Favourite');
			$faves = $this->Favourite->find('all', array(
				'conditions'=>array(
					'Favourite.user_id' => $this->Auth->user('id'),
					'Favourite.deleted' => null,
				),
			));
			$faveIDs = array();
			foreach( $faves as $fave ) $faveIDs[] = $fave['Favourite']['service_id'];

			$conditions['Service.id'] = $faveIDs;

			// Set query and view vars
			$this->set('favourites', true);
		} else {
		// NORMAL CATEGORIES
			if($selected_parent_slug){
				$selected_parent_id = $this->Service->Category->getIdFromSlug($selected_parent_slug);
			}
			if($selected_category_slug){
				$selected_category_id = $this->Service->Category->getIdFromSlug($selected_category_slug);
			}
		}

		// pr($sub_category_list);

		if($selected_parent_id || $selected_category_id){
			if($selected_parent_id){
				$conditions['Category.parent_id'] = $selected_parent_id;
				$sub_category_list = $this->Service->Category->getChildrenOfCategoryWithId($selected_parent_id);

				$this->set( 'parent_category', $this->Service->Category->read(array('id','name','description'), $selected_parent_id) );
			}
			$joins = array(
				array(
					'table'=>'categories_services',
					'alias'=>'CategoriesServices',
					'type'=>'inner',
					'conditions'=>array(
						'Service.id = CategoriesServices.service_id',
					),
				),
				array(
					'table'=>'categories',
					'alias'=>'Category',
					'type'=>'inner',
					'conditions'=>array(
						'CategoriesServices.category_id = Category.id',
					),
				),
			);
			if($selected_category_id){
				$conditions['Category.id'] = $selected_category_id;
			}
		}

		// GEO
		if($this->request->query('miles')){
			$miles = $this->request->query('miles');
		}else{
			$miles = 5;
		}

		// if( $selected_parent_slug == 'my-map' ){
		// 	$miles = 1;
		// }

		$postcode = $this->request->query('postcode');
		$latitude = $this->request->query('latitude');
		$longitude = $this->request->query('longitude');

		if( !$postcode ){
			$geo = $this->_response_geo();
			$postcode = $geo['Response']['postcode'];
			$latitude = $geo['Response']['lat'];
			$longitude = $geo['Response']['lng'];
		}

		if($latitude && $longitude && $geo_permitted){
	    //Lat and lng change per mile at 53 degrees latitude (http://www.csgnetwork.com/degreelenllavcalc.html)
	    //Don't go international with this
	    $lat_mile = 0.014461316;
			$lng_mile = 0.023969319;

			$conditions['lat >'] = (float)$latitude - $lat_mile*$miles;
			$conditions['lat <'] = (float)$latitude + $lat_mile*$miles;
			$conditions['lng >'] = (float)$longitude - $lng_mile*$miles;
			$conditions['lng <'] = (float)$longitude + $lng_mile*$miles;
		}

		// KEYWORD SEARCH
		if( $search = $this->request->query('search') ){
			$searchBits = explode( ' ', $search );

			foreach( $searchBits as &$searchBit ){
				$conditions['OR'][] = array( 'Service.name LIKE' => '%'.$searchBit.'%' );
				$conditions['OR'][] = array( 'Service.description LIKE' => '%'.$searchBit.'%' );
			}
		}

		// Language
		$conditions['lang'] = Configure::read('Config.language');

		//Get services and split into parent categories
		$paginateArgs = array(
			'limit' => 10,
			'contain' => array(
				'Category' => array(
					'fields' => array('name','parent_id','slug'),
					'ParentCategory' => array( 'fields' => array('name','slug'), ),
				),
				'Video'
			),
			'joins' => $joins,
			'conditions' => $conditions,
		);

		// Order by category? (Used for My PLANS weightings)
		if( is_array( $selected_category_id ) ){
			$paginateArgs['order'] = 'FIELD(Category.id, '.implode( ',', $selected_category_id ).')';
		}

		// Get favourite status
		if( $this->Auth->user('id') ){
			$paginateArgs['contain']['Favourite'] = array(
				'fields' => array('Favourite.id','Favourite.user_id','Favourite.service_id'),
				'conditions' => array(
					'Favourite.user_id' => $this->Auth->user('id'),
					'Favourite.deleted' => null
				),
			);
		}

		// pr($paginateArgs);
		// exit;
		$this->paginate = $paginateArgs;

		$topInterests = $this->_get_top_response_categories();

		// Override results with top 3
		// Get 3 closest services based on the top 3 interests
		if (	 $selected_parent_slug == 'my-map'
				&& empty($selected_category_slug)
				&& empty($selected_service_slug)
				&& empty($this->request->query)
				&& count($topInterests) > 0
			) {

			// Increase it to 2 miles
			$miles = 2;
			$lat_mile = 0.014461316;
			$lng_mile = 0.023969319;

			$conditions['lat >'] = (float)$latitude - $lat_mile*$miles;
			$conditions['lat <'] = (float)$latitude + $lat_mile*$miles;
			$conditions['lng >'] = (float)$longitude - $lng_mile*$miles;
			$conditions['lng <'] = (float)$longitude + $lng_mile*$miles;

			$topServices = array();

			$reponseCoordinates = array();
			$reponseCoordinates['lat'] = $response['Response']['lat'];
			$reponseCoordinates['lng'] = $response['Response']['lng'];
			// pr($reponseCoordinates);

			// pr($this->paginate);
			// pr($joins);
			// exit;
			$topServiceId = array();
			$results = array();
			foreach ($topInterests as $key => $value) {
				$query = $this->Service->distanceQuery($reponseCoordinates);
				unset($conditions['Category.id']);
				$query['conditions'] = $conditions;
				$query['conditions']['lat !='] = NULL;
				$query['conditions']['lng !='] = NULL;
				if (!empty($topServiceId)) {
					$query['conditions']['NOT']['Service.id'] = $topServiceId;
				}
				$query['contain'] = $this->paginate['contain'];
				$query['conditions']['Category.id'] = $value;
				$query['joins'] = $joins;
				$query['fields'][] = 'Service.id';
				$query['limit'] = 3;

				// pr($query);
				// exit;
				// exit;
				$res = $this->Service->find('all', $query);

				foreach ($res as $resKey => $resValue) {
					$service = $this->Service->find('first',
						array(
							'conditions' => array(
								'Service.id' => $resValue['Service']['id']
							),
							'contain' => array()
						)
					);
					$resValue['Service'] = $service['Service'];

					$topServiceId[] = $resValue['Service']['id'];
					$results[] = $resValue;
				}
			}

			$this->set('top_three', true);
		} else {
			 // Default pagination if not top 3
			$results = null;
			try {
				$results = $this->paginate();
			}catch(NotFoundException $e){
				//$this->request->params['paging']['Service']['prevPage'] = true;
				//$this->request->params['paging']['Service']['current'] = $this->request->params['paging']['Service']['options']['page'];
				//debug($this->request->params['paging']);//die();
				//$this->request->params['paging']['Service']['page'] = 0;
				//$results = $this->paginate();
			}
		}
		// pr($results);

		// Load Twitter stuff
		// Go through results and put in parent categories
		$parents = array();
		if($results){
			foreach($results as $result){
				$parent_id = $result['Category'][0]['parent_id'];

				//If parent doesn't exist yet, create it
				if(!isset($parents[$parent_id])){
					$parents[$parent_id] = array();
				}

				//Find tweets
				if(isset($result['Service']['twitter']) && $result['Service']['twitter'] != ''){
					$twitter = $this->_getTweetsWithUsername($result['Service']['twitter']);
					//debug($twitter); die();
					$result['Twitter'] = $twitter;
				}
				//Add result to parents array
				$parents[$parent_id][] = $result;
			}
		}

		// Get list of categories
		$categories = $this->Service->Category->find('list');
		// pr($categories);

		$this->set('hasResponse', $this->Session->read( 'response' ));
		$this->set(compact('parents','postcode','categories','miles','selected_parent_id','selected_category_id',
							'sub_category_list','selected_parent_slug','longitude','latitude'));

		if($this->RequestHandler->isAjax()){
			Configure::write('debug',0);
			$this->autoRender = false;
			$this->layout = 'ajax';
			$this->render('index_ajax');
		}
	}

	public function availability(){
		$joins = array(
			array(
				'table'=>'categories_services',
				'alias'=>'CategoriesServices',
				'type'=>'inner',
				'conditions'=>array(
					'Service.id = CategoriesServices.service_id',
				),
			),
			array(
				'table'=>'categories',
				'alias'=>'Category',
				'type'=>'inner',
				'conditions'=>array(
					'CategoriesServices.category_id = Category.id',
				),
			),
		);

		if ($this->request->is('post')) {
			$servicesInLocation = array();

			foreach ($this->request->data['Location'] as $key => $value) {
				if (empty($value['name'])) {
					unset($this->request->data['Location'][$key]);
					continue;
				}

				// Get coordinates of that locations
				$location = $value['name'];
				$coordinates = $this->_getCoordinates($location);
				if (is_array($coordinates)) {
					$value['latitude'] = $coordinates['lat'];
					$value['longitude'] = $coordinates['lng'];
				}

				if($value['latitude'] && $value['longitude']){
					// Lat and lng change per mile at 53 degrees latitude (http://www.csgnetwork.com/degreelenllavcalc.html)
					$lat_mile = 0.014461316;
					$lng_mile = 0.023969319;

					$conditions['lat >'] = (float)$value['latitude'] - $lat_mile*$value['radius'];
					$conditions['lat <'] = (float)$value['latitude'] + $lat_mile*$value['radius'];
					$conditions['lng >'] = (float)$value['longitude'] - $lng_mile*$value['radius'];
					$conditions['lng <'] = (float)$value['longitude'] + $lng_mile*$value['radius'];
				}

				$conditions['lang'] = Configure::read('Config.language');

				// $conditions['Category.id'] = $this->request->data['Service']['Category'];
				if (	!empty($this->request->data['Service']['category'])
						&& empty($this->request->data['Service']['keyword'])
					) {
					$this->loadModel('Category');
					$parentCategories = $this->Category->find('list',
						array(
							'conditions' => array(
								'Category.parent_id' => NULL
							),
							'contain' => array()
						)
					);

					$queryParentCategories = array();
					$queryCategories = array();

					foreach ($this->request->data['Service']['category'] as $categoryKey => $categoryValue) {
						if (array_key_exists($categoryValue, $parentCategories)) {
							$queryParentCategories[] = $categoryValue;
						} else {
							$queryCategories[] = $categoryValue;
						}
					}

					if (!empty($queryParentCategories)) {
						$conditions['OR']['Category.parent_id'] = $queryParentCategories;
					}
					if (!empty($queryCategories)) {
						$conditions['OR']['Category.id'] = $queryCategories;
					}
				}

				// pr($this->request->data);
				// Search by keyword overrides categories
				if (!empty($this->request->data['Service']['keyword'])) {
					$conditions['OR'][] = array('Service.name LIKE' => '%' . $this->request->data['Service']['keyword'] . '%');
				}

				$query = array(
					'limit' => 1000,
					'contain' => array(
						'Category' => array(
							'fields' => array(
								'name',
								'parent_id',
								'slug'
							),
							'ParentCategory' => array(
								'fields' => array(
									'name',
									'slug'
								)
							)
						),
						'Video'
					),
					'joins' => $joins,
					'conditions' => $conditions,
				);

				// pr($query);
				// exit;
				$this->paginate = $query;

				$servicesInLocation[$key]['Location'] = $value;

				$services = $this->paginate();
				// pr($services);

				// remove dupes
				$servicesIDs = array();
				foreach ($services as $serviceKey => $service) {
					if (in_array($service['Service']['id'], $servicesIDs)) {
						unset($services[$serviceKey]);
					} else {
						$servicesIDs[] = $service['Service']['id'];
					}
				}
				// pr($services);
				$servicesInLocation[$key]['Services'] = $services;

			}
			// pr($servicesInLocation);

			$this->Session->write('ServicesAvailability.Post', $this->request->data);
			$this->Session->write('ServicesAvailability.Data', $servicesInLocation);
			$this->set(compact('servicesInLocation'));

			// Data for the map
			// pr($servicesInLocation);
			// exit;

			$results = Hash::extract($servicesInLocation, '{n}.Services.{n}');
			// remove dupes
			$servicesIDs = array();
			foreach ($results as $key => $value) {
				if (in_array($value['Service']['id'], $servicesIDs)) {
					unset($results[$key]);
				} else {
					$servicesIDs[] = $value['Service']['id'];
				}
			}

			$parents = array();
			if($results){
				foreach($results as $result){
					$parent_id = $result['Category'][0]['parent_id'];

					//If parent doesn't exist yet, create it
					if(!isset($parents[$parent_id])){
						$parents[$parent_id] = array();
					}

					//Find tweets
					if(isset($result['Service']['twitter']) && $result['Service']['twitter'] != ''){
						$twitter = $this->_getTweetsWithUsername($result['Service']['twitter']);
						//debug($twitter); die();
						$result['Twitter'] = $twitter;
					}
					//Add result to parents array
					$parents[$parent_id][] = $result;
				}
			}

			$this->set(compact('parents'));
			$this->Session->write('ServicesAvailability.Parents', $parents);

		} else {
			$this->request->data = $this->Session->read('ServicesAvailability.Post');

			$servicesInLocation = $this->Session->read('ServicesAvailability.Data');
			$this->set(compact('servicesInLocation'));

			$parents = $this->Session->read('ServicesAvailability.Parents');
			$this->set(compact('parents'));
		}

		$categories = $this->Service->Category->find('list');
		$this->set(compact('categories'));
		$this->set('active_nav','activities_overview');

	}

	public function _getTweetsWithUsername($username){

		$duration = 'twitter';
		$name = $username;
		$twitter = Cache::read($name, $duration);
		if(!$twitter){
			$twitter = $this->Twitter->userTimeline($username,3);
			Cache::write($name, $twitter, $duration);
		// debug("NO CACHE ".$name);
		} else {
		// debug("CACHE ".$name);
		}

		if(isset($twitter['error'])){
			$twitter = null;
		}else{
			foreach($twitter as $index => $tweet){
				$twitter[$index]['time_difference'] = $this->_timeDifference($tweet['created_at']);
			}
		}

		return $twitter;
	}

	//Twitter style time differences
	public function _timeDifference($date){
	    if(empty($date)) {
	        return "No date provided";
	    }

	    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	    $lengths = array("60","60","24","7","4.35","12","10");

	    $now = time();
	    $unix_date = strtotime($date);

			// check validity of date
			if(empty($unix_date)) {
				return "Bad date";
	    }

	    // is it future date or past date
	    if($now > $unix_date) {
	        $difference     = $now - $unix_date;
	        $tense         = "ago";

	    } else {
	        $difference     = $unix_date - $now;
	        $tense         = "from now";
	    }

	    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	        $difference /= $lengths[$j];
	    }

	    $difference = round($difference);

	    if($difference != 1) {
	        $periods[$j].= "s";
	    }

	    return "$difference $periods[$j] {$tense}";
	}

	protected function _response_geo(){
		$id = $this->Session->read( 'response' );
		if( !$id ) return false;

		// Get statements and their categories
		$this->loadModel('Response');
		$geo = $this->Response->find('first', array(
			'fields'=>array('Response.postcode','Response.lat','Response.lng'),
			'conditions'=>array('Response.id'=>$id ),
		));

		// Return array of cat ids
		return $geo;
	}

	protected function _get_top_response_categories(){
		$id = $this->Session->read( 'response' );
		if( !$id ) return array();

		$this->loadModel('Response');
		$cats = array();
		$catIDs = array();

		$catData = $this->Response->find('first', array(
			'conditions'=>array('Response.id'=>$id ),
			'contain' => array(
				'ResponseStatement' => array(
					'conditions' => array( array('ResponseStatement.weighting >' => 0 ) ),
					'fields' => array('id', 'weighting', 'statement_id'),
					'order' => array( 'ResponseStatement.weighting' => 'DESC' ),
					'Category' => array(
						'fields' => array('id', 'name', 'slug', 'parent_id'),
					),
				),
				'Condition'=>array(
					'Category' => array(
						'fields' => array('id', 'name', 'slug', 'parent_id'),
					),
				),
			),
		));

		$topInterests = array();

		if( !empty( $catData['ResponseStatement'] ) ){
			foreach( $catData['ResponseStatement'] as $responseStatement ){
				if( !empty( $responseStatement['Category'] ) ){
					foreach( $responseStatement['Category'] as $category ){
						if( !in_array( $category['id'], $catIDs ) ){ // No duplicates
							if ($category['CategoriesResponsesStatement']['top_interest']) {
								$topInterests[] = $category['id'];
								$catIDs[] = $category['id'];
							}
						}
					}
				}
			}
		}
		return $topInterests;
	}

	protected function _response_categories(){
		$id = $this->Session->read( 'response' );
		if( !$id ) return array();

		// Get statements and their categories
		$this->loadModel('Response');
		$cats = array();
		$catIDs = array();

		$catData = $this->Response->find('first', array(
			'conditions'=>array('Response.id'=>$id ),
			'contain' => array(
				'ResponseStatement' => array(
					'conditions' => array( array('ResponseStatement.weighting >' => 0 ) ),
					'fields' => array('id', 'weighting', 'statement_id'),
					'order' => array( 'ResponseStatement.weighting' => 'DESC' ),
					'Category' => array(
						'fields' => array('id', 'name', 'slug', 'parent_id'),
					),
				),
				'Condition'=>array(
					'Category' => array(
						'fields' => array('id', 'name', 'slug', 'parent_id'),
					),
				),
			),
		));

		// pr($catData);

		// Get cat ids
		if( !empty( $catData['ResponseStatement'] ) ){
			foreach( $catData['ResponseStatement'] as $responseStatement ){
				if( !empty( $responseStatement['Category'] ) ){
					foreach( $responseStatement['Category'] as $category ){
						if( !in_array( $category['id'], $catIDs ) ){ // No duplicates
							$cats[]['Category'] = $category;
							$catIDs[] = $category['id'];
						}
					}
				}
			}
		}

		// Also add IDs based on network type
		if( !empty( $catData['Response'] ) &&
				( $catData['Response']['network_type'] == 'isolated' || $catData['Response']['network_type'] == 'highly_isolated'  ) ){
			// Get extra cats from question 8
			$catData2 = $this->Response->ResponseStatement->Statement->find('first', array(
				'conditions'=>array('Statement.id'=>8 ),
				'contain' => array(
					'Category' => array(
						'fields' => array('id', 'name', 'slug', 'parent_id'),
					),
				),
			));
			if( !empty( $catData2['Category'] ) ){
				foreach( $catData2['Category'] as $category ){
					if( !in_array( $category['id'], $catIDs ) ){ // No duplicates
						$cats[]['Category'] = $category;
						$catIDs[] = $category['id'];
					}
				}
			}
		}

		// Also add IDs based on health condition
		if( !empty( $catData['Condition'] ) ){
			foreach( $catData['Condition'] as $responseCondition ){
				if( !empty( $responseCondition['Category'] ) ){
					$category = $responseCondition['Category'];
					if( !in_array( $category['id'], $catIDs ) ){ // No duplicates
						$cats[]['Category'] = $category;
						$catIDs[] = $category['id'];
					}
				}
			}
		}
		return $cats;
	}

	// Age/gender conditions
	protected function _response_age_gender_conditions(){
		$id = $this->Session->read( 'response' );
		if( !$id ) return array();

		$response = $this->Response->find('first', array(
			'conditions'=>array('Response.id'=>$id ),
			'recursive'=>-1,
			'fields' => array('id', 'age', 'gender'),
		));

		if( !$response ) return array();

		$conditions = array();

		if( $response['Response']['age'] ){
			$age_parts = explode( '-', $response['Response']['age'] );
			$age_upper = !empty( $age_parts[1] ) ? $age_parts[1] : 150;
			$age_lower = trim($age_parts[0], '+');

			$conditions['Service.age_upper >='] = $age_lower;
			$conditions['Service.age_lower <='] = $age_upper;
		}

		if( $response['Response']['gender'] ){
			$conditions[ 'Service.gender_'.$response['Response']['gender'] ] = 1;
		}

		return $conditions;
	}

	protected function _extract_ids( $result_list, $model ){
		$resultIDs = array();

		foreach( $result_list as $result )
			$resultIDs[] = $result[$model]['id'];

		return $resultIDs;
	}

	protected function _getCoordinates($location){
		// Get lat / lon
		if ($xml = simplexml_load_file('https://maps.googleapis.com/maps/api/geocode/xml?sensor=false&region=uk&address=' . $location)){
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

/*
	public function _format_postcode($postcode){
	    //--------------------------------------------------
	    // Clean up the user input

        $postcode = strtoupper($postcode);
        $postcode = preg_replace('/[^A-Z0-9]/', '', $postcode);
        $postcode = preg_replace('/([A-Z0-9]{3})$/', ' \1', $postcode);
        $postcode = trim($postcode);

	    //--------------------------------------------------
	    // Check that the submitted value is a valid
	    // British postcode: AN NAA | ANN NAA | AAN NAA |
	    // AANN NAA | ANA NAA | AANA NAA

        if (preg_match('/^[a-z](\d[a-z\d]?|[a-z]\d[a-z\d]?) \d[a-z]{2}$/i', $postcode)) {
            return $postcode;
        } else {
            return NULL;
        }
	}
*/
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Service->recursive = 0;

		if($q = $this->request->query('q')){
			$conditions = array();
			$conditions['OR'][] = array('Service.name LIKE' => '%' . $q . '%');
			$this->Paginator->settings = array(
	      'conditions' => $conditions,
	    );
		}
		// pr($this->paginate);
		// exit;
		$this->set('services', $this->paginate());
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_mapping_tool() {
		if ($this->request->is('post')) {

		}

		// $this->set(compact('categories')); ...
	}

	public function admin_autocomplete(){
		if ($this->request->is('get')) {
			$this->autoLayout = false;
			$this->autoRender = false;
			$this->Service->recursive = -1;
			$results = $this->Service->find('all', array('fields' => array('id', 'name'), 'conditions' => array('name LIKE "%'.$_GET['name_startsWith'].'%"')));
			$response = array();
			$i = 0;
			foreach($results as $result){
				$response[$i]['value'] = $result['Service']['name'];
				$response[$i]['id'] = $result['Service']['id'];
				$i++;
			}
			echo json_encode($response);
		}

	}

	public function admin_initializeRevisions() {
		$this->Service->initializeRevisions();
		exit;
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->Service->id = $id;

		if (!$this->Service->exists()) {
			throw new NotFoundException(__('Invalid service'));
		}

		$options = array('conditions' => array('Service.' . $this->Service->primaryKey => $id));
		$this->set('service', $this->Service->find('first', $options));

		$newestRevision = $this->Service->newest();
		$previousRevision = $this->Service->previous();

		$this->set(compact('newestRevision', 'previousRevision'));

		$this->loadModel('ServiceEdit');
		$data = $this->ServiceEdit->find('all');
		$this->set(compact('data'));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add($id = null) {
		if ($this->request->is('post')) {
			$this->Service->create();
			if ($this->Service->saveAll($this->request->data)) {

				/*
				 * Facilitators need to get notifications of any services deleted by champions
				 */
				$afterCreateServiceVersion = $this->Service->newest();
				$afterCreateServiceVersionId = $afterCreateServiceVersion['Service']['version_id'];
				if ($this->Auth->user('role') === 'c') {
					$data = array('ServiceEdit' => array());
					$data['ServiceEdit']['version_id_after_save'] = $afterCreateServiceVersionId;
					$data['ServiceEdit']['service_id'] = $this->Service->id;
					$data['ServiceEdit']['user_id'] = $this->Auth->user('id');
					$data['ServiceEdit']['action'] = 'create';
					$this->loadModel('ServiceEdit');
					$this->ServiceEdit->save($data);
				}

				$this->Session->setFlash(__('The service has been saved'));
				$service_videos = $this->Service->Video->find('all', array('conditions' => array('Video.service_id' => $this->Service->id) ) );
				if( $service_videos ){
					foreach($service_videos as &$video){
						$embed_movie = $this->oembedGet( $video['Video']['url'] );
						$video['Video']['embed_code'] = $embed_movie['html'];
						$video['Video']['thumb_url'] = $embed_movie['thumbnail_url'];
					}
					$this->Service->Video->saveMany($service_videos, array('deep'=>false));
				}

				// Successfully saved
				if( !$this->request->is('ajax') ){
					$this->redirect(array('action' => 'index'));
					$this->request->data = false;
				}
			} else {
				$this->Session->setFlash(__('The service could not be saved. Please, try again.'));
			}
		}

		$categories = $this->Service->Category->find('list');
		$this->set(compact('categories'));

		if( $this->request->is('ajax') ){
			if( !$this->request->data && $id ) {
				$options = array(
					'conditions' => array('Service.' . $this->Service->primaryKey => $id),
				);
				$this->request->data = $this->Service->find('first', $options);
			}

			$this->render('admin_ajax_add');
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Service->id = $id;
		if (!$this->Service->exists()) {
			throw new NotFoundException(__('Service does not exist'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {

			$beforeSaveServiceVersion = $this->Service->newest();
			$beforeSaveServiceVersionId = $beforeSaveServiceVersion['Service']['version_id'];

			if ($this->Service->saveAll($this->request->data)) {
				$this->Session->setFlash(__('The service has been saved'));
				// Remove unmentioned (deleted) videos
				$videoIds = Set::classicExtract($this->request->data, 'Video.{n}.id');
				$this->Service->Video->deleteAll(
					array(
						'NOT' => array(
							'Video.id' => $videoIds,
						),
						'Video.service_id' => $this->Service->id
					)
				);

				// Update video embed codes
				$service_videos = $this->Service->Video->find('all', array('conditions' => array('Video.service_id' => $this->Service->id) ) );
				if( $service_videos ){
					foreach($service_videos as &$video){
						$embed_movie = $this->oembedGet( $video['Video']['url'] );
						$video['Video']['embed_code'] = $embed_movie['html'];
						$video['Video']['thumb_url'] = $embed_movie['thumbnail_url'];
					}
					$this->Service->Video->saveMany($service_videos, array('deep'=>false));
				}

				/*
				 * Facilitatos need to get notifications of any services modified by champions
				 */
				if ($this->Auth->user('role') === 'c') {
					$afterSaveServiceVersion = $this->Service->newest();
					$afterSaveServiceVersionId = $afterSaveServiceVersion['Service']['version_id'];

					if ($beforeSaveServiceVersionId != $afterSaveServiceVersionId) {
						$data = array('ServiceEdit' => array());
						$data['ServiceEdit']['version_id_before_save'] = $beforeSaveServiceVersionId;
						$data['ServiceEdit']['version_id_after_save'] = $afterSaveServiceVersionId;
						$data['ServiceEdit']['service_id'] = $this->Service->id;
						$data['ServiceEdit']['user_id'] = $this->Auth->user('id');
						$data['ServiceEdit']['action'] = 'update';
						$this->loadModel('ServiceEdit');
						$this->ServiceEdit->save($data);
					}
				}

				// Redirect
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash(__('The service could not be saved. Please, try again.'));
			}
		} else {
			$options = array(
				'conditions' => array('Service.' . $this->Service->primaryKey => $id),
				'contain' => array('Video', 'Category'),
			);
			$data = $this->Service->find('first', $options);
			$this->request->data = $data;

			/*
			 * Champions cannot edit services they have been disallowed from
			 */
			if ($this->Auth->user('role') === 'c') {
				if ($data['Service']['disable_editing']) {
					$this->Session->setFlash(__('You\'re not allowed to edit that service.'));
					$this->redirect(array('action' => 'index'));
				}
			}
		}

		$categories = $this->Service->Category->find('list');
		$this->set(compact('categories'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Service->id = $id;
		if (!$this->Service->exists()) {
			throw new NotFoundException(__('Invalid service'));
		}

		/*
		 * Champions cannot delete services they have been disallowed from
		 */
		if ($this->Auth->user('role') === 'c') {
			$options = array(
				'conditions' => array('Service.' . $this->Service->primaryKey => $id)
			);
			$data = $this->Service->find('first', $options);
			if ($data['Service']['disable_editing']) {
				$this->Session->setFlash(__('You\'re not allowed to delete that service.'));
				$this->redirect(array('action' => 'index'));
			}
		}

		$this->request->onlyAllow('post', 'delete');

		/*
		 * Prepare: Facilitatos need to get notifications of any services deleted by champions
		 */
		$beforeDeleteServiceId = $this->Service->id;
		$beforeDeleteServiceVersion = $this->Service->newest();
		$beforeDeleteServiceVersionId = $beforeDeleteServiceVersion['Service']['version_id'];

		if ($this->Service->delete()) {
			/*
			 * Facilitatos need to get notifications of any services deleted by champions
			 */
			if ($this->Auth->user('role') === 'c') {
				$data = array('ServiceEdit' => array());
				$data['ServiceEdit']['version_id_before_save'] = $beforeDeleteServiceVersionId;
				$data['ServiceEdit']['service_id'] = $beforeDeleteServiceId;
				$data['ServiceEdit']['user_id'] = $this->Auth->user('id');
				$data['ServiceEdit']['action'] = 'delete';
				$this->loadModel('ServiceEdit');
				$this->ServiceEdit->save($data);
			}

			$this->Session->setFlash(__('Service deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Service was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

	public function admin_undo_delete($id = null, $service_edit_id = null) {
		$this->Service->id = $id;
		if ($this->Service->undelete()) {
			$this->Session->setFlash(__('Service has been undeleted.'));

			// Approve that delete
			$this->loadModel('ServiceEdit');
			$this->ServiceEdit->delete($service_edit_id);
			$this->redirect($this->referer());
		} else {
			$this->Session->setFlash(__('Cannot undelete this service.'));
			$this->redirect($this->referer());
		}
	}

	public function oembedGet( $url ){

		foreach( Configure::read('Site.oEmbedEndpoints') as $endpoint ){
		if( preg_match ( '#'.$endpoint['url_re'].'#i', $url ) ){
			$encoded_url = $endpoint['endpoint_url']."?url=".rawurlencode($url)."&format=json&maxwidth=".Configure::read('Site.embed_width')."&maxheight=".Configure::read('Site.embed_height');

			$curl_handle = curl_init();
			curl_setopt($curl_handle,CURLOPT_URL, $encoded_url);
			curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT, Configure::read('Site.remote_timeout'));
			curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($curl_handle,CURLOPT_HEADER,0);
			$buffer = curl_exec($curl_handle);
			curl_close($curl_handle);

			$json_content = @json_decode($buffer, true);
			if( $json_content ) return $json_content;
			}
		}
	return false;
	}
}
