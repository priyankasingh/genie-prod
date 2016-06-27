<div id="category-filter">
	<?php if(isset($sub_category_list)):?>
		<h2 id="filter-label" class="category-<?php echo $selected_parent_id;?>">
			<?php echo !empty($selected_parent_id) ? __('Filter results by:') : __('Filter my results by:'); ?>
		</h2>
		<?php //echo ($selected_category_id ); ?>
		<ul id="filter-list" class="category-<?php echo $selected_parent_id;?>">
			<li
				class="
				<?php
				echo (!$selected_category_id || is_array($selected_category_id) && !empty($all_id) ) ? 'active ' : '';
				?>
				all-results
				category-<?php echo $selected_parent_id;?>
			">
				<a
					href="<?php
						echo $this->Html->url(
							array(
								'controller' => 'onlineResources',
								'action' => 'index',
								$selected_parent_slug
							)
						);
					?>"
				>
					<?php if (isset($top_three)): ?>
						<?php echo __('My Top Results') ; ?>
					<?php else: ?>
						<?php echo __('All') ; ?>
						<?php
						echo !empty($selected_parent_id) ? $categories[$selected_parent_id] : __('My Results');
						?>
					<?php endif ?>
				</a>
			</li>
			<?php foreach($sub_category_list as $sub_category): ?>

				<li class="
				<?php
				if(is_array($selected_category_id) && empty($all_id)){
					if(in_array($sub_category['Category']['id'], $selected_category_id, false)){
						echo "active";
					}
				}
				elseif($selected_category_id == $sub_category['Category']['id']){
					echo "active";
				} ?>
				<?php  //echo $selected_category_id == $sub_category['Category']['id']?'active ':''; ?>
				category-<?php echo $sub_category['Category']['parent_id']; ?>">
					<a href="<?php echo $this->Html->url( array('controller'=>'onlineResources', 'action'=>'index', $selected_parent_slug, $sub_category['Category']['slug']) ); ?>" class="ajax" data-cat="<?php echo $sub_category['Category']['slug'];?>">
						<?php echo $sub_category['Category']['name'] ;?>
					</a>
				</li>

			<?php endforeach;?>

			<div class="clear"></div>
		</ul>
	<?php else: ?>
	<span class="cat-helper"><?php echo __('Click on the categories above if you would like to filter your search'); ?></span>
	<?php endif; ?>
	<div class="clear"></div>
</div>
