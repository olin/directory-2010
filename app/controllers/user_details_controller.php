<?php
class UserDetailsController extends AppController {

	var $name = 'UserDetails';

	function admin_index() {
		$this->UserDetail->recursive = 0;
		$this->set('userDetails', $this->paginate());
	}
	
	function edit() {
		$user = @$this->Session->read($this->Auth->sessionKey);
		$userID = @$user['id'];
		if($userID===false){
			$this->Session->setFlash(__('Something went wrong, please sign in later.'));
		}
		if (!empty($this->data)) {
			$this->data['UserDetail']['account_id'] = $userID;
			if ($this->UserDetail->save($this->data)) {
				$this->Session->setFlash(__('Your changes have been saved.', true));
				$this->redirect(array('action'=>'edit'));
			} else {
				$this->Session->setFlash(__('Your profile could not be saved. Please, try again later.', true));
			}
		}else{
			$this->data = $this->UserDetail->read(null, $userID);
		}
		$accounts = $this->UserDetail->Account->find('list');
		$buildings = $this->UserDetail->Building->find('list');
		$this->set(compact('accounts', 'buildings'));
	}
	
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user detail', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('userDetail', $this->UserDetail->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->UserDetail->create();
			if ($this->UserDetail->save($this->data)) {
				$this->Session->setFlash(__('The user detail has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user detail could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->UserDetail->Account->find('list');
		$buildings = $this->UserDetail->Building->find('list');
		$this->set(compact('accounts', 'buildings'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user detail', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->UserDetail->save($this->data)) {
				$this->Session->setFlash(__('The user detail has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user detail could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->UserDetail->read(null, $id);
		}
		$accounts = $this->UserDetail->Account->find('list');
		$buildings = $this->UserDetail->Building->find('list');
		$this->set(compact('accounts', 'buildings'));
	}

}
?>