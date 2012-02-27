<?php
//Scripts to include in the page
$javascript->link('lib/jquery-1.4.3.min.js', false);
//useful library functions
$javascript->link('lib/utils.js', false);
//focus on first non-empty text/password field
$javascript->codeBlock("setupStandardInputFocus(true);", array('inline'=>false));
?>

<h2>Forgot Your Password?</h2> 
<p>Enter your email address below, and we'll send you a link you can click that will let you enter a new password.</p> 

<?php if(@$email_failed){ ?>
<p class="Error">Oops, error sending email; try again later.  Sorry!</p>
<?php } ?>

<div class="joinrequest form">
<?php echo $this->Form->create('ResetRequest');?>
	<fieldset>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->end('Send Reset Message',true);
	?>
	</fieldset>
<?php ?>
</div>

<p class="Reminder">
If you don't get an email in a couple of minutes, make sure you typed in your address correctly and check your spam filter.
</p> 
