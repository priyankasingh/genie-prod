<?php
App::uses('AppModel', 'Model');
/**
 * Category Model
 *
 * @property Category $ParentCategory
 * @property Category $ChildCategory
 * @property Service $Service
 * @property Statement $Statement
 */
class Category extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	// Translation
	public $actsAs = array(
		'Sluggable' => array(
			'separator' => '-',
			'label' => 'name',
			),
		'Translate' => array(
			'name'=>'nameTranslation',
			'description' =>'descriptionTranslation'
			)
		);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You must set a category name.',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'maxlength' => array(
				'rule' => array('maxlength', 45),
				'message' => 'The maximum allowed length is 45 characters.',
				'allowEmpty' => false,
				'required' => true,
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
		'ParentCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id',
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
		'ChildCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id',
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
		'Service' => array(
			'className' => 'Service',
			'joinTable' => 'categories_services',
			'foreignKey' => 'category_id',
			'associationForeignKey' => 'service_id',
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
		'Statement' => array(
			'className' => 'Statement',
			'joinTable' => 'categories_statements',
			'foreignKey' => 'category_id',
			'associationForeignKey' => 'statement_id',
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
		'ResponseStatement' => array(
			'className' => 'ResponseStatement',
			'joinTable' => 'categories_responses_statements',
			'foreignKey' => 'category_id',
			'associationForeignKey' => 'responses_statement_id',
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
                'OnlineResource' => array(
			'className' => 'OnlineResource',
			'joinTable' => 'categories_online_resources',
			'foreignKey' => 'category_id',
			'associationForeignKey' => 'online_resource_id',
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

	public function getParentCategories(){
		$parent_categories = $this->find('all', array(
			'recursive' => -1,
			'conditions' => array('parent_id IS NULL'),
		));
		return $parent_categories;
	}

	public function getIdFromSlug($slug){
		return $this->field('id',array('slug'=>$slug));
	}

	public function getChildrenOfCategoryWithId($id){
		return $this->find('all',array(
			'recursive' => -1,
			'conditions' => array('parent_id =' => $id),
		));
	}
}
