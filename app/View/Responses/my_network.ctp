<div class="network-type-report main-holder">
	<div class="page-content text-box">
		<h2><?php echo __('My Network'); ?> &mdash; <?php echo h($network_type['NetworkType']['name']); ?></h2>
		<?php echo $network_type['NetworkType']['description']; ?>

		<h3><?php echo __('In your network'); ?></h3>
		<?php $count = (count($response['NetworkMember'])) ;?>

		<p><?php echo $count == 1 ? __('There is 1 network member.') : sprintf(__('There are %s network members.'), $count); ?>

		<?php if ($count != 0) : ?>
			<?php echo __('This includes'); ?> <?php

			$scoreSentences = array();

			foreach($scores as $networkCategoryId => $score){
				if( $score > 0 ){
					$sentence = $score .' '. strtolower( $parentNetworkCategories[ $networkCategoryId ] );
					if( $score > 1 ) $sentence.='s';

					$scoreSentences[] = $sentence;
				}
			}
			$last = ( count( $scoreSentences ) > 1 ) ? array_pop( $scoreSentences ) : false;
			echo implode( ', ', $scoreSentences );
			if( $last ) echo __(' and ') . $last;
			?>
		<?php endif; ?>
	</p>
	</div>

	<div id="network-diagram">
		<div id="network-circle">
			<div class="network-circle-name"><?php echo h($response['Response']['name']); ?></div>
		</div>

		<?php
		$numRows = 1;
		foreach( $response['NetworkMember'] as $index => $networkMember ):
			$x = empty( $networkMember['diagram_x'] ) ? '0' : $networkMember['diagram_x'];
			$y = empty( $networkMember['diagram_y'] ) ? '0' : $networkMember['diagram_y'] - 288;
		?>
			<div class="network-pin network-pin-<?php echo $networkMember['frequency']; ?> network-pin-placed" style="position:absolute;top:<?php echo $y; ?>px;left:<?php echo $x; ?>px;">
				<div class="network-pin-info">
					<div class="network-pin-name"><?php
						if( !empty($networkMember['name']) )
							echo h( $networkMember['name'] );
					?></div>
					<div class="network-pin-role"><?php
						if( !empty( $networkMember['NetworkCategory'] ) )
							echo h( $networkMember['NetworkCategory']['name'] );
					?></div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>


