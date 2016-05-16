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
    <fieldset class="questionnaire-page network-members" id="network-members">
      <legend class="legend-title"><?php echo __('My Network'); ?></legend>
      <div id="network-diagram">
      <div id="network-leg">
        <ul class="leg-frequency">
          <li><?php echo __('Daily'); ?></li>
          <li><?php echo __('At least once a week'); ?></li>
          <li><?php echo __('At least once a month'); ?></li>
          <li><?php echo __('Less often') ; ?></li>
        </ul>
        <div id="network-trash-can"></div>
        <div class="trash-instructions">
          <?php echo __('Drag your pin over the trash icon above to delete it'); ?>.
        </div>
      </div>

        <p class="legend-desc">
          <em><?php echo __("Please tell us about your network of friends, family and others you're
          in contact with, to help us supply you with the most useful results"); ?>.</em>
          <a
            href="<?php echo $this->Html->url('/img/', true); ?>"
          >
            <?php echo __("For an example click here"); ?>
          </a>.
        </p>
        <p class="legend-desc"></p>

        <div class="group-rows-inner">
          <div class="group-row" id="add-pin-form">
            <div class="network-name">
              <?php
              echo $this->Form->input('NetworkMember.-1.name',
                array(
                  'after' => '<small>' . __('Their name, or just a nickname if you prefer') . '.</small>'
                )
              );
              ?>
            </div>
            <div class="network-frequency">
              <?php
              echo $this->Form->input('NetworkMember.-1.frequency',
                array(
                  'after' => '<small>' . __('How often are you in contact with them?') . '</small>'
                )
              );
              ?>
            </div>
            <div class="network-category">
              <?php
              echo $this->Form->input('NetworkMember.-1.network_category_parent_id',
                array(
                  'after' => '<small>' . __('What type of relationship do they have to you') . ' </small>',
                  'options' => $parentNetworkCategories
                )
              );
              ?>
            </div>
            <?php
            foreach($parentNetworkCategories as $parentNetworkCategoryId => $parentNetworkCategory):
              $subOptions = array();
              foreach($networkCategories as $networkCategory) {
                if ($parentNetworkCategoryId == $networkCategory['NetworkCategory']['parent_id']) {
                  $subOptions[$networkCategory['NetworkCategory']['id']] = $networkCategory['NetworkCategory']['name'];
                }
              }
              if(empty($subOptions)) {
                continue;
              }
            ?>
              <div class="network-category network-child-category">
                <?php
                echo $this->Form->input('NetworkMember.-1.network_category_id',
                  array(
                    'after' => '<small>' . __('Who are they?') . '</small>',
                    'options' => $subOptions,
                    'id' => 'network-category-' . $parentNetworkCategoryId,
                    'class' => 'dropdown_category_' . $parentNetworkCategoryId
                  )
                );
                ?>
              </div>
            <?php
            endforeach;
            ?>
            <input name="data[NetworkMember][-1][other]" maxlength="200" type="text" id="other-name" value="please enter relationship">
          </div>
        </div>

        <div class="network-pin-area">
        <?php
        $numRows = 1;
        if( !empty($this->request->data['NetworkMember']) ) $numRows = count( $this->request->data['NetworkMember'] ) + 1;
        for( $rowKey=0; $rowKey<$numRows; $rowKey++ ):
          $existingKey = !empty($this->request->data['NetworkMember'][$rowKey]) && !empty($this->request->data['NetworkMember'][$rowKey]);
          $x = empty( $this->request->data['NetworkMember'][$rowKey]['diagram_x'] ) ? '0' : $this->request->data['NetworkMember'][$rowKey]['diagram_x'];
          $y = empty( $this->request->data['NetworkMember'][$rowKey]['diagram_y'] ) ? '0' : $this->request->data['NetworkMember'][$rowKey]['diagram_y'];
        ?>
          <div id="drag_<?php echo $rowKey; ?>" class="network-pin network-pin-<?php if( $existingKey ): echo $this->request->data['NetworkMember'][$rowKey]['frequency']; ?> network-pin-placed" style="position:absolute;top:<?php echo $y; ?>px;left:<?php echo $x; ?>px;<?php else: ?>daily<?php endif; ?>">
            <div class="network-pin-info">
              <div class="network-pin-name"><?php
                if( $existingKey && !empty($this->request->data['NetworkMember'][$rowKey]['name']) )
                  echo h( $this->request->data['NetworkMember'][$rowKey]['name'] );
              ?></div>
              <div class="network-pin-role"><?php

                if( $existingKey && !empty($this->request->data['NetworkMember'][$rowKey]['network_category_id']) ) {
                  if($networkCategories[ $this->request->data['NetworkMember'][$rowKey]['network_category_id'] ]['NetworkCategory']['name'] != "Other"){
                    echo h( $networkCategories[ $this->request->data['NetworkMember'][$rowKey]['network_category_id'] ]['NetworkCategory']['name'] );
                  } elseif( !empty($this->request->data['NetworkMember'][$rowKey]['other']) ) {
                    echo h( $this->request->data['NetworkMember'][$rowKey]['other']);
                  }
                }
                ?>
              </div>
            </div>

            <?php
            if( $existingKey ) echo $this->Form->input('NetworkMember.'.$rowKey.'.id', array('type'=>'hidden'));
            echo $this->Form->input('NetworkMember.' . $rowKey . '.name', array('type'=>'hidden') );
            echo $this->Form->input('NetworkMember.' . $rowKey . '.frequency', array('type'=>'hidden') );
            echo $this->Form->input('NetworkMember.' . $rowKey . '.network_category_id', array('type'=>'hidden') );
            echo $this->Form->input('NetworkMember.' . $rowKey . '.other', array('type'=>'hidden') );
            echo $this->Form->input('NetworkMember.' . $rowKey . '.diagram_x', array('type'=>'hidden') );
            echo $this->Form->input('NetworkMember.' . $rowKey . '.diagram_y', array('type'=>'hidden') );
            if( !$existingKey ) echo $this->Form->input('NetworkMember.'.$rowKey.'.dummy_pin', array('type'=>'hidden', 'default'=>'1') );
            ?>
          </div>
        <?php endfor; ?>
        </div>
        <div class="network-instructions">
          <?php echo __('Once you have finished entering your information, drag your newly created pin to the diagram.') ; ?>
        </div>

        <div id="network-circle">
          <div class="network-circle-name">
            <?php
            echo !empty($this->request->data['Response']['name']) ? $this->request->data['Response']['name'] : __('You');
            ?>
          </div>
        </div>

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
$(document).ready(function() {
  // QUESTIONNAIRE - MY NETWORK
  function ohReplaceAttrNums( elem, attrName, newVal ){
    elem.attr(attrName, elem.attr(attrName).replace( /[0-9]+/, newVal ));
    return elem;
  }

  function makePinDraggable( pin ){
    pin.draggable({
      stop: function( event, ui ) {
        ui.helper.find('input[name$="[diagram_x]"]').val( ui.position.left );
        ui.helper.find('input[name$="[diagram_y]"]').val( ui.position.top );
      },
      revert: function(destination) {
        if( !$(this).find('input[name$="[name]"]').val() || $(this).find('input[name$="[name]"]').val() == 'Name' ||
            !$(this).find('input[name$="[network_category_id]"]').val() )
          return true;

        return !destination; // Revert back if we're not over the diagram
      }
    }).css('position', 'absolute');
  }

  function createNewPin( currentPin ){
    // Prepare new pin
    var newId = parseInt( currentPin.attr('id').replace('drag_', '') ) + 1;
    var newPin = currentPin.clone( false );
    currentPin.addClass('network-pin-placed');

    // Attribures and fields
    ohReplaceAttrNums( newPin, 'id', newId);
    newPin.find('.network-pin-name').each(function(){
      $(this).empty();
    });
    newPin.find('input').each(function(){
      ohReplaceAttrNums( $(this), 'id', newId);
      ohReplaceAttrNums( $(this), 'name', newId);
    });
    newPin.find('input[name$="[dummy_pin]"]').val('1');
    currentPin.find('input[name$="[dummy_pin]"]').val('0');

    newPin.removeAttr('style');
    newPin.appendTo('.network-pin-area');

    makePinDraggable( newPin );

    // Clear fields
    $('#NetworkMember-1Name').val("");
    newPin.find('input[name$="[name]"]').val('');
  }

  function getCurrentPin(){
    return $('.network-pin-area .network-pin:last');
  }

  // Initialize the first draggables
  $('.network-pin').each(function() {
    makePinDraggable( $(this) );
  });

  // Initialize the diagram droppable
  $('#network-circle').droppable({
    accept:'.network-pin',
    drop: function( event, ui ) {
      if( !ui.draggable.hasClass('network-pin-placed') ){
        createNewPin( ui.draggable );
      }
    }
  });

  // Delete function droppable
  $("#network-trash-can").droppable({
    accept:'.network-pin-placed',
    hoverClass: "network-trash-hover",
    drop: function(event, ui){
      $(ui.draggable).remove();
    }
  });

  // Change name
  $('#NetworkMember-1Name').bind("change propertychange keyup input paste", function(event){
    getCurrentPin().find('.network-pin-name').text($(this).val());
  });

  // Change role
  $("#other-name").bind("change propertychange keyup input paste", function(event){
    getCurrentPin().find('.network-pin-role').text($(this).val());
  });
  $(".network-child-category select").change(function(){
    if($(this).find("option[value='" + $(this).val() + "']").text() == "Other"){
      $("#other-name").show();
      $("#other-name").change();
    } else {
      $("#other-name").hide();
      getCurrentPin().find('.network-pin-role').text($(this).find("option:selected").text());
    }
  });

  // Change frequency
  $("#NetworkMember-1Frequency").change(function(){
    var id = $(this).find("option:selected").attr("value");
    getCurrentPin().removeClass().addClass('network-pin network-pin-'+id);
  });

  // Dropdowns appear
  $("#NetworkMember-1NetworkCategoryParentId").change(function(){
    $(".network-child-category, #other-name").hide();

    if($("#NetworkMember-1NetworkCategoryParentId option[value='" + $(this).val() + "']").text() == "Other"){
      $("#other-name").show().change();
    }
    else{
      $("#other-name").hide();
      $(".dropdown_category_" + $(this).val()).closest(".network-child-category").show().find('select').change();
    }

  });

  // Set up hidden fields
  $('#add-pin-form').find('input, select').bind("change propertychange keyup input paste", function(event){
    $('#add-pin-form').find('input, select:visible').each( function(){
      var nameSuffix = $(this).attr('name').split('][');
      nameSuffix = nameSuffix[ nameSuffix.length - 1 ].replace(']','');
      getCurrentPin().find('input[name$="['+nameSuffix+']"]').val( $(this).val() );
    });
  });

  function setupMyNetworkPage(){
    $('#ResponseName').change();
    $(".network-child-category, #other-name").hide();
    $("#NetworkMember-1NetworkCategoryParentId").change();
    $('#add-pin-form').find('input:visible, select:visible').change();
  }
  setupMyNetworkPage();
});
<?php $this->Html->scriptEnd(); ?>
