<?php
//Scripts to include in the page
//$javascript->link('lib/jquery-1.4.3.min.js', false);
//useful library functions
//$javascript->link('lib/utils.js', false);
//focus on first non-empty text/password field
//$javascript->codeBlock("setupStandardInputFocus(false);", array('inline'=>false));
?>

<div class="accounts form">
<?php echo $this->Form->create('Account',array('class'=>'Small'));?>
	<fieldset style="vertical-align:top;">
 		<legend><?php __('Admin Edit Account'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('authType',array('options'=>array("Email"=>"E-Mail","LDAP"=>"LDAP (External)")));
		echo $this->Form->input('authName');
		echo $this->Form->input('email');
		echo $this->Form->input('permissions');
		echo $this->Form->input('firstName');
		echo $this->Form->input('lastName');
	?>
	</fieldset>
	<fieldset style="vertical-align:top;">
		<legend>Reset Password</legend>
		<p class="Reminder">Leave this blank if you don't want to change the password.</p>
	<?php
		echo $this->Form->input('authPassword',array('type'=>'password','label'=>'Password'));
		echo $this->Form->input('authPassword_confirm',array('type'=>'password','label'=>'(Confirm)'));
	?>
	</fieldset>
<div class="submit">
<?php echo $this->Form->button('Save Changes', array('type'=>'submit')); ?>
<?php echo $this->Html->link(__('Cancel', true), array('action' => 'index'));?>
</div>
<?php echo $this->Form->end(); ?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Delete this Account (and User Detail)', true), array('action' => 'delete', $this->Form->value('Account.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Account.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List All Accounts', true), array('action' => 'index'));?></li>
	</ul>
</div>