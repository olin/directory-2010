<?php
/* Accounts Test cases generated on: 2010-10-20 19:10:19 : 1287618079*/
App::import('Controller', 'Accounts');

class TestAccountsController extends AccountsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AccountsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.account', 'app.reset_request', 'app.user_detail', 'app.building');

	function startTest() {
		$this->Accounts =& new TestAccountsController();
		$this->Accounts->constructClasses();
	}

	function endTest() {
		unset($this->Accounts);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>