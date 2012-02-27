<?php
/* Buildings Test cases generated on: 2010-09-19 00:09:57 : 1284871257*/
App::import('Controller', 'Buildings');

class TestBuildingsController extends BuildingsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class BuildingsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.building');

	function startTest() {
		$this->Buildings =& new TestBuildingsController();
		$this->Buildings->constructClasses();
	}

	function endTest() {
		unset($this->Buildings);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

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