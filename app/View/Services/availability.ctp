<?php
$this->Html->script('jquery.history', array('inline' => false));
$this->Html->script('richmarker-compiled', array('inline' => false));
$this->Html->script('services_availability.js', array('inline' => false));
$this->Html->script('jquery.multiple.select.js', array('inline' => false));
$this->Html->script('services_availability_map.js', array('inline' => false));
// pr($this->request->data);
?>

<?php
$radiusOptions = array(
  '0.5' => '1/2 mile radius',
  '1' => '1 mile radius',
  '2' => '2 miles radius',
  '3' => '3 miles radius',
  '4' => '4 miles radius',
  '5' => '5 miles radius'
);
?>

<div class="static-page">
  <h1>Activities Overview</h1>
</div>
<div class="servicesAvailability clearfix">
  <?php
  echo $this->Form->create('Service',
    array(
      'style' => 'margin:20px 0 0;'
    )
  );
  ?>
    <?php
    if (   isset($this->request->data['Location'])
        && !empty($this->request->data['Location'])
      ):
    ?>
      <?php
      $key = 0;
      ?>
      <?php foreach ($this->request->data['Location'] as $locationKey => $value): ?>
      <div class="js-locationWrapper">
        <div class="form-inline">
          <p>Location</p>
          <input
            name="data[Location][<?php echo $key; ?>][name]"
            type="text"
            class="form-control"
            value="<?php echo $value['name']; ?>"
          >
          <select
            name="data[Location][<?php echo $key; ?>][radius]"
            class="form-control"
          >
            <?php foreach ($radiusOptions as $radiusKey => $radiusValue): ?>
            <option
              <?php echo $radiusKey == $value['radius'] ? 'selected' : ''; ?>
              value="<?php echo $radiusKey; ?>"
            >
              <?php echo $radiusValue; ?>
            </option>
            <?php endforeach ?>
          </select>
        </div>
        <?php if ($key > 0): ?>
        <button
          type="button"
          class="button button-link button-remove-location"
          onclick="removeLocation(this);"
        >Remove</button>
        <?php endif ?>
      </div>
      <?php
      $key++;
      ?>
      <?php endforeach ?>
    <?php else: ?>
      <div class="js-locationWrapper">
        <div class="form-inline">
          <p>Location</p>
          <input
            name="data[Location][0][name]"
            type="text"
            class="form-control"
          >
          <select
            name="data[Location][0][radius]"
            class="form-control"
          >
            <?php foreach ($radiusOptions as $radiusKey => $radiusValue): ?>
            <option value="<?php echo $radiusKey; ?>"><?php echo $radiusValue; ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
    <?php endif ?>

    <div class="form-group">
      <button
        type="button"
        onclick="addLocation();"
        class="button button-default"
      >Add another location to compare</button>
    </div>


    <div class="categories-keywords-wrapper clearfix">
      <div>
        <p>Categories</p>
        <?php
        echo $this->Form->input('category',
          array(
            'multiple' => 'multiple',
            'class' => 'categories',
            'options' => $categories,
            'label' => false
          )
        );
        ?>
      </div>
      <div>
        <p>or, search by keyword</p>
        <?php
        echo $this->Form->input('keyword',
          array(
            'class' => 'form-control',
            'label' => false,
            'style' => 'width:242px;'
          )
        );
        ?>
      </div>
    </div>

    <button
      type="submit"
      class="button button-primary"
    >Search</button>

  <?php echo $this->Form->end(); ?>

  <?php
  // check if there are any services available in selected locations
  $services = array();
  if (isset($servicesInLocation) && !empty($servicesInLocation)) {
    $services = Hash::extract($servicesInLocation, '{n}.Services');
    $services = Hash::filter($services);
  }
  ?>
  <?php if (isset($servicesInLocation) && empty($services)): ?>
  <p>There are no services available for selected locations/categories. Please try widening your search criteria.</p>
  <?php endif ?>

  <?php if (!empty($services)): ?>
  <div class="servicesAvailabilityResults clearfix">
    <div id="barchart_plain" style="width: 900px; height: 300px;"></div>

    <div class="aside">
      <?php
      echo $this->element('availability_box',
        array(
          'parents' => isset($parents) ? $parents : null,
          'categories' => isset($categories) ? $categories : null,
          'paginator' => isset($this->Paginator) ? $this->Paginator : null,
          'category' => isset($category) ? $category : null,
          'service' => isset($service) ? $service : null,
          'twitter' => isset($twitter) ? $twitter : null,
        )
      );
      ?>
    </div>

    <div class="map">
      <?php echo $this->OHPinMap->map();?>
    </div>
  </div>
  <?php endif ?>
</div>

<?php $this->Html->scriptStart(array('inline' => false, 'block' => 'pageScript')); ?>
// <script>
$(document).ready(function(){
  $('select.categories').multipleSelect();

  <?php
  if (isset($servicesInLocation) && !empty($services)):
  ?>
  google.load("visualization", '1.1', {packages:['corechart']});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Location', '', { role: 'style' }],
      <?php foreach ($servicesInLocation as $key => $value): ?>
      ['<?php echo $value['Location']['name']; ?>', <?php echo count($value['Services']); ?>, '#613277'],
      <?php endforeach ?>
    ]);
    var options = {
      title: "Number of services in each area",
      width: 600,
      height: 300,
      bar: {groupWidth: '95%'},
      legend: { position: 'none' },
    };
    var chart = new google.visualization.BarChart(document.getElementById('barchart_plain'));
    chart.draw(data, options);
  }
  <?php endif ?>
});
<?php $this->Html->scriptEnd(); ?>
