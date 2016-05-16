<?php
App::uses('AppController', 'Controller');
/**
 * Services Edits Controller
 *
 * @property Service $Service
 */
class ServicesEditsController extends AppController {

  public $uses = array('User', 'Service', 'ServiceEdit');

  public function admin_index(){
    if ($this->Auth->user('role') == 'f') {
      $facilitatorId = $this->Auth->user('id');
      // $facilitatorId = 87;

      // Get updated records
      $updated = $this->User->find('all',
        array(
          'conditions' => array(
            'facilitator_id' => $facilitatorId,
          ),
          'contain' => array(
            'ServiceEdit' => array(
              'conditions' => array(
                'action' => 'update',
                'approved' => 0
              )
            )
          )
        )
      );

      foreach ($updated as $key => $value) {
        foreach ($value['ServiceEdit'] as $editKey => $editValue) {
          $this->Service->id = $editValue['service_id'];
          $diff = $this->Service->diff($editValue['version_id_before_save'], $editValue['version_id_after_save']);
          $updated[$key]['ServiceEdit'][$editKey]['diff'] = $diff;
        }
      }
      // pr($data);
      $this->set(compact('updated'));

      // Get created records
      $created = $this->User->find('all',
        array(
          'conditions' => array(
            'facilitator_id' => $facilitatorId,
          ),
          'contain' => array(
            'ServiceEdit' => array(
              'conditions' => array(
                'action' => 'create',
                'approved' => 0
              )
            )
          )
        )
      );
      // pr($created);
      $this->set(compact('created'));

      // Get deleted records
      $deleted = $this->User->find('all',
        array(
          'conditions' => array(
            'facilitator_id' => $facilitatorId,
          ),
          'contain' => array(
            'ServiceEdit' => array(
              'conditions' => array(
                'action' => 'delete',
                'approved' => 0
              )
            )
          )
        )
      );

      foreach ($deleted as $key => $value) {
        foreach ($value['ServiceEdit'] as $editKey => $editValue) {
          $this->Service->id = $editValue['service_id'];
          $diff = $this->Service->diff($editValue['version_id_before_save'], $editValue['version_id_after_save']);
          $deleted[$key]['ServiceEdit'][$editKey]['diff'] = $diff;
        }
      }
      // pr($deleted);
      $this->set(compact('deleted'));

    }
  }

  public function admin_approve($id = null){
    $this->ServiceEdit->id = $id;
    if (!$this->ServiceEdit->exists()) {
      throw new NotFoundException();
    }
    $this->ServiceEdit->set('approved', 1);
    $this->ServiceEdit->save();
    $this->redirect($this->referer());
  }

}
