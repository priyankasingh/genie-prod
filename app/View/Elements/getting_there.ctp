					<div class="gray-section">
						<h2><?php echo __('Getting there'); ?></h2>
						<div class="gray-holder">
							<h3><?php echo __('Fill in your current location and the location of your destination to get a public transport route.'); ?> </h3>
							<span class="text"><?php echo __('You will be redirected to the GMPTE site to get your results'); ?></span>
							<form target="_blank" action="http://www.traveline-northwest.co.uk/journeyplanner/confirmJourneyPlan.do" class="placeholder-labels gray-form">
								<input type="hidden" value="3" name="maximumJourneys">
								<input type="hidden" value="true" name="complexWalk">
								<input type="hidden" value="<?php echo date('H:i'); ?>" id="mainJourneyRequestDetailstime" name="time">
								<input type="hidden" value="<?php echo date('d/m/Y'); ?>" id="mainJourneyRequestDetailsdate" name="date">

								<fieldset>
									<div class="column-holder">
										<div class="column1">
											<h3><?php echo __('Travelling...'); ?></h3>
											<div class="row">
												<label for="originName"><?php echo __('From'); ?>:</label>
												<input id="originName" name="originName" type="text" value="" />
											</div>
											<div class="row">
												<label for="destinationName">To:</label>
												<input id="destinationName" name="destinationName" type="text" value="" />
											</div>
											<div class="row">
												<label for="viaName"><?php echo __('Via (optional)'); ?>:</label>
												<input id="viaName" name="viaName" type="text" value="" />
											</div>
										</div>
										<div class="column2">
											<h3><?php echo __('Time And Date...'); ?></h3>
											<div class="row-radio">
												<input id="leave" type="radio" name="journeyTypeLeaveBy" checked="checked" value="true" />
												<label for="leave"><?php echo __('Leave after'); ?></label>
												<input id="arrive" type="radio" name="journeyTypeLeaveBy" value="false" />
												<label for="arrive"><?php echo __('Arrive By'); ?></label>
											</div>
											<div class="col-holder">
												<div class="col1">
													<div class="row-select">
														<label for="timeH"><?php echo __('Time'); ?></label>
														<select id="timeH" name="timeH">
															<?php for( $i=1; $i<=23; $i++ ): ?>
															<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
														<?php endfor; ?>
														</select>
													</div>
													<div class="row-select">
														<label for="dateH"><?php echo __('Date'); ?></label>
														<select id="dateH" name="dateH">
														<?php for( $i=1; $i<=31; $i++ ): ?>
															<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
														<?php endfor; ?>
														</select>
													</div>
												</div>
												<div class="col2">
													<div class="row-select">
														<select name="timeM" id="timeM">
															<option value="00">00</option>
															<option value="05">05</option>
															<option value="10">10</option>
															<option value="15">15</option>
															<option value="20">20</option>
															<option value="25">25</option>
															<option value="30">30</option>
															<option value="35">35</option>
															<option value="40">40</option>
															<option value="45">45</option>
															<option value="50">50</option>
															<option value="55">55</option>
														</select>
													</div>
													<div class="row-select">
														<select class="date" name="dateM" id="dateM">
														<?php 
															$thisMonth = new DateTime();
															$nextMonth = new DateTime('+1 month');
														?>
															<option value="<?php echo $thisMonth->format('Y-m'); ?>"><?php echo $thisMonth->format('M Y'); ?></option>
															<option value="<?php echo $nextMonth->format('Y-m'); ?>"><?php echo $nextMonth->format('M Y'); ?></option>
														</select>
													</div>
												</div>
											</div>
											<span class="label"><?php echo sprintf( __('Enter a date between %s and %s'), $thisMonth->format('j M Y'), $nextMonth->format('j M Y') ); ?></span>
										</div>
										<div class="column3">
											<h3><?php echo __('Mode Of Transport...'); ?></h3>
											<h4><?php echo __('I would like to travel by...'); ?></h4>
											<div class="radio-holder">
												<div class="radio-box">
													<div class="area">
														<input id="bus" type="checkbox" name="transportModes" checked="checked" value="Bus" />
														<label for="bus"><?php echo __('Bus'); ?></label>
													</div>
													<div class="area">
														<input id="metro" type="checkbox" name="transportModes" value="Metro" />
														<label for="metro"><?php echo __('Metro'); ?></label>
													</div>
													<div class="area">
														<input id="ferry" type="checkbox" name="transportModes" value="Ferry" />
														<label for="ferry"><?php echo __('Ferry'); ?></label>
													</div>
												</div>
												<div class="radio-box">
													<div class="area">
														<input id="rail" type="checkbox" name="transportModes" value="Train" />
														<label for="rail"><?php echo __('Rail'); ?></label>
													</div>
													<div class="area">
														<input id="coach" type="checkbox" name="transportModes" value="Coach" />
														<label for="coach"><?php echo __('Coach'); ?></label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<input type="submit" value="Get Results" />
								</fieldset>
							</form>
						</div>
					</div>