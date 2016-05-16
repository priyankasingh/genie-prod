<div class="favourites index">
	<h2><?php echo __('Favourites'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('service_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($favourites as $favourite): ?>
	<tr>
		<td><?php echo h($favourite['Favourite']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($favourite['User']['email'], array('controller' => 'users', 'action' => 'view', $favourite['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($favourite['Service']['name'], array('controller' => 'services', 'action' => 'view', $favourite['Service']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $favourite['Favourite']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $favourite['Favourite']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $favourite['Favourite']['id']), null, __('Are you sure you want to delete # %s?', $favourite['Favourite']['id'])); ?>
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
