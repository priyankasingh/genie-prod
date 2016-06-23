<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Response $Response
 * @property Favourite $Favourite
 */
class User extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'user';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'email';
	
	public $authorizationRoles = array( // DB id => label
		'0' => 'None',
		's' => 'Super Administrator',
		'r' => 'Researcher',
		'f' => 'Facilitator',
                'c' => 'Champion',
	);
	
	public $adminWhitelist = array( // role => controllers
		's' => array(
			'users',
			'favourites',
			'statements',
			'conditions',
			'responses',
			'network_members',
			'network_categories',
			'network_types',
			'services',
			'categories',
                        'online_resources',
			'pages',
			'contacts',
		),
		'r' => array(
			'users',
			'favourites',
			'statements',
			'conditions',
			'responses',
			'network_members',
			'network_categories',
			'network_types',
			'services',
			'categories',
                        'online_resources',
			'pages',
			'contacts',
		),
		'f' => array(
			'users',
			//'favourites',
			//'responses',
			//'network_members',
			'services',
                        'services_edits',
			//'categories',
			//'contacts',
		),
                'c' => array(
			'services',
		),
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'You must enter a valid email address.',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'An account using this email address already exists. Please log in instead.',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_admin' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			'minlength' => array(
				'rule' => array('minlength',8),
				'message' => 'Passwords must be 8 characters or more to ensure the security of your account',
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password_confirm' => array(
			'matches' => array(
				'rule' => array('passwordsMatch'),
				'message' => 'Your entered passwords should match',
				'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				'on' => 'update', // Limit validation to 'create' or 'update' operations
			),
		),
		'old_password' => array(
			'matches' => array(
				'rule' => array('checkCurrentPassword'),
				'message' => 'Your old password is incorrect.',
				'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				'on' => 'update', // Limit validation to 'create' or 'update' operations
			),
		),

	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'Response' => array(
			'className' => 'Response',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Favourite' => array(
			'className' => 'Favourite',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
                'ServiceEdit'
	);

// Generate/hash password on save.
	public function beforeSave($options = array()) {
		/*
		 * Cannot assign a Facilitator to non-champion users
		 */
		if ($this->data['User']['role'] != 'c') {
			$this->data['User']['facilitator_id'] = NULL;
		}
            
                if( !empty( $this->data['User']['password'] ) ){
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		} else {
			// Create a random password and send it to the user
			$this->newPassword = $this->generateHash( 8 );
			$this->data['User']['password'] = AuthComponent::password($this->newPassword);
		}
		return true;
	}

	function passwordsMatch($data) {
		return ( $this->data['User']['password'] == $this->data['User']['password_confirm'] );
    }

	public function checkCurrentPassword($data) {
		$this->id = AuthComponent::user('id');
		$password = $this->field('password');
		return(AuthComponent::password($data['old_password']) == $password);
	}

	function generateHash( $length = false ){
		$result = "";
		$charPool = '0123456789abcdefghijklmnopqrstuvwxyz';
		for($p = 0; $p<20; $p++)
			$result .= $charPool[mt_rand(0,strlen($charPool)-1)];

		$md5 = md5($result);
		if( $length ) $md5 = substr( $md5, 0, $length );

		return $md5;
	}
	
	public function getRoles(){
		return $this->authorizationRoles;
	}
	
	public function getWhitelist( $role ){
		return $this->adminWhitelist[$role];
	}
	
	public function isAdminPermitted( $role, $controller ){
		if( !isset( $this->adminWhitelist[$role] ) ) return false;
		return in_array( $controller, $this->adminWhitelist[$role] );
	}
        
        public function getFacilitatorsList(){
		$data = $this->find('list',
			array(
				'fields' => array(
					'id',
					'email'
				),
				'conditions' => array(
					'User.role' => array('f')
				)
			)
		);
		return $data;
	}
}
