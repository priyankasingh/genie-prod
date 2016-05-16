<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	var $components = array(
		'DebugKit.Toolbar',
		'Session',
		'Cookie',
		'Auth' => array(
			'loginAction' => array(
				'controller' => 'users',
				'action' => 'login',
				'admin' => 0,
			),
			'authError' => "Sorry, you\'re not allowed to do that. You may need to log in first.",
			'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'email', 'password' => 'password'),
					'userModel' => 'User',
					'scope' => array( 'User.deleted' => null ),
				)
			),
			'authorize' => array('Controller'),
		),
	);

	var $helpers = array('Text','Form','Html','Time','Session','Js');

	function beforeFilter(){
		parent::beforeFilter();

		$this->Auth->authError = __("Sorry, you're not allowed to do that.", true);

		// Language redirection
		if( empty( $this->request->params['admin'] ) && empty( $this->request->params['requested'] ) ) $this->_checkLanguage();

		// Layouts
		if( $this->request->is('ajax') ){
			$this->layout = 'ajax';
		} elseif( !empty( $this->request->params['prefix'] ) && $this->request->params['prefix'] == 'admin' ){
			$this->layout = 'admin' ;
		}

		// Ensure KCFINDER is set up properly
		if( $this->Auth->user() && $this->Auth->user('is_admin') ){
			$_SESSION['KCFINDER'] = array(
				'disabled' => false,
				'uploadURL' => Configure::read('Site.kcfinder_upload_url'),
				'uploadDir' => Configure::read('Site.kcfinder_upload_dir'),
			);
		} else {
			unset($_SESSION['KCFINDER']);
		}

	}

	function beforeRender(){
		parent::beforeRender();

		$this->set('parent_categories', ClassRegistry::init('Category')->getParentCategories());

		// Admin permissions
		if( !empty( $this->request->params['prefix'] ) && $this->request->params['prefix'] == 'admin' ){
			$this->loadModel('User');
			$this->set('permitted_controllers', $this->User->getWhitelist( AuthComponent::user('role') ) );
		}

		$this->set('overriden_response', $this->Session->read('response_replaced') );

		// Get number of modified services for currently logged in Facilitator
		if ($this->Auth->user('role') == 'f') {
		  $facilitatorId = $this->Auth->user('id');

		  // Get updated records
		  $facilitatorChampions = $this->User->find('all',
		    array(
		      'conditions' => array(
		        'facilitator_id' => $facilitatorId,
		      )
		    )
		  );

		  $this->loadModel('ServiceEdit');
		  $modifiedServicesForFacilitator = 0;
		  foreach ($facilitatorChampions as $key => $value) {
		  	$modifiedServicesForFacilitator += $this->ServiceEdit->find('count',
		  		array(
		  			'conditions' => array(
		  				'user_id' => $value['User']['id'],
		  				'approved' => 0
		  			)
		  		)
		  	);
		  }
		  $this->set(compact('modifiedServicesForFacilitator'));
		}

		// Disable login
		//$this->Auth->logout();
		//$this->Session->setFlash( '<strong>Login and registration is currently disabled while we undergo maintenance.</strong> Thanks for your patience.' );
	}

	public function isAuthorized($user = null) {
		// Sanity
		if(!$user) {
			return false;
		}

		// Only admins can access admin functions
		if(isset($this->request->params['admin'])){
			if(!$user['is_admin']) {
				return false;
			}

			// Certain roles have access blacklisted to some controllers
			$this->loadModel('User');
			return $this->User->isAdminPermitted($user['role'], $this->request->controller);
		}

		// Unless overridden, all other actions are permitted for logged in users
		return true;
	}

	/* LANGUAGE */
	protected function _checkLanguage() {
		// Ensure param is set correctly in URL
		$currentLang = $this->_getLanguage();
		if( !isset( $this->params['language'] ) || $currentLang != $this->params['language'] ){
			$this->redirect( "/$currentLang/".$this->request->url );
		} else {
			$this->_setLanguage( $currentLang );
		}

	}

	protected function _setLanguage( $lang ) {
		if( $this->_validLanguage( $lang ) ){
			Configure::write( 'Config.language', $lang );

			if( $lang != $this->Cookie->read('lang') ){
				$this->Cookie->write('lang', $lang, false, '20 days');
			}
			return true;
		}
		return false;
	}

	protected function _getLanguage() {
		$paramLang = $this->params['language'];
		$cookieLang = $this->Cookie->read('lang');

		if( $this->_validLanguage( $paramLang ) ) return $paramLang;
		elseif( $this->_validLanguage( $cookieLang ) ) return $cookieLang;

		return Configure::read( 'Config.language' );
	}

	protected function _validLanguage( $lang ){
		return array_key_exists( $lang, Configure::read( 'Site.languages' ) );
	}

	/* REFERER */
	protected function _getRedirect( $default ){
		$redirect = '';
		if( !empty( $this->request->data['referer'] ) ){
			$redirect = $this->request->data['referer'];
		} elseif( $this->referer() ){
			$redirect = $this->referer();
		}

		if( !$redirect || Router::url($redirect) == Router::url() ){
			$redirect = $default;
		}
		return $redirect;
	}

	/*function storeRedirect( $default = '/' ){
		$this->data['referer'] = $this->_getRedirect( $default );
	}*/

	protected function goAway( $message='You cannot perform that action. Check you are logged in.', $default='/' ){
		$this->Session->setFlash( $message );
		$this->redirect( $this->_getRedirect( $default ) );
	}

	// Reusable methods
	protected function _signupEmail($emailAddress){
		App::uses('CakeEmail', 'Network/Email');

		$userModel = isset( $this->Response ) ? $this->Response->User : $this->User;

		$Email = new CakeEmail();
		$Email
			->emailFormat('text')
			->template('new_user', 'default')
			->viewVars(array('url' => Configure::read('Site.url'), 'password' => $userModel->newPassword))
			->from(Configure::read('Site.email_from'))
			->subject('Thank you for registering with EU-GENIE')
			->to($emailAddress);

		$Email->send();
	}
}
