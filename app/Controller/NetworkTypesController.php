<?php
App::uses('AppController', 'Controller');
/**
 * NetworkTypes Controller
 *
 * @property NetworkType $NetworkType
 * @property PaginatorComponent $Paginator
 */
class NetworkTypesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	function beforeFilter(){
		$this->Auth->allow( array( 'view' ) );
		parent::beforeFilter();
	}
	
	public function view($id = null) {
		if( !$id && $response_id = $this->Session->read( 'response' ) ){
			$response = $this->NetworkType->Response->read( 'network_type_id', $response_id );
			if( $response ){
				$id = $response['Response']['network_type_id'];
			}
		}

		if (!$this->NetworkType->exists($id)) {
			throw new NotFoundException(__('Invalid network type'));
		}
		$options = array('conditions' => array('NetworkType.' . $this->NetworkType->primaryKey => $id));
		$this->set('networkType', $this->NetworkType->find('first', $options));
		$this->set('active_nav', 'my_network');
	}
	
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->NetworkType->recursive = 0;
		$this->set('networkTypes', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->NetworkType->exists($id)) {
			throw new NotFoundException(__('Invalid network type'));
		}
		$options = array('conditions' => array('NetworkType.' . $this->NetworkType->primaryKey => $id));
		$this->set('networkType', $this->NetworkType->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		$this->NetworkType->locale = array_keys( Configure::read('Site.languages') );
		if ($this->request->is('post')) {
			$this->NetworkType->create();
			if ($this->NetworkType->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The network type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The network type could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->NetworkType->locale = array_keys( Configure::read('Site.languages') );
		if (!$this->NetworkType->exists($id)) {
			throw new NotFoundException(__('Invalid network type'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->NetworkType->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The network type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The network type could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('NetworkType.' . $this->NetworkType->primaryKey => $id));
			$this->request->data = $this->NetworkType->find('first', $options);
			
			foreach( $this->NetworkType->actsAs['Translate'] as $field => $fieldAlias ){
				$translations = Set::combine( $this->request->data, $fieldAlias.'.{n}.locale', $fieldAlias.'.{n}.content' );
				$this->request->data['NetworkType'][$field] = $translations;
			}
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->NetworkType->id = $id;
		if (!$this->NetworkType->exists()) {
			throw new NotFoundException(__('Invalid network type'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->NetworkType->delete()) {
			$this->Session->setFlash(__('The network type has been deleted.'));
		} else {
			$this->Session->setFlash(__('The network type could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
