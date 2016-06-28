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
                
		<dt>
                    <?php if( !empty($onlineResources['OnlineResource']['url']) ):?>
                        <?php echo __('Url'); ?></dt>
                    <dd>
			<?php echo h($onlineResources['OnlineResource']['url']); ?>
			&nbsp;
                    </dd>
                    <?php endif;?>
                    
		<dt>
                    <?php if( !empty($onlineResources['OnlineResource']['description']) ):?>
                        <?php echo __('Description'); ?></dt>
                    <dd>
                            <?php echo h($onlineResources['OnlineResource']['description']); ?>
                            &nbsp;
                    </dd>
                    <?php endif;?>
                    
                <dt>
                    <?php if( !empty($onlineResources['OnlineResource']['age_lower']) ):?>
                        <?php echo __('Lower Age'); ?></dt>
                    <dd>
			<?php echo h($onlineResources['OnlineResource']['age_lower']); ?>
			&nbsp;
                    </dd>
                    <?php endif;?>
                    
                <dt>
                    <?php if( !empty($onlineResources['OnlineResource']['age_upper']) ):?>
                        <?php echo __('Upper Age'); ?></dt>
                    <dd>
			<?php echo h($onlineResources['OnlineResource']['age_upper']); ?>
			&nbsp;
                    </dd>
                    <?php endif;?>
                    
		<dt>
                    <?php if( !empty($onlineResources['OnlineResource']['created']) ):?>
                        <?php echo __('Created'); ?></dt>
                    <dd>
			<?php echo h($onlineResources['OnlineResource']['created']); ?>
			&nbsp;
                    </dd>
                    <?php endif;?>
                    
		<dt>
                    <?php if( !empty($onlineResources['OnlineResource']['modified']) ):?>
                        <?php echo __('Modified'); ?></dt>
                    <dd>
			<?php echo h($onlineResources['OnlineResource']['modified']); ?>
			&nbsp;
                    </dd>
                    <?php endif;?>
                    
                <dt>
                    <?php if( !empty($onlineResources['OnlineResource']['image_path']) ):?>
                        <?php echo __('Image'); ?></dt>
                    <dd>
                        <img src="<?php echo ($this->webroot. 'uploads/images/' . $onlineResources['OnlineResource']['image_path']); ?>">
                    </dd>
                    <?php endif;?>
                    
	</dl>

    <div class="related">
        <?php if (!empty($onlineResources['Category'])): ?>
            <h3><?php echo __('Related Categories'); ?></h3>
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
    

            <div class="actions">
                    <ul>
                            <li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
                    </ul>
            </div>
        <?php endif; ?>
    </div>
</div>