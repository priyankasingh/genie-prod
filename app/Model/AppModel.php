<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	var $actsAs = array('Containable');

	public function getTranslatedModelField($id = 0, $field) {
	    // reterive active language

	    $locale = $this->locale ;// gets the current assigned locale
	    if( !$locale ) $locale = Configure::read('Config.language');

	    $res = false;
	    $translateTable = (isset($this->translateTable))?$this->translateTable:"i18n";

	    $db = $this->getDataSource();

	    $tmp = $db->fetchAll(
	        "SELECT content from {$translateTable} WHERE model = ? AND locale = ? AND foreign_key = ? AND field = ? LIMIT 1",
	        array($this->alias, $locale , $id, $field)
	    );
	    if (!empty($tmp)) {
	        $res = $tmp[0][$translateTable]['content'];
	    }
	    return $res;
	}

	public function afterFind($results, $primary = false) {

	    if($primary == false && array_key_exists('Translate', $this->actsAs)) {

	        foreach ($results as $key => $val) {
	            if (isset($val[$this->name]) && isset($val[$this->name]['id'])) {
	                foreach($this->actsAs['Translate'] as $translationfield) {
	                    $results[$key][$this->name][$translationfield] = $this->getTranslatedModelField($val[$this->name]['id'], $translationfield);
	                }
	            } elseif( isset( $val['id'] ) && is_numeric($key)) {
	                foreach($this->actsAs['Translate'] as $translationfield) {
	                    $results[$key][$translationfield] = $this->getTranslatedModelField($val['id'], $translationfield);
	                }
	            }
	         }
	    }

	    return $results;
	}

}
