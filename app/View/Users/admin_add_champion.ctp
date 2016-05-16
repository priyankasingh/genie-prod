<div class="users form">
<?php echo $this->Form->create('User'); ?>
  <fieldset>
    <legend><?php echo __('Admin Add User') . ' (Champion)'; ?></legend>
  <?php
    echo $this->Form->input('email');
    echo $this->Form->input('password');
    echo $this->Form->input('is_admin',
      array(
        'type' => 'hidden',
        'value' => 1
      )
    );
    echo $this->Form->input('role',
      array(
        'type' => 'hidden',
        'value' => 'c'
      )
    );
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
