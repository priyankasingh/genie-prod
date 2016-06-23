			<div class="login-box">
					<h2><?php echo __('Forgot Password'); ?></h2>
					<?php if( empty( $success ) ): ?>
						<?php
							echo $this->Form->create('User', array('url' => ['action'=>'forgot_password'], 'class'=>'user-form'));
							echo $this->Form->input('email', array('label'=>__('Please enter the email address you used to sign up, and a new password will be sent to you.')));
						?>
						<div class="submit">
							<?php 
								echo $this->Form->submit('Reset Password', array('class'=>'btn'));
							?>
						</div>
						<?php echo $this->Form->end(); ?>
						
						
					<?php elseif($success==1): ?>
						<h3><?php echo __('Thank you'); ?></h3>
						<p><?php echo __('Please check your email inbox for further instructions.'); ?> </p>
						<p><?php echo __("If the email doesn't arrive, please check your junk email folder before trying again."); ?></p>
					<?php elseif($success==2): ?>
						<h3><?php echo __('Please choose a new password'); ?></h3>
						<?php
							echo $this->Form->create('User', array('url'=>array( 'controller'=>'users', 'action'=>'forgot_password', $user_id, $user_key ), 'class'=>'user-form'));
							echo $this->Form->input('password', array('type'=>'password', 'label'=> 'New password')); 
							echo $this->Form->input('password_confirm', array('type'=>'password', 'label'=> 'Confirm new password')); 
						?>
						<div class="submit">
							<?php 
								echo $this->Form->submit('Reset Password', array('class'=>'btn'));
							?>
						</div>
						<?php echo $this->Form->end(); ?>
					<?php elseif($success==3): ?>
						<h3><?php echo __('Thank you'); ?></h3>
						<p><?php echo __('Your new password is now active.'); ?></p>
						<p><?php echo __('Please log in above with your new details.'); ?></p>
					<?php endif; ?>
			</div>
