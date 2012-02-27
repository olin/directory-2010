<div class="feedbacks form">
<?php echo $this->Form->create('Feedback');?>
	<fieldset>
 		<legend><?php __('Admin Add Feedback'); ?></legend>
	<?php
		echo $this->Form->input('account_id');
		echo $this->Form->input('name');
		echo $this->Form->input('email');
		echo $this->Form->input('text');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Feedbacks', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>