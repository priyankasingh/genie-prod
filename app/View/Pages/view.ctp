<div class="main-holder">
  <h1><?php echo $page['Page']['name'];?></h1>
  <div class="text-box">
    <div class="two-columns page-content"><?php echo $page['Page']['content'];?></div>
  </div>
</div>
<div class="options-section">
  <h2><?php echo __('What would you like to do next?'); ?></h2>
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
    <li class="search">
      <a
        id="search-button"
        href="<?php
          echo $this->Html->url(
            array(
              'controller' => 'responses',
              'action' => 'add',
              '#' => 'postcode-search'
            )
          );
        ?>"
        class="question-button"
      >
        <?php
        echo __("Have a quick look at<br/> what's available in your<br/> area by entering your<br/> postcode");
        ?>
      </a>
    </li>
    <li class="pencil">
      <a
        id="login-button"
        href="<?php
          echo $this->Html->url(
            array(
              'controller' => 'responses',
              'action' => 'add',
              '#' => 'login'
            )
          );
        ?>"
        class="question-button"
      >
        <?php
        echo __("Sign in if you already<br/> have an account<br/> with us.");
        ?>
      </a>
    </li>
  </ul>
</div>
