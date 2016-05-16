<?php 
	$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
?>
<ul id="results-list">
	<?php $index = 0;?>
	<?php foreach($parents as $parent_id => $parent):?>
		<li class="category-<?php echo $parent_id;?>">
			<h2><?php echo $categories[$parent_id];?></h2>
			<ul class="aside-list">
				<?php foreach($parent as $service):?>
					<li class="service" data-lat="<?php echo $service['Service']['lat'];?>"
						data-lng="<?php echo $service['Service']['lng'];?>"
						data-content="<?php echo '<div class=\'oh-pin category-'.$parent_id.'\'>'.$alphabet[$index].'</div>';?>">
						<span class="number"><?php echo $alphabet[$index];?></span>
						<div class="text-section">
							<h3><?php echo $service['Service']['name'];?> - 
								<span class="mark"><?php echo $service['Category'][0]['name'];?></span></h3>
							
							<?php if( empty( $service['Favourite'] ) ): ?>
								<?php echo $this->Html->link( __('Favourite This'), array('controller'=>'favourites', 'action'=>'add', $service['Service']['id']), array('class'=>'favourite-link ajax') ); ?>
							<?php else: ?>
								<?php echo $this->Html->link( __('Remove from favourites'), array('controller'=>'favourites', 'action'=>'delete', $service['Service']['id']), array('class'=>'favourite-link ajax favourite-exists') ); ?>
							<?php endif; ?>

						</div>
						<a class="more ajax" href="#"><?php echo __('Read More'); ?></a>
					</li>
				<?php $index++; endforeach;?>
			</ul>
		</li>
	<?php endforeach;?>
</ul>