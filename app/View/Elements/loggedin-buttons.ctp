<div id="loggedin">
    <?php //echo $this->Html->link('My Network Type', array('controller' => 'network_types', 'action' => 'view' ), array('class' => 'btn fancybox-ajax')); ?>
	<?php echo $this->Html->link(__('My Account'), array('controller' => 'users', 'action' => 'account'), array('class' => 'btn')); ?>
    <?php if (AuthComponent::user('is_admin')) echo $this->Html->link(__('Admin'), '/admin/', array('class' => 'btn')); ?>
	<?php echo $this->Html->link(__('Log Out'), array('controller' => 'users', 'action' => 'logout'), array('class' => 'btn')); ?>
</div>