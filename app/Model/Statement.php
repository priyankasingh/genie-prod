<?php
App::uses('AppModel', 'Model');
/**
 * Statement Model
 *
 * @property Category $Category
 * @property Response $Response
 */
class Statement extends AppModel {
	// Translation
	public $actsAs = array(
		'Translate' => array(
			'statement' => 'statementTranslation',
			'description' => 'descriptionTranslation',
		)
	);
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'statement';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'statement' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'order' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ResponseStatement' => array(
			'className' => 'ResponseStatement',
			'foreignKey' => 'statement_id',
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
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Category' => array(
			'className' => 'Category',
			'joinTable' => 'categories_statements',
			'foreignKey' => 'statement_id',
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
		),
	);

	public function getStatement($order = null) {
		if ($order == null) {
			return;
		}
		$data = $this->find('first',
			array(
				'conditions' => array(
					'order' => $order
				),
				'contain' => array(
					'Category'
				)
			)
		);
		return $data;
	}

}
