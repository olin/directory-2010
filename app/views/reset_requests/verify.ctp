<?php
//Scripts to include in the page
$javascript->link('lib/jquery-1.4.3.min.js', false);
//useful library functions
$javascript->link('lib/utils.js', false);
//focus on first non-empty text/password field
$javascript->codeBlock("setupStandardInputFocus(false);", array('inline'=>false));
?>

<h2>Reset Your Password</h2> 

<div class="Wrapped">
<p>Please pick a new password for your account, <strong><?php print $email?></strong></p>
<p class="Reminder">Make sure you pick a strong password that you'll remember, but that somebody else can't easily guess and that you haven't already used somewhere else.</p>

<div class="account form">
<fieldset>	
<?php echo $this->Form->create('Account',array('url'=>array('controller'=>'reset_requests','action'=>'verify',@$token),'class'=>'Tiny'));?>
	<input type="hidden" name="token" id="token" value="<?php print @$token; ?>" />
	<?php
		echo $this->Form->input('authPassword',array('type'=>'password','label'=>'New Password'));
		echo $this->Form->input('authPassword_confirm',array('type'=>'password','label'=>'(Confirm)'));
		echo $this->Form->end('Change My Password',true);
	?>
	</fieldset>
<?php ?>
</div>

</div>
