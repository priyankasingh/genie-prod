<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>

	<title><?php if( $title_for_layout ) echo $title_for_layout . ' - '; echo Configure::read('Site.name'); ?></title>

	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('https://fast.fonts.net/cssapi/fdd1e71d-fe7f-47a7-a5a6-c61ebcc695d6.css');
		echo $this->Html->css('cake.generic');
		echo $this->Html->css('admin.css');
		echo $this->Html->css('/css/ui-lightness/jquery-ui-1.10.4.custom.css');

		echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
		echo $this->Html->script($this->OHPinMap->apiUrl());
		echo $this->Html->script('lib/ckeditor/ckeditor.js');
		echo $this->Html->script('jquery-ui-1.10.4.custom.min.js');
		echo $this->Html->script('admin.js?v=2');


		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<?php echo $this->Html->link('Log Out', array('controller' => 'users', 'action' => 'logout', 'admin' => false), array('class' => 'btn')); ?>
			<?php if( $overriden_response ) echo $this->Html->link('Restore My Questionnaire Response', array('controller' => 'users', 'action' => 'restore_response', 'admin' => true), array('class' => 'btn')); ?>
			<h1><?php echo $this->Html->link( Configure::read('Site.name'), Configure::read('Site.url')); ?></h1>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>
			<?php echo $this->Session->flash('auth'); ?>

			<?php echo $this->fetch('content'); ?>

			<div class="actions">
				<h3><?php echo __('Tools'); ?></h3>
				<ul>
					<li><?php echo $this->Html->link(__('Service Quick-add'), array('controller' => 'services', 'action' => 'mapping_tool')); ?></li>
				</ul>
				<h3><?php echo __('Actions'); ?></h3>
				<ul>
					<?php if( in_array( 'users', $permitted_controllers ) ): ?>
					<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?>
						<ul>
							<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
							<?php if( in_array( 'favourites', $permitted_controllers ) ): ?>
							<li><?php echo $this->Html->link(__('List Favourites'), array('controller' => 'favourites', 'action' => 'index')); ?> </li>
							<li><?php echo $this->Html->link(__('New Favourite'), array('controller' => 'favourites', 'action' => 'add')); ?> </li>
							<?php endif; ?>
						</ul>
					</li>
					<?php endif; ?>

					<?php if( in_array( 'statements', $permitted_controllers ) ): ?>
					<li><?php echo $this->Html->link(__('List Statements'), array('controller' => 'statements', 'action' => 'index')); ?>
						<ul>
							<li><?php echo $this->Html->link(__('New Statement'), array('controller' => 'statements', 'action' => 'add')); ?> </li>
						</ul>
					</li>
					<?php endif; ?>

					<?php if( in_array( 'conditions', $permitted_controllers ) ): ?>
					<li><?php echo $this->Html->link(__('List Conditions'), array('controller' => 'conditions', 'action' => 'index')); ?>
						<ul>
							<li><?php echo $this->Html->link(__('New Condition'), array('controller' => 'conditions', 'action' => 'add')); ?> </li>
						</ul>
					</li>
					<?php endif; ?>

					<?php if( in_array( 'responses', $permitted_controllers ) ): ?>
					<li><?php echo $this->Html->link(__('List Responses'), array('controller' => 'responses', 'action' => 'index')); ?>
						<ul>
							<li><?php echo $this->Html->link(__('New Response'), array('controller' => 'responses', 'action' => 'add')); ?> </li>
							<?php if( in_array( 'network_members', $permitted_controllers ) ): ?>
							<li><?php echo $this->Html->link(__('List Network Members'), array('controller' => 'network_members', 'action' => 'index')); ?> </li>
							<li><?php echo $this->Html->link(__('New Network Member'), array('controller' => 'network_members', 'action' => 'add')); ?> </li>
							<?php endif; ?>
							<?php if( in_array( 'network_categories', $permitted_controllers ) ): ?>
							<li><?php echo $this->Html->link(__('List Network Categories'), array('controller' => 'network_categories', 'action' => 'index')); ?> </li>
							<li><?php echo $this->Html->link(__('New Network Category'), array('controller' => 'network_categories', 'action' => 'add')); ?> </li>
							<?php endif; ?>
							<?php if( in_array( 'network_types', $permitted_controllers ) ): ?>
							<li><?php echo $this->Html->link(__('List Network Types'), array('controller' => 'network_types', 'action' => 'index')); ?> </li>
							<li><?php echo $this->Html->link(__('New Network Type'), array('controller' => 'network_types', 'action' => 'add')); ?> </li>
							<?php endif; ?>
						</ul>
					</li>
					<?php endif; ?>

					<?php if( in_array( 'services', $permitted_controllers ) ): ?>
					<li><?php echo $this->Html->link(__('List Services'), array('controller' => 'services', 'action' => 'index')); ?>
						<ul>
							<li><?php echo $this->Html->link(__('New Service'), array('controller' => 'services', 'action' => 'add')); ?> </li>
							<?php if( in_array( 'categories', $permitted_controllers ) ): ?>
							<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
							<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
							<?php endif; ?>
						</ul>
					</li>
					<?php endif; ?>

					<?php if (AuthComponent::user('role') == 'f'): ?>
					<li>
					<?php
					echo $this->Html->link(__('Recently modified services (' . $modifiedServicesForFacilitator . ')'),
						array(
							'controller' => 'services_edits',
							'action' => 'index'
						),
						array(
							'class' => 'modified_services_nav' . ($modifiedServicesForFacilitator ? '_new' : '')
						)
					);
					?>
					</li>
					<?php endif ?>
                                        
                                        <?php if(in_array('online_resources', $permitted_controllers ) ): ?>
					<li><?php echo $this->Html->link(__('List Online Resources'), array('controller' => 'online_resources', 'action' => 'index')); ?>
						<ul>
							<li><?php echo $this->Html->link(__('New Online Resources'), array('controller' => 'online_resources', 'action' => 'add')); ?> </li>
						</ul>
					</li>
					<?php endif; ?>
                                        
                                        

					<?php if( in_array( 'pages', $permitted_controllers ) ): ?>
					<li><?php echo $this->Html->link(__('List Pages'), array('controller' => 'pages', 'action' => 'index')); ?>
						<ul>
							<li><?php echo $this->Html->link(__('New Page'), array('controller' => 'pages', 'action' => 'add')); ?> </li>
						</ul>
					</li>
					<?php endif; ?>

					<?php if( in_array( 'contacts', $permitted_controllers ) ): ?>
					<li><?php echo $this->Html->link(__('Contact Form Submissions'), array('controller' => 'contacts', 'action' => 'index')); ?></li>
					<?php endif; ?>

				</ul>
			</div>
		</div>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
