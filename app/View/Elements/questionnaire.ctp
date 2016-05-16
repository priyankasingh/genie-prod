<?php if( $this->Session->read('response_replaced') ): ?>
	<p><?php echo __('You are currently previewing another user\'s saved response. Please switch to your own response in the admin area to use the questionnaire.'); ?></p>
<?php else: ?>
<?php echo $this->Form->create(array('url'=>array( 'controller'=>'responses', 'action'=>'add', '#'=> 'questionnaire' ), 'class'=>'postcode-form placeholder-labels', 'type'=>'post'));?>

	<fieldset class="questionnaire-page">
		<legend class="legend-title"><?php echo __('General Information about you'); ?></legend>
		<p class="legend-desc" ><em><?php echo __("Click 'Next Question' once you have completed this page"); ?>.</em></p>
		<div class="column-holder">
			<div class="column1">
				<?php
				echo $this->Form->input('postcode', array('after'=>'<small>' . __("We need this to find services near you") . '.</small>') );
				echo $this->Form->input('lat', array('type'=>'hidden') );
				echo $this->Form->input('lng', array('type'=>'hidden') );

				echo $this->Form->input('name', array('after'=>'<small>' . __("Optional").'.</small>'));
				echo $this->Form->input('User.email', array('type'=>'text', 'disabled'=>( AuthComponent::user('id') ?true :false ), 'default'=>( AuthComponent::user('id') ? AuthComponent::user('email') : false ), 'after'=>'<small>' . __("Optional. If you supply your email address, we'll save your answers and email you a login so you won't need to take the questionnaire again") . '.</small>'));
				?>
			</div>
			<div class="column2">
				<?php
				echo $this->Form->input('gender', array( 'type'=>'radio', 'options'=>array( 'm'=>__('Male'),'f'=>__('Female') ), 'legend'=>__('Please select your gender:') ) );
				echo $this->Form->input('age', array('legend'=>__('Please select your age range:'), 'type'=>'radio', 'options'=>array( '18-24'=>'18-24','25-40'=>'25-40','41-55'=>'41-55','56-65'=>'56-65','66+'=>'66+' ) ));
				echo $this->Form->input('Condition', array('label'=>__("Do you have any of the following health conditions?") . '<span>' . __('(Click all that are appropriate to you.)') . '</span>', 'multiple'=>'checkbox'));
				?>
			</div>
		</div>
	</fieldset>

