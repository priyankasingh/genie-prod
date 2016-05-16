<?php
// pr($parents);
?>

<?php
	$this->Html->script('jquery.history',array('inline'=>false));
	$this->Html->script('richmarker-compiled',array('inline'=>false));

	$this->Html->script('services.js?v=1.2.1',array('inline'=>false));
	$this->Html->script('getting_there',array('inline'=>false));
	$this->Html->script('map.js?v=1.0', array('inline'=>false));

?>
<div class="content-box <?php if( !empty( $favourites ) ) echo 'favourites '; ?>">
	<div id="category-description">
		<div id="parent-helper" <?php if(isset($active_nav) && $active_nav == 'my_plans') echo "class='serviceParent'"; ?>></div>
		<?php
		//if(isset($active_nav) && $active_nav == 'my_plans') echo "<h2>HOLLA</h2>";
		?>
	<?php
	if( !empty( $parent_category ) ) echo $parent_category['Category']['description'];
	?>
	</div>
	<form id="postcode-form" method="get" >
		<fieldset class="change-form placeholder-labels">
			<label for="postcodeField"><?php echo __('Change your postcode'); ?></label>
			<input id="postcodeField" type="text" name="postcode" value="<?php echo isset($postcode)?$postcode:''?>" />
			<input id="latField" type="hidden" name="latitude" value="<?php echo isset($latitude)?$latitude:'';?>"/>
			<input id="lngField" type="hidden" name="longitude" value="<?php echo isset($longitude)?$longitude:'';?>"/>
			<input id="searchField" type="hidden" name="search" value="<?php echo isset($search)?$search:'';?>"/>
			<input id="oldPostcodeField" type="hidden" value="<?php echo isset($postcode)?$postcode:'Change your Postcode'?>" />
			<input type="image" value="SEARCH" src="/img/btn-search2.png" />
		</fieldset>

		<?php
		echo $this->element('category_filter',
			array(
				'sub_category_list' => isset($sub_category_list)?$sub_category_list:null,
				'categories' => isset($categories)?$categories:null,
				'selected_parent_id' => isset($selected_parent_id)?$selected_parent_id:null,
				'selected_parent_slug' => isset($selected_parent_slug)?$selected_parent_slug:null,
			)
		);
		?>

		<fieldset class="show-form">
			<h2><?php echo __('Show Results Within:'); ?></h2>
			<?php
				$checked = array_fill(0,11,'');
				if(isset($miles)){
					$checked[$miles] = 'checked="checked"';
				}else{
					$checked[5] = 'checked="checked"';
				}
			?>
			<div class="area">
				<label for="mile1"><?php echo __('1 Mile'); ?></label>
				<input id="mile1" type="radio" name="miles" value="1" class="radio" <?php echo $checked[1];?>/>
			</div>
			<div class="area">
				<label for="mile2"><?php echo __('2 Miles'); ?></label>
				<input id="mile2" type="radio" name="miles" value="2" <?php echo $checked[2];?>/>
			</div>
			<div class="area">
				<label for="mile5"><?php echo __('5 Miles'); ?></label>
				<input id="mile5" type="radio" name="miles" value="5" <?php echo $checked[5];?>/>
			</div>
			<div class="area">
				<label for="mile10"><?php echo __('10 Miles'); ?></label>
				<input id="mile10" type="radio" name="miles" value="10" <?php echo $checked[10];?>/>
			</div>
		</fieldset>
	</form>

	<div class="aside">
		<a class="print" href="#"><?php echo __('Print Your results'); ?></a>
		<?php if( !empty( $hasResponse ) ): ?>
		<div class="link-holder">
			<?php
			if (isset($top_three)) {
				$resultsAnchorText = 'My Top Results';
			} else {
				$resultsAnchorText = 'My Results';
			}

			echo $this->Html->link(__($resultsAnchorText),
				array(
					'controller' => 'services',
					'action' => 'index',
					'my-map'
				),
				array(
					'class' => 'results'
				)
			);
			?>
			<?php if (AuthComponent::user('id')): ?>
			<?php
			echo $this->Html->link(__('My Favourites'),
				array(
					'controller' => 'services',
					'action' => 'index',
					'favourites'
				),
				array(
					'class' => 'favourites'
				)
			);
			?>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php
		echo $this->element('results_box',
			array(
				'parents' => isset($parents) ? $parents : null,
				'categories' => isset($categories) ? $categories : null,
				'paginator' => isset($this->Paginator) ? $this->Paginator : null,
				'category' => isset($category) ? $category : null,
				'service' => isset($service) ? $service : null,
				'twitter' => isset($twitter) ? $twitter : null,
			)
		);
		?>
	</div>

	<div class="map">
		<?php echo $this->OHPinMap->map();?>
	</div>

	<?php // Favourites box ?>
	<?php if(!AuthComponent::user('id')): ?>
	<div id="favourites-action-wrapper">
		<div id="favourites-action">
			<h2><?php echo __('Already have a EUGENIE account?'); ?></h2>
			<strong><?php echo __('Log In below to add favourites'); ?></strong>
			<?php echo $this->element('login-form'); ?>

			<h2><?php echo __("Don't have an account? Fill in the EUGENIE questionnaire..."); ?></h2>
			<strong><?php echo __('Put in your email address when taking the questionnaire to automatically set up an account.'); ?></strong>
			<div class="btn-holder">
				<a href="/#questionnaire" class="btn question-button" ><?php echo __('Fill in the EUGENIE questionnaire...'); ?></a>
			</div>
		</div>
	</div>
	<?php endif; ?>

</div>

<div id="loadingspinner"></div>
<?php //echo $this->element('getting_there'); ?>
