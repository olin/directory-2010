<?php
class AccountsController extends AppController {

	var $name = 'Accounts';
	var $helpers = array('Javascript');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index','signin','admin_signin');
    }
	
	function index() {
		 $this->redirect(array('controller' => 'account', 'action' => 'edit'));
	}
	
	function welcome() {
		//do nothing, just show view
	}
	
	function signin() {
		//if user is already signed in (or signed in successfully), redirect them
		if($this->Auth->user()!==null) {
			if(isset($this->data['Account']['authName'])) {
				$this->Session->write('rr.authName',$this->data['Account']['authName']);
			}
			$this->redirect($this->Auth->loginRedirect);
		}
		if(isset($this->data['Account']['authName'])) {
			$this->Session->write('rr.authName',$this->data['Account']['authName']);
		}else if($this->Session->check('rr.authName')) {
			$this->data['Account']['authName'] = $this->Session->read('rr.authName');
		}
		$this->data['Account']['authPassword'] = '';
	}
	
	function signout() {
		$this->Session->setFlash("You have been signed out");
		$this->redirect($this->Auth->logout());
	}
	
	function admin_index() {
		$this->Account->recursive = 0;
		$this->set('accounts', $this->paginate());
	}
	
	function admin_signin() {
		$this->redirect(array('action'=>'signin','admin'=>0));
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid account', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('account', $this->Account->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			//Auth automatically hashes authPassword but not the confirm; we must hash confirm so the equality validator succeeds
			$this->data['Account']['authPassword_confirm'] = $this->Auth->password($this->data['Account']['authPassword_confirm']);
			$this->data['Account']['email'] = $this->data['Account']['authName'];
			$this->Account->create();
			if ($this->Account->save($this->data)) {
				//create the associated UserDetail
				$this->data['UserDetail']['account_id'] = $this->Account->id;
				$this->Account->UserDetail->save($this->data);
				//redirect
				$this->Session->setFlash(__('The account has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.', true));
			}
		}
	
		$this->data['Account']['authPassword'] = "";
		$this->data['Account']['authPassword_confirm'] = "";
	}

	function admin_edit($id = null) {
		$saveableFields = array('authType','authName','permissions','firstName','lastName','email');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid account', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			//determine whether user wanted to reset password too
			$authPassword = @$this->data['Account']['authPassword'];
			$authPasswordConf = @$this->data['Account']['authPassword_confirm'];
			if($authPassword || $authPasswordConf){ //user wants to reset password
				//Auth automatically hashes authPassword but not the confirm; we must hash confirm so the equality validator succeeds
				$this->data['Account']['authPassword_confirm'] = $this->Auth->password($authPasswordConf);
				$saveableFields[] = 'authPassword';	
			}else{ //don't reset password
				unset($this->data['Account']['authPassword']);
				unset($this->data['Account']['authPassword_confirm']);
			}
			//attempt to save data
			if ($this->Account->save($this->data, true, $saveableFields)) {
				$this->Session->setFlash(__('The account has been saved', true));
				$this->redirect(array('action' => 'view', $id));
			}
			//otherwise, we failed to save and so redisplay the page
		}else{
			$this->data = $this->Account->read(null, $id);
		}
		//never pass hashed password data back to the frontend
		$this->data['Account']['authPassword'] = "";
		$this->data['Account']['authPassword_confirm'] = "";
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for account', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Account->delete($id)) {
			$this->Session->setFlash(__('Account deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Account was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>