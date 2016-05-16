<div class="conditions form">
<?php echo $this->Form->create('Condition'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Condition'); ?></legend>
	<?php
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input('Condition.name.'.$lang, array( 'label'=> 'Name ('.$langLabel.')' ));
		echo $this->Form->input('category_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
