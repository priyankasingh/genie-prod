<div class="conditions form">
<?php echo $this->Form->create('Condition'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Condition'); ?></legend>
	<?php
		echo $this->Form->input('id');
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input('Condition.name.'.$lang, array( 'label'=> 'Name ('.$langLabel.')' ));
		echo $this->Form->input('category_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
