<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

	// Services, responses etc
 	Router::connect('/:language', array('controller' => 'responses', 'action' => 'add' ), array('language' => '[a-z]{3}'));
	Router::connect('/:language/services/*',array('controller'=>'services', 'action'=>'index'), array('language' => '[a-z]{3}'));

	Router::connect('/:language/activities-overview',
		array(
			'controller' => 'services',
			'action' => 'availability'
		),
		array(
			'language' => '[a-z]{3}'
		)
	);
	Router::connect('/:language/activities-overview/*',
		array(
			'controller' => 'services',
			'action' => 'availability'
		),
		array(
			'language' => '[a-z]{3}'
		)
	);

	Router::connect('/:language/questionnaire',
		array(
			'controller' => 'responses',
			'action' => 'questionnaire_setup'
		),
		array(
			'language' => '[a-z]{3}'
		)
	);

	Router::connect('/:language/about', array('controller' => 'pages', 'action' => 'view', 1 ), array('language' => '[a-z]{3}'));
	Router::connect('/:language/contact', array('controller' => 'contacts', 'action' => 'add'), array('language' => '[a-z]{3}'));
	Router::connect('/:language/my-network', array('controller' => 'responses', 'action' => 'my_network'), array('language' => '[a-z]{3}'));

	Router::connect('/:language/:controller/:action/*', array(), array('language' => '[a-z]{3}'));


	Router::connect('/admin', array('controller' => 'services', 'action' => 'index', 'admin'=>true));
	Router::connect('/admin/', array('controller' => 'services', 'action' => 'index', 'admin'=>true));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
