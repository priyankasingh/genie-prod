<div class="statements form">
<?php echo $this->Form->create('Statement'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Statement'); ?></legend>
	<?php
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input('Statement.statement.'.$lang, array( 'label'=> 'Statement ('.$langLabel.')' ));
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input('Statement.description.'.$lang, array( 'label'=> 'Description ('.$langLabel.')', 'type'=>'textarea' ));
		echo $this->Form->input('order');
		echo $this->Form->input('Category');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
