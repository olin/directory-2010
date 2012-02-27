<?php
//Scripts to include in the page
$javascript->link('lib/jquery-1.4.3.min.js', false);
//useful library functions
$javascript->link('lib/utils.js', false);
//focus on first non-empty text/password field
$javascript->codeBlock("setupStandardInputFocus(false);", array('inline'=>false));
?>

<h2>Got Feedback?</h2>
<p>Awesome! Let us know what you're thinking, or (if you're having any problems) what you were trying to do and what happened.</p>
<p>Providing contact info is optional, but encouraged.</p>

<div class="Wrapped">
<div class="account form">
<fieldset>	
<?php echo $this->Form->create('Feedback');?>
	<?php
		echo $this->Form->input('name', array('label' => 'Full Name'));
		echo $this->Form->input('email');
		//echo "<br />";
		echo $this->Form->input('text',array('type'=>'textarea','label'=>'Comments'));
		echo $this->Form->end('Send Feedback',true);
	?>
	</fieldset>
<?php ?>
</div>

<p class="Reminder">Providing contact details is optional.  We won't send you junk mail.  Your email won't be shared with anybody else, sold for profit, etc.</p> 

</div>
