<?php
App::uses('AppController', 'Controller');
/**
 * Favourites Controller
 *
 * @property Favourite $Favourite
 */
class FavouritesController extends AppController {
	
	function add( $service_id = null ){
		$success = false;
		
		// Is logged in only, otherwise just render.
		if( !$this->Auth->user('id') ) $this->goAway( __('You need to be logged in to favourite items'), '/' );

		$this->loadModel('Service');
		$this->loadModel('User');
		
		if( $service_id && ( $service = $this->Service->read( null, $service_id ) ) ){
			// Already Favourited?
			$hasRated = $this->Favourite->find('first', array( 
				'fields'=>'Favourite.id',
				'conditions'=>array(
					'Favourite.user_id' => $this->Auth->user('id'),
					'Favourite.service_id' => $service['Service']['id'],
					'Favourite.deleted' => null,
				)
			) );
			
			if( !$hasRated ){
				// Add new Favourite
				$this->Favourite->create();
				$success = $this->Favourite->save( array( 'Favourite'=>array(
					'user_id' => $this->Auth->user('id'),
					'service_id' => $service['Service']['id'],
				) ) );
			} else {
				// Already Favourited - update modified date
				$this->Favourite->save( $hasRated );
				$success = true;
			}
		}
		
		if( $this->request->is( 'ajax' ) ){
			$this->autoRender = false;
			echo $success?'true':'false';
			return;
		} else {
			$this->goAway( ( $success ? 
				__('Thanks! The item has been added to your favourites.'):
				__('Sorry, we couldn\'t process that request. Please try again later.')
			), '/' );
		}
	}

	function delete( $service_id = null ){
		$success = false;
		
		// Logged in
		if( !$this->Auth->user('id') ) $this->goAway( __('You need to be logged in to favourite items'), '/' );
		
		// Id and record found?
		if( $service_id && $favourite = $this->Favourite->find('first', array( 
			'fields'=>'Favourite.id',
			'conditions'=>array(
				'Favourite.user_id' => $this->Auth->user('id'),
				'Favourite.service_id' => $service_id,
				'Favourite.deleted' => null,
			)
		) ) ){
			// Soft delete
			$favourite['Favourite']['deleted'] = date('Y-m-d H:i:s');
			$success = $this->Favourite->save( $favourite );
		}
		
		// Success?
		if( $this->request->is( 'ajax' ) ){
			$this->autoRender = false;
			echo $success?'true':'false';
			return;
		} else {
			$this->goAway( ( $success ? 
				__('Thanks! The item has been removed from your favourites.'):
				__('Sorry, we couldn\'t find that favourite or an error occurred. Please try again later.')
			) , '/'  );
		}
	}



/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Favourite->recursive = 0;
		$this->set('favourites', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Favourite->exists($id)) {
			throw new NotFoundException(__('Invalid favourite'));
		}
		$options = array('conditions' => array('Favourite.' . $this->Favourite->primaryKey => $id));
		$this->set('favourite', $this->Favourite->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Favourite->create();
			if ($this->Favourite->save($this->request->data)) {
				$this->Session->setFlash(__('The favourite has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The favourite could not be saved. Please, try again.'));
			}
		}
		$users = $this->Favourite->User->find('list');
		$services = $this->Favourite->Service->find('list');
		$this->set(compact('users', 'services'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Favourite->exists($id)) {
			throw new NotFoundException(__('Invalid favourite'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Favourite->save($this->request->data)) {
				$this->Session->setFlash(__('The favourite has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The favourite could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Favourite.' . $this->Favourite->primaryKey => $id));
			$this->request->data = $this->Favourite->find('first', $options);
		}
		$users = $this->Favourite->User->find('list');
		$services = $this->Favourite->Service->find('list');
		$this->set(compact('users', 'services'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Favourite->id = $id;
		if (!$this->Favourite->exists()) {
			throw new NotFoundException(__('Invalid favourite'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Favourite->delete()) {
			$this->Session->setFlash(__('Favourite deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Favourite was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
