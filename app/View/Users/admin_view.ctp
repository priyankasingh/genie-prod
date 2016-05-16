<div class="users view">
	<h2><?php  echo __('User'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($user['User']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Password'); ?></dt>
		<dd>
			<?php echo h($user['User']['password']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Admin'); ?></dt>
		<dd>
			<?php echo h($user['User']['is_admin']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Forgot Password Key'); ?></dt>
		<dd>
			<?php echo h($user['User']['forgot_password_key']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($user['User']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($user['User']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deleted'); ?></dt>
		<dd>
			<?php echo h($user['User']['deleted']); ?>
			&nbsp;
		</dd>
	</dl>

	<div class="related">
		<h3><?php echo __('Related Responses'); ?></h3>
	<?php if (!empty($user['Response']['id'])): ?>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
		<dd>
	<?php echo $user['Response']['id']; ?>
&nbsp;</dd>
		<dt><?php echo __('User Id'); ?></dt>
		<dd>
	<?php echo $user['Response']['user_id']; ?>
&nbsp;</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
	<?php echo $user['Response']['title']; ?>
&nbsp;</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
	<?php echo $user['Response']['name']; ?>
&nbsp;</dd>
		<dt><?php echo __('Age'); ?></dt>
		<dd>
	<?php echo $user['Response']['age']; ?>
&nbsp;</dd>
		<dt><?php echo __('Gender'); ?></dt>
		<dd>
	<?php echo $user['Response']['gender']; ?>
&nbsp;</dd>
		<dt><?php echo __('Marital Status'); ?></dt>
		<dd>
	<?php echo $user['Response']['marital_status']; ?>
&nbsp;</dd>
		<dt><?php echo __('Postcode'); ?></dt>
		<dd>
	<?php echo $user['Response']['postcode']; ?>
&nbsp;</dd>
		<dt><?php echo __('Telephone'); ?></dt>
		<dd>
	<?php echo $user['Response']['telephone']; ?>
&nbsp;</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
	<?php echo $user['Response']['created']; ?>
&nbsp;</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
	<?php echo $user['Response']['modified']; ?>
&nbsp;</dd>
		<dt><?php echo __('Deleted'); ?></dt>
		<dd>
	<?php echo $user['Response']['deleted']; ?>
&nbsp;</dd>
		</dl>
		<div class="actions">
			<ul>
				<li><?php echo $this->Html->link(__('Edit Response'), array('controller' => 'responses', 'action' => 'edit', $user['Response']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('View Response'), array('controller' => 'responses', 'action' => 'view', $user['Response']['id'])); ?></li>
			</ul>
		</div>
<?php else: ?>
		<div><em>None found</em></div>
<?php endif; ?>
	</div>
	<div class="related">
		<h3><?php echo __('Related Favourites'); ?></h3>
		<?php if (!empty($user['Favourite'])): ?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('User Id'); ?></th>
			<th><?php echo __('Service Id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($user['Favourite'] as $favourite): ?>
			<tr>
				<td><?php echo $favourite['id']; ?></td>
				<td><?php echo $favourite['user_id']; ?></td>
				<td><?php echo $favourite['service_id']; ?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'favourites', 'action' => 'view', $favourite['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'favourites', 'action' => 'edit', $favourite['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'favourites', 'action' => 'delete', $favourite['id']), null, __('Are you sure you want to delete # %s?', $favourite['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

		<div class="actions">
			<ul>
				<li><?php echo $this->Html->link(__('New Favourite'), array('controller' => 'favourites', 'action' => 'add')); ?> </li>
			</ul>
		</div>
	</div>
</div>
