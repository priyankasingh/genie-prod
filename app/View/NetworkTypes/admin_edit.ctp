<div class="networkTypes form">
<?php echo $this->Form->create('NetworkType'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Network Type'); ?></legend>
	<?php
		echo $this->Form->input('id');
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input( 'NetworkType.name.'.$lang, array( 'label'=> 'Name ('.$langLabel.')' ) );
		foreach( Configure::read('Site.languages') as $lang => $langLabel ) echo $this->Form->input( 'NetworkType.description.'.$lang, array( 'label'=> 'Description ('.$langLabel.')', 'type'=>'textarea' ) );
		echo $this->Form->input('ruleset');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
