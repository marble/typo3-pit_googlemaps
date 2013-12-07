<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Paulsen-IT <service@paulsen-it.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Google Maps' for the 'pit_googlemaps' extension.
 *
 * @author	Paulsen-IT <service@paulsen-it.de>
 * @package	TYPO3
 * @subpackage	tx_pitgooglemaps
 */
class tx_pitgooglemaps_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_pitgooglemaps_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_pitgooglemaps_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'pit_googlemaps';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * This function checks if the Addresses of the Backend Plugin are changed so we need new geodata
	 * @return unknown_type
	 */
	private function checkSameAddresses()
		{
		$geodata = unserialize($this->geocache);
		if(is_array($geodata))
			{
			$allOk = true;
			if(count($geodata) <> count($this->addresses))
				$allOk = false;
			foreach($geodata as $k=>$v)
				{
				if(trim($this->addresses[$k]) <> $v['cacheAddress'])
					$allOk = false;
				if($v["geoData"]["lon"] == 0 && $v["geoData"]["lat"] == 0)
					$allOk = false;
				}
			if(!$allOk)
				$this->getNewGeoData();
			}
		else
			$this->getNewGeoData();
		}
	
	/**
	 * If the addresses in Backend change we need to get new Geocache Data
	 * @return nothing
	 */
	private function getNewGeoData()
		{
		foreach($this->addresses as $k=>$v)
			{
			if(trim($v)<>'')
				{
				if(is_array($this->userGeoData[$k]) && count($this->userGeoData[$k]) == 3)
					{
					$newGeoData[$k]['geoData']['lon'] = $this->userGeoData[$k][1];
					$newGeoData[$k]['geoData']['lat'] = $this->userGeoData[$k][2];
					}
				else
					{
					$newGeoData[$k]['cacheAddress'] = trim($v);
					$newGeoData[$k]['geoData'] = $this->geoGetCoords(trim($v));
					if(!is_array($newGeoData[$k]['geoData']))
						$newGeoData[$k]['geoData'] = $this->geoGetCoords(trim($v), 'YAHOO');
					if(!is_array($newGeoData[$k]['geoData']))
						{
						$newGeoData[$k]['geoData']['lon'] = 0.00;
						$newGeoData[$k]['geoData']['lat'] = 0.00;
						}
					}
				}
			}
		$this->geocache = serialize($newGeoData);
		$updateArray=array( 'tx_pitgooglemaps_geodata' => $this->geocache,
							'tstamp' => time(),
							);
		$where = 'uid = '.$this->cObj->data['uid'];
		$query   = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', $where, $updateArray);
		//$res     = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, $query);
		$res     = $GLOBALS['TYPO3_DB']->sql_query(TYPO3_db, $query);
		}
	
	/**
	 * get geocode lat/lon points for given address from some geocoding service
	 *
	 * @param string $address
	 * @param string $service
	 */
	private function geoGetCoords($address, $service = 'GOOGLE')
		{
		// Pre-set result
		$_result = false;
		// Do work according to chosen lookup service
		switch ($service) 
			{
			// Yahoo Geocoding API
			case 'YAHOO':
				// Compose URL
				$_url = 'http://api.local.yahoo.com/MapsService/V1/geocode';
				$_url .= '?appid=' . 'MyMapApp';//$this->app_id;
				$_url .= '&location=' . urlencode($address); //rawurlencode($address);
				$curlHandle = curl_init(); // init curl

				// options
				curl_setopt($curlHandle, CURLOPT_URL, $_url); // set the url to fetch
				curl_setopt($curlHandle, CURLOPT_HEADER, 0); // set headers (0 = no headers in result)
				curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1); // type of transfer (1 = to string)
				curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10); // time to wait in seconds
				curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 5);

				$_result = curl_exec($curlHandle); // make the call

				curl_close($curlHandle); // close the connection
				if($_result<>'') {
				//if ($_result = file_get_contents($_url)) {
					if (preg_match('/<Latitude>(-?\d+\.\d+)<\/Latitude><Longitude>(-?\d+\.\d+)<\/Longitude>/', $_result, $_match)) {
						$_coords['lon'] = $_match[2];
						$_coords['lat'] = $_match[1];
						$_result = true;
					}
				}
				break;
			// Google Maps API
			case 'GOOGLE':
			default:
				// Compose URL
				$_url = 'http://maps.google.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false';
				$curlHandle = curl_init(); // init curl

				// options
				curl_setopt($curlHandle, CURLOPT_URL, $_url); // set the url to fetch
				curl_setopt($curlHandle, CURLOPT_HEADER, 0); // set headers (0 = no headers in result)
				curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1); // type of transfer (1 = to string)
				curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10); // time to wait in seconds
				curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 5);

				$_result = curl_exec($curlHandle); // make the call

				curl_close($curlHandle); // close the connection
				if($_result<>'') {
					$resp = json_decode($_result, true);
					if($resp['status']='OK') {
						$_coords['lon'] = $resp['results'][0]['geometry']['location']['lng'];
						$_coords['lat'] = $resp['results'][0]['geometry']['location']['lat'];
						$_result = true;
					}
				}
				break;
			}
		
		// Return coordinates or false
		if ($_result) 
			{
			return $_coords;
			}
		return $_result; 
		}
	
	/**
	 * The center position of Google Maps is needed, here we calculate it
	 * @return array
	 */
	private function gettingCenterOfMap()
		{
		$geodata = unserialize($this->geocache);
		if(is_array($geodata) && count($geodata) > 1)
			{
			foreach($geodata as $gd)
				{
				$lons[] = $gd['geoData']['lon'];
				$lats[] = $gd['geoData']['lat'];
				}
			sort($lons, SORT_NUMERIC);
			sort($lats, SORT_NUMERIC);
		
			$biggestlon = array_pop($lons);
			$biggestlat = array_pop($lats);
			
			$smallestlon = array_shift($lons);
			$smallestlat = array_shift($lats);
		
			return array(	'lon' => round(($biggestlon+$smallestlon)/2, 3),
							'lat' => round(($biggestlat+$smallestlat)/2, 3)
							);
			}
		elseif(is_array($geodata) && count($geodata) == 1)
			{
			return array(	'lon' => $geodata[0]['geoData']['lon'],
							'lat' => $geodata[0]['geoData']['lat']
							);
			}
		}
	
	/**
	 * Google needs some JavaScript to generate the Point (Markers) on Maps, here the JS-Code will be generated
	 * @return string
	 */
	private function gettingJSforPointers()
		{
		$geodata = unserialize($this->geocache);
		$js = '';
		$jsIcon .= '';
		asort($this->addresses);
		$id = -1;
		if($this->markerImages[0] <> '')
			$lastIcon = $this->markerImages[0];
		foreach($this->addresses as $k => $v)
			{
			$id++;
			$gd = $geodata[$k];
			$htmlInfo = '';
			if(is_array($this->marker[$k]))
				{
				$htmlInfo = $this->infowindow;
				foreach($this->marker[$k] as $key=>$value)
					$htmlInfo = str_replace($key, $value, $htmlInfo);
				$htmlInfo = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $htmlInfo);
				$htmlInfo = str_replace("'", "\\'", $htmlInfo);
				}			
			// The Icon would be created for Google, if some exists
			if(is_array($this->markerImages))
				//if($this->markerImages[$k] <> '')
					$currentIcon = $this->markerImages[$k];
				if($currentIcon == '')
					$currentIcon = $lastIcon;
				if($currentIcon <> '')
					{
					$icon = 'icon: "' . $this->upload.$currentIcon . '",';
					}
				
			// The point for google is created
			$js .= '
							markerTitle_'.$this->uid.'['.$id.'] = "'.preg_replace("/\r|\n/s", "", $v).'"; 
							point_'.$this->uid.'['.$id.'] = new google.maps.LatLng('.$gd['geoData']['lat'].','.$gd['geoData']['lon'].');
							marker_'.$this->uid.'_'.$id.'_Options = {
								map: map,
								'.$icon.'
								position: point_'.$this->uid.'['.$id.'],
								title: "'.preg_replace("/\r|\n/s", "", $v).'",
								}
							marker_'.$this->uid.'['.$id.'] = new google.maps.Marker(marker_'.$this->uid.'_'.$id.'_Options);

							';
			if($this->cObj->data['tx_pitgooglemaps_showroute'])
				$js .= '
								marker_'.$this->uid.'_'.$id.'_InfoWindowHtml = new google.maps.InfoWindow({ content:\''.$htmlInfo.' <p class="tx-pitgooglemaps-pi1_route">Route: <a href="http://maps.google.com/maps?daddr=\'+point_'.$this->uid.'['.$id.']+\'" target="_blank">Hierher</a> - <a href="http://maps.google.com/maps?saddr=\'+point_'.$this->uid.'['.$id.']+\'" target="_blank">Von hier</a></p>\'});
						';
			else
				$js .= '
								marker_'.$this->uid.'_'.$id.'_InfoWindowHtml = new google.maps.InfoWindow({ content: \''.$htmlInfo.'\'});
						';
			$js .= '
							google.maps.event.addListener(marker_'.$this->uid.'['.$id.'], "click", function() {
									marker_'.$this->uid.'_'.$id.'_InfoWindowHtml.open(map,marker_'.$this->uid.'['.$id.']);
								});
						
						';
			}
			$js = $jsIcon.$js;
		return $js;
		}
		
	function createSidebar()
		{
		$sidebarContent = '
			function createSidebar'.$this->uid.'()
				{
				var html = "";
				for (var i = 0; i < marker_'.$this->uid.'.length; i++)
					{
					html += \'<a id="marker\'+i+\'" href="javascript:sidebar_click'.$this->uid.'(\'+i+\')" onmouseover="javascript:markerhover_in'.$this->uid.'(\'+i+\')" onmouseout="javascript:markerhover_out'.$this->uid.'(\'+i+\')">\' + markerTitle_'.$this->uid.'[i] + \'</a><br />\';
					}
				document.getElementById("sidebar'.$this->uid.'").innerHTML = html;
				}
			
			function sidebar_click'.$this->uid.'(id)
				{
				window.setTimeout("google.maps.event.trigger(marker_'.$this->uid.'["+id+"],\'click\')", 500);
				}
				
			function markerhover_in'.$this->uid.'(id)
				{
				google.maps.event.trigger(marker_'.$this->uid.'[id],\'click\');
				}
	
			function markerhover_out'.$this->uid.'(id)
				{
				setTimeout("marker_'.$this->uid.'_"+id+"_InfoWindowHtml.close();",1)
				
				}
			';
		return $sidebarContent;
		}
		
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->upload = 'uploads/tx_pitgooglemaps/';
		
		// Set Google Maps Java-Files to Header
		$GLOBALS['TSFE']->additionalHeaderData[].='<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>';
		
		// Make a Div for the Map minimum Height = 100, minimum width = 100
		$width = (int) $this->cObj->data["tx_pitgooglemaps_width"];
		if($width < 100)
			$width = 100;
		$height = (int) $this->cObj->data["tx_pitgooglemaps_height"];
		if($height < 100)
			$height = 100;
		$this->uid = $this->cObj->data['uid'];
		if($this->cObj->data['tx_pitgooglemaps_showsidebar'])
			$mapclass = 'pit_googlemaps-map-withsidebar';
		else
			$mapclass = 'pit_googlemaps-map';
		$content = '<div class="pit_googlemaps-wrap clearfix">
						<div class="'.$mapclass.'" id="map_canvas'.$this->uid.'" style="width:'.$width.'px; height:'.$height.'px;"></div>';
		
		if($this->cObj->data['tx_pitgooglemaps_showsidebar'])
			$content .= '
						<div class="pit_googlemaps-sidebar" id="sidebar'.$this->uid.'"></div>';
		$content .= '
					</div>
					';
		
		// Splitting Informations
		$this->addresses = explode("\n", $this->cObj->data['tx_pitgooglemaps_addresses']);
		
		// Clean Adresses from Breaks and empty Lines
		foreach($this->addresses as $addr)
			{
			$cleanaddr = preg_replace("/\r|\n/s", "", $addr);
			if($cleanaddr <> '')
				$newAddr[] = $cleanaddr; 
			}
		$this->addresses = $newAddr;
		$this->geocache = $this->cObj->data['tx_pitgooglemaps_geodata'];

		// If User have set geodata manual
		foreach($this->addresses as $k=>$add)
			{
			$userGeo = strstr($add, '|');
			if(!($userGeo===false))
				{
				$this->userGeoData[$k] = explode('|', $userGeo);
				$this->addresses[$k] = substr($add, 0, strpos($add, '|'));
				} 
			}
		//var_dump($this->userGeoData);
		$markers = explode("\n", $this->cObj->data['tx_pitgooglemaps_markers']);
		$this->infowindow = $this->cObj->data['tx_pitgooglemaps_infowindow'];
		$this->markerImages = explode(',', $this->cObj->data['tx_pitgooglemaps_markericons']);
		
		// Getting markers in right form
		$m = array();
		foreach($markers as $x)
			array_push($m, explode('|', $x));
		foreach($m as $k=>$v)
			foreach($v as $x)
				{
				$z = explode('=', $x);
				$this->marker[$k][$z[0]] = trim($z[1]);
				}
		
		// Checking if geocache is right one to addresses
		$this->checkSameAddresses();
		
		// Setting the center of the map
		$mapCenter = $this->gettingCenterOfMap();
		
		// Getting JavaScript-Code of all Points
		$pointerJS = $this->gettingJSforPointers();
		
		// Create Content for the Sidebar 
		if($this->cObj->data['tx_pitgooglemaps_showsidebar'])
			$sidebarContent = $this->createSidebar();
		
		$map_type[0] = 'google.maps.MapTypeId.ROADMAP';
		$map_type[1] = 'google.maps.MapTypeId.SATELLITE';
		$map_type[3] = 'google.maps.MapTypeId.HYBRID';
		$map_type[5] = 'google.maps.MapTypeId.TERRAIN';
		$map_type[6] = 'google.maps.MapTypeId.SATELLITE';
		
		if( $this->cObj->data["tx_pitgooglemaps_showtype"] >= 0)
			$map_typeJS = 'map.setMapTypeId('.$map_type[$this->cObj->data["tx_pitgooglemaps_showtype"]].');';
		
		$content .= '
					<script type="text/javascript">
					map = null;
					var markerIcon_'.$this->uid.' = new Array();
					var point_'.$this->uid.' = new Array();
					var marker_'.$this->uid.' = new Array();
					var markerTitle_'.$this->uid.' = new Array();
					
					'.$sidebarContent.'
					
					function setupMap()
						{
							var mapOptions = {
								center: new google.maps.LatLng('.$mapCenter['lat'].', '.$mapCenter['lon'].'),
								zoom: '.$this->cObj->data['tx_pitgooglemaps_zoom'].',
								};

							map = new google.maps.Map(document.getElementById("map_canvas'.$this->cObj->data['uid'].'"),mapOptions);
							map.setZoom('.$this->cObj->data['tx_pitgooglemaps_zoom'].');
							'.$map_typeJS.'
        					'.$pointerJS;
		if($this->cObj->data['tx_pitgooglemaps_showsidebar'])
			$content .= 	'
        					createSidebar'.$this->uid.'();
        					';
		$content .=			'
							}
					google.maps.event.addDomListener(window, "load", setupMap);
					</script>
					';
	
		return $this->pi_wrapInBaseClass($content);
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pit_googlemaps/pi1/class.tx_pitgooglemaps_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pit_googlemaps/pi1/class.tx_pitgooglemaps_pi1.php']);
}

?>