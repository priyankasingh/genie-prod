<?php
// pr($this->request->data);
?>
<?php
	$this->Html->script('home.js?v=2.0.5', array('inline' => false));
	$what_is = $this->requestAction('pages/view/1');
?>
<div class="main-holder">
	<div class="text-box">
		<h1><?php echo $what_is['name'];?></h1>
		<div class="two-columns page-content"><?php echo $what_is['content'];?></div>
	</div>
</div>
<?php echo $this->Session->flash(); ?>

<div id="default-box">
	<div class="options-section">
		<h2><?php echo __('What would you like to do next?') ; ?></h2>
		<ul class="options-list">
			<li class="mouse">
				<?php
				echo $this->Html->link(__('Fill in the short EU-GENIE questionnaire and get your own tailored, personal map'),
					array(
						'controller' => 'responses',
						'action' => 'questionnaire_setup'
					)
				);
				?>
			</li>
			<li class="search"><a id="search-button" href="#postcode-search" class="question-button" ><?php echo __("Have a quick look at<br/> what's available in your<br/> area by entering your<br/> postcode") ; ?></a></li>
			<li class="pencil"><a id="login-button" href="#login" class="question-button" ><?php echo __('Sign in if you already<br/> have an account<br/> with us.'); ?></a></li>
		</ul>
	</div>
</div>

<div id="postcode-search" class="question-box">
	<a href="#" class="close-button"><?php echo __('Close'); ?></a>
	<h2><?php echo __('Enter your postcode…'); ?></h2>
	<?php echo $this->Form->create('Service', array('url'=>'/services/','id'=>'postcode-form','class'=>'postcode-form placeholder-labels','type'=>'get'));?>
		<fieldset>
			<label for="PostcodeSearch"><?php echo __('Eg. M3 5HW'); ?></label>
			<input id="PostcodeSearch" type="text" name="postcode" value="">
			<input id="PostcodeLat" type="hidden" name="latitude" value="">
			<input id="PostcodeLng" type="hidden" name="longitude" value="">
			<input id="PostcodeMiles" type="hidden" name="miles" value="2">
			<input type="submit" value="">
		</fieldset>
	<?php echo $this->Form->end();?>
	<strong><?php echo __('This way we can get results local to you.'); ?></strong>
</div>

<div id="login" class="question-box">
	<a href="#" class="close-button"><?php echo __('Close'); ?></a>
	<?php if (AuthComponent::user('id')): ?>
	<h2><?php echo __('You are logged in as'); ?> <?php echo AuthComponent::user('email'); ?></h2>
	<?php
	echo $this->Html->link(__('Update your questionnaire'),
		array(
			'controller' => 'responses',
			'action' => 'questionnaire_setup'
		),
		array(
			'class' => 'btn'
		)
	);
	?>
	<?php else: ?>
	<h2><?php echo __('Already have a'); ?> <?php echo Configure::read('Site.name'); ?> <?php echo __('account?'); ?></h2>
	<strong><?php echo __('Log In below and access your personal homepage'); ?></strong>
	<?php echo $this->element('login-form'); ?>
	<strong><?php echo __("Don't have an account? Fill in the"); ?> <?php echo Configure::read('Site.name'); ?> <?php echo __('questionnaire...'); ?></strong>
	<div class="btn-holder">
		<?php
		echo $this->Html->link(__('Fill in the') . ' ' . Configure::read('Site.name') . ' ' . __('questionnaire...'),
			array(
				'controller' => 'responses',
				'action' => 'questionnaire_setup'
			),
			array(
				'class' => 'btn'
			)
		);
		?>
	</div>
	<?php endif; ?>
</div>
