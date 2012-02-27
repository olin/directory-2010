<div class="userDetails form">
<?php echo $this->Form->create('UserDetail');?>
	<fieldset>
 		<legend><?php __('Admin Add User Detail'); ?></legend>
	<?php
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
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List User Details', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Buildings', true), array('controller' => 'buildings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Building', true), array('controller' => 'buildings', 'action' => 'add')); ?> </li>
	</ul>
</div>