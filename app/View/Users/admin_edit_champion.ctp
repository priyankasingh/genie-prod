<div class="users form">
<?php echo $this->Form->create('User'); ?>
  <fieldset>
    <legend><?php echo __('Admin Edit User') . ' (Champion)'; ?></legend>
  <?php
    echo $this->Form->input('id');
    echo $this->Form->input('email');
    echo $this->Form->input('password',
      array(
        'value' => ''
      )
    );
    echo $this->Form->input('is_admin');
    echo $this->Form->input('role',
      array(
        'type' => 'select',
        'options' => array(
          'c' => 'Champion',
        ),
        'empty' => 'None'
      )
    );
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
