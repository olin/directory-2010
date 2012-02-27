<?php
/* UserDetail Fixture generated on: 2010-10-19 22:10:26 : 1287540206 */
class UserDetailFixture extends CakeTestFixture {
	var $name = 'UserDetail';

	var $fields = array(
		'account_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'nickname' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'isAway' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'classYearEntry' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'classYearExpected' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'campusMailbox' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'building_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'campusRoom' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'phoneMobile' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'imAOL' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'imGTalk' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'imICQ' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'imMSN' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'imSkype' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'account_id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'account_id' => 1,
			'nickname' => 'Lorem ipsum dolor sit amet',
			'email' => 'Lorem ipsum dolor sit amet',
			'isAway' => 1,
			'classYearEntry' => 1,
			'classYearExpected' => 1,
			'campusMailbox' => 'Lorem ipsum dolor ',
			'building_id' => 1,
			'campusRoom' => 'Lorem ip',
			'phoneMobile' => 'Lorem ipsum dolor ',
			'imAOL' => 'Lorem ipsum dolor sit amet',
			'imGTalk' => 'Lorem ipsum dolor sit amet',
			'imICQ' => 'Lorem ipsum dolor sit amet',
			'imMSN' => 'Lorem ipsum dolor sit amet',
			'imSkype' => 'Lorem ipsum dolor sit amet',
			'created' => '2010-10-19 22:03:26',
			'modified' => '2010-10-19 22:03:26'
		),
	);
}
?>