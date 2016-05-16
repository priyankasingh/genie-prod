<div class="favourites view">
<h2><?php  echo __('Favourite'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($favourite['Favourite']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($favourite['User']['email'], array('controller' => 'users', 'action' => 'view', $favourite['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Service'); ?></dt>
		<dd>
			<?php echo $this->Html->link($favourite['Service']['name'], array('controller' => 'services', 'action' => 'view', $favourite['Service']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
