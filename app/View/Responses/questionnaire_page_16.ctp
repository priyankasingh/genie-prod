<?php
// pr($this->request->data);
?>
<div id="questionnaire" class="question-box">
  <h2><?php echo Configure::read('Site.name'); ?> <?php echo __('Questionnaire'); ?></h2>
  <?php
  echo $this->Form->create('Response',
    array(
      'class' => 'postcode-form placeholder-labels'
    )
  );
  ?>
    <fieldset class="questionnaire-page statement questionnaire-page-top-interest">
      <legend class="legend-page">
        <?php echo __('Please select your 3 most important interests'); ?>
      </legend>
      <?php
      $options = array();
      foreach ($this->request->data['ResponseStatement'] as $StatementKey => $statement) {
        foreach ($statement['Category'] as $categoryKey => $categoryId) {
          foreach ($categories as $key => $value) {
            if ($categoryId == $value['Category']['id']) {
              $options[$categoryId] = $value['Category']['name'];
              break;
            }
          }
        }
      }
      ?>

      <?php
      echo $this->Form->input('TopInterest.data',
        array(
          'options' => $options,
          'multiple' => 'checkbox',
          'label' => false
        )
      );
      echo $this->Form->input('TopInterest.id',
        array(
          'type' => 'hidden'
        )
      );
      ?>

    </fieldset>
    <?php
    echo $this->Html->link(__('Previous page'),
      array(
        'action' => 'questionnaire_page',
        $params['currentPage'] -1
      ),
      array(
        'class' => 'prev-button'
      )
    );

    echo $this->Form->submit(__('Submit'),
      array(
        'formnovalidate' => true
      )
    );
    ?>
  <?php echo $this->Form->end(); ?>
</div>
