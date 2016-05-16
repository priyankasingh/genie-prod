<?php
App::uses('AppModel', 'Model');
/**
 * NetworkMember Model
 *
 * @property NetworkCategory $NetworkCategory
 * @property Response $Response
 */
class NetworkMember extends AppModel {

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
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'NetworkCategory' => array(
			'className' => 'NetworkCategory',
			'foreignKey' => 'network_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Response' => array(
			'className' => 'Response',
			'foreignKey' => 'response_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public function getFrequencies(){
		return array(
			'daily'=>'Every day',
			'weekly'=>'At least once a week',
			'monthly'=>'At least once a month',
			'less'=>'Less often',
		);
	}
	
	public function getFrequencyScores(){
		return array(
			'daily'=>10,
			'weekly'=>5,
			'monthly'=>1,
			'less'=>0,
		);
	}
	
	public function getFrequencyScore( $frequency ){
		$scores = $this->getFrequencyScores();
		return $scores[ $frequency ];
	}
}
