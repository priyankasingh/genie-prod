<div class="networkMembers index">
	<h2><?php echo __('Network Members'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('frequency'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('network_category_id'); ?></th>
			<th><?php echo $this->Paginator->sort('response_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($networkMembers as $networkMember): ?>
	<tr>
		<td><?php echo h($networkMember['NetworkMember']['id']); ?>&nbsp;</td>
		<td><?php echo h($networkMember['NetworkMember']['name']); ?>&nbsp;</td>
		<td><?php echo h($networkMember['NetworkMember']['frequency']); ?>&nbsp;</td>
		<td><?php echo h($networkMember['NetworkMember']['created']); ?>&nbsp;</td>
		<td><?php echo h($networkMember['NetworkMember']['modified']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($networkMember['NetworkCategory']['name'], array('controller' => 'network_categories', 'action' => 'view', $networkMember['NetworkCategory']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($networkMember['Response']['name'], array('controller' => 'responses', 'action' => 'view', $networkMember['Response']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $networkMember['NetworkMember']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $networkMember['NetworkMember']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $networkMember['NetworkMember']['id']), null, __('Are you sure you want to delete # %s?', $networkMember['NetworkMember']['id'])); ?>
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
