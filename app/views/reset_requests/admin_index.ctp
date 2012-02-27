<div class="resetRequests index">
	<h2><?php __('Reset Requests');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('used');?></th>
			<th><?php echo $this->Paginator->sort('account_id');?></th>
			<th><?php echo $this->Paginator->sort('expires');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($resetRequests as $resetRequest):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $resetRequest['ResetRequest']['id']; ?>&nbsp;</td>
		<td><?php echo $resetRequest['ResetRequest']['used'] ? "yes" : "no"; ?></td>
		<td>
			<?php echo $this->Html->link($resetRequest['Account']['email'], array('controller' => 'accounts', 'action' => 'view', $resetRequest['Account']['id'])); ?>
		</td>
		<td><?php echo $resetRequest['ResetRequest']['expires']; ?> (<?php echo $time->timeAgoInWords($resetRequest['ResetRequest']['expires']); ?>)&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $resetRequest['ResetRequest']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $resetRequest['ResetRequest']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $resetRequest['ResetRequest']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List All Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
	</ul>
</div>