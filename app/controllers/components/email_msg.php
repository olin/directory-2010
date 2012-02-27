<?php
class EmailMsgComponent extends Object {
	
	var $DEBUG = false;
	
	function sendMessage($controller,$template,$to,$title,$token=null,$firstName=null,$lastName=null){
		//preprocess some variables
		$fullName =  ($firstName!=null && $lastName!=null) ? "$firstName $lastName" : $firstName;
		$email = $to;
		if($fullName!=null) $to = "$fullName <$to>";
		
		//load email settings from config file
		Configure::load('OlinDirectory');
		
		//prepare the email helper
	    $controller->Email->to = $to;  
	    $controller->Email->subject = "[OlinDirectory] $title";
	    //$controller->Email->replyTo = 'OlinDirectory <olindirectory@gmail.com>';
	    $controller->Email->from = Configure::read('email.from');
	    $controller->Email->template = $template;
	    $controller->Email->sendAs = 'html';
	    
	    //Configure the delivery method
		$delivery = Configure::read('email.delivery');
		if($this->DEBUG) $delivery='debug';
		if($delivery=='smtp') {
		    $controller->Email->smtpOptions = Configure::read('email.smtp');
		}
		$controller->Email->delivery = $delivery;
		
	    //Set view variables for rendering in template
		$controller->set('title',$title);
		$controller->set('token',$token);
		$controller->set('email',$email);
		$controller->set('data',$controller->data);
	    
		//send the message
	    if(!$this->DEBUG) {
	    	$ret = $controller->Email->send();
	    	return $delivery=='smtp' ? $controller->Email->smtpError : $ret;
	    }else{
	    	return null;
	    }
	}
	
}
?>
