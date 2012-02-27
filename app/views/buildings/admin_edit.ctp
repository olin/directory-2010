<div class="buildings form">
<?php echo $this->Form->create('Building');?>
	<fieldset>
 		<legend><?php __('Admin Edit Building'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('shortName');
		echo $this->Form->input('longName');
		echo $this->Form->input('latitude');
		echo $this->Form->input('longitude');
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

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Building.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Building.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Buildings', true), array('action' => 'index'));?></li>
	</ul>
</div>