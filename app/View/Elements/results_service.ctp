<?php
	$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
?>
<div class="category-<?php echo $parent_id;?> service service-<?php echo $service['Service']['slug'];?>" data-lat="<?php echo $service['Service']['lat'];?>"
					 data-lng="<?php echo $service['Service']['lng'];?>"
					 data-content="<?php echo '<div class=\'oh-pin category-'.$parent_id.'\'>'.$alphabet[$index].'</div>';?>"
					 data-slug="<?php echo $service['Service']['slug'];?>"
					 data-id="<?php echo $service['Service']['id'];?>">
	<div class="text-box">
		<h2><?php echo $categories[$parent_id];?></h2>
		<span class="number"><?php echo $alphabet[$index];?></span>
		<div class="text-section">
			<h3><?php echo $service['Service']['name'];?> -
				<span class="mark"><?php echo $service['Category'][0]['name'];?></span></h3>

			<?php if( empty( $service['Favourite'] ) ): ?>
				<?php echo $this->Html->link( __('Favourite This'), array('controller'=>'favourites', 'action'=>'add', $service['Service']['id']), array('class'=>'favourite-link ajax') ); ?>
			<?php else: ?>
				<?php echo $this->Html->link( __('Remove from favourites'), array('controller'=>'favourites', 'action'=>'delete', $service['Service']['id']), array('class'=>'favourite-link ajax favourite-exists') ); ?>
			<?php endif; ?>


			<div class="clear"></div>
		</div>
	</div>
	<div class="text-box">
		<h2><?php echo __('When?'); ?></h2>
		<p><?php echo $service['Service']['time_details'];?></p>
	</div>
	<div class="text-box">
		<h2><?php echo __('Where?'); ?></h2>
		<p><?php echo $service['Service']['address_1'].', '.$service['Service']['address_2'].', '.$service['Service']['town'].', '.$service['Service']['postcode'];?></p>
		<a href="#" data-lat="<?php echo $service['Service']['lat']; ?>" data-lng="<?php echo $service['Service']['lng']; ?>" class="street-start"><img src="/img/street-icon.png"><?php echo __('Street view'); ?></a>
	</div>
	<div class="text-box">
		<h2><?php echo __('Contact'); ?></h2>
		<p><strong><?php echo __('Tel:'); ?> </strong><?php echo $service['Service']['phone'];?></p>
		<?php if( !empty($service['Service']['email']) ):?>
			<p><strong><?php echo __('Email:'); ?> </strong><a href="mailto:<?php echo h($service['Service']['email']);?>" class="email-link" target="_blank"><?php echo h($service['Service']['email']);?></a></p>
		<?php endif;?>
		<p><strong><?php echo __('Web:'); ?> </strong><a href="<?php echo $service['Service']['url'];?>" target="_blank"><?php echo $service['Service']['url'];?></a></p>
		<?php if(isset($service['Service']['twitter']) && $service['Service']['twitter'] != ''):?>
			<a href="https://twitter.com/<?php echo $service['Service']['twitter'];?>" class="twitter-link" target="_blank"><?php echo $service['Service']['twitter'];?></a>
		<?php endif;?>
		<?php if(isset($service['Service']['facebook_url']) && $service['Service']['facebook_url'] != ''):?>
			<a href="<?php echo $service['Service']['facebook_url'];?>" class="facebook-link" target="_blank"><?php echo __('Facebook'); ?></a>
		<?php endif;?>
	</div>
	<div class="text-box info-box">
	<h2><?php echo __('Info'); ?></h2>
		<?php echo $service['Service']['description'];?>
	</div>
	<?php if(!empty($service['Video'])) : ?>
		<div class="text-box">
			<h2><?php echo __('Video'); ?></h2>
			<ul><?php $i = 0; ?>
				<?php foreach($service['Video'] as $video) : ?>
					<li>
						<a href="#video-box-<?php echo $i; ?>" class="video-link">
							<h4><?php echo $video['name']; ?></h4>
							<img class="video-thumb" src="<?php echo $video['thumb_url']; ?>">
						</a>	
						<div class="fancy-hide">
							<div class="" id="video-box-<?php echo $i; ?>">
								<?php echo $video['embed_code']; ?>
								<?php $i++ ; ?>
							</div>
						</div>
					</li>
				<?php endforeach; ?>					
			</ul>
			<div style="clear:both"></div>
		</div>
	<?php endif; ?>	
	<?php if($twitter):?>
		<div class="text-box">
			<h2><?php echo __('Tweets from'); ?> <?php echo $service['Service']['twitter'];?></h2>
			<ul class="twitter">
			<?php foreach($twitter as $tweet):?>
				<li>
					<div class="text"><?php echo $tweet['text'];?></div>
					<div class="date"><?php echo $tweet['time_difference'];?></div>
				</li>
			<?php endforeach;?>
			</ul>
		</div>
	<?php endif;?>

		<a class="back-link" href="#"><?php echo __('Back to all Results'); ?></a>
</div>