<?php
// Set incrementing $i as greater than last element in existing array
$i = empty( $this->request->data['NetworkMember'] ) ? 0 : max(array_keys($this->request->data['NetworkMember'])) + 1;
?>
	<fieldset class="questionnaire-page network-members" id="network-members">
		<legend class="legend-title"><?php echo __('My Network'); ?></legend>

		<div id="network-diagram">
		<div id="network-leg">
				<?php /*<ul class="leg-distance">
					<li>Co-habiting</li>
					<li>Short Walk/Drive Away</li>
					<li>Lives up to one hour away</li>
					<li>Over one hour away</li>
				</ul>*/ ?>
				<ul class="leg-frequency">
					<li><?php echo __('Daily'); ?></li>
					<li><?php echo __('At least once a week'); ?></li>
					<li><?php echo __('At least once a month'); ?></li>
					<li><?php echo __('Less often') ; ?></li>
				</ul>
				<div id="network-trash-can"></div>
				<div class="trash-instructions">
				<?php echo __('Drag your pin over the trash icon above to delete it'); ?>.
			</div>
		</div>

			<p class="legend-desc">
				<em><?php echo __("Please tell us about your network of friends, family and others you're in contact with, to help us supply you with the most useful results"); ?>.</em>
				<a
					href="<?php echo $this->Html->url('/img/', true); ?>"
				>
					<?php echo __("For an example click here"); ?>
				</a>.
			</p>
			<p class="legend-desc" ></p>

			<div class="group-rows-inner">
				<div class="group-row" id="add-pin-form" >
					<?php
					echo '<div class="network-name">'.$this->Form->input('NetworkMember.-1.name', array('after'=>'<small>' . __('Their name, or just a nickname if you prefer') . '.</small>') ).'</div>';
					echo '<div class="network-frequency">'.$this->Form->input('NetworkMember.-1.frequency', array('after'=>'<small>' . __('How often are you in contact with them?') . '</small>') ).'</div>';
					echo '<div class="network-category">'.$this->Form->input('NetworkMember.-1.network_category_parent_id', array('after'=>'<small>' . __('What type of relationship do they have to you') .' </small>', 'options'=>$parentNetworkCategories) ).'</div>';

					foreach( $parentNetworkCategories as $parentNetworkCategoryId => $parentNetworkCategory ):
						$subOptions = array();
						foreach( $networkCategories as $networkCategory )
							if( $parentNetworkCategoryId == $networkCategory['NetworkCategory']['parent_id'] )
								$subOptions[ $networkCategory['NetworkCategory']['id'] ] = $networkCategory['NetworkCategory']['name'];
						if( empty( $subOptions ) ) continue;

						echo '<div class="network-category network-child-category">'.$this->Form->input('NetworkMember.-1.network_category_id', array('after'=>'<small>'. __('Who are they?') . '</small>', 'options'=>$subOptions, "id" => "network-category-" .$parentNetworkCategoryId , "class" => "dropdown_category_".$parentNetworkCategoryId) ).'</div>';
					endforeach; ?>
					<input name="data[NetworkMember][-1][other]" maxlength="200" type="text" id="other-name" value="other">
				</div>

			</div>
			<div class="network-pin-area">
			<?php
			$numRows = 1;
			if( !empty($this->request->data['NetworkMember']) ) $numRows = count( $this->request->data['NetworkMember'] ) + 1;
			for( $rowKey=0; $rowKey<$numRows; $rowKey++ ):
				$existingKey = !empty($this->request->data['NetworkMember'][$rowKey]) && !empty($this->request->data['NetworkMember'][$rowKey]);
				$x = empty( $this->request->data['NetworkMember'][$rowKey]['diagram_x'] ) ? '0' : $this->request->data['NetworkMember'][$rowKey]['diagram_x'];
				$y = empty( $this->request->data['NetworkMember'][$rowKey]['diagram_y'] ) ? '0' : $this->request->data['NetworkMember'][$rowKey]['diagram_y'];
			?>
				<div id="drag_<?php echo $rowKey; ?>" class="network-pin network-pin-<?php if( $existingKey ): echo $this->request->data['NetworkMember'][$rowKey]['frequency']; ?> network-pin-placed" style="position:absolute;top:<?php echo $y; ?>px;left:<?php echo $x; ?>px;<?php else: ?>daily<?php endif; ?>">
					<div class="network-pin-info">
						<div class="network-pin-name"><?php
							if( $existingKey && !empty($this->request->data['NetworkMember'][$rowKey]['name']) )
								echo h( $this->request->data['NetworkMember'][$rowKey]['name'] );
						?></div>
						<div class="network-pin-role"><?php

							if( $existingKey && !empty($this->request->data['NetworkMember'][$rowKey]['network_category_id']) ) {
								if($networkCategories[ $this->request->data['NetworkMember'][$rowKey]['network_category_id'] ]['NetworkCategory']['name'] != "Other"){
									echo h( $networkCategories[ $this->request->data['NetworkMember'][$rowKey]['network_category_id'] ]['NetworkCategory']['name'] );
								} elseif( !empty($this->request->data['NetworkMember'][$rowKey]['other']) ) {
									echo h( $this->request->data['NetworkMember'][$rowKey]['other']);
								}
							}
							?>
						</div>
					</div>

					<?php
					if( $existingKey ) echo $this->Form->input('NetworkMember.'.$rowKey.'.id', array('type'=>'hidden'));
					echo $this->Form->input('NetworkMember.'.$rowKey.'.name', array('type'=>'hidden') );
					echo $this->Form->input('NetworkMember.'.$rowKey.'.frequency', array('type'=>'hidden') );
					echo $this->Form->input('NetworkMember.'.$rowKey.'.network_category_id', array('type'=>'hidden') );
					echo $this->Form->input('NetworkMember.'.$rowKey.'.other', array('type'=>'hidden') );
					echo $this->Form->input('NetworkMember.'.$rowKey.'.diagram_x', array('type'=>'hidden') );
					echo $this->Form->input('NetworkMember.'.$rowKey.'.diagram_y', array('type'=>'hidden') );
					if( !$existingKey ) echo $this->Form->input('NetworkMember.'.$rowKey.'.dummy_pin', array('type'=>'hidden', 'default'=>'1') );
					?>
				</div>
			<?php endfor; ?>
			</div>
			<div class="network-instructions">
				<?php echo __('Once you have finished entering your information, drag your newly created pin to the diagram.') ; ?>
			</div>

			<div id="network-circle">
				<div class="network-circle-name"><?php echo __('You'); ?></div>
			</div>

		</div>
	</fieldset>

