<?php
class JoinRequest extends AppModel {
	var $name = 'JoinRequest';
	var $displayField = 'id';
	var $validate = array(
		'hash' => array(
			'notempty' => array(
				'rule' => array('notempty')
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Please enter a valid e-mail address.',
				'allowEmpty' => false,
				'required' => true
			),
		),
	);
}
?>