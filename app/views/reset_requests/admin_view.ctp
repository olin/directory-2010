<div class="resetRequests view">
<h2><?php  __('Reset Request');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Req ID'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $resetRequest['ResetRequest']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Used'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $resetRequest['ResetRequest']['used'] ? "yes" : "no"; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Account'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($resetRequest['Account']['email'], array('controller' => 'accounts', 'action' => 'view', $resetRequest['Account']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Hash'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $resetRequest['ResetRequest']['hash']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Expires'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $resetRequest['ResetRequest']['expires']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Delete This Reset Request', true), array('action' => 'delete', $resetRequest['ResetRequest']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $resetRequest['ResetRequest']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List All Reset Requests', true), array('action' => 'index')); ?> </li>
	</ul>
</div>
