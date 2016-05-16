<?php
App::uses('AppController', 'Controller');
/**
 * Statements Controller
 *
 * @property Statement $Statement
 */
class StatementsController extends AppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Statement->recursive = 0;
		$this->set('statements', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Statement->exists($id)) {
			throw new NotFoundException(__('Invalid statement'));
		}
		$options = array('conditions' => array('Statement.' . $this->Statement->primaryKey => $id));
		$this->set('statement', $this->Statement->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		$this->Statement->locale = array_keys( Configure::read('Site.languages') );
		
		if ($this->request->is('post')) {
			$this->Statement->create();
			if ($this->Statement->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The statement has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The statement could not be saved. Please, try again.'));
			}
		}
		$categories = $this->Statement->Category->find('list');
		$this->set(compact('categories', 'responses'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Statement->locale = array_keys( Configure::read('Site.languages') );
		
		if (!$this->Statement->exists($id)) {
			throw new NotFoundException(__('Invalid statement'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Statement->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The statement has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The statement could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Statement.' . $this->Statement->primaryKey => $id));
			$this->request->data = $this->Statement->find('first', $options);
			
			foreach( $this->Statement->actsAs['Translate'] as $field => $fieldAlias ){
				$translations = Set::combine( $this->request->data, $fieldAlias.'.{n}.locale', $fieldAlias.'.{n}.content' );
				$this->request->data['Statement'][$field] = $translations;
			}
		}
		$categories = $this->Statement->Category->find('list');
		$this->set(compact('categories', 'responses'));
	}
/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Statement->id = $id;
		if (!$this->Statement->exists()) {
			throw new NotFoundException(__('Invalid statement'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Statement->delete()) {
			$this->Session->setFlash(__('Statement deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Statement was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
