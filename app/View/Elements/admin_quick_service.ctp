<?php echo $this->Form->create('Service', array('action'=>'add')); ?>

	<?php echo $this->Session->flash(); ?>

	<fieldset class="column-holder">
		<legend><?php echo __('Services Quick-Add'); ?></legend>
		<div class="column1">
	<?php
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
		echo $this->Form->input('time_details');
		echo $this->Form->input('twitter');
		echo $this->Form->input('facebook_url');
	?>
		</div>
		<div class="column2">
	<?php
		echo $this->Form->input('description');
		$this->OHPinMap->admin_map();
		echo $this->Form->input('lat', array('type'=>'hidden'));
		echo $this->Form->input('lng', array('type'=>'hidden'));
		echo $this->Form->input('Category');
		
		echo $this->Form->submit('Save');
		echo $this->Html->image('ajax-loader-small.gif', array('alt' => 'Loading...', 'class'=>'ajax-loader'))
	?>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>
