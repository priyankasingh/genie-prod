<?php echo $this->Form->create('User', array( 'url' => ['action'=>'login'], 'model'=>'User', 'class' => 'login-form')); ?>
    <fieldset>
        <?php echo $this->Form->input('email', array('id'=>'LoginEmail','placeholder'=>__('Email'), 'label' => false, 'error' => false)); ?>
        <?php echo $this->Form->input('password', array('id'=>'LoginPassword','placeholder'=>__('Password'),'type'=>'password', 'label' => false, 'error' => false)); ?>
        <?php echo $this->Form->submit(__('Log In'), array('id'=>'LoginSubmit','class'=>'btn', 'formnovalidate' => true)); ?>
    </fieldset>
<?php echo $this->Form->end(); ?>
