<?php

App::uses('AppHelper', 'View/Helper');
	
class OHPinMapHelper extends AppHelper {
	
	const API = 'maps.google.com/maps/api/js?';
	
	public $pins = array();
/*	
	public function addPin($params = array()){
		if(!$params['lat'] || !$params['lng']) return;
		$pin = array();
		$pin['location'] = $params['lat'].','.$params['lng'];
		$pin['content'] = $params['content'];
		$this->pins[] = $pin;
	}
*/	
	public function ApiURL($sensor = false){
		$url = $this->_protocol().self::API;
		$url .= 'sensor=' . ($sensor ? 'true' : 'false');
		return $url;
	}
	
	public function map(){
		//Map holder
		echo '<div id="oh-pin-map"></div>';
	}
	
	public function admin_map(){
		//Map holder
		?>
		<div id="AdminMapWrapper">
			<div id="AdminMap"></div>
			<div id="AdminMapFields" class="input text">
				<label for="AdminMapSearch">Search map</label>
				<input type="text" id="AdminMapSearch" value="" maxlength="200" name="AdminMapSearch"> 
				<button class="button" id="AdminMapSearchSubmit" name="oh_pinmap_map_search_submit" value="Search"><?php echo __('Search Map'); ?></button>
				<p><?php echo __('Drag the map to move.'); ?> <br/><?php echo __('Click anywhere on the map to place the marker.'); ?> <br/><?php echo __('Use the search to find a postcode or town.'); ?></p>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Ensure that we stay on the appropriate protocol
	 *
	 * @return string protocol base (including ://)
	 */
	protected function _protocol() {
		if (($https = $this->_currentOptions['https']) === null) {
			$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
		}
		return ($https ? 'https' : 'http') . '://';
	}
}