<div class="networkCategories form">
<?php echo $this->Form->create('NetworkCategory'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Network Category'); ?></legend>
	<?php
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input( 'NetworkCategory.name.'.$lang, array( 'label'=> 'Name ('.$langLabel.')' ) );
		echo $this->Form->input('parent_id', array('options'=>$parentNetworkCategories, 'empty'=>'(None)'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
