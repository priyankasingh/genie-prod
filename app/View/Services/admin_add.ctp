<div class="services form">
<?php echo $this->Form->create('Service'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Service'); ?></legend>
	<?php
		echo $this->Form->input('lang', array('label'=>'Language', 'options'=>Configure::read('Site.languages')));
		echo $this->Form->input('name');
		echo $this->Form->input('contact_name');
		echo $this->Form->input('address_1');
		echo $this->Form->input('address_2');
		echo $this->Form->input('address_3');
		echo $this->Form->input('town');
		echo $this->Form->input('postcode');
		echo $this->Form->input('phone');
		echo $this->Form->input('email');
		echo $this->Form->input('url');
		echo $this->Form->input('description');
		echo $this->Form->input('time_details');
		echo $this->Form->input('twitter');
		echo $this->Form->input('facebook_url');
		$this->OHPinMap->admin_map();
		echo $this->Form->input('lat', array('type'=>'text'));
		echo $this->Form->input('lng', array('type'=>'text'));
		echo $this->Form->input('Category');
	?>
	</fieldset>
	<fieldset>
		<h3>Suitable for</h3>
		<?php
		echo $this->Form->input('age_lower', array('label'=>'Ages from...'));
		echo $this->Form->input('age_upper', array('label'=>'...to...'));
		echo $this->Form->input('gender_m', array('label'=>'Males'));
		echo $this->Form->input('gender_f', array('label'=>'Females'));
		?>
	<fieldset>
	</fieldset>
		<h3>Videos</h3>
		<div class="multi-rows">
			<div class="multi-rows-inner">
				<div class="multi-row">
					<?php echo $this->Form->input('Video.0.name'); ?>
					<?php echo $this->Form->input('Video.0.url'); ?>
					<div class="multi-row-actions">
						<a class="multi-row-remove" title="Delete row" href="#">Delete row</a>
					</div>
				</div>
			</div>
			<a class="multi-row-add" title="Add another row" href="#">Add another</a>
		</div>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
