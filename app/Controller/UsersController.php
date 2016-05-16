<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	public $components = array('Paginator');

	// AUTH FUNCTIONALITY
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('login', 'logout', 'forgot_password', 'check_for_new_service_updates');
	}

	public function isAuthorized($user = null) {
		// Only admin users can elevate permissions
		if( $this->action == 'admin_edit' || $this->action == 'admin_add' ){
			return in_array($user['role'], array('s', 'f'));
		}
		return parent::isAuthorized($user);
	}

	public function login() {

		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				// Fetch response
				$response = $this->User->Response->find('first', array(
					'fields'=>array('id','user_id'),
					'conditions'=>array('Response.user_id'=>$this->Auth->user('id')),
				));
				$this->Session->write('response', $response['Response']['id'] );

				// Redirect
				if( !empty( $this->request->data['User']['redirect'] ) ){
					$this->redirect( $this->request->data['User']['redirect'] );
				} elseif ($this->Auth->user('is_admin')) {
					$this->Session->setFlash( 'Welcome to '.Configure::read('Site.name').'! You are now logged in.' );
					$this->redirect('/admin/');
				} else {
					$this->redirect( '/services/my-map/' );
				}
			} else {
				$this->Session->setFlash( __('Invalid username or password, please try again.') . ' <a href="/users/forgot_password/">' . __("Click here if you've forgotten your password."). '</a>' );
			}
		} elseif($this->Auth->user('id')){
			$this->redirect('/services/my-map/');
		}
	}

	public function logout() {
		$this->Auth->logout();
		$this->Session->setFlash('<strong>'. __('You are now logged out.') . '</strong>' . __(' Thanks for using ') .Configure::read('Site.name').'.');
		$this->Session->destroy();
		$this->redirect('/');
	}

	// Use another user's response
    public function admin_use_response($id) {
		// Fetch response
		$response = $this->User->Response->find('first', array(
			'fields'=>array('id','user_id'),
			'conditions'=>array('Response.id'=>$id),
		));

		if( !$response ) throw new NotFoundException(__('Invalid Response ID'));

		// Switch to new response
		$this->Session->write('response', $response['Response']['id'] );
		$this->Session->write('response_replaced', true);

		$this->redirect(array('admin'=>false, 'controller'=>'services', 'action'=>'index', 'my-map'));
        $this->autoRender = false;
    }

	// Log out as another user
    public function admin_restore_response() {
		$response = $this->User->Response->find('first', array(
			'fields'=>array('id','user_id'),
			'conditions'=>array('Response.user_id'=>$this->Auth->user('id')),
		));
		$this->Session->write('response', $response['Response']['id'] );
		$this->Session->write('response_replaced', false);

		$this->redirect($this->Auth->redirectUrl());
		$this->autoRender = false;
    }

	public function account() {
		$this->set('title_for_layout', __('My Account'));

		// Dont change pw if empty
		if( empty( $this->request->data['User']['password'] ) ){
			unset( $this->request->data['User']['password'] );
			unset( $this->request->data['User']['old_password'] );
			unset( $this->request->data['User']['password_confirm'] );
		} else {
			// Require old password to be set if changing, though
			if( empty($this->request->data['User']['old_password'] ) )
				$this->request->data['User']['old_password'] = '@';
			if( empty($this->request->data['User']['password_confirm'] ) )
				$this->request->data['User']['password_confirm'] = '@';
		}


		if ($this->request->is('post') || $this->request->is('put')) {
				$this->request->data['User']['id'] = $this->Auth->user('id');

			if ($this->User->save($this->request->data, true, array('id', 'email', 'password', 'password_confirm', 'old_password') )) {
				$this->Session->setFlash(__('Account updated.'));
			} else {
				$this->Session->setFlash(__('Please double check your entry...'));
			}
		} else {
			$options = array('conditions' => array('User.id' => $this->Auth->user('id')));
			$this->request->data = $this->User->find('first', $options);
			$this->request->data['User']['password'] = '';
		}
	}

	function forgot_password( $id=null, $key=null ){
		$this->set('title_for_layout', __('Forgot password'));

		// Logged in?
		if( $this->Auth->user('id') ) $this->redirect('/');

		if( !empty( $this->request->data ) && ( !$id || !$key ) ){
			if( ( $user = $this->User->find( 'first', array('conditions'=>array(
					'email'=>$this->request->data['User']['email']
			) ) ) ) == true ){
				// Step 1 - submit username form

				// Email code
				$emailCode = $this->User->generateHash(15);
				$this->User->id = $user['User']['id'];
				$this->User->saveField('forgot_password_key', $emailCode, false);

				// Send email
				App::uses('CakeEmail', 'Network/Email');
				$Email = new CakeEmail();
				$Email
					->emailFormat('text')
					->template('forgot_password', 'default')
					->viewVars(array(
						'emailId' => $user['User']['id'],
						'emailName' => $user['User']['email'],
						'emailKey' => $emailCode,
					))
					->from( Configure::read('Site.email_from') )
					->subject(__('Password Reset for your ') .Configure::read('Site.name').' account')
					->to( $user['User']['email'] );

				$Email->send();
				$this->set( 'success', 1 );

			} else {
				$this->Session->setFlash(__('Sorry, that account could not be found.'));
			}
		} elseif( $id && $key ){
			// Step 2 - Clicked email link
			$this->User->recursive = 1;
			$user = $this->User->find( 'first', array('conditions'=>array('User.id'=>$id)) );

			if( $user && $key === $user['User']['forgot_password_key'] ){
				// Process password form?
				if ($this->request->is('post') || $this->request->is('put')) {
					$this->request->data['User']['id'] = $user['User']['id'];
					$this->request->data['User']['forgot_password_key'] = null;
					if( empty($this->request->data['User']['password_confirm'] ) )
						$this->request->data['User']['password_confirm'] = '';

					if ($this->User->save($this->request->data, true, array('id', 'forgot_password_key', 'password', 'password_confirm') )) {
						// Step 3 - Done
						$this->set('success', 3);

						// Send email
						App::uses('CakeEmail', 'Network/Email');
						$Email = new CakeEmail();
						$Email
							->emailFormat('text')
							->template('new_password', 'default')
							->viewVars(array(
								'emailId' => $user['User']['id'],
								'emailName' => $user['User']['email'],
								'emailPass' => $newPass,
							))
							->from( Configure::read('Site.email_from') )
							->subject(__('Password changed for your ').Configure::read('Site.name').' account')
							->to( $user['User']['email'] );

						$Email->send();

					} else {
						$this->Session->setFlash(__('Please double check your entry...'));
						$this->set('success', 2);
					}
				} else {
					$this->set('success', 2);
				}
			} else {
				$this->Session->setFlash(__('Sorry, that reset link is invalid.'));
			}
		}

		$this->set('user_id',$id);
		$this->set('user_key',$key);
	}

	public function check_for_new_service_updates() {
		App::uses('CakeEmail', 'Network/Email');

		$this->loadModel('ServiceEdit');
		$facilitators = $this->User->find('all',
			array(
		    'conditions' => array(
		      'role' => 'f',
		      'is_admin' => 1,
		    ),
		    'contain' => false
		  )
		);
		// pr($facilitators);

		foreach ($facilitators as $facilitatorKey => $facilitator) {
			$newEdits = false;
			$champions = $this->User->find('all',
				array(
					'conditions' => array(
						'facilitator_id' => $facilitator['User']['id'],
					),
					'contain' => array(
						'ServiceEdit' => array(
							'conditions' => array(
								'approved' => 0
							)
						)
					)
				)
			);

			foreach ($champions as $championKey => $champion) {
				if (!empty($champion['ServiceEdit'])) {
					$newEdits = true;
				}
			}

			if ($newEdits) {
				$email = new CakeEmail();
				$email->from(array('noreply@eu-genie.org' => 'EU-GENIE'));
				$email->to($facilitator['User']['email']);
				$email->template('services_edits_notification');
				$email->emailFormat('html');
				$email->subject('New service modifications');

				if ($email->send()) {
					echo "Sent new services edits notification to " . $facilitator['User']['email'];
				} else {
					echo "Unable to send new services edits notification to " . $facilitator['User']['email'];
				}
				unset($email);
			}
		}

		exit;

	}

	// ADMIN

	public function admin_index() {
		$this->Paginator->settings['maxLimit'] = 1000;
		$this->User->recursive = 0;
		if ($this->Auth->user('role') === 'f') {
			$this->Paginator->settings['conditions'] = array('role' => array('c'));
		}
		$this->set('users', $this->Paginator->paginate('User'));
	}

	public function admin_view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$data = $this->User->find('first', $options);

		/*
		 * Facilitators should only be able to view champions
		 */
		if ($this->Auth->user('role') === 'f') {
			if ($data['User']['role'] != 'c') {
				$this->Session->setFlash(__('You don\'t have permissions to view that user.'));
				$this->redirect(array('action' => 'index'));
			}
		}

		$this->set('user', $data);
	}

	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}

		/*
		 * Facilitators should only be able to add champions
		 */
		if ($this->Auth->user('role') === 'f') {
			$this->render('admin_add_champion');
		}

		$this->set('facilitatorsList', $this->User->getFacilitatorsList());
		$this->set('roles', $this->User->getRoles());
	}

	public function admin_edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}

		// Dont change pw if empty
		if( empty( $this->request->data['User']['password'] ) ) {
			unset( $this->request->data['User']['password'] );
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$data = $this->User->find('first', $options);
			$this->request->data = $data;

			/*
			 * Facilitators should only be able to edit champions
			 */
			if ($this->Auth->user('role') === 'f') {
				if ($data['User']['role'] != 'c') {
					$this->Session->setFlash(__('You don\'t have permissions to view that user.'));
					$this->redirect(array('action' => 'index'));
				}
				$this->render('admin_edit_champion');
			}
		}
		$this->set('facilitatorsList', $this->User->getFacilitatorsList());
		$this->set( 'roles', $this->User->getRoles() );
	}

	public function admin_delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

}
