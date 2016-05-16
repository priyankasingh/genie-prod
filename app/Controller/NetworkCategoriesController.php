<?php
App::uses('AppController', 'Controller');
/**
 * NetworkCategories Controller
 *
 * @property NetworkCategory $NetworkCategory
 * @property PaginatorComponent $Paginator
 */
class NetworkCategoriesController extends AppController {

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
		$this->NetworkCategory->recursive = 0;
		$this->set('networkCategories', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->NetworkCategory->exists($id)) {
			throw new NotFoundException(__('Invalid network category'));
		}
		$options = array('conditions' => array('NetworkCategory.' . $this->NetworkCategory->primaryKey => $id));
		$this->set('networkCategory', $this->NetworkCategory->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		$this->NetworkCategory->locale = array_keys( Configure::read('Site.languages') );
		
		if ($this->request->is('post')) {
			$this->NetworkCategory->create();
			if ($this->NetworkCategory->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The network category has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The network category could not be saved. Please, try again.'));
			}
		}
		$parentNetworkCategories = $this->NetworkCategory->ParentNetworkCategory->find('list', array(
			'conditions'=>array('parent_id'=>null),
		));
		$this->set(compact('parentNetworkCategories'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->NetworkCategory->locale = array_keys( Configure::read('Site.languages') );
		
		if (!$this->NetworkCategory->exists($id)) {
			throw new NotFoundException(__('Invalid network category'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->NetworkCategory->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The network category has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The network category could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('NetworkCategory.' . $this->NetworkCategory->primaryKey => $id));
			$this->request->data = $this->NetworkCategory->find('first', $options);
			
			foreach( $this->NetworkCategory->actsAs['Translate'] as $field => $fieldAlias ){
				$translations = Set::combine( $this->request->data, $fieldAlias.'.{n}.locale', $fieldAlias.'.{n}.content' );
				$this->request->data['NetworkCategory'][$field] = $translations;
			}
		}
		$parentNetworkCategories = $this->NetworkCategory->ParentNetworkCategory->find('list', array(
			'conditions'=>array('parent_id'=>null),
		));
		$this->set(compact('parentNetworkCategories'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->NetworkCategory->id = $id;
		if (!$this->NetworkCategory->exists()) {
			throw new NotFoundException(__('Invalid network category'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->NetworkCategory->delete()) {
			$this->Session->setFlash(__('The network category has been deleted.'));
		} else {
			$this->Session->setFlash(__('The network category could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
