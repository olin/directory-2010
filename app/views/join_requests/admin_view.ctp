<div class="joinRequests view">
<h2><?php  __('Join Request');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $joinRequest['JoinRequest']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Used'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $joinRequest['JoinRequest']['used'] ? "yes" : "no"; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Hash'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $joinRequest['JoinRequest']['hash']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $joinRequest['JoinRequest']['email']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Expires'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $joinRequest['JoinRequest']['expires']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Delete Join Request', true), array('action' => 'delete', $joinRequest['JoinRequest']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $joinRequest['JoinRequest']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Join Requests', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Join Request', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
