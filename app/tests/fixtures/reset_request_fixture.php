<?php
/* ResetRequest Fixture generated on: 2010-09-19 00:09:26 : 1284870926 */
class ResetRequestFixture extends CakeTestFixture {
	var $name = 'ResetRequest';

	var $fields = array(
		'account_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'hash' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'salt' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'expires' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'account_id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'account_id' => 1,
			'hash' => 'Lorem ipsum dolor sit amet',
			'salt' => 'Lorem ipsum dolor sit amet',
			'expires' => '2010-09-19 00:35:26'
		),
	);
}
?>