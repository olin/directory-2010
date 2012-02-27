<?php
class HelpController extends AppController {

	var $name = 'Help';
	var $uses = array();
	var $helpers = array('Javascript');
	var $components = array('RequestHandler');
	
	//mapping between supported formats and content-types
	
	function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index','help','users','users_search');
    }
	
	function index($topic='index') {
		$topic = preg_replace("/[^A-Za-z]/", "", $topic);
		if(!$topic){
			$this->redirect(array('action'=>'index','index'));
		}
		//render help page
		$this->render($topic);
	}

}
?>