<div class="responses form">
<?php echo $this->Form->create('Response'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Response'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('title');
		echo $this->Form->input('name');
		echo $this->Form->input('age');
		echo $this->Form->input('gender');
		echo $this->Form->input('marital_status');
		echo $this->Form->input('postcode');
		echo $this->Form->input('telephone');
		echo $this->Form->input('health_conditions');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
