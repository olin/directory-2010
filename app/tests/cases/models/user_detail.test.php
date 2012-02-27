<?php
/* UserDetail Test cases generated on: 2010-10-19 22:10:26 : 1287540206*/
App::import('Model', 'UserDetail');

class UserDetailTestCase extends CakeTestCase {
	var $fixtures = array('app.user_detail', 'app.account', 'app.building');

	function startTest() {
		$this->UserDetail =& ClassRegistry::init('UserDetail');
	}

	function endTest() {
		unset($this->UserDetail);
		ClassRegistry::flush();
	}

}
?>