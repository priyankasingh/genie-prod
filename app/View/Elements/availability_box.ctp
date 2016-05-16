<?php
  $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
?>
<div id="results-box">
  <?php if($parents):?>
    <ul id="results-list">
      <?php $index = 1;?>
      <?php $services = '';?>
      <?php foreach($parents as $parent_id => $parent):?>
        <li class="category-<?php echo $parent_id;?>">
          <h2 class="results-list"><?php echo $categories[$parent_id];?></h2>
          <ul class="aside-list">
            <?php foreach($parent as $service):?>
              <?php
              // pr($service);
              ?>
              <?php $twitter = isset($service['Twitter'])?$service['Twitter']:null;?>
              <?php
              $services .= $this->element('availability_results_service',
                array(
                  'index' => $index,
                  'parent_id' => $parent_id,
                  'categories' => $categories,
                  'service' => $service,
                  'twitter' => $twitter,
                )
              );
              ?>
              <li id="service-<?php echo $service['Service']['id'];?>" class="results-list">
                <div class="clearfix">
                  <span class="number"><?php echo $index;?></span>
                  <div class="text-section">

                    <h3><?php echo $service['Service']['name'];?> -
                      <span class="mark"><?php echo $service['Category'][0]['name'];?></span></h3>
                    <div class="text-box">
                      <h4><?php echo __('Where?'); ?></h4>
                      <p><?php echo $service['Service']['address_1'].', '.$service['Service']['address_2'].', '.$service['Service']['town'].', '.$service['Service']['postcode'];?></p>
                    </div>

                    <div class="text-box">
                      <h4><?php echo __('Contact'); ?></h4>
                      <p><strong><?php echo __('Tel:'); ?> </strong><?php echo $service['Service']['phone'];?></p>
                      <?php if( !empty($service['Service']['email']) ):?>
                        <p><strong><?php echo __('Email:'); ?> </strong><a href="mailto:<?php echo h($service['Service']['email']);?>" class="email-link" target="_blank"><?php echo h($service['Service']['email']);?></a></p>
                      <?php endif;?>
                      <p><strong><?php echo __('Web:'); ?> </strong><a href="<?php echo $service['Service']['url'];?>" target="_blank"><?php echo $service['Service']['url'];?></a></p>
                      <?php if(isset($service['Service']['twitter']) && $service['Service']['twitter'] != ''):?>
                        <a href="https://twitter.com/<?php echo $service['Service']['twitter'];?>" class="twitter-link" target="_blank"><?php echo $service['Service']['twitter'];?></a>
                      <?php endif;?>
                      <?php if(isset($service['Service']['facebook_url']) && $service['Service']['facebook_url'] != ''):?>
                        <a href="<?php echo $service['Service']['facebook_url'];?>" class="facebook-link" target="_blank"><?php echo __('Facebook'); ?></a>
                      <?php endif;?>
                    </div>
                  </div>

                  <?php
                  echo $this->Html->link( __('Read More'),
                    array(
                      'controller'=>'services',
                      'action'=>'index',
                      $service['Category'][0]['ParentCategory']['slug'],
                      $service['Category'][0]['slug'],
                      $service['Service']['slug']
                    ),
                    array(
                      'class'=>'more ajax'
                    )
                  );
                  ?>
                </div>
              </li>
            <?php
            $index++;
            endforeach;
            ?>
          </ul>
        </li>
      <?php endforeach;?>
    </ul>
    <?php echo $services;?>
    <div class="aside-holder">
      <a class="print" href="#"><?php echo __('Print results'); ?></a>
      <?php if($paginator):?>
        <div id="results-pager">
          <div class="pager-holder">
            <?php if($paginator->hasPrev()) echo $paginator->prev('Prev',array('tag'=>false, 'class'=>'prev ajax'));?>
            <?php if($paginator->hasNext()) echo $paginator->next('Next',array('tag'=>false, 'class'=>'next ajax'));?>
          </div>
          <ul class="pager-list">
            <?php
            echo $paginator->numbers(
              array(
                'tag' => 'li',
                'separator' => '',
                'class' => 'ajax',
                'currentClass' => 'active',
                'currentTag' => 'a'
              )
            );
            ?>
          </ul>
        </div>
      <?php endif;?>
    </div>
  <?php elseif($service):?>
    <?php echo $this->element('results_service', array(
      'index' => 0,
      'parent_id' => $selected_parent_id,
      'categories' => $categories,
      'service' => $service,
      'twitter' => $twitter,
    ));?>
  <?php else: ?>
    <ul id="results-list">
      <li>
        <h2 class="results-list"><?php echo __('No Results'); ?></h2>
        <p class="no-results"><?php echo __('Sorry, no results were found for this category in the chosen area. Please try widening the search area, or try another category or postcode.'); ?></p>
      </li>
    </ul>

  <?php endif;?>
</div>
