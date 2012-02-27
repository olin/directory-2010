<?php
/* JoinRequest Test cases generated on: 2010-10-02 17:10:14 : 1286054534*/
App::import('Model', 'JoinRequest');

class JoinRequestTestCase extends CakeTestCase {
	var $fixtures = array('app.join_request');

	function startTest() {
		$this->JoinRequest =& ClassRegistry::init('JoinRequest');
	}

	function endTest() {
		unset($this->JoinRequest);
		ClassRegistry::flush();
	}

}
?>