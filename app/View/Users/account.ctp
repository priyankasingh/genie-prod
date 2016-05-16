<div class="login-box">
<?php echo $this->Form->create('User', array('action' => 'account', 'class'=>'user-form'));?>
	<fieldset>
		<h2><?php echo __('My Account'); ?></h2>
		<?php 
		echo $this->Form->input('email', array('label'=>  __('Change email address') ) ); 
		?>
		<h3><?php echo __('Change password'); ?></h3>
		<div class="change_password">
		<?php
		echo $this->Form->input('old_password', array('type'=>'password', 'label'=>__('Old password'))); 
		echo $this->Form->input('password', array('type'=>'password', 'label'=> __('New password'))); 
		echo $this->Form->input('password_confirm', array('type'=>'password', 'label'=> __('Confirm new password'))); 
		?>
		</div>
		<div class="btn-holder">
			<?php echo $this->Form->submit('Save changes', array('class'=>'btn')); ?>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>

</div>