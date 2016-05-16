<div class="pages form">
<?php echo $this->Form->create('Page'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Page'); ?></legend>
	<?php
		echo $this->Form->input('id');
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input( 'Page.name.'.$lang, array( 'label'=> 'Name ('.$langLabel.')' ) );
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input( 'Page.content.'.$lang, array( 'label'=> 'Content ('.$langLabel.')', 'type'=>'textarea' ) );
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
