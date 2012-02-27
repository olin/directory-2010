<?php
/* ResetRequests Test cases generated on: 2010-09-19 00:09:41 : 1284871661*/
App::import('Controller', 'ResetRequests');

class TestResetRequestsController extends ResetRequestsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ResetRequestsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.reset_request', 'app.account');

	function startTest() {
		$this->ResetRequests =& new TestResetRequestsController();
		$this->ResetRequests->constructClasses();
	}

	function endTest() {
		unset($this->ResetRequests);
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