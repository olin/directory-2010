<?php
class UserDetail extends AppModel {
	var $name = 'UserDetail';
	var $primaryKey = 'account_id';
	var $displayField = 'account_id';

	var $belongsTo = array(
		'Account' => array(
			'className' => 'Account'
		),
		'Building' => array(
			'className' => 'Building'
		)
	);
}
?>