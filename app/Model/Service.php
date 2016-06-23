<?php
App::uses('AppModel', 'Model');
/**
 * Service Model
 *
 * @property Favourite $Favourite
 * @property Category $Category
 */
class Service extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	public $actsAs = array(
		'Sluggable' => array(
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
				'rule' => array('notempty'),
				'message' => 'Name cannot be omitted',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Must be a valid email address.',
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Favourite' => array(
			'className' => 'Favourite',
			'foreignKey' => 'service_id',
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
		'Video' => array(
			'className' => 'Video',
			'foreignKey' => 'service_id',
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
		'Category' => array(
			'className' => 'Category',
			'joinTable' => 'categories_services',
			'foreignKey' => 'service_id',
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

	/**
	 * @author Reed Dadoune
	 * distanceQuery
	 * A genral case distance query builder
	 * Pass a number of options to this function and recieve a query
	 * you can pass to either the find or paginate functions to get
	 * objects back by distance
	 *
	 * Example:
	 *  $query = $this->Model->distanceQuery(array(
	 *    'lat' => 34.2746405,
	 *    'lng' => -119.2290053
	 *  ));
	 *  $query['conditions']['published'] = true;
	 *  $results = $this->Model->find('all', $query);
	 *
	 * @param array $opts Options
	 *                    - lat The latitude coordinate of the center point
	 *                    - lng The longitude coordinate of the center point
	 *                    - alias The model name of the query this is for
	 *                      defaults to the current model alias
	 *                    - radius The distance to at which to find objects at
	 *                      defaults to false in which case distance is calculated
	 *                      only for the sort order
	 * @return array A query that can be modified and passed to find or paginate
	 */
	public function distanceQuery($opts = array()) {
		$defaults = array(
			'lat'  => 0,
			'lng' => 0,
			'alias'     => $this->alias,
			'radius'    => false
		);
		$opts = Set::merge($defaults, $opts);

		$query = array(
			'fields' => array(
				// '*',
				CakeText::insert(
					'3956 * 2 *
					ASIN(SQRT(
						POWER(SIN((:lat - ABS(:alias.lat)) * PI() / 180 / 2), 2) +
						COS(:lat * PI() / 180) *
						COS(ABS(:alias.lat) * PI() / 180) *
						POWER(SIN((:lng - :alias.lng) * PI() / 180 / 2), 2)
					)) AS distance',
					array('alias' => $opts['alias'], 'lat' => $opts['lat'], 'lng' => $opts['lng'])
				)
			),
			'order' => array('distance' => 'ASC')
		);

		if ($opts['radius']) {
			$longitudeLower = $opts['lng'] - $opts['radius']  / abs(cos(deg2rad($opts['lat'])) * 69);
			$longitudeUpper = $opts['lng'] + $opts['radius']  / abs(cos(deg2rad($opts['lat'])) * 69);
			$latitudeLower  = $opts['lat']  - ($opts['radius'] / 69);
			$latitudeUpper  = $opts['lat']  + ($opts['radius'] / 69);
			$query['conditions'] = array(
				String::insert(':alias.lat  BETWEEN ? AND ?', array('alias' => $opts['alias'])) => array($latitudeLower,  $latitudeUpper),
				String::insert(':alias.lng BETWEEN ? AND ?', array('alias' => $opts['alias'])) => array($longitudeLower, $longitudeUpper)
			);
			$query['group'] = sprintf('%s.id HAVING distance < %f', $opts['alias'], $opts['radius']);
		}

		return $query;
	}

}
