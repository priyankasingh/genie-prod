<?php
App::uses('AppModel', 'Model');
/**
 * Condition Model
 *
 * @property Response $Response
 */
class TopInterest extends AppModel {

  public function beforeSave($options = array()) {
    if (!empty($this->data['TopInterest']['data'])) {
      $this->data['TopInterest']['data'] = json_encode($this->data['TopInterest']['data']);
    }
    return true;
  }

  public function afterFind($data, $primary = false) {
    foreach ($data as $key => $value) {
      if (isset($value['TopInterest']['data'])) {
        $data[$key]['TopInterest']['data'] = json_decode($data[$key]['TopInterest']['data']);
      }
    }
    return $data;
  }

  public $validate = array(
    'data' => array(
      'rule' => array('check_max_three_submitted'),
      'message' => 'Please select maximum 3 most important interests.'
    )
  );

  public function check_max_three_submitted($data) {
    if (count($data['data']) > 3) {
      return false;
    }
    return true;
  }

}
