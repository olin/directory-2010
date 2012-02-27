<?php
class SearchController extends AppController {
	var $name = 'Search';
	var $helpers = array('Html', 'Session', 'Javascript');
	var $uses = array();
	
	function beforeFilter() {
        parent::beforeFilter();
		Configure::load('OlinDirectory');
		if(Configure::read('search.public')){
        	$this->Auth->allow('index','mobile');
		}else{
			$this->Auth->allow('');
		}
    }
	
	function index(){
		//do nothing
	}
	
	function mobile(){
		$this->layout = 'mobile';
	}
}
