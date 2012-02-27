<?php
//Scripts to include in the page
$javascript->link('lib/jquery-1.4.3.min.js', false);
//useful library functions
$javascript->link('lib/utils.js', false);
//focus on first non-empty text/password field
$javascript->codeBlock("setupStandardInputFocus(true);", array('inline'=>false));
?>

<h2>Join OlinDirectory</h2> 
 
<p>Right now, OlinDirectory is open to any Olin College student who has an @students.olin.edu email address.  It's easy to join!  Here's how you do it: <ol class="Steps"> 
	<li>Enter your <span class="Olin Account">@students.olin.edu</span> email address.<br /> 
		<span class="Reminder">(This verifies that you are currently an Olin student.)</span> 
		</li> 
	<li>Check your email for a confirmation message from OlinDirectory.<br /> 
		<span class="Reminder">(If it doesn't get to you in a few minutes, check your spam folder.)</span> 
		</li> 
	<li>Click the link to activate your <span class="Directory Account">OlinDirectory</span> account.<br /> 
		<span class="Reminder">(After you verify your email address, you can set your password, name, and other information.)</span> 
		</li> 
	</ol> 
</p> 
 
<h3>Sign Me Up!</h3>

<?php if(@$join_closed){
/**** Registration is closed ****/?>

<p class="Warning">Registration is closed</p>
<p>Sorry, but OlinDirectory is not currently open for new accounts.  Please come back later.</p>

<?php }else{
/**** Registration is open ****/ ?>

<p>Please get started by entering your <span class="Olin Account">@students.olin.edu</span> email address:

<?php if(@$email_failed){ ?>
<p class="Error">Oops, error sending email; try again later.  Sorry!</p>
<?php } ?>

<?php if(@$bad_email){ ?>
<p class="Error">Your email address is not eligible to create an account.  Sorry!</p>
<?php } ?>

<div class="joinrequest form">
<?php echo $this->Form->create('JoinRequest');?>
	<fieldset>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->end('Sign Up',true);
	?>
	</fieldset>
<?php ?>
</div>

<p class="Reminder">We won't send you junk mail.  Your email won't be shared with anybody else, sold for profit, etc.</p> 

<?php }
/**** End of registration ****/?>
