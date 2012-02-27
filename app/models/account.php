<?php
class Account extends AppModel {
	var $name = 'Account';
	var $displayField = 'id';
	
	var $hasOne = array(
		'ResetRequest' => array(
			'className' => 'ResetRequest',
			'dependent' => true
		),
		'UserDetail' => array(
			'className' => 'UserDetail',
			'dependent' => true
		)
	);
	
	var $validate = array(
		'authType' => array(
			'inList' => array(
				'rule' => array('inList',array('Email','LDAP')),
				'message' => 'Select which type of authentication',
				'allowEmpty' => False,
				'required' => True,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'authName' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'AuthName required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Email Address is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'firstName' => array(
			'alphanumeric' => array(
				'rule' => array('alphanumeric'),
				'message' => 'Please enter your first name',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'lastName' => array(
			'notempty' => array(
				'rule' => array('alphanumeric'),
				'message' => 'Please enter your last name',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'authPassword' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a password',
				'allowEmpty' => false,
				'required' => true,
			),
			'matchesConfirm' => array(
				'rule' => array('matchesOtherField','authPassword_confirm'),
				'message' => 'Make sure both passwords match',
			),
		),
	);
	
	function matchesOtherField($field=array(), $otherField=null){ 
		foreach($field as $k=>$v){
			if($v!==$this->data[$this->name][$otherField]){
				return false;
			}else{
				continue; 
			} 
		} 
		return true; 
	}
	
	function hasPermission($permission, $data){
		$perms = @$data[$this->name]['permissions'];
		if(!$perms){ return null; }
		$perms = split("[, ]+",strtolower($perms));
		return array_search(strtolower($permission), $perms)!==false;
	}
	
	function afterFind($results, $query){
		for($i=0; $i<count($results); $i++){
			$val = $this->hasPermission('admin',$results[$i]);
			if($val!==null){
				$results[$i][$this->name]['isAdmin'] = $val;
			}
			$val = $this->hasPermission('invisible',$results[$i]);
			if($val!==null){
				$results[$i][$this->name]['isInvisible'] = $val;
			}
		}
		return $results;
	}

}
?>