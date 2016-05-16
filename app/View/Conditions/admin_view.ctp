<div class="conditions view">
<h2><?php  echo __('Condition'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['name']); ?>
			&nbsp;
		</dd>
	</dl>

	<div class="related">
		<h3><?php echo __('Related Responses'); ?></h3>
		<?php if (!empty($condition['Response'])): ?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('User Id'); ?></th>
			<th><?php echo __('Title'); ?></th>
			<th><?php echo __('Name'); ?></th>
			<th><?php echo __('Age'); ?></th>
			<th><?php echo __('Gender'); ?></th>
			<th><?php echo __('Marital Status'); ?></th>
			<th><?php echo __('Postcode'); ?></th>
			<th><?php echo __('Telephone'); ?></th>
			<th><?php echo __('Created'); ?></th>
			<th><?php echo __('Modified'); ?></th>
			<th><?php echo __('Deleted'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($condition['Response'] as $response): ?>
			<tr>
				<td><?php echo $response['id']; ?></td>
				<td><?php echo $response['user_id']; ?></td>
				<td><?php echo $response['title']; ?></td>
				<td><?php echo $response['name']; ?></td>
				<td><?php echo $response['age']; ?></td>
				<td><?php echo $response['gender']; ?></td>
				<td><?php echo $response['marital_status']; ?></td>
				<td><?php echo $response['postcode']; ?></td>
				<td><?php echo $response['telephone']; ?></td>
				<td><?php echo $response['created']; ?></td>
				<td><?php echo $response['modified']; ?></td>
				<td><?php echo $response['deleted']; ?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'responses', 'action' => 'view', $response['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'responses', 'action' => 'edit', $response['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'responses', 'action' => 'delete', $response['id']), null, __('Are you sure you want to delete # %s?', $response['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

		<div class="actions">
			<ul>
				<li><?php echo $this->Html->link(__('New Response'), array('controller' => 'responses', 'action' => 'add')); ?> </li>
			</ul>
		</div>
	</div>
</div>