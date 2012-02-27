<?php
/* UserDetails Test cases generated on: 2010-10-20 21:10:17 : 1287624797*/
App::import('Controller', 'UserDetails');

class TestUserDetailsController extends UserDetailsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class UserDetailsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.user_detail', 'app.account', 'app.reset_request', 'app.building');

	function startTest() {
		$this->UserDetails =& new TestUserDetailsController();
		$this->UserDetails->constructClasses();
	}

	function endTest() {
		unset($this->UserDetails);
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