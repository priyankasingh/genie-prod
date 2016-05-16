<?php
App::uses('AppModel', 'Model');
/**
 * Statement Model
 *
 * @property Category $Category
 * @property Response $Response
 */
class ResponseStatement extends AppModel {

	public $useTable = 'responses_statements'; 

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'weighting';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'weighting' => array(
			'notempty' => array(
				'rule' => array('range', -1, 4),
				'message' => 'Invalid weighting',
				'allowEmpty' => true,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Response' => array(
			'className' => 'Response',
			'foreignKey' => 'response_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Statement' => array(
			'className' => 'Statement',
			'foreignKey' => 'statement_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public $hasAndBelongsToMany = array(
		'Category' => array(
			'className' => 'Category',
			'joinTable' => 'categories_responses_statements',
			'foreignKey' => 'responses_statement_id',
			'associationForeignKey' => 'category_id',
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


}
