<?php
class ResetRequest extends AppModel {
	var $name = 'ResetRequest';
	var $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Please enter a valid e-mail address.',
				'allowEmpty' => false,
				'required' => true,
			),
		),
		'account_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid account id',
				'allowEmpty' => false,
				'required' => true
			),
		),
		'hash' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Include a valid sha1 hash',
				'allowEmpty' => false,
				'required' => true
			),
		),
		'expires' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Include a valid expiration date',
				'allowEmpty' => false,
				'required' => true
			),
		),
	);

	var $belongsTo = array(
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>