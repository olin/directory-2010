<div class="userDetails form">
<?php echo $this->Form->create('UserDetail',array('class'=>'Formatted'));?>
	<fieldset>
 		<legend><?php __('Edit Your Profile'); ?></legend>
 	<div class="Medium Large FormSection">
	<?php
		echo $this->Form->input('nickname',array('label'=>'Nickname'));
		echo $this->Form->input('classYearExpected',array('label'=>'Class of'));
		echo $this->Form->input('campusMailbox',array('label'=>'Mailbox'));
		echo $this->Form->input('building_id',array('label'=>'Dorm'));
		echo $this->Form->input('campusRoom',array('label'=>'Room #'));
		echo $this->Form->input('phoneMobile',array('label'=>'Mobile Phone <span class="Reminder">###-###-####</span>'));
	?>
	</div>
	<div class="Medium IM FormSection">
	<?php
		function genLabel($base,$title,$lc){
			return '<img class="IM Iconic" title="Your '.$title.' Screen Name" alt="Your '.$title.' Screen Name" src="'.$base.'/css/forms/img/im/'.$lc.'" valign="middle" />';
		}
	
		echo $this->Form->input('imAOL',array('label'=>'AIM','before'=>genLabel($this->base,'AIM','aim.png')));
		echo $this->Form->input('imGTalk',array('label'=>'GTalk','before'=>genLabel($this->base,'GTalk','gtalk.png')));
		echo $this->Form->input('imICQ',array('label'=>'ICQ','before'=>genLabel($this->base,'ICQ','icq.png')));
		echo $this->Form->input('imMSN',array('label'=>'MSN','before'=>genLabel($this->base,'MSN','msn.png')));
		echo $this->Form->input('imSkype',array('label'=>'Skype','before'=>genLabel($this->base,'Skype','skype.png')));
	?>
	</div>
	</fieldset>
<div class="submit">
<?php echo $this->Form->button('Save Changes', array('type'=>'submit')); ?>
<?php echo $this->Html->link(__('Cancel', true), array('controller'=>'accounts', 'action' => 'index'));?>
</div>
<?php echo $this->Form->end(); ?>
</div>
