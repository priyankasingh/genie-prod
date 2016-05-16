<div class="favourites form">
<?php echo $this->Form->create('Favourite'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Favourite'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('service_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
