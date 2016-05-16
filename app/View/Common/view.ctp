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
    <fieldset class="questionnaire-page statement">
      <legend class="legend-page">
        <?php echo __('Question'); ?>
        <?php echo $pageNumber - 2; ?> / <?php echo $statementCount; ?>
      </legend>

      <div class="question-choice">
      <?php
        // Existing ResponseStatement?
      if($pageNumber <=14 ){
        echo $this->Form->input('ResponseStatement.' . $statement['Statement']['id'] . '.weighting',
          array(
            'type' => 'radio',
            'default' => 0,
            'options' => array(
              0 => __('No, I am not interested'),
              1 => __('Yes, I might be interested'),
              2 => __('Yes, I am definitely interested')
            ),
            'legend' => $statement['Statement']['statement']
          )
        );
      }
      if($pageNumber ==15 ){
        echo $this->Form->input('ResponseStatement.' . $statement['Statement']['id'] . '.weighting',
          array(
            'type' => 'radio',
            'default' => 0,
            'options' => array(
              0 => __('At home'),
              1 => __('Through someone I know'),
              2 => __('In a public space outside home'),
              3 => __('I would like help with accessing the internet')
            ),
            'legend' => $statement['Statement']['statement']
          )
        );
        
       }
      
        echo $this->Form->input('ResponseStatement.' . $statement['Statement']['id'] . '.statement_id',
          array(
            'type' => 'hidden',
            'default' => $statement['Statement']['id']
          )
        );
        if(isset($this->request->data['ResponseStatement'][$statement['Statement']['id']])) {
          echo $this->Form->input('ResponseStatement.' . $statement['Statement']['id'] . '.id',
            array(
              'type' => 'hidden'
            )
          );
        }
      ?>
      </div>
      <div class="question-categories-wrapper">
        <div class="question-categories">
          <fieldset>
            <legend><?php if($pageNumber <=14 ) echo __('I am interested in the following things') ; ?></legend>
            <?php
            if($pageNumber <=14 ){
                $options = array();
                foreach( $statement['Category'] as $category ){
                  $options[$category['id']] = $category['name'];
                }

                echo $this->Form->input('ResponseStatement.' . $statement['Statement']['id'] . '.Category',
                  array(
                    'options' => $options,
                    'multiple' => 'checkbox',
                    'label' => false
                  )
                );
            }
            ?>
          </fieldset>

        </div>

        <?php if ($pageNumber <=14 && !empty($this->request->data['NetworkMember'])): ?>
        <div class="networkMemberStatementHeading">
          <p>My network members who may be relevant (please tick as appropriate)</p>
        </div>
        <?php foreach ($this->request->data['NetworkMember'] as $key => $value): ?>
        <div class="networkMemberStatement">
          <span class="networkMemberStatement-name">
            <?php echo $value['name']; ?>:
          </span>
          <?php
          if($pageNumber <=14 ){
            echo $this->Form->select('NetworkMember.' . $key . '.Statement' . $statement['Statement']['id'],
              $options,
              array(
                'multiple' => 'checkbox',
              )
            );
          }
          ?>
        </div>
        <?php endforeach ?>
        <?php endif ?>

      </div>

      <div class="question-description formatted">
        <?php echo $statement['Statement']['description']; ?>
      </div>
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

    echo $this->Form->submit(__('Next page'),
      array(
        'formnovalidate' => true
      )
    );
    ?>
  <?php echo $this->Form->end(); ?>
</div>

<?php $this->Html->scriptStart(array('inline' => false)); ?>
//<script>

$(document).ready(function(){
  // SECONDARY QUESTIONS
  $(".question-categories-wrapper").each(function(){
    if( $(this).parents('.statement').find('.question-choice input[type="radio"]:checked').val() ==0 ) {
      $(this).hide();
    }
  });

  $('.question-choice input[type="radio"]').click(function(){
    if ($(this).is(':checked') && ( $(this).val() == '2' || $(this).val() == '1' ) ){
      $(this).parents('.statement').find(".question-categories-wrapper").fadeIn();
      var checkboxes = $(this).parents('.statement').find('.question-categories input[type="checkbox"]');
      if( checkboxes.length == 1 ) checkboxes.prop('checked', true);
    } else if($(this).is(':checked') && $(this).val() == '0'){
      $(this).parents('.statement').find(".question-categories-wrapper").fadeOut();
    }
  });
});
<?php $this->Html->scriptEnd(array('inline' => false)); ?>
