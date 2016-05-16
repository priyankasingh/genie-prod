<div class="services_edits index">
  <h2><?php echo __('Updated'); ?></h2>
  <?php foreach ($updated as $key => $value): ?>
    <?php foreach ($value['ServiceEdit'] as $serviceEditKey => $serviceEditValue): ?>
      <div class="diff">
        <p>
          Service ID: <?php echo $serviceEditValue['service_id']; ?>
        </p>

        <p>
          Changes made on <?php echo $serviceEditValue['created']; ?>
          by <?php echo $value['User']['email']; ?>
        </p>

        <?php
        $ignoreDiffs = array(
          'version_id',
          'modified',
          'version_created'
        );
        foreach ($serviceEditValue['diff']['Service'] as $diffKey => $diffValue) {
          if (in_array($diffKey, $ignoreDiffs)) {
            continue;
          }
          if (is_array($diffValue)) {
            echo "<p>$diffKey</p>";
            foreach ($diffValue as $diffValueKey => $diffValueValue) {
              if ($diffValueKey == 0) {
                $diffSign = "+";
                $diffClass = 'new';
              } else {
                $diffSign = "-";
                $diffClass = 'old';
              }
              echo "<p class='line_content_$diffClass'>$diffSign $diffValueValue</p>";
            }
          }
        }
        ?>
        <?php
        echo $this->Html->link(__('View'),
          array(
            'controller' => 'services',
            'action' => 'view',
            $serviceEditValue['service_id']
          ),
          array(
            'class' => 'button'
          )
        );

        echo $this->Html->link(__('Approve'),
          array(
            'action' => 'approve',
            $serviceEditValue['id']
          ),
          array(
            'class' => 'button'
          )
        );
        ?>

      </div>
    <?php endforeach ?>
  <?php endforeach ?>

  <h2><?php echo __('Created'); ?></h2>
  <?php foreach ($created as $key => $value): ?>
    <?php foreach ($value['ServiceEdit'] as $serviceEditKey => $serviceEditValue): ?>
    <div class="diff">
      <p>Service ID: <?php echo $serviceEditValue['service_id']; ?></p>
      <p>
        Created on <?php echo $serviceEditValue['created']; ?>
        by <?php echo $value['User']['email']; ?>
      </p>
      <?php
      echo $this->Html->link(__('View'),
        array(
          'controller' => 'services',
          'action' => 'view',
          $serviceEditValue['service_id']
        ),
        array(
          'class' => 'button'
        )
      );

      echo $this->Html->link(__('Approve'),
        array(
          'action' => 'approve',
          $serviceEditValue['id']
        ),
        array(
          'class' => 'button'
        )
      );
      ?>

    </div>
    <?php endforeach ?>
  <?php endforeach ?>

  <h2><?php echo __('Deleted'); ?></h2>
  <?php foreach ($deleted as $key => $value): ?>
    <?php foreach ($value['ServiceEdit'] as $serviceEditKey => $serviceEditValue): ?>
    <div class="diff">
      <p>Service ID: <?php echo $serviceEditValue['service_id']; ?></p>
      <p>
        Deleted on <?php echo $serviceEditValue['created']; ?>
        by <?php echo $value['User']['email']; ?>
      </p>
      <?php
      $ignoreDiffs = array(
        'version_id',
        'modified',
        'version_created'
      );
      foreach ($serviceEditValue['diff']['Service'] as $diffKey => $diffValue) {
        if (in_array($diffKey, $ignoreDiffs)) {
          continue;
        }
        echo "<p style='margin-bottom:0;'>$diffKey</p>";
        echo "<p class='line_content_old'>-" . strip_tags($diffValue) . "</p>";
      }
      ?>
      <?php
      echo $this->Html->link(__('Undo delete'),
        array(
          'controller' => 'services',
          'action' => 'undo_delete',
          $serviceEditValue['service_id'],
          $serviceEditValue['id']
        ),
        array(
          'class' => 'button'
        )
      );

      echo $this->Html->link(__('Approve'),
        array(
          'action' => 'approve',
          $serviceEditValue['id']
        ),
        array(
          'class' => 'button'
        )
      );
      ?>
    </div>
    <?php endforeach ?>
  <?php endforeach ?>
</div>
