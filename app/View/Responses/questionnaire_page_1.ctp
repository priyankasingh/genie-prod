<?php
// pr(AuthComponent::user('email'));
// pr($this->request->data);
// pr($existingResponse);
?>
<div id="questionnaire" class="question-box">
  <h2><?php echo Configure::read('Site.name'); ?> <?php echo __('Questionnaire'); ?></h2>
  <?php
  echo $this->Form->create('Response',
    array(
      'class' => 'postcode-form placeholder-labels',
      'novalidate' => true
    )
  );
  ?>
  <fieldset class="questionnaire-page">
    <legend class="legend-title"><?php echo __('General Information about you'); ?></legend>
    <p class="legend-desc">
      <em><?php echo __("Click 'Next Question' once you have completed this page"); ?>.</em>
    </p>
    <div class="column-holder">
      <div class="column1">
        <?php
        echo $this->Form->input('postcode',
          array(
            'after' => '<small>' . __("We need this to find services near you") . '.</small>'
          )
        );
        echo $this->Form->input('lat',
          array(
            'type' => 'hidden'
          )
        );
        echo $this->Form->input('lng',
          array(
            'type' => 'hidden'
          )
        );
        echo $this->Form->input('name',
          array(
            'after' => '<small>' . __("Optional").'.</small>'
          )
        );
        echo $this->Form->input('User.email',
          array(
            'disabled' => (AuthComponent::user('id') ? true : false),
            'value' => (AuthComponent::user('email') ? AuthComponent::user('email') : false),
            'after' => '<small>' .
            __("Optional. If you supply your email address,
              we'll save your answers and email you a login so
              you won't need to take the questionnaire again") .
            '.</small>'
          )
        );
        ?>
      </div>
      <div class="column2">
        <?php
        echo $this->Form->input('gender',
          array(
            'type' => 'radio',
            'options' => array(
              'm' => __('Male'),
              'f' => __('Female')
            ),
            'legend' => __('Please select your gender:')
          )
        );
        echo $this->Form->input('age',
          array(
            'legend' => __('Please select your age range:'),
            'type' => 'radio',
            'options' => array(
              '18-24' => '18-24',
              '25-40' => '25-40',
              '41-55' => '41-55',
              '56-65' => '56-65',
              '66+' => '66+'
            )
          )
        );
        echo $this->Form->input('Condition',
          array(
            'label' => __("Do you have any of the following health conditions?") . '<span>' .
            __('(Click all that are appropriate to you.)') . '</span>',
            'multiple' => 'checkbox'
          )
        );

        echo $this->Form->input('id');
        ?>
      </div>
    </div>
  </fieldset>
  <?php
  $nextPageStyles = '';
  if (isset($existingResponse) && $existingResponse) {
    echo $this->Form->submit(__('Finish'),
      array(
        'name' => 'finish',
        'formnovalidate' => true
      )
    );
    $nextPageStyles = 'right:15em;';
  }

  echo $this->Form->submit(__('Next page'),
    array(
      'style' => $nextPageStyles,
      'formnovalidate' => true
    )
  );
  ?>
  <?php echo $this->Form->end(); ?>
</div>
