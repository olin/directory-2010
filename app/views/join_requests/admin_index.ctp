<?php $this->helpers[] = 'Time'; ?>
<div class="joinRequests index">
	<h2><?php __('Join Requests');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('used');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('expires');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($joinRequests as $joinRequest):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $joinRequest['JoinRequest']['id']; ?>&nbsp;</td>
		<td><?php echo $joinRequest['JoinRequest']['used']?"yes":"no"; ?>&nbsp;</td>
		<td><?php echo $joinRequest['JoinRequest']['email']; ?>&nbsp;</td>
		<td><?php echo $joinRequest['JoinRequest']['expires']; ?> (<?php echo $time->timeAgoInWords($joinRequest['JoinRequest']['expires']); ?>)&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $joinRequest['JoinRequest']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $joinRequest['JoinRequest']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $joinRequest['JoinRequest']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Join Request', true), array('action' => 'add')); ?></li>
	</ul>
</div>