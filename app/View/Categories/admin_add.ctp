<div class="categories form">
<?php echo $this->Form->create('Category'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Category'); ?></legend>
	<?php
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input('Category.name.'.$lang, array( 'label'=> 'Name ('.$langLabel.')' ));
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input('Category.description.'.$lang, array( 'label'=> 'Description ('.$langLabel.')', 'type'=>'textarea' ));
		echo $this->Form->input('parent_id', array('options'=>$parentCategories, 'empty'=>'(None)'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
