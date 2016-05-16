<div class="networkMembers form">
<?php echo $this->Form->create('NetworkMember'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Network Member'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('frequency');
		echo $this->Form->input('network_category_id');
		echo $this->Form->input('response_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
