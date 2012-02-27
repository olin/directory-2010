<div class="userDetails form">
<?php echo $this->Form->create('UserDetail');?>
	<fieldset>
 		<legend><?php __('Admin Edit User Detail'); ?></legend>
	<?php
		echo $this->Form->input('account_id');
		echo $this->Form->input('nickname');
		echo $this->Form->input('isAway');
		echo $this->Form->input('classYearEntry');
		echo $this->Form->input('classYearExpected');
		echo $this->Form->input('campusMailbox');
		echo $this->Form->input('building_id');
		echo $this->Form->input('campusRoom');
		echo $this->Form->input('phoneMobile');
		echo $this->Form->input('imAOL');
		echo $this->Form->input('imGTalk');
		echo $this->Form->input('imICQ');
		echo $this->Form->input('imMSN');
		echo $this->Form->input('imSkype');
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
		<li><?php echo $this->Html->link(__('List All User Details', true), array('action' => 'index'));?></li>
	</ul>
</div>