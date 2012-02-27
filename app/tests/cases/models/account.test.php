<?php
/* Account Test cases generated on: 2010-10-20 19:10:27 : 1287617607*/
App::import('Model', 'Account');

class AccountTestCase extends CakeTestCase {
	var $fixtures = array('app.account', 'app.reset_request', 'app.user_detail', 'app.building');

	function startTest() {
		$this->Account =& ClassRegistry::init('Account');
	}

	function endTest() {
		unset($this->Account);
		ClassRegistry::flush();
	}

}
?>