<?php
class ResetRequestsController extends AppController {

	var $name = 'ResetRequests';
	var $components = array('RandomHelper', 'TimeHelper', 'Security', 'Email', 'EmailMsg');
	var $helpers = array('Javascript');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index','sent','verify','verify_problem','verify_exists');
	}
	
	function admin_index() {
		$this->helpers[] = 'Time';
		$this->ResetRequest->recursive = 0;
		$this->set('resetRequests', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid reset request', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('resetRequest', $this->ResetRequest->read(null, $id));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for reset request', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ResetRequest->delete($id)) {
			$this->Session->setFlash(__('Reset request deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Reset request was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function index(){
		if (!empty($this->data) && isset($this->data['ResetRequest']['email'])) {
			$authName = $this->data['ResetRequest']['email'];
			$this->loadModel('Account');
			$acct = $this->Account->find('first',array(
				'conditions' => array('Account.authName' => $authName),
				'fields' => array('id','email','firstName','lastName')
			));
			$this->Session->write('rr.email',$authName);
			$aid = isset($acct['Account']['id']) ? $acct['Account']['id'] : 0;
			$secret = $this->RandomHelper->string(20);
			$this->data['ResetRequest']['account_id'] = $aid; 
			$this->data['ResetRequest']['hash'] = Security::hash($secret,'sha1',true);
			$this->data['ResetRequest']['expires'] = $this->TimeHelper->futureSQL(0,30,0);
			$this->ResetRequest->create();
			if ($this->ResetRequest->save($this->data,true,array('email','account_id','hash','expires'))) {
				$email = $acct['Account']['email'];
				$firstName = null; $lastName = null;
				if(isset($acct['Account']['firstName'])) $firstName = $acct['Account']['firstName'];
				if(isset($acct['Account']['lastName'])) $firstName = $acct['Account']['lastName'];
				$this->data['ResetRequest'] = array('email'=>$email);
				//send the email
				$ret = $this->EmailMsg->sendMessage($this,"reset",$email,"Reset Your Password",$secret,$firstName,$lastName);
				if($ret){ //sending the email failed
					$this->Session->setFlash(__('Aww shucks, something broke. Please, try again in a few minutes.', true));
					$this->set('email_failed',true);
				}else{
					$this->redirect(array('action' => 'sent'));
				}
			} else {
				$this->Session->setFlash(__('Aww shucks, something broke. Please, try again in a few minutes.', true));
			}
		}else{
			$this->data['ResetRequest'] = array('email' => $this->Session->read('rr.authName'));
		}
	}
	
	function sent(){
		if($this->Session->read('rr.email')==null){
			$this->redirect(array('action'=>'index'));
		}
		//boom, sent
		$this->set('rr_email',$this->Session->read('rr.email'));
		$this->set('rr_secret',$this->Session->read('rr.secret'));
		//$this->Session->delete('rr.email');
		//$this->Session->delete('rr.secret');
	}
	
	function verify($secret = null){
		$verified = false;
		$rr = null;
		//initially $session is in the get string, so we pull it from there
		if($secret!=null){
			//$this->Session->write('rr.secret',$secret);
			//on subsequent calls we retrieve the secret from the session
		}else{
			if(isset($_POST['token'])){
				$secret = $_POST['token'];
			}
		}
		//now we verify the secret before allowing access
		if($secret != null){
			$shash = Security::hash($secret,'sha1',true);
			$rr = $this->ResetRequest->find('first',array(
				'conditions' => array('hash' => $shash)
			));
			if($rr && isset($rr['ResetRequest'])){
				$rr = $rr['ResetRequest'];
				$expired = strtotime($rr['expires']) < time();
				if(!$rr['used'] && !$expired){ $verified = true; }
			}else{
				$rr = null;
				//$this->Session->delete('rr.secret');
			}
		}
		if($rr!=null) {
			//check to see if an account with this email address already exists
			//if no such account exists, flag this reset request as invalid
			$this->loadModel('Account');
			$a = $this->Account->find('first',array(
				'conditions' => array('Account.id' => $rr['account_id'])
			));
			//account doesn't exist
			if(!($a & isset($a['Account']))){ //error, account doesn't exist
				//$this->Session->delete('rr.secret');
				$rr['used'] = 1;
				$this->ResetRequest->save(array('ResetRequest'=>$rr));
				/*$this->Session->setFlash('Account '.$rr['account_id'].' does not exist.');
				print "<pre>".'Account '.$rr['account_id'].' does not exist.'."\n";
				print_r($this->data);
				die();*/
				$this->redirect(array('action' => 'verify_problem'));
			}
			$a = $a['Account'];
		}
		//if secret fails, redirect and cease operation
		if(!$verified){
			/*$this->Session->delete('rr.secret');
			$this->Session->setFlash('General failure resetting acct '.$rr['account_id']);
			print "<pre>".'General failure resetting acct '.$rr['account_id']."\n";
			print "token = \"$secret\"\n";
			print_r($this->data);
			die();*/
			$this->redirect(array('action' => 'verify_problem'));
		}
		//now secret is verified, prompt for information
		$this->set('email',$a['email']);
		if (!empty($this->data)) {
			//once user submits data, validate and set up account
			//validate data and attempt to reset password
			$this->data['Account']['authName'] = $a['email'];
			$this->data['Account']['authType'] = 'Email';
			$this->data['Account']['id'] = $a['id']; //copy the property
			//$this->Auth only automatically hashes passwords in $this->data[$this->name], sadly
			//meaning we have to do it ourselves
			if(isset($this->data['Account']['authPassword']) && $this->data['Account']['authPassword']!=""){
				$this->data['Account']['authPassword'] = $this->Auth->password($this->data['Account']['authPassword']);
				$this->data['Account']['authPassword_confirm'] = $this->Auth->password($this->data['Account']['authPassword_confirm']);
			}
			if ($this->Account->save($this->data, true, array('authType','authPassword','authPassword_confirm'))) {
				//revoke the reset request, it has now been used
				//$this->Session->delete('rr.secret');
				unset($this->data['token']);
				$rr['used'] = 1;
				$this->ResetRequest->save(array('ResetRequest'=>$rr),false,array('used'));
				if($this->Auth->login($this->data)){
					$this->Session->setFlash('Your password has been reset');
					$this->redirect(array('controller'=>'account', 'action'=>'edit'));
				}else{
					$this->Session->setFlash('Aww shucks, something broke. Please, try again in a few minutes.', true);
				}
			} //if we get here, validation failed or otherwise couldn't save to DB
		}
		//never send passwords back to the UI
		$this->data['Account']['authPassword'] = "";
		$this->data['Account']['authPassword_confirm'] = "";
		unset($this->data['Account']['id']); //delete so forms work out right
		$this->set('token',$secret);

	}
	
	function verify_problem(){
		//just show that there's a problem and couldn't verify user :(
		//no other code needed here
	}
	
}
?>