<?php
App::uses('AppModel', 'Model');
/**
 * Response Model
 *
 * @property User $User
 * @property Statement $Statement
 */
class Response extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'age' => array(
			'list' => array(
				'rule' => array('inList', array('18-24','25-40','41-55','56-65','66+')),
				'message' => 'Please choose an age range',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'gender' => array(
			'list' => array(
				'rule' => array('inList', array('m','f')),
				'message' => 'Please choose a gender',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'postcode' => array(
			'notempty' => array(
				'rule' => array('notblank'),
				'message' => 'Please enter your postcode',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public function beforeSave($options = array()) {
		if (	 isset($this->data['TopInterest']['data'])
				&& !empty($this->data['TopInterest']['data'])
			) {
			$this->data['TopInterest']['data'] = json_encode($this->data['TopInterest']['data']);
		}
		return true;
	}

	//The Associations below have been created with all possible keys, those that are not needed can be removed


	public $hasOne = 'TopInterest';

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'NetworkType' => array(
			'className' => 'NetworkType',
			'foreignKey' => 'network_type_id',
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
		'ResponseStatement' => array(
			'className' => 'ResponseStatement',
			'foreignKey' => 'response_id',
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
		'NetworkMember' => array(
			'className' => 'NetworkMember',
			'foreignKey' => 'response_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Condition' => array(
			'className' => 'Condition',
			'joinTable' => 'conditions_responses',
			'foreignKey' => 'response_id',
			'associationForeignKey' => 'condition_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

	public function getNetworkTypes(){
		return array(
			'very_diverse',
			'diverse',
			'family_friend_centered',
			'friend_centered',
			'family_centered',
			'family_friend_supported',
			'friend_supported',
			'family_supported',
			'isolated',
			'highly_isolated',
		);
	}

	public function getNetworkType( $data = null ){
		if( !$data ) $data = $this->data;

		$scores = $this->getScoreBreakDown( $data );

		// Scoring
		$family = $scores[1];
		$friends = $scores[9];
		$groups = $scores[17];
		$overall = $family + $friends + $groups;

		if( $family >= 20 && $friends >= 15 && $groups >= 2 ){
			return 'very_diverse';
		} elseif( $family >=20 && $friends > 0 && $friends < 15 && $groups >= 2
				|| $family > 0 && $family < 20 && $friends >= 15 && $groups >= 2
				|| $family >= 20 && $friends >= 15 && $groups = 1 ){
			return 'diverse';
		} elseif( $family >= 20 && $friends >= 15 && $groups < 2 ){
			return 'family_friend_centered';
		} elseif( $family < 20 && $friends >= 15 && $groups < 2 ){
			return 'friend_centered';
		} elseif( $family >= 20 && $friends < 15 && $groups < 2 ){
			return 'family_centered';
		} elseif( $family >= 7 && $family < 20 && $friends >= 5 && $friends < 15 ){
			return 'family_friend_supported';
		} elseif( $family < 7 && $friends >= 5 && $friends < 15 ){
			return 'friend_supported';
		} elseif( $family >= 7 && $family < 20 && $friends < 5 ){
			return 'family_supported';
		} elseif( $family < 7 && $friends < 5 && $overall >= 8 ){
			return 'isolated';
		} else {
			return 'highly_isolated';
		}
	}

	public function getScoreBreakDown( $data = null, $countsOnly = false ){
		if( !$data ) $data = $this->data;

		$scores = array();
		$parentNetworkCategories = $this->NetworkMember->NetworkCategory->find('all', array(
			'conditions'=>array('NetworkCategory.parent_id'=>null),
		));

		// Foreach Parent Category
		foreach( $parentNetworkCategories as $parentNetworkCategory ){
			$score = 0;
			foreach( $data['NetworkMember'] as $member ){
				if( empty( $member['NetworkCategory'] ) ){
					$networkCategory = $this->NetworkMember->NetworkCategory->find('first', array(
						'conditions'=>array('NetworkCategory.id'=>$member['network_category_id']),
					));
					$member['NetworkCategory'] = $networkCategory['NetworkCategory'];
				}

				if( $parentNetworkCategory['NetworkCategory']['id'] == $member['NetworkCategory']['parent_id'] )
					$score += ( $countsOnly ? 1 : $this->NetworkMember->getFrequencyScore( $member['frequency'] ) );
			}

			$scores[ $parentNetworkCategory['NetworkCategory']['id'] ] = $score;
		}

		return $scores;
	}
}
