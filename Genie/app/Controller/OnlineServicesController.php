<?php
App::uses('AppController', 'Controller');
/**
 * Services Controller
 *
 * @property Service $Service
 */
class OnlineServicesController extends AppController {
    
    public $helpers = array('Html', 'Form');

    public function index() {
        $this->set('online_services', $this->OnlineService->find('all'));
    }
    
}