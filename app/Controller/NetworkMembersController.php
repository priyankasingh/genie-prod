<?php
App::uses('AppController', 'Controller');
/**
 * NetworkMembers Controller
 *
 * @property NetworkMember $NetworkMember
 * @property PaginatorComponent $Paginator
 */
class NetworkMembersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->NetworkMember->recursive = 0;
		$this->set('networkMembers', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->NetworkMember->exists($id)) {
			throw new NotFoundException(__('Invalid network member'));
		}
		$options = array('conditions' => array('NetworkMember.' . $this->NetworkMember->primaryKey => $id));
		$this->set('networkMember', $this->NetworkMember->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->NetworkMember->create();
			if ($this->NetworkMember->save($this->request->data)) {
				$this->Session->setFlash(__('The network member has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The network member could not be saved. Please, try again.'));
			}
		}
		$networkCategories = $this->NetworkMember->NetworkCategory->find('list', array(
			'conditions'=>array('parent_id <>'=>null),
		));
		$responses = $this->NetworkMember->Response->find('list');
		$frequencies = $this->NetworkMember->getFrequencies();
		$this->set(compact('networkCategories','frequencies','responses'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->NetworkMember->exists($id)) {
			throw new NotFoundException(__('Invalid network member'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->NetworkMember->save($this->request->data)) {
				$this->Session->setFlash(__('The network member has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The network member could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('NetworkMember.' . $this->NetworkMember->primaryKey => $id));
			$this->request->data = $this->NetworkMember->find('first', $options);
		}
		$networkCategories = $this->NetworkMember->NetworkCategory->find('list', array(
			'conditions'=>array('parent_id <>'=>null),
		));
		$responses = $this->NetworkMember->Response->find('list');
		$frequencies = $this->NetworkMember->getFrequencies();
		$this->set(compact('networkCategories','frequencies','responses'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->NetworkMember->id = $id;
		if (!$this->NetworkMember->exists()) {
			throw new NotFoundException(__('Invalid network member'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->NetworkMember->delete()) {
			$this->Session->setFlash(__('The network member has been deleted.'));
		} else {
			$this->Session->setFlash(__('The network member could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
