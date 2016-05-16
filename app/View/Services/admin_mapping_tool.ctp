<div class="services form">
	<div id="mapping-search">
		<div class="number-circle">1</div>
		<h3>Your Search</h3>
		<p>Enter your search term below then select your chosen service from the list</p>
		<?php
			echo $this->Form->create('mappingSearch', array('action'=>'add'));
			echo $this->Form->input('field', array('label' => 'Search field')); ?><img src="/img/ajax-loader-small.gif" alt="Loading..." class="ajax-loader" id="search-loader" />
			<input type="hidden" id="serviceId" name="id" value="" />
			
			<div class="mapping-second-stage">
				<div class="mapping-box">
				
					<strong>Your current selection is:</strong><span class="mapping-current-service"></span>
					<p>Fill out your location and service type then click submit to generate your google searches and quick add form.</p>
				</div>
				<?php 
				echo $this->Form->input('location');
				echo $this->Form->input('service type');
				echo $this->Form->end('Submit'); ?><img src="/img/ajax-loader-small.gif" alt="Loading..." class="ajax-loader" id="search-loader2" />

			</div>
	</div>
	
	<div id="mapping-results">
		<div class="number-circle">2</div>
		<h3>Your search results</h3>
			<div class="google-search-box">
				<p>Click on the links below to open your search in a new tab.</p>

				<ul>
					
					<li><a target="_blank" id="google-link" href="https://www.google.com/search?q=">Google search</a></li>
					<li><a target="_blank" id="google-map" href="https://www.google.com/search?q=">Google maps search</a></li>
				</ul>
			</div>
	</div>
	<div style="clear:both"></div>
	
	<div id="mapping-service-form">
		<div class="number-circle">3</div>
		<?php echo $this->element('admin_quick_service'); ?>
	</div>
	
</div>
