<?php	
	$this->Html->script('jquery.history',array('inline'=>false));
	//$this->Html->script('services',array('inline'=>false));
		
	$this->Html->script($this->OHPinMap->apiUrl(), array('inline' => false));	
	$this->Html->script('richmarker-compiled',array('inline'=>false));
	$this->Html->script('map', array('inline'=>false));
	
?>			
					<div class="content-box">
						

							<form id="postcode-form" method="get">
								<fieldset class="change-form">
									<input id="postcodeField" type="text" name="postcode" value="<?php echo isset($postcode)?$postcode:'Change your Postcode'?>" />
									<input id="latField" type="hidden" name="latitude" value="<?php echo isset($latitude)?$latitude:'';?>"/>
									<input id="lngField" type="hidden" name="longitude" value="<?php echo isset($longitude)?$longitude:'';?>"/>
									<input id="oldPostcodeField" type="hidden" value="<?php echo isset($postcode)?$postcode:'Change your Postcode'?>" />
									<input type="image" value="SEARCH" src="/img/btn-search2.png" />
								</fieldset>

									<?php echo $this->element('category_filter', array(
										'sub_category_list' => isset($sub_category_list)?$sub_category_list:null,
										'categories' => isset($categories)?$categories:null,
										'selected_parent_id' => isset($selected_parent_id)?$selected_parent_id:null,
										'selected_parent_slug' => isset($selected_parent_slug)?$selected_parent_slug:null,
									));?>
								
								<fieldset class="show-form">
									<h2><?php echo __('Show Results Within:'); ?></h2>
									<?php
										$checked = array_fill(0,11,'');
										if(isset($miles)){
											$checked[$miles] = 'checked="checked"';
										}else{
											$checked[5] = 'checked="checked"';
										}
									?>
									<div class="area">
										<label for="mile1"><?php echo __('1 Mile'); ?></label>
										<input id="mile1" type="radio" name="miles" value="1" class="radio" <?php echo $checked[1];?>/>
									</div>
									<div class="area">
										<label for="mile2"><?php echo __('2 Miles'); ?></label>
										<input id="mile2" type="radio" name="miles" value="2" <?php echo $checked[2];?>/>
									</div>
									<div class="area">
										<label for="mile5"><?php echo __('5 Miles'); ?></label>
										<input id="mile5" type="radio" name="miles" value="5" <?php echo $checked[5];?>/>
									</div>
									<div class="area">
										<label for="mile10"><?php echo __('10 Miles'); ?></label>
										<input id="mile10" type="radio" name="miles" value="10" <?php echo $checked[10];?>/>
									</div>
								</fieldset>
							</form>
							
							<div class="aside">
							<a class="print" href="#"><?php echo __('Print Your results'); ?></a>
							<div class="link-holder">
								<a class="results" href="#"><?php echo __('My Results'); ?></a>
								<a class="favourites" href="#"><?php echo __('My Favourites'); ?></a>
							</div>
							<?php echo $this->element('results_box', array(
									'parents' => isset($parents)?$parents:null,
									'categories' => isset($categories)?$categories:null,
									'paginator' => isset($this->Paginator)?$this->Paginator:null,
							));?>	
						</div>
							
							<div class="map">
								<?php echo $this->OHPinMap->map();?>
							</div>
					</div>
					<div class="gray-section">
						<h2><?php echo __('Getting there'); ?></h2>
						<div class="gray-holder">
							<h3><?php echo __('Fill in your current location and the location of your destination to get a public transport route.'); ?> </h3>
							<span class="text"><?php echo __('You will be redirected to the GMPTE site to get your results'); ?></span>
							<form action="#" class="gray-form">
								<fieldset>
									<div class="column-holder">
										<div class="column1">
											<h3><?php echo __('Travelling...'); ?></h3>
											<div class="row">
												<input type="text" value="From:" />
											</div>
											<div class="row">
												<input type="text" value="To:" />
											</div>
											<div class="row">
												<input type="text" value="Via (optional):" />
											</div>
										</div>
										<div class="column2">
											<h3><?php echo __('Time And Date...');?></h3>
											<div class="row-radio">
												<input id="leave" type="radio" name="time" checked="checked" />
												<label for="leave"><?php echo __('Leave after'); ?></label>
												<input id="arrive" type="radio" name="time" />
												<label for="arrive"><?php echo __('Arrive By'); ?></label>
											</div>
											<div class="col-holder">
												<div class="col1">
													<div class="row-select">
														<label for="time"><?php echo __('Time'); ?></label>
														<select id="time">
															<option>17</option>
															<option>17</option>
															<option>17</option>
														</select>
													</div>
													<div class="row-select">
														<label for="date">Date</label>
														<select id="date">
															<option>21</option>
															<option>21</option>
															<option>21</option>
														</select>
													</div>
												</div>
												<div class="col2">
													<div class="row-select">
														<select>
															<option>35</option>
															<option>35</option>
															<option>35</option>
														</select>
													</div>
													<div class="row-select">
														<select class="date">
															<option>February 13</option>
															<option>February 13</option>
															<option>February 13</option>
														</select>
													</div>
												</div>
											</div>
											<span class="label"><?php echo __('Enter a date within the next month.'); ?></span>
										</div>
										<div class="column3">
											<h3><?php echo __('Mode Of Transport...');?></h3>
											<h4><?php echo __('I would like to travel by...'); ?></h4>
											<div class="radio-holder">
												<div class="radio-box">
													<div class="area">
														<input id="bus" type="radio" name="bus" checked="checked" />
														<label for="bus"><?php echo __('Bus'); ?></label>
													</div>
													<div class="area">
														<input id="metro" type="radio" name="bus" />
														<label for="metro"><?php echo __('Metro'); ?></label>
													</div>
													<div class="area">
														<input id="ferry" type="radio" name="bus" />
														<label for="ferry"><?php echo __('Ferry'); ?></label>
													</div>
												</div>
												<div class="radio-box">
													<div class="area">
														<input id="rail" type="radio" name="bus" />
														<label for="rail"><?php echo __('Rail') ;?></label>
													</div>
													<div class="area">
														<input id="coach" type="radio" name="bus" />
														<label for="coach"><?php echo __('Coach'); ?></label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<input type="submit" value="<?php echo __('Get Results'); ?>" />
								</fieldset>
							</form>
						</div>
					</div>