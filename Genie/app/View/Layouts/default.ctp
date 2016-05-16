<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php if( $title_for_layout ) echo $title_for_layout . ' - '; echo Configure::read('Site.name'); ?></title>

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="robots" content="noindex">

	<?php
		$version_no = '2.8.0';

		echo $this->Html->meta('icon');

		echo $this->Html->css('http://fast.fonts.net/cssapi/fdd1e71d-fe7f-47a7-a5a6-c61ebcc695d6.css?v='.$version_no);
		echo $this->Html->css('/css/ui-lightness/jquery-ui-1.10.4.custom.css');
		echo $this->Html->css('all.css?v='.$version_no);
		echo $this->Html->css('/js/lib/fancybox/jquery.fancybox-1.3.4.css');
		echo $this->Html->css('multiple-select.css');

		echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
		echo $this->Html->script('//code.jquery.com/jquery-migrate-1.2.1.min.js');
		echo $this->Html->script($this->OHPinMap->apiUrl());

		echo $this->Html->script('lib/fancybox/jquery.fancybox-1.3.4.pack.js');
		echo $this->Html->script('jquery.placeholder.js?v='.$version_no);
		echo $this->Html->script('jquery.cookie.js?v='.$version_no);
		echo $this->Html->script('lib.js?v='.$version_no);

		echo $this->Html->script('jquery-ui-1.10.4.custom.min.js');
		?>
		<?php if (!($this->params['controller'] == 'services' && $this->params['action'] == 'availability')): ?>
		<script>
		$(document).ready(function(){
			// JCF - page init
			bindReady(function(){
				jcf.customForms.replaceAll();
			});
		});
		</script>
		<?php endif ?>

		<?php if ($this->params['controller'] == 'services' && $this->params['action'] == 'availability'): ?>
		<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
		<?php endif ?>

		<?php
		echo $this->Html->script('main.js?v='.$version_no);

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		echo $this->fetch('pageScript');
	?>

	<!--[if IE 7]> <link href= "css/ie.css" rel= "stylesheet" media= "all" /> <![endif]-->
	<!--[if lte IE 9]><?php

	// CSS 3 columns
	echo $this->Html->script('jquery.columnizer.min.js?v='.$version_no);
	echo $this->Html->script('ie.js?v='.$version_no);
	?><![endif]-->

	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-42641233-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</head>
