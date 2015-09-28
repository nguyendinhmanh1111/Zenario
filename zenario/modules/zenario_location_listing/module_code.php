<?php
/*
 * Copyright (c) 2015, Tribal Limited
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Zenario, Tribal Limited nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL TRIBAL LTD BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
if (!defined('NOT_ACCESSED_DIRECTLY')) exit('This file may not be directly accessed');

class zenario_location_listing extends module_base_class {
	
	var $page;
	var $pageSize;
	var $pageCount;
	var $mergeFields = array();
	var $subSections = array();



	public function init() {
		$this->allowCaching(
			$atAll = true, $ifUserLoggedIn = false, $ifGetSet = true, $ifPostSet = true, $ifSessionSet = true, $ifCookieSet = true);
		$this->clearCacheBy(
			$clearByContent = true, $clearByMenu = false, $clearByUser = false, $clearByFile = true, $clearByModuleData = true);
		
		$this->pageSize = ifNull((int) $this->setting('page_size'), 10);
		$this->page = ifNull((int) get('page'), 1);
		$this->registerGetRequest('page', 1);
		
		return true;
	}
	
	
	protected function getLocationCount() {
		$result = sqlSelect($this->buildmysql_query(true));
		$row = sqlFetchRow($result);
		return (int) $row[0];
	}
	
	protected function buildmysql_query($count = false) {
		
		if ($count) {
			$sql = "
				SELECT COUNT(loc.id)";
		
		} else {
			$sql = "
				SELECT
					loc.id,
					loc.description AS name,
					loc.address1,
					loc.address2,
					loc.locality,
					loc.city,
					loc.state,
					loc.postcode,
					loc.country_id,
					vp_cn.local_text AS country,
					loc.equiv_id,
					loc.content_type,
					loc.latitude,
					loc.longitude, 
					loc.map_zoom,";
			
			if ((int) $this->setting('sector')) {
				$sql .= "
					lnk.score_id,";
			}
		
			$sql .= "
					im.image_id,
					im.filename,";
			
			//Custom data:
			$sql .= "
					cd.*";
		}
		
		$sql .= "
			FROM ". DB_NAME_PREFIX. ZENARIO_LOCATION_MANAGER_PREFIX. "locations AS loc";
		
		if ((int) $this->setting('region')) {
			$sql .= "
				INNER JOIN ". DB_NAME_PREFIX. ZENARIO_LOCATION_MANAGER_PREFIX. "location_region_link AS lrl 
				   ON loc.id = lrl.location_id 
				  AND lrl.region_id = ". (int) $this->setting('region');
		}
		
		if ((int) $this->setting('sector')) {
			$sql .= "
				INNER JOIN ". DB_NAME_PREFIX. ZENARIO_LOCATION_MANAGER_PREFIX. "location_sector_score_link AS lnk
				   ON loc.id = lnk.location_id";
		}
		
		if (!$count) {
			$sql .= "
				LEFT JOIN ". DB_NAME_PREFIX. "content_items AS c
				   ON c.equiv_id = loc.equiv_id
				  AND c.type = loc.content_type
				  AND c.language_id = '". sqlEscape(cms_core::$langId). "'
				LEFT JOIN ". DB_NAME_PREFIX. ZENARIO_LOCATION_MANAGER_PREFIX. "location_images AS im
				   ON im.location_id = loc.id 
				  AND im.sticky_flag = 1 
				LEFT JOIN ". DB_NAME_PREFIX. "visitor_phrases AS vp_cn
				   ON loc.country_id IS NOT NULL
				  AND module_class_name = 'zenario_country_manager'
				  AND CONCAT('_COUNTRY_NAME_', loc.country_id) = vp_cn.code 
				  AND vp_cn.language_id = '". sqlEscape(cms_core::$langId). "'";
			
			//Custom data:
			$sql .= "
				LEFT JOIN ". DB_NAME_PREFIX. ZENARIO_LOCATION_MANAGER_PREFIX. "locations_custom_data AS cd
				   ON cd.location_id = loc.id";
		}
		
		$sql .= "
			WHERE loc.status = 'active'";
				
		if ((int) $this->setting('sector')) {
			$sql .= "
			  AND lnk.sector_id = ". (int) $this->setting('sector');
		}
		
		if ($this->setting('country')) {
			$sql .= "
			  AND loc.country_id = '". sqlEscape($this->setting('country')). "'";
		}
		
		if (!$count) {
			$orderBy = array();
			switch ($this->setting('order_by_1')){
				case 'sector_score':
					$orderBy[] = 'lnk.score_id DESC';
					break;
				case 'country':
					$orderBy[] = 'country ASC';
					break;
				case 'name':
					$orderBy[] = 'name ASC';
					break;
			}

			switch ($this->setting('order_by_2')){
				case 'sector_score':
					$orderBy[] = 'lnk.score_id DESC';
					break;
				case 'country':
					$orderBy[] = 'country ASC';
					break;
				case 'name':
					$orderBy[] = 'name ASC';
					break;
			}

			switch ($this->setting('order_by_3')){
				case 'sector_score':
					$orderBy[] = 'lnk.score_id DESC';
					break;
				case 'country':
					$orderBy[] = 'country ASC';
					break;
				case 'name':
					$orderBy[] = 'name ASC';
					break;
			}

			if (!empty($orderBy)) {
				$sql .= "
					ORDER BY ". implode(', ', $orderBy);
			}
		}
		
		return $sql;
	}

	public function showSlot() {
		$mergeFields = array();
		$subSections = array();
		if ($numRows = $this->getLocationCount()) {
			
			$this->pageCount = (int) ceil($numRows / $this->pageSize);
			
			if ($this->page > $this->pageCount) {
				$this->page = 1;
			}
			
			$result = sqlSelect($this->buildmysql_query(). paginationLimit($this->page, $this->pageSize));

			$pages=array();
			for ($i = 1; $i <= $this->pageCount; ++$i){
				$pages[$i] = '&page='. $i;
			}
			if ($mergeFields['Title'] = $this->setting('title')) {
				$subSections = array('Location_Listing_Title' => true);
			}
			$this->frameworkHead('Location_List','Location_Row_On_List',$mergeFields, $subSections);

			$lastCountryCode = "";
			while($row=sqlFetchAssoc($result)){
				$mergeFields = array();
				$subSections = array();
				
				$mergeFields['Country_Code'] = $row['country_id'];
				
				if ($this->setting("order_by_1")=="country") {
					if ($lastCountryCode != $row['country_id']) {
						$lastCountryCode = $row['country_id'];
						$mergeFields['Country_Code_Section'] = $lastCountryCode;
						$subSections['Country_Section_Anchor'] = true;
					}
				}

				if ($row['image_id']) {
					$mergeFields['Link_To_Image'] =
					$mergeFields['Image_Width'] =
					$mergeFields['Image_Height'] = '';
	
					imageLink($mergeFields['Image_Width'], $mergeFields['Image_Height'], $mergeFields['Link_To_Image'], $row['image_id'], 150, 100);
				}

				$cID = $row['equiv_id'];
				$cType = $row['content_type'];
				langEquivalentItem($cID, $cType);
				$linkToLocation = linkToItem($cID, $cType, true);
				
				if ($linkToLocation) {
					$mergeFields['Link_To_Location'] = htmlspecialchars($linkToLocation);
					
					if ($row['image_id']) {
						$subSections['Location_Image_With_Link']=true;				
					} else {
						$subSections['Default_Image_With_Link']=true;				
					}
				
				} else {
					if ($row['image_id']) {
						$subSections['Location_Image_No_Link']=true;				
					} else {
						$subSections['Default_Image_No_Link']=true;				
					}
				}

				if ($row['latitude'] && $row['longitude']) {
					$subSections['Location_Map'] = true;
					$mergeFields['Map_URL'] = $this->showFloatingBoxLink("&map_zoom=" . $row['map_zoom'] ."&map_center_lat=" . $row['latitude'] . "&map_center_lng=" . $row['longitude'] . "&location_id=" . (int) $row['id']);
				}
				
				foreach ($row as $key => $value) {
					$fieldName = 'Location_'. str_replace(' ', '_', ucwords(str_replace('_', ' ', $key)));
					$mergeFields[$fieldName] = htmlspecialchars($value);
					$subSections[$fieldName] = (bool) $value;
				}
				
				if (isset($mergeFields['Location_Summary'])) {
					$mergeFields['Location_Description'] = nl2br($mergeFields['Location_Summary']);
					$subSections['Location_Description'] = true;
				}
				
				$this->framework('Location_Row_On_List',$mergeFields,$subSections);
			}
			
			$mergeFields['Pagination'] = '';
			$this->pagination('pagination', $this->page, $pages, $mergeFields['Pagination']);
			
			$this->frameworkFoot('Location_List','Location_Row_On_List',$mergeFields);
		} else {
			$mergeFields['Msg_Empty_List']=	$this->phrase ('_NO_LOCATIONS_TO_DISPLAY');
			$this->framework('No_Location',$mergeFields,array());
		}
	}

	public function fillAdminBox($path, $settingGroup, &$box, &$fields, &$values) {
		switch ($path) {
			case 'plugin_settings':
				$box['tabs']['display']['fields']['pagination']['values'] = paginationOptions();
				break;

		}
	}


	public function formatAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		switch ($path){
			case 'plugin_settings':
				if (!$values['first_tab/sector']) {
					if ($values['first_tab/order_by_1']=='sector_score'){
						$box['tabs']['first_tab']['fields']['order_by_1']['current_value']='';
					}
					unset($box['tabs']['first_tab']['fields']['order_by_1']['values']['sector_score']);

					if ($values['first_tab/order_by_2']=='sector_score'){
						$box['tabs']['first_tab']['fields']['order_by_2']['current_value']='';
					}
					unset($box['tabs']['first_tab']['fields']['order_by_2']['values']['sector_score']);

					if ($values['first_tab/order_by_3']=='sector_score'){
						$box['tabs']['first_tab']['fields']['order_by_3']['current_value']='';
					}
					unset($box['tabs']['first_tab']['fields']['order_by_3']['values']['sector_score']);
				}
				if ($values['first_tab/country']) {
					if ((int)$values['first_tab/region']) {
						$regionCountry = zenario_country_manager::getCountryOfRegion((int)$values['first_tab/region']);
						if (arrayKey($regionCountry,'id') != $values['first_tab/country']) {
							unset($box['tabs']['first_tab']['fields']['region']['value']);
							unset($box['tabs']['first_tab']['fields']['region']['current_value']);
						}
					}
					$box['tabs']['first_tab']['fields']['region']['pick_items']['path'] = 'zenario__languages/panels/countries/item//' .  $values['first_tab/country'] . '//';
					$box['tabs']['first_tab']['fields']['region']['hidden'] = false;
				} else {
					$box['tabs']['first_tab']['fields']['region']['hidden'] = true;
				}
				break;
		}
	}

	function showFloatingBox() {
		
		echo '
			<html>
				<head>
					<script id="google_api" type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
				</head>
				<body onload="
					function initMap(elId,lat,lng,zoom) {
						var mapOptions;
						var map;
						var marker;
						var actualZoom;
					
						if (zoom==\'undefined\') {
							actualZoom = 12;
						} else {
							actualZoom = zoom;
						}
					
						mapOptions = {
							center: new google.maps.LatLng(lat,lng),
							zoom: actualZoom,
							mapTypeId: google.maps.MapTypeId.ROADMAP
						}
					
						map = new google.maps.Map(document.getElementById(elId),mapOptions);
				
						marker = new google.maps.Marker({
							position: new google.maps.LatLng(lat,lng),
							map: map
						});
					};
					
					initMap(
						\'map\',
						'. (float) get('map_center_lat'). ',
						'. (float) get('map_center_lng'). ',
						'. (float) get('map_zoom'). '
					);
				">
					<div id="map" style="width: 475px; height: 324px;"></div>
				</body>
			</html>';
	}


}