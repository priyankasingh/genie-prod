<?php
App::uses('AppController', 'Controller');
/**
 * Contacts Controller
 *
 * @property Contact $Contact
 */
class ContactsController extends AppController {

	function beforeFilter(){
        $this->Auth->allow(array('add'));
        parent::beforeFilter();
    }

    public function add(){
        $this->set('title_for_layout', __('Contact Us'));

        // Save/validate data?
        if ($this->request->is('post')) {

            // Save everything
            $fieldList = array('name', 'email', 'question');

            if( $this->Contact->save($this->request->data, array('fieldList' => $fieldList))){
                $this->Session->setFlash(__('We have received your message and will get back to you shortly.'));
                try {
	                $this->_sendEmail();
	            }
	            catch(Exception $e) {
	            	$this->log("Contact form email didn't send");
	            }
                $this->request->data = array();
            }
            else {
            	$this->Session->setFlash(__('Please check the form fields for any errors'));
            }
        }

        // Prepare view
        $contacts = $this->Contact->find('all', array());

        $this->set('contacts', $contacts);
        $this->set('editing', $contacts);

        $this->set('active_nav', 'contact');
    }

    protected function _sendEmail(){
        App::uses('CakeEmail', 'Network/Email');
        $Email = new CakeEmail();
        $Email
            ->emailFormat('text')
            ->template('new_user', 'default')
            ->from(Configure::read('Site.email_from'))
            ->replyTo($this->request->data['Contact']['email'])
            ->subject('EU-GENIE Contact Form')
            ->to(Configure::read('Site.email_to'));

        $Email->send();
    }

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Contact->recursive = 0;
		$this->set('contacts', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Contact->exists($id)) {
			throw new NotFoundException(__('Invalid contact'));
		}
		$options = array('conditions' => array('Contact.' . $this->Contact->primaryKey => $id));
		$this->set('contact', $this->Contact->find('first', $options));
	}
 
/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Contact->create();
			if ($this->Contact->save($this->request->data)) {
				$this->Session->setFlash(__('The contact has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The contact could not be saved. Please, try again.'));
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
		if (!$this->Contact->exists($id)) {
			throw new NotFoundException(__('Invalid contact'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Contact->save($this->request->data)) {
				$this->Session->setFlash(__('The contact has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The contact could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Contact.' . $this->Contact->primaryKey => $id));
			$this->request->data = $this->Contact->find('first', $options);
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
		$this->Contact->id = $id;
		if (!$this->Contact->exists()) {
			throw new NotFoundException(__('Invalid contact'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Contact->delete()) {
			$this->Session->setFlash(__('Contact deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Contact was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
