<?php
//Scripts to include in the page
$javascript->link('lib/jquery-1.4.3.min.js', false);
//useful library functions
$javascript->link('lib/utils.js', false);
//focus on first non-empty text/password field
$javascript->codeBlock("setupStandardInputFocus(true);", array('inline'=>false));
?>

<div class="accounts form">
<?php echo $this->Form->create('Account',array('class'=>'Small'));?>
	<fieldset>
 		<legend><?php __('Admin Add Account'); ?></legend>
	<?php
		echo $this->Form->input('authType',array('options'=>array("Email"=>"E-Mail","LDAP"=>"LDAP (External)")));
		echo $this->Form->input('authName');
		echo $this->Form->input('authPassword',array('label'=>'Password','type'=>'password'));
		echo $this->Form->input('authPassword_confirm',array('type'=>'password','label'=>'&nbsp; (Confirm)'));
		echo $this->Form->input('permissions');
		echo $this->Form->input('firstName');
		echo $this->Form->input('lastName');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Add Account', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Accounts', true), array('action' => 'index'));?></li>
	</ul>
</div>
