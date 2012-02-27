<?php
class ApiController extends AppController {

	var $name = 'Api';
	var $uses = array('UserDetail');
	var $helpers = array('Javascript','Xml');
	var $components = array('SearchHelper','RequestHandler');
	
	//mapping between supported formats and content-types
	var $contentTypes = array(
		"csv" => "text/csv",
		"xml" => "text/xml",
		"json" => "application/json",
		"vcard" => "text/x-vcard"
	);
	function getContentType($format){
		if(isset($this->contentTypes[$format])){
			return $this->contentTypes[$format];
		}
		return "text/plain";
	}
	
	function beforeFilter() {
        parent::beforeFilter();
		Configure::load('OlinDirectory');
		if(Configure::read('search.public')){
        	$this->Auth->allow('index','help','users','users_search');
		}else{
			$this->Auth->allow('index','help');
		}
    }
	
	function index() {
		$this->redirect(array('action'=>'help','index'));
	}
	
	function help($topic='index') {
		$topic = preg_replace("/[^A-Za-z]/", "", $topic);
		if(!$topic){
			$this->redirect(array('action'=>'help','index'));
		}
		//render API info page
		
		Configure::load('OlinDirectory');
		$isSSL = Configure::read('ssl') && true;
		$this->render("help/$topic");
	}
	
	function users($query=null, $format=null){
		if(isset($this->params['id'])){
			$query = $this->params['id'];
		}
		if(isset($this->params['format'])){
			$format = $this->params['format'];
		}
		if(!$format){ $format = 'json'; }
		if($query==null||$query===false||$query===''||!$format){
			$this->redirect(array('action'=>'help','users'));
			return;
		}
		$user = $this->UserDetail->find('first',
			array('conditions' => array(
				'Account.id' => $query,
				"Account.permissions NOT LIKE"=>"%invisible%"
			))
		);
		if(!$user){
			$user = array();
		}else{
			$user = array($user);
		}
		$isHierarchyFormat = ($format!='csv');
		$user = $this->SearchHelper->flattenResultsForAPI($user,$isHierarchyFormat);
		$user = $this->SearchHelper->postProcessUsers($user);
		$this->set('results',$user);
		$this->set('query',$query);
		$this->set('queryType','query');
		$this->set('format',$format);
		$this->set('itemName','user');
		$this->layout = 'ajax';
		$this->RequestHandler->respondAs($this->getContentType($format));
		$this->render('render');
	}
	
	function users_search($query=null, $format=null) {
		Configure::load('OlinDirectory');
		$maxResults = Configure::read('api.maxResults');
		if(isset($this->params['query'])){
			$query = $this->params['query'];
		}
		if(isset($this->params['format'])){
			$format = $this->params['format'];
		}
		if(!$format){ $format = 'json'; }
		if(!$query||!$format){
			$this->redirect(array('action'=>'help','users'));
			return;
		}
		$query = str_replace("*","%",$query);
		$query = preg_replace("/%+/","%",$query);
		$conditions = $this->SearchHelper->buildQuery($query);
		$fields = array_keys($this->SearchHelper->apiFieldMapping);
		$opts = array(
			'conditions' => $conditions,
			'fields' => $fields,
			'limit' => $maxResults,
			'order' => array('Account.lastName ASC','Account.firstName ASC')
		);
		if($conditions==null){
			$results = array();
		}else{
			$results = $this->UserDetail->find('all', $opts);
		}
		if(!$results){
			$results = array();
		}
		$isHierarchyFormat = ($format!='csv');
		$results = $this->SearchHelper->flattenResultsForAPI($results,$isHierarchyFormat);
		$results = $this->SearchHelper->postProcessUsers($results);
		$this->set('results',$results);
		$this->set('query',$query);
		$this->set('queryType','query');
		$this->set('format',$format);
		$this->set('itemName','user');
		$this->layout = 'empty';
		$this->RequestHandler->respondAs($this->getContentType($format));
		$this->render('render');
	}

}
?>