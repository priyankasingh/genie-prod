<?php
App::uses('AppController', 'Controller');
/**
 * Pages Controller
 *
 * @property Page $Page
 */
class PagesController extends AppController {

	function beforeFilter(){
		$this->Auth->allow( array( 'view', 'display' ) );
		parent::beforeFilter();
	}

/**
 * Displays a dynamic view. Content is taken from the database.
 *
 * @param mixed What page to display
 * @return void
 */
	public function view($id = null){
		$slug = trim( $this->request->here, '/' );
		
		$this->Page->id = $id;
		if (!$this->Page->exists()) {
			if( isset( $this->params['requested'] ) ){
				return null;
			} else {
				throw new NotFoundException(__('404 - Page Not Found'));
			}
		}
		$page = $this->Page->read(null, $id);
		
		if( isset( $this->params['requested'] ) ){
			if( isset($page['Page']) ){
				return $page['Page'];
			} else {
				return null;
			}
		} else {
			$title_for_layout = $this->Page->data['Page']['name'];
			$this->set('body_class', $slug.' static-page');
			$this->set('active_nav', $slug);
			$this->set(compact('page', 'title_for_layout'));
		}
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Page->recursive = 0;
		$this->set('pages', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Page->exists($id)) {
			throw new NotFoundException(__('Invalid page'));
		}
		$options = array('conditions' => array('Page.' . $this->Page->primaryKey => $id));
		$this->set('page', $this->Page->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Page->create();
			if ($this->Page->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The page has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.'));
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
		$this->Page->locale = array_keys( Configure::read('Site.languages') );
		
		if (!$this->Page->exists($id)) {
			throw new NotFoundException(__('Invalid page'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Page->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The page has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Page.' . $this->Page->primaryKey => $id));
			$this->request->data = $this->Page->find('first', $options);
			
			foreach( $this->Page->actsAs['Translate'] as $field => $fieldAlias ){
				$translations = Set::combine( $this->request->data, $fieldAlias.'.{n}.locale', $fieldAlias.'.{n}.content' );
				$this->request->data['Page'][$field] = $translations;
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
		$this->Page->id = $id;
		if (!$this->Page->exists()) {
			throw new NotFoundException(__('Invalid page'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Page->delete()) {
			$this->Session->setFlash(__('Page deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Page was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
