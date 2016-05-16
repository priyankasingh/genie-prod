<?php
App::uses('AppController', 'Controller');
/**
 * Conditions Controller
 *
 * @property Condition $Condition
 */
class ConditionsController extends AppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Condition->recursive = 0;
		$this->set('conditions', $this->paginate());
	}

/** 
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Condition->exists($id)) {
			throw new NotFoundException(__('Invalid condition'));
		}
		$options = array('conditions' => array('Condition.' . $this->Condition->primaryKey => $id));
		$this->set('condition', $this->Condition->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		$this->Condition->locale = array_keys( Configure::read('Site.languages') );
	
		if ($this->request->is('post')) {
			$this->Condition->create();
			if ($this->Condition->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The condition has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The condition could not be saved. Please, try again.'));
			}
		}
		$categories = $this->Condition->Category->find('list');
		$this->set(compact('categories'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Condition->locale = array_keys( Configure::read('Site.languages') );

		if (!$this->Condition->exists($id)) {
			throw new NotFoundException(__('Invalid condition'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Condition->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The condition has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The condition could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Condition.' . $this->Condition->primaryKey => $id));
			$this->request->data = $this->Condition->find('first', $options);
			
			foreach( $this->Condition->actsAs['Translate'] as $field => $fieldAlias ){
				$translations = Set::combine( $this->request->data, $fieldAlias.'.{n}.locale', $fieldAlias.'.{n}.content' );
				$this->request->data['Condition'][$field] = $translations;
			}
		}
		$categories = $this->Condition->Category->find('list', array('conditions'=>array('parent_id !=' => null)));
		$this->set(compact('categories'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Condition->id = $id;
		if (!$this->Condition->exists()) {
			throw new NotFoundException(__('Invalid condition'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Condition->delete()) {
			$this->Session->setFlash(__('Condition deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Condition was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
