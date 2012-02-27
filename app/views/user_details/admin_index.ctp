<div class="userDetails index">
	<h2><?php __('User Details');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('account_id');?></th>
			<th><?php echo $this->Paginator->sort('nickname');?></th>
			<th><?php echo $this->Paginator->sort('classYearEntry');?></th>
			<th><?php echo $this->Paginator->sort('classYearExpected');?></th>
			<th><?php echo $this->Paginator->sort('phoneMobile');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($userDetails as $userDetail):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $this->Html->link($userDetail['Account']['authName'], array('controller' => 'accounts', 'action' => 'view', $userDetail['Account']['id'])); ?>
		</td>
		<td><?php echo $userDetail['UserDetail']['nickname']; ?>&nbsp;</td>
		<td><?php echo $userDetail['UserDetail']['classYearEntry']; ?>&nbsp;</td>
		<td><?php echo $userDetail['UserDetail']['classYearExpected']; ?>&nbsp;</td>
		<td><?php echo $userDetail['UserDetail']['phoneMobile']; ?>&nbsp;</td>
		<td><?php echo $userDetail['UserDetail']['created']; ?>&nbsp;</td>
		<td><?php echo $userDetail['UserDetail']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $userDetail['UserDetail']['account_id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $userDetail['UserDetail']['account_id'])); ?>
			<?php //echo $this->Html->link(__('Delete', true), array('action' => 'delete', $userDetail['UserDetail']['account_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $userDetail['UserDetail']['account_id'])); ?>
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