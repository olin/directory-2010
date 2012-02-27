<?php
class PagesController extends AppController {
	var $name = 'Pages';
	var $uses = array();
	
	function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
	}
	
	function index(){
		$this->redirect(array('controller'=>'search','action'=>'index'));
	}
	
}
