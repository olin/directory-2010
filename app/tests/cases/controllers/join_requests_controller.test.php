<?php
/* JoinRequests Test cases generated on: 2010-09-19 00:09:12 : 1284871572*/
App::import('Controller', 'JoinRequests');

class TestJoinRequestsController extends JoinRequestsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class JoinRequestsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.join_request');

	function startTest() {
		$this->JoinRequests =& new TestJoinRequestsController();
		$this->JoinRequests->constructClasses();
	}

	function endTest() {
		unset($this->JoinRequests);
		ClassRegistry::flush();
	}

}
?>