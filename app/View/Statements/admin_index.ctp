<div class="statements index">
	<h2><?php echo __('Statements'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('statement'); ?></th>
			<th><?php echo $this->Paginator->sort('order'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($statements as $statement): ?>
	<tr>
		<td><?php echo h($statement['Statement']['id']); ?>&nbsp;</td>
		<td><?php echo h($statement['Statement']['statement']); ?>&nbsp;</td>
		<td><?php echo h($statement['Statement']['order']); ?>&nbsp;</td>
		<td><?php echo h($statement['Statement']['created']); ?>&nbsp;</td>
		<td><?php echo h($statement['Statement']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $statement['Statement']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $statement['Statement']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $statement['Statement']['id']), null, __('Are you sure you want to delete # %s?', $statement['Statement']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
