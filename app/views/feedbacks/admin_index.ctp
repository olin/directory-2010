<div class="feedbacks index">
	<h2><?php __('Feedbacks');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('account_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('text');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($feedbacks as $feedback):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $feedback['Feedback']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($feedback['Account']['email'], array('controller' => 'accounts', 'action' => 'view', $feedback['Account']['id'])); ?>
		</td>
		<td><?php echo htmlspecialchars($feedback['Feedback']['name']); ?>&nbsp;</td>
		<?php $text = $feedback['Feedback']['text'];
			if (strlen($text)>40) $text = substr($text,0,40)."...";
			$text = htmlspecialchars($text);
		?>
		<td><?php echo htmlspecialchars($feedback['Feedback']['email']); ?>&nbsp;</td>
		<td><?php echo $text; ?>&nbsp;</td>
		<td><?php echo $time->timeAgoInWords($feedback['Feedback']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $feedback['Feedback']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $feedback['Feedback']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $feedback['Feedback']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Feedback', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>