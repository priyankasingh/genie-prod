<?php
App::uses('AppModel', 'Model');
/**
 * Service Model
 *
 * @property Favourite $Favourite
 * @property Category $Category
 */
class OnlineResource extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
        
        public $actsAs = array('Sluggable' => array(
		'separator' => '-',
		'label' => 'name',
            ),
            'Revision' => array(
			'limit' => 10,
			'ignore' => array(
				'modified',
				'disable_editing'
			)
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
				'rule' => array('notblank'),
				'message' => 'Name cannot be omitted',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
                'url' => array(
			'url' => array(
				'rule' => array('url'),
				'message' => 'Must be a valid URL.',
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
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
			'joinTable' => 'categories_online_resources',
			'foreignKey' => 'online_resource_id',
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
	
	public function getIdFromSlug($slug){
		return $this->field('id',array('slug'=>$slug));
	}
        
}