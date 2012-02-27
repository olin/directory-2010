<div class="buildings form">
<?php echo $this->Form->create('Building');?>
	<fieldset>
 		<legend><?php __('Admin Add Building'); ?></legend>
	<?php
		echo $this->Form->input('shortName');
		echo $this->Form->input('longName');
		echo $this->Form->input('latitude');
		echo $this->Form->input('longitude');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Buildings', true), array('action' => 'index'));?></li>
	</ul>
</div>