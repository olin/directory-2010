<?php
//Scripts to include in the page
$javascript->link('lib/jquery-1.4.3.min.js', false);
//useful library functions
$javascript->link('lib/utils.js', false);
//focus on first non-empty text/password field
$javascript->codeBlock("setupStandardInputFocus(false);", array('inline'=>false));
?>

<h2>Join OlinDirectory</h2> 

<div class="Wrapped">
<p class="Welcome">Your E-Mail Address has been verified!</p>
<p>Thank you for verifying your email address <strong><?php print $email?></strong>.  You're almost done!  Just tell us your name and pick a password, and you'll be all set.</p>
<p class="Reminder">Note that for Olin email addresses, we've tried to guess your first and last name based on your address.  You should check and make sure they are correct.</p>

<div class="account form">
<fieldset>	
<?php echo $this->Form->create('Account',array('url'=>array('controller'=>'join','action'=>'verify'),'class'=>'Tiny'));?>
	<?php
		echo $this->Form->input('firstName');
		echo $this->Form->input('lastName');
		echo "<br />";
		echo $this->Form->input('authPassword',array('type'=>'password','label'=>'Password'));
		echo $this->Form->input('authPassword_confirm',array('type'=>'password','label'=>'(Confirm)'));
		echo $this->Form->end('Create Account',true);
	?>
	</fieldset>
<?php ?>
</div>

<p class="Reminder">We won't send you junk mail.  Your email won't be shared with anybody else, sold for profit, etc.</p> 



</div>
