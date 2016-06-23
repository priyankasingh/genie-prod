<?php
App::uses('AppController', 'Controller');
/**
 * Services Controller
 *
 * @property Service $Service
 */
class OnlineResourcesController extends AppController {
    
    function beforeFilter(){
	$this->Auth->allow(array('index', 'view'));
	parent::beforeFilter();
    }
    
    //public $helpers = array('Html', 'Form');

    public function index() {
        $this->set('onlineResources', $this->OnlineResource->find('all'));
        pr($onlineResources);
    }
    
    /**
    * admin_index method
    *
    * @return void
    */
    public function admin_index() {
		$this->OnlineResource->recursive = 0;
		$this->set('onlineResources', $this->paginate());
    }
    
    /**
    * admin_view method
    *
    * @throws NotFoundException
    * @param string $id
    * @return void
    */
    public function admin_view($id = null) {
	if (!$this->OnlineResource->exists($id)) {
            throw new NotFoundException(__('Invalid Online Service'));
	}
	$options = array('conditions' => array('OnlineResource.' . $this->OnlineResource->primaryKey => $id));
	$this->set('onlineResources', $this->OnlineResource->find('first', $options));
    }
    
    
    /**
    * admin_add method
    *
    * @return void
    */
    public function admin_add() {
	//$this->OnlineResource->locale = array_keys( Configure::read('Site.languages') );
	
	if ($this->request->is('post')) {
            $this->OnlineResource->create();
            debug($this->request->data);
            if ($this->OnlineResource->save($this->request->data)) {
		$this->Session->setFlash(__('The online resource has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
		$this->Session->setFlash(__('The online resource could not be saved. Please, try again.'));
            }
	}
	$categories = $this->OnlineResource->Category->find('list');
        $this->set(compact('categories'));
    }
    
    
    
    
}