<body <?php echo (isset($body_class) ? 'class = "'.$body_class.'"' : '');?>>
	<?php
	pr($this->params['controller']);
	pr($this->params['action']);
	?>
	<div id="wrapper">
		<div class="w1">
			<div class="w2">
				<div id="header">
					<div class="header-section">
					<div class="header-holder">
						<div class="color-box">
							<ul>
								<li class="yellow"><a id="contrast-on" href="#" title="High Contrast Mode">yellow</a></li>
								<li class="blue"><a id="contrast-off" href="#" title="Normal Theme">blue</a></li>
							</ul>
							<span class="title"><?php echo __('Change contrast'); ?></span>
						</div>
						<div class="size-box">
							<ul>
								<li class="large"><a href="#" id="increase">T</a></li>
								<li class="average"><a href="#" id="reset">T</a></li>
								<li class="small"><a href="#" id="decrease">T</a></li>
							</ul>
							<span class="title"><?php echo __('Change Type Size'); ?></span>
						</div>
						<div class="password-box">
						<?php
							if (AuthComponent::user('id')) {
								echo $this->element('loggedin-buttons');
							}
							else {
								echo $this->element('login-form');

								echo "<div id='forgot'>" . $this->Html->link(__('Forgotten your Password?'), array('controller' => 'users', 'action' => 'forgot_password')) . "</div>";
							}
						?>
						</div>

						<?php if( $languages = Configure::read('Site.languages') ): ?>
						<div class="language-box">
							<ul>
							<?php foreach( $languages as $code => $label ): ?>
								<li><?php echo $this->Html->link( $label, array_merge(array('language'=>$code),array_values($this->params['pass'])), array( 'class' => 'lang-'.$code ) ); ?></li>
							<?php endforeach; ?>
							</ul>
							<?php echo __('Change Language'); ?>
						</div>
						<?php endif; ?>
					</div>
						<strong class="logo">
							<?php
							echo $this->Html->link( $this->Html->image('logo.png'),
								array(
									'controller'=>'responses',
									'action'=>'add'
								),
								array(
									'escape' => false
								)
							);
							?>
						</strong>
						<div class="header-box">
							<ul class="menu">
								<?php if(!isset( $active_nav )) $active_nav = null; ?>
								<li <?php if( $active_nav == 'home'): ?>class="active"<?php endif; ?>>
									<?php
									echo $this->Html->link( __('Home'),
										array(
											'controller'=>'responses',
											'action'=>'add'
										)
									);
									?>
								</li>
								<li>
									<?php
									echo $this->Html->link(__('Questionnaire'),
										array(
											'controller' => 'responses',
											'action' => 'questionnaire_setup',
										)
									);
									?>
								</li>
								<li <?php if( $active_nav == 'my_plans'): ?>class="active"<?php endif; ?>>
									<?php
									echo $this->Html->link( __('My EU-GENIE'),
										array(
											'controller'=>'services',
											'action'=>'index',
											'my-map'
										)
									);
									?>
								</li>
								<li <?php if( $active_nav == 'my_network'): ?>class="active"<?php endif; ?>>
									<?php
									echo $this->Html->link( __('My Network'),
										array(
											'controller' => 'responses',
											'action' => 'my_network'
										)
									);
									?>
								</li>
								<li <?php if( $active_nav == 'activities_overview'): ?>class="active"<?php endif; ?>>
									<?php
									echo $this->Html->link( __('Activities Overview'),
										array(
											'controller' => 'services',
											'action' => 'availability'
										)
									);
									?>
								</li>
								<li <?php if( $active_nav == 'about'): ?>class="active"<?php endif; ?>>
									<?php
									echo $this->Html->link( __('About'),
										array(
											'controller'=>'pages',
											'action'=>'view',
											1
										)
									);
									?>
								</li>
								<li <?php if( $active_nav == 'contact'): ?>class="active"<?php endif; ?>>
									<?php
									echo $this->Html->link( __('Contact'),
										array(
											'controller' => 'contacts',
											'action' => 'add'
										)
									);
									?>
								</li>
							</ul>
							<form action="<?php echo $this->Html->url( array('controller'=>'services', 'action'=>'index') ); ?>" class="search-form placeholder-labels">
								<fieldset>
									<label for="GroupSearch"><?php echo __('Search for groups (e.g. "community centre" or "swimming")'); ?></label>
									<input type="text" name="search" id="GroupSearch"  />
									<input type="image" alt="SEARCH" src="/img/btn-search.png" />
								</fieldset>
							</form>
						</div>
					</div>
					<ul id="nav">
						<?php foreach($parent_categories as $parent_category):?>
						<?php
							$class = 'category-'.$parent_category['Category']['id'];
							if(isset($selected_parent_id) && $selected_parent_id == $parent_category['Category']['id']){
								$class .= ' active';
							}
						?>
						<li class="<?php echo $class;?>">
							<?php echo $this->Html->link( $parent_category['Category']['name'], array('controller'=>'services', 'action'=>'index', $parent_category['Category']['slug']), array('class'=>'ajax') ); ?>
						</li>
						<?php endforeach;?>
					</ul>
				</div>
				<div id="main">
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->fetch('content'); ?>
				</div>
				<div id="footer">
					<div class="footer-box">
						<a class="btn-twitter" target="_blank" href="http://twitter.com/<?php echo Configure::read('Site.twitter'); ?>"><?php echo __('Tweet us'); ?></a>
						<ul class="logos">
							<li><a href="http://eu-wise.com/"><img alt="" src="/img/logo-euwise.png"></a></li>
							<li><a href="http://cordis.europa.eu/fp7/home_en.html"><img alt="" src="/img/logo-seventh.png"></a></li>
							<li><a href="http://www.southampton.ac.uk/"><img alt="" src="/img/logo-southhampton.png"></a></li>
							<li><a href="http://www.nihr.ac.uk/Pages/default.aspx"><img alt="" src="/img/logo-nhs.png"></a></li>
						</ul>
					</div>
					<p style="text-align:right;">
						<?php echo __('EU-GENIE is released under the'); ?>
						<a target="_blank" href="http://opensource.org/licenses/gpl-2.0.php">General Public License</a>,
						<?php echo __('it is'); ?>
						<a target="_blank" href="https://github.com/priyankasingh/old-eu-genie"><?php echo __('open source'); ?></a>
						<?php echo __('and free to use'); ?>.
					</p>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->Js->writeBuffer(); // Write cached scripts ?>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
