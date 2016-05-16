<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('email');
		echo $this->Form->input('password', array('value'=>''));
		echo $this->Form->input('is_admin');
		echo $this->Form->input('role', array('type'=>'select', 'options'=>$roles));

		echo $this->Form->input('facilitator_id',
		  array(
				'label' => 'Assigned facilitator (only applicable to Champions)',
				'options' => $facilitatorsList,
				'empty' => 'Please choose...'
			)
		);
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
