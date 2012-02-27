<?php
/* Building Test cases generated on: 2010-09-19 00:09:47 : 1284870827*/
App::import('Model', 'Building');

class BuildingTestCase extends CakeTestCase {
	var $fixtures = array('app.building');

	function startTest() {
		$this->Building =& ClassRegistry::init('Building');
	}

	function endTest() {
		unset($this->Building);
		ClassRegistry::flush();
	}

}
?>