<div class="networkCategories view">
<h2><?php echo __('Network Category'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($networkCategory['NetworkCategory']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($networkCategory['NetworkCategory']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($networkCategory['NetworkCategory']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($networkCategory['NetworkCategory']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deleted'); ?></dt>
		<dd>
			<?php echo h($networkCategory['NetworkCategory']['deleted']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Parent Network Category'); ?></dt>
		<dd>
			<?php echo $this->Html->link($networkCategory['ParentNetworkCategory']['name'], array('controller' => 'network_categories', 'action' => 'view', $networkCategory['ParentNetworkCategory']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
<div class="related">
	<h3><?php echo __('Related Network Categories'); ?></h3>
	<?php if (!empty($networkCategory['ChildNetworkCategory'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Deleted'); ?></th>
		<th><?php echo __('Parent Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($networkCategory['ChildNetworkCategory'] as $childNetworkCategory): ?>
		<tr>
			<td><?php echo $childNetworkCategory['id']; ?></td>
			<td><?php echo $childNetworkCategory['name']; ?></td>
			<td><?php echo $childNetworkCategory['created']; ?></td>
			<td><?php echo $childNetworkCategory['modified']; ?></td>
			<td><?php echo $childNetworkCategory['deleted']; ?></td>
			<td><?php echo $childNetworkCategory['parent_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'network_categories', 'action' => 'view', $childNetworkCategory['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'network_categories', 'action' => 'edit', $childNetworkCategory['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'network_categories', 'action' => 'delete', $childNetworkCategory['id']), null, __('Are you sure you want to delete # %s?', $childNetworkCategory['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Child Network Category'), array('controller' => 'network_categories', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Network Members'); ?></h3>
	<?php if (!empty($networkCategory['NetworkMember'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Frequency'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Network Category Id'); ?></th>
		<th><?php echo __('Response Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($networkCategory['NetworkMember'] as $networkMember): ?>
		<tr>
			<td><?php echo $networkMember['id']; ?></td>
			<td><?php echo $networkMember['name']; ?></td>
			<td><?php echo $networkMember['frequency']; ?></td>
			<td><?php echo $networkMember['created']; ?></td>
			<td><?php echo $networkMember['modified']; ?></td>
			<td><?php echo $networkMember['network_category_id']; ?></td>
			<td><?php echo $networkMember['response_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'network_members', 'action' => 'view', $networkMember['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'network_members', 'action' => 'edit', $networkMember['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'network_members', 'action' => 'delete', $networkMember['id']), null, __('Are you sure you want to delete # %s?', $networkMember['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Network Member'), array('controller' => 'network_members', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
</div>