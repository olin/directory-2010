<div class="buildings view">
<h2><?php  __('Building');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $building['Building']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ShortName'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $building['Building']['shortName']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('LongName'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $building['Building']['longName']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Latitude'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $building['Building']['latitude']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Longitude'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $building['Building']['longitude']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Building', true), array('action' => 'edit', $building['Building']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Building', true), array('action' => 'delete', $building['Building']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $building['Building']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Buildings', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Building', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
