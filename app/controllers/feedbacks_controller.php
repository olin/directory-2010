<?php
class FeedbacksController extends AppController {

	var $name = 'Feedbacks';
	var $components = array('Email', 'EmailMsg');
	var $helpers = array('Javascript');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index','sent');
	}
	
	function index(){
		$user = @$this->Auth->user();
		if (!empty($this->data)) {
			$email = $this->data['Feedback']['email'];
			$this->data['Feedback']['account_id'] = @$user['Account']['id'];
			if($this->Feedback->save($this->data)) {
				//send the email
				Configure::load('OlinDirectory');
				$feedback_admin = Configure::read('feedback.email');
				$title = $this->data['Feedback']['text'];
				if(strlen($title)>40)
					$title = substr($title,0,40)."...";
				$ret = $this->EmailMsg->sendMessage($this,"feedback",$feedback_admin,"Feedback: $title");
				if($ret){ //sending the email failed
					$this->Session->setFlash(__('Aww shucks, something broke. Please, try again in a few minutes.', true));
					$this->set('email_failed',true);
				}else{
					$this->redirect(array('action' => 'sent'));
				}
			} else {
				$this->Session->setFlash(__('Aww shucks, something broke. Please, try again in a few minutes.', true));
			}
		} else if ($user) {
			$this->data['Feedback']['email'] = @$user['Account']['email'];
			$this->data['Feedback']['name'] = @$user['Account']['firstName'] . ' ' . @$user['Account']['lastName'];
		}
	}
	
	function sent() {
		//do nothing
	}
	
	function admin_index() {
		$this->helpers[] = 'Time';
		$this->Feedback->recursive = 0;
		$this->set('feedbacks', $this->paginate());
	}

	function admin_view($id = null) {
		$this->helpers[] = 'Time';
		if (!$id) {
			$this->Session->setFlash(__('Invalid feedback', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('feedback', $this->Feedback->read(null, $id));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for feedback', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Feedback->delete($id)) {
			$this->Session->setFlash(__('Feedback deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Feedback was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>