<div class="networkMembers view">
<h2><?php echo __('Network Member'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($networkMember['NetworkMember']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($networkMember['NetworkMember']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Frequency'); ?></dt>
		<dd>
			<?php echo h($networkMember['NetworkMember']['frequency']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($networkMember['NetworkMember']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($networkMember['NetworkMember']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deleted'); ?></dt>
		<dd>
			<?php echo h($networkMember['NetworkMember']['deleted']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Network Category'); ?></dt>
		<dd>
			<?php echo $this->Html->link($networkMember['NetworkCategory']['name'], array('controller' => 'network_categories', 'action' => 'view', $networkMember['NetworkCategory']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Response'); ?></dt>
		<dd>
			<?php echo $this->Html->link($networkMember['Response']['name'], array('controller' => 'responses', 'action' => 'view', $networkMember['Response']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
