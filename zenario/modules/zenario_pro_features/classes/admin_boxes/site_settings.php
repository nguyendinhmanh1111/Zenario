<?php
/*
 * Copyright (c) 2023, Tribal Limited
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

				
class zenario_pro_features__admin_boxes__site_settings extends ze\moduleBaseClass {

	public function fillAdminBox($path, $settingGroup, &$box, &$fields, &$values){
		
		if (isset($box['tabs']['admin_domain']['fields']['admin_use_ssl'])) {
			if (!ze::setting('admin_use_ssl') && ze\link::protocol() != 'https://') {
				$box['tabs']['admin_domain']['fields']['admin_use_ssl']['readonly'] = true;
			}
		}
		
		$userIP = ze\user::ip();
		$selectedIP = ze::setting('limit_caching_debug_info_by_ip');
		$effectiveIP = $selectedIP ?: $userIP;
		$mrg = ['ip' => $effectiveIP];
		
		if (!$selectedIP) {
			$fields['caching/caching_debug_info']['note_below'] = 
				ze\admin::phrase('Debug info will only be shown to your current IP address ([[ip]]).', $mrg);
		
		} elseif ($effectiveIP == $userIP) {
			$fields['caching/caching_debug_info']['note_below'] = 
				ze\admin::phrase('Debug info is only being shown to your current IP address ([[ip]]).', $mrg);
		
		} else {
			$fields['caching/caching_debug_info']['note_below'] = 
				ze\admin::phrase('Debug info is only being shown to the IP address that this setting was turned on from ([[ip]]).', $mrg);
		}
	}
	
	public function formatAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		
		if (isset($box['tabs']['zenario_pro_features__cache_stats']) && ze\priv::check('_PRIV_EDIT_SITE_SETTING')) {
				
			$reset = false;
			$dir = CMS_ROOT. 'cache/stats/page_caching/';
				
			//Reset the stats
			if (!empty($box['tabs']['zenario_pro_features__cache_stats']['fields']['clear_stats']['pressed'])) {
				$reset = true;
				foreach (['total', 'hits', 'writes', 'partial_hits', 'partial_writes', 'misses', 'from', 'to'] as $stat) {
					if (is_writable($dir. $stat)) {
						unlink($dir. $stat);
					} else {
						$reset = false;
					}
				}
				unset($box['tabs']['zenario_pro_features__cache_stats']['fields']['clear_stats']['pressed']);
			}
				
			if ($reset) {
				$box['tabs']['zenario_pro_features__cache_stats']['notices']['notice']['show'] = true;
			} else {
				$box['tabs']['zenario_pro_features__cache_stats']['notices']['notice']['show'] = false;
			}
				
			$stats = [];
			foreach (['total', 'hits', 'writes', 'partial_hits', 'partial_writes', 'misses'] as $stat) {
				$stats[$stat] = 0;
				if (file_exists($dir. $stat)) {
					$stats[$stat] = (int) trim(file_get_contents($dir. $stat));
				}

				unset($box['tabs']['zenario_pro_features__cache_stats']['fields'][$stat]['current_value']);
				$box['tabs']['zenario_pro_features__cache_stats']['fields'][$stat]['value'] = $stats[$stat];
			}
				
			foreach (['from', 'to'] as $stat) {
				$stats[$stat] = '';
				if (file_exists($dir. $stat)) {
					$stats[$stat] = filemtime($dir. $stat);
				}

				unset($box['tabs']['zenario_pro_features__cache_stats']['fields'][$stat]['current_value']);
				$box['tabs']['zenario_pro_features__cache_stats']['fields'][$stat]['value'] = $stats[$stat];
			}
				
			unset($box['tabs']['zenario_pro_features__cache_stats']['fields']['hits_pc']['current_value']);
			if ($stats['total'] == 0) {
				$box['tabs']['zenario_pro_features__cache_stats']['fields']['hits_pc']['value'] = '';
			} else {
				$box['tabs']['zenario_pro_features__cache_stats']['fields']['hits_pc']['value'] = round(100 * (float) $stats['hits'] / $stats['total'], 2);
			}
				
			if (file_exists($dir. 'from')) {
				$box['tabs']['zenario_pro_features__cache_stats']['fields']['clear_stats']['class'] = 'submit_selected';
			} else {
				$box['tabs']['zenario_pro_features__cache_stats']['fields']['clear_stats']['class'] = 'submit_disabled';
			}
		}
	}
	
	public function validateAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes, $saving) {
		
		if (isset($box['tabs']['caching'])) {
			
			if ($values['caching/caching_enabled']) {
				if (!$values['caching/cache_web_pages']
				 && !$values['caching/cache_css_js_wrappers']
				 && !$values['caching/cache_ajax']) {
					$box['tabs']['caching']['errors'][] =
						ze\admin::phrase('Please select which things you wish to cache.');
				}
			}
			
		}
	}
	
	public function saveAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		
		//Changes to the cache site settings..?
		if (ze\priv::check('_PRIV_EDIT_SITE_SETTING')
		 && ze\ring::engToBoolean($box['tabs']['caching']['edit_mode']['on'] ?? false)) {
			
			//Empty the cache if so
			ze\pageCache::clearOnShutdown($clearAll = true);
			
			//If the caching_debug_info site setting is enabled, make sure that the
			//limit_caching_debug_info_by_ip setting is always checked, and set it to the
			//current IP if it is not currently set to something.
			if ($values['caching/caching_debug_info']) {
				$userIP = ze\user::ip();
				$selectedIP = ze::setting('limit_caching_debug_info_by_ip');
				$effectiveIP = $selectedIP ?: $userIP;
				
				ze\site::setSetting('limit_caching_debug_info_by_ip', $effectiveIP);
			
			//If the caching_debug_info site setting is turned off, clear the value of the
			//limit_caching_debug_info_by_ip setting.
			} else {
				ze\site::setSetting('limit_caching_debug_info_by_ip', '');
			}
			
			//Note that the caching has the option to show debug info to everyone,
			//however we currently do not enable this option to be set.
		}
	}
}
