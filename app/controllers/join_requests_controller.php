<?php
class JoinRequestsController extends AppController {

	var $name = 'JoinRequests';
	var $components = array('RandomHelper', 'TimeHelper', 'OlinHelper', 'Security', 'Email', 'EmailMsg');
	var $helpers = array('Javascript');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index','sent','verify','verify_problem','verify_exists');
	}

	function index(){
		Configure::load('OlinDirectory');
		if(Configure::read('join.closed')) {
			$this->set('join_closed',true);
		}else if (!empty($this->data)) {
			$email = $this->data['JoinRequest']['email'];
			$secret = $this->RandomHelper->string(20);
			$this->data['JoinRequest']['hash'] = Security::hash($secret,'sha1',true);
			$this->data['JoinRequest']['expires'] = $this->TimeHelper->futureSQL(0,30,0);
			$this->JoinRequest->create();
			if(!$this->OlinHelper->canEmailRegister($email)){
				$this->set('bad_email',true);
				unset($this->data['JoinRequest']['hash']);
				unset($this->data['JoinRequest']['expires']);
			}else if($this->JoinRequest->save($this->data)) {
				$email = $this->data['JoinRequest']['email'];
				$this->Session->write('jr.email',$email);
				$this->data['JoinRequest'] = array('email'=>$email);
				//send the email
				$ret = $this->EmailMsg->sendMessage($this,"welcome",$email,"Welcome!",$secret);
				if($ret){ //sending the email failed
					$this->Session->setFlash(__('Aww shucks, something broke. Please, try again in a few minutes.', true));
					$this->set('email_failed',true);
				}else{
					$this->redirect(array('action' => 'sent'));
				}
			} else {
				$this->Session->setFlash(__('Aww shucks, something broke. Please, try again in a few minutes.', true));
			}
		}else if($this->Session->check('rr.authName')) {
			$this->data['JoinRequest']['email'] = $this->Session->read('rr.authName');
		}
		$email = @$this->data['JoinRequest']['email'];
		if($email && strlen($email)>0 && !$this->OlinHelper->canEmailRegister($email)){
			$this->set('bad_email',true);
		}
	}

	function sent(){
		if($this->Session->read('jr.email')==null){
			$this->redirect(array('action'=>'index'));
		}
		//boom, sent
		$this->set('jr_email',$this->Session->read('jr.email'));
		$this->set('jr_secret',$this->Session->read('jr.secret'));
		$this->Session->delete('jr.email');
		$this->Session->delete('jr.secret');
	}

	function verify($secret = null){
		$verified = false;
		$jr = null;
		//initially $session is in the get string, so we pull it from there
		if($secret!=null){
			$this->Session->write('jr.secret',$secret);
			//on subsequent calls we retrieve the secret from the session
		}else{
			if($this->Session->check('jr.secret')){
				$secret = $this->Session->read('jr.secret');
			}
		}
		//now we verify the secret before allowing access
		if($secret != null){
			$shash = Security::hash($secret,'sha1',true);
			$jr = $this->JoinRequest->find('first',array(
				'conditions' => array('hash' => $shash)
			));
			if($jr && isset($jr['JoinRequest'])){
				$jr = $jr['JoinRequest'];
				$expired = strtotime($jr['expires']) < time();
				if(!$jr['used'] && !$expired){ $verified = true; }
			}else{
				$jr = null;
				$this->Session->delete('jr.secret');
			}
		}
		//check to see if an account with this email address already exists
		$this->loadModel('Account');
		$a = $this->Account->find('first',array(
			'conditions' => array('email' => $jr['email'])
		));
		//account already exists
		if(!$jr['used'] && $a & isset($a['Account'])){ //error, account already exists and JR hasn't already been used
			$this->Session->delete('jr.secret');
			$jr['used'] = 1;
			$this->JoinRequest->save(array('JoinRequest'=>$jr),false,array('used'));
			$this->Session->write('rr.authName',$jr['email']);
			$this->redirect(array('action' => 'verify_exists'));
		}
		//if secret fails, redirect and cease operation
		if(!$verified){
			$this->Session->delete('jr.secret');
			$this->redirect(array('action' => 'verify_problem'));
		}
		//now secret is verified, prompt for information
		$this->set('email',$jr['email']);
		//on initial load, attempt to guess name from email
		if(empty($this->data)){
			$this->data['Account'] = array();
			$guess = $this->OlinHelper->guessNameFromEmail($jr['email']);
			if($guess){
				$this->data['Account'] = $guess;
			}
		}else{
			//once user submits data, validate and set up account
			//validate data and attempt to create account (will fail if validation fails)
			$this->Account->create();
			$this->data['Account']['authName'] = $jr['email'];
			$this->data['Account']['authType'] = 'Email';
			$this->data['Account']['email'] = $jr['email']; //copy the property
			//$this->Auth only automatically hashes passwords in $this->data[$this->name], sadly
			//meaning we have to do it ourselves
			if(isset($this->data['Account']['authPassword']) && $this->data['Account']['authPassword']!=""){
				$this->data['Account']['authPassword'] = $this->Auth->password($this->data['Account']['authPassword']);
				$this->data['Account']['authPassword_confirm'] = $this->Auth->password($this->data['Account']['authPassword_confirm']);
			}
			if ($this->Account->save($this->data, true, array('authType','authName','authPassword','firstName','lastName','email'))) {
				//create the associated UserDetail
				$this->data['UserDetail']['account_id'] = $this->Account->id;
				$this->Account->UserDetail->save($this->data);
				//revoke the join request, it has now been used
				$this->Session->delete('jr.secret');
				$jr['used'] = 1;
				$this->JoinRequest->save(array('JoinRequest'=>$jr));
				if($this->Auth->login($this->data)){
					$this->redirect(array('controller'=>'account', 'action'=>'welcome'));
				}else{
					$this->Session->setFlash(__('Aww shucks, something broke. Please, try again in a few minutes.', true));
				}
			} else {
				//$this->Session->setFlash(__('Aww shucks, something broke. Please, try again in a few minutes.', true));
			}
			//if the user couldn't be created, remove the password from being displayed
			$this->data['Account']['authPassword'] = "";
			$this->data['Account']['authPassword_confirm'] = "";
		}

	}

	function verify_problem(){
		//just show that there's a problem and couldn't verify user :(
		//no other code needed here
	}

	function verify_exists(){
		//just show warning that an account already exists :(
		//no other code needed here
		if($this->Session->check('rr.authName')){
			$this->set('email',$this->Session->read('rr.authName'));
		}
	}
	
	function admin_index() {
		$this->helpers[] = 'Time';
		$this->JoinRequest->recursive = 0;
		$this->set('joinRequests', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid join request', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('joinRequest', $this->JoinRequest->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$secret = $this->RandomHelper->string(20);
			$this->data['JoinRequest']['hash'] = Security::hash($secret,'sha1',true);
			$this->data['JoinRequest']['expires'] = $this->TimeHelper->futureSQL(0,30,0);
			$this->JoinRequest->create();
			if ($this->JoinRequest->save($this->data)) {
				$this->Session->setFlash(__('The join request has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The join request could not be saved. Please, try again.', true));
			}
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for join request', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->JoinRequest->delete($id)) {
			$this->Session->setFlash(__('Join request deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Join request was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>