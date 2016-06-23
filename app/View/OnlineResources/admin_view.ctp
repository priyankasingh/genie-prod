<div class="services view">
<h2><?php  echo __('Online Resource'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($onlineResources['OnlineResource']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($onlineResources['OnlineResource']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url'); ?></dt>
		<dd>
			<?php echo h($onlineResources['OnlineResource']['url']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($onlineResources['OnlineResource']['description']); ?>
			&nbsp;
		</dd>
                <dt><?php echo __('Lower Age'); ?></dt>
		<dd>
			<?php echo h($onlineResources['OnlineResource']['age_lower']); ?>
			&nbsp;
		</dd>
                <dt><?php echo __('Upper Age'); ?></dt>
		<dd>
			<?php echo h($onlineResources['OnlineResource']['age_upper']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($onlineResources['OnlineResource']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($onlineResources['OnlineResource']['modified']); ?>
			&nbsp;
		</dd>
	</dl>

    <div class="related">
            <h3><?php echo __('Related Categories'); ?></h3>
            <?php if (!empty($onlineResources['Category'])): ?>
            <table cellpadding = "0" cellspacing = "0">
            <tr>
                    <th><?php echo __('Id'); ?></th>
                    <th><?php echo __('Name'); ?></th>
                    <th><?php echo __('Parent Id'); ?></th>
                    <th><?php echo __('Created'); ?></th>
                    <th><?php echo __('Modified'); ?></th>
                    <th><?php echo __('Deleted'); ?></th>
                    <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
            <?php
                    $i = 0;
                    foreach ($onlineResources['Category'] as $category): ?>
                    <tr>
                            <td><?php echo $category['id']; ?></td>
                            <td><?php echo $category['name']; ?></td>
                            <td><?php echo $category['parent_id']; ?></td>
                            <td><?php echo $category['created']; ?></td>
                            <td><?php echo $category['modified']; ?></td>
                            <td><?php echo $category['deleted']; ?></td>
                            <td class="actions">
                                    <?php echo $this->Html->link(__('View'), array('controller' => 'categories', 'action' => 'view', $category['id'])); ?>
                                    <?php echo $this->Html->link(__('Edit'), array('controller' => 'categories', 'action' => 'edit', $category['id'])); ?>
                                    <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'categories', 'action' => 'delete', $category['id']), null, __('Are you sure you want to delete # %s?', $category['id'])); ?>
                            </td>
                    </tr>
            <?php endforeach; ?>
            </table>
    <?php endif; ?>

            <div class="actions">
                    <ul>
                            <li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
                    </ul>
            </div>
    </div>
</div>

