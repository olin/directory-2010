<?php
/* ResetRequest Test cases generated on: 2010-09-19 00:09:26 : 1284870926*/
App::import('Model', 'ResetRequest');

class ResetRequestTestCase extends CakeTestCase {
	var $fixtures = array('app.reset_request', 'app.account');

	function startTest() {
		$this->ResetRequest =& ClassRegistry::init('ResetRequest');
	}

	function endTest() {
		unset($this->ResetRequest);
		ClassRegistry::flush();
	}

}
?>