<?php
$questionCount = count( $statements );
$i = 0;
foreach( $statements as $statement ):
	$i++;
?>

	<fieldset class="questionnaire-page statement question-page-<?php echo $i; ?>">
		<legend class="legend-page"><?php echo __('Page'); ?> <?php echo $i; ?> / <?php echo $questionCount; ?></legend>

		<div class="question-choice">
		<?php
			// Existing ResponseStatement?
			echo $this->Form->input( 'ResponseStatement.'.$statement['Statement']['id'].'.weighting',
				array(
					'type'=>'radio',
					'default'=>0,
					'options'=>array(
						0=>__('No, I am not interested'),
						1=>__('Yes, I might be interested'),
						2=>__('Yes, I am definitely interested')
					),
					'legend'=>$statement['Statement']['statement']
				)
			);
			echo $this->Form->input( 'ResponseStatement.'.$statement['Statement']['id'].'.statement_id',
				array(
					'type'=>'hidden',
					'default'=>$statement['Statement']['id']
				)
			);
			if( isset( $this->request->data['ResponseStatement'][$statement['Statement']['id']] ) )
				echo $this->Form->input( 'ResponseStatement.'.$statement['Statement']['id'].'.id', array('type'=>'hidden') );
		?>
		</div>
		<div class="question-categories">
			<fieldset>
				<?php
				// pr($statement);
				?>
				<legend><?php echo __('I am interested in the following things') ; ?></legend>
				<?php
				$options = array();
				foreach( $statement['Category'] as $category ){
					$options[$category['id']] = $category['name'];
				}
				?>
				<?php
				echo $this->Form->input( 'ResponseStatement.'.$statement['Statement']['id'].'.Category',
					array(
						'options'=>$options,
						'multiple' => 'checkbox',
						'label'=>false
					)
				);
				?>
			</fieldset>
		</div>
			
		<div class="question-categories-network-template">
			<fieldset>
				<legend>
					<span class="networkMemberName"></span>
					<?php echo __(' is interested in the following things') ; ?>
				</legend>
				<?php
				$options = array();
				foreach( $statement['Category'] as $category ){
					$options[$category['name']] = $category['name'];
				}
				?>
				<?php
				echo $this->Form->input( 'NetworkMember.NetworkMemberIndex.Interests.',
					array(
						'options' => $options,
						'multiple' => 'checkbox',
						'label' => false,
					)
				);
				?>
			</fieldset>
		</div>

		<div class="question-description formatted">
			<?php echo $statement['Statement']['description']; ?>
		</div>
	</fieldset>
<?php endforeach; ?>
	<?php echo $this->Form->submit(__('Finish'), array('formnovalidate' => true)); ?>

<?php echo $this->Form->end();?>

<?php endif; ?>
