<div class="services index">

	<?php
	echo $this->Form->create('OnlineResource',
		array(
			'type' => 'get',
			'url' => array(
				'action' => 'index'
			),
			'style' => 'width:200px;float:right;'
		)
	);
	echo $this->Form->input('q',
		array(
			'type' => 'text',
			'placeholder' => 'Search',
			'label' => false,
			'value' => $this->request->query('q')
		)
	);
	echo $this->Form->end();
	?>
	<h2><?php echo __('Online Resources'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('url'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($onlineResources as $online_resource): ?>
	<tr>
		<td><?php echo h($online_resource['OnlineResource']['id']); ?>&nbsp;</td>
		<td><?php echo h($online_resource['OnlineResource']['name']); ?>&nbsp;</td>
		<td><?php echo h($online_resource['OnlineResource']['url']); ?>&nbsp;</td>
		<td><?php echo h($online_resource['OnlineResource']['created']); ?>&nbsp;</td>
		<td><?php echo h($online_resource['OnlineResource']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $online_resource['OnlineResource']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $online_resource['OnlineResource']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $online_resource['OnlineResource']['id']), null, __('Are you sure you want to delete # %s?', $online_resource['OnlineResource']['id'])); ?>
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
