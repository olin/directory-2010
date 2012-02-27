<div class="accounts index">
	<h2><?php __('Accounts');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('authType');?></th>
			<th><?php echo $this->Paginator->sort('authName');?></th>
			<th><?php echo $this->Paginator->sort('permissions');?></th>
			<th><?php echo $this->Paginator->sort('firstName');?></th>
			<th><?php echo $this->Paginator->sort('lastName');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($accounts as $account):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $account['Account']['id']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['authType']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['authName']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['permissions']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['firstName']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['lastName']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['created']; ?>&nbsp;</td>
		<td><?php echo $account['Account']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $account['Account']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $account['Account']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $account['Account']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $account['Account']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Account', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Reset Requests', true), array('controller' => 'reset_requests', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Reset Request', true), array('controller' => 'reset_requests', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List User Details', true), array('controller' => 'user_details', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User Detail', true), array('controller' => 'user_details', 'action' => 'add')); ?> </li>
	</ul>
</div>