<div class="login-box">
<?php if (AuthComponent::user('id')): ?>
	<p><?php echo __('Welcome,'); ?> <?php echo AuthComponent::user('email') ?>!</p>
	<p>
		<?php echo $this->Html->link( __('LOGOUT'), array('controller'=>'users', 'action'=>'logout',), array('class'=>'logout') ); ?> / 
		<?php echo $this->Html->link( __('MY ACCOUNT'), array('controller'=>'users', 'action'=>'index'), array('class'=>'my-account') ); ?>
	</p>
<?php else: ?>
	<?php echo $this->Form->create('User', array('controller'=>'Users', 'url' => ['action'=>'login'], 'class'=>'user-form'));?>
	<fieldset>
		<div class="row">
			<?php echo $this->Form->input('email'); ?>
		</div>
		<div class="row">
			<?php echo $this->Form->input('password', array('type'=>'password')); ?>
		</div>
		<div class="btn-holder">
			<?php echo $this->Form->hidden('redirect'); ?>
			<?php echo $this->Form->submit('Log In', array('class'=>'btn')); ?>
		</div>
		<div><a class="logout" href="/users/forgot_password"><?php echo __('Forgot password'); ?></a> | <a class="logout" href="/#questionnaire"><?php echo __('Create Account'); ?></a></div>
	</fieldset>
<?php echo $this->Form->end(); ?>
	
<?php endif; ?>
</div>