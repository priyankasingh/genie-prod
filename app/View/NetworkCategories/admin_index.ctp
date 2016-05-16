<div class="networkCategories index">
	<h2><?php echo __('Network Categories'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('parent_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($networkCategories as $networkCategory): ?>
	<tr>
		<td><?php echo h($networkCategory['NetworkCategory']['id']); ?>&nbsp;</td>
		<td><?php echo h($networkCategory['NetworkCategory']['name']); ?>&nbsp;</td>
		<td><?php echo h($networkCategory['NetworkCategory']['created']); ?>&nbsp;</td>
		<td><?php echo h($networkCategory['NetworkCategory']['modified']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($networkCategory['ParentNetworkCategory']['name'], array('controller' => 'network_categories', 'action' => 'view', $networkCategory['ParentNetworkCategory']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $networkCategory['NetworkCategory']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $networkCategory['NetworkCategory']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $networkCategory['NetworkCategory']['id']), null, __('Are you sure you want to delete # %s?', $networkCategory['NetworkCategory']['id'])); ?>
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
