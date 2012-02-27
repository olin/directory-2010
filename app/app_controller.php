<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * Modified by Jeffrey Stanton in 2010
 * Also licensed under the MIT license
 * Redistributions of files must retain the above copyright notice.
 */

class AppController extends Controller {
	var $components = array('Auth', 'Session', 'Security');

	function beforeFilter() {
		//authorization permissions
		$this->Auth->fields = array(
			'username' => 'authName', 
			'password' => 'authPassword'
		);
		$this->Auth->loginAction = array('controller'=>'account', 'action'=>'signin');
		$this->Auth->loginRedirect = array('controller'=>'account', 'action'=>'edit');
		$this->Auth->loginError = 'Your username or password was wrong, please try again!';
		$this->Auth->authError = 'Please sign in to continue.';
		$this->Auth->logoutRedirect = array('controller'=>'pages', 'action'=>'display', 'home');
		$this->Auth->authorize = 'controller';
		$this->Auth->userModel = 'Account';
		//Is SSL?
		Configure::load('OlinDirectory');
		$isSSL = Configure::read('ssl') && true;
		//global template var for absolute-path
		$absoluteBase = ($isSSL ? 'https' : 'http').'://'.$_SERVER['SERVER_NAME'].$this->base;
		$this->set('absoluteBase', $absoluteBase);
		//force-SSL
		if ($isSSL) {
			$this->Security->blackHoleCallback = 'forceSSL';
			$this->Security->requireSecure();
		}
	}

	function forceSSL() {
		$port = env('SERVER_PORT')==80 ? '' : ':'.env('SERVER_PORT');  
		$this->redirect('https://' . env('SERVER_NAME') . $port . $this->here);
	}
	
	//after auth runs but before view is rendered,
	//go ahead and set some convenience vars for the view
	function beforeRender() {
		$user = $this->Auth->user();
		$this->set('isAdminUser', ($user!=null && @$user['Account']['isAdmin']));
	}
	
	//stub method; individual controllers can override for more specific authorization checking
	function isAuthorized(){
		$user = $this->Auth->user();
		$isAdminUser = ($user!=null && @$user['Account']['isAdmin']);
		$isAdminPage = isset($this->params['admin']);
		if($isAdminPage && !$isAdminUser){
			//access denied
			return false;
		}
		return true;
	}
	
}
