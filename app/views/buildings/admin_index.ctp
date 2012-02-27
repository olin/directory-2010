<div class="buildings index">
	<h2><?php __('Buildings');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('shortName');?></th>
			<th><?php echo $this->Paginator->sort('longName');?></th>
			<th><?php echo $this->Paginator->sort('latitude');?></th>
			<th><?php echo $this->Paginator->sort('longitude');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($buildings as $building):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $building['Building']['id']; ?>&nbsp;</td>
		<td><?php echo $building['Building']['shortName']; ?>&nbsp;</td>
		<td><?php echo $building['Building']['longName']; ?>&nbsp;</td>
		<td><?php echo $building['Building']['latitude']; ?>&nbsp;</td>
		<td><?php echo $building['Building']['longitude']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $building['Building']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $building['Building']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $building['Building']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $building['Building']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Building', true), array('action' => 'add')); ?></li>
	</ul>
</div>