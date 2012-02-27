<?php
class Feedback2Controller extends AppController {

	var $name = 'Feedback2';
	var $components = array('Email', 'EmailMsg');
	var $helpers = array('Javascript');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index','sent');
	}

	function index(){
		//Configure::load('OlinDirectory');
		if (!empty($this->data)) {
			$email = $this->data['JoinRequest']['email'];
			//TODO: Validate feedback
			/*if($this->JoinRequest->save($this->data)) {
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
			}*/
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
	
}
?>