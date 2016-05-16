<div class="responses view">
	<h2><?php  echo __('Response'); ?></h2>
	<div class="actions">
		<ul>
			<?php echo $this->Html->link(__('Live Site View'), array( 'controller'=>'users', 'action' => 'admin_use_response', $response['Response']['id'], 'admin'=>true ) ); ?>
		</ul>
	</div>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($response['Response']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($response['User']['email'], array('controller' => 'users', 'action' => 'view', $response['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($response['Response']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($response['Response']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Age'); ?></dt>
		<dd>
			<?php echo h($response['Response']['age']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Gender'); ?></dt>
		<dd>
			<?php echo h($response['Response']['gender']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marital Status'); ?></dt>
		<dd>
			<?php echo h($response['Response']['marital_status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Postcode'); ?></dt>
		<dd>
			<?php echo h($response['Response']['postcode']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Telephone'); ?></dt>
		<dd>
			<?php echo h($response['Response']['telephone']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Network Type'); ?></dt>
		<dd>
			<?php echo h($response['Response']['network_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($response['Response']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($response['Response']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deleted'); ?></dt>
		<dd>
			<?php echo h($response['Response']['deleted']); ?>
			&nbsp;
		</dd>
	</dl>


	<div class="related">
		<h3><?php echo __('Related Conditions'); ?></h3>
		<?php if (!empty($response['Condition'])): ?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('Name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($response['Condition'] as $condition): ?>
			<tr>
				<td><?php echo $condition['id']; ?></td>
				<td><?php echo $condition['name']; ?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'conditions', 'action' => 'view', $condition['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'conditions', 'action' => 'edit', $condition['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'conditions', 'action' => 'delete', $condition['id']), null, __('Are you sure you want to delete # %s?', $condition['id'])); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php endif; ?>
		
		<h3><?php echo __('Related Statements'); ?></h3>
		<?php if (!empty($response['ResponseStatement'])): ?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('Statement'); ?></th>
			<th><?php echo __('Weighting'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($response['ResponseStatement'] as $responseStatement): ?>
			<tr>
				<td><?php echo $responseStatement['id']; ?></td>
				<td><?php echo $responseStatement['Statement']['statement']; ?></td>
				<td><?php echo $responseStatement['weighting']; ?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'statements', 'action' => 'view', $responseStatement['Statement']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'statements', 'action' => 'edit', $responseStatement['Statement']['id'])); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php endif; ?>

		<div class="actions">
			<ul>
				<li><?php echo $this->Html->link(__('New Statement'), array('controller' => 'statements', 'action' => 'add')); ?> </li>
			</ul>
		</div>
	</div>
	<div class="related">
		<h3><?php echo __('Related Network Members'); ?></h3>
		<?php if (!empty($response['NetworkMember'])): ?>
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
		<?php foreach ($response['NetworkMember'] as $networkMember): ?>
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
