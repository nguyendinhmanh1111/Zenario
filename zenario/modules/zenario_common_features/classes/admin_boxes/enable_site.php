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


class zenario_common_features__admin_boxes__enable_site extends ze\moduleBaseClass {

	public function fillAdminBox($path, $settingGroup, &$box, &$fields, &$values) {
		
		$box['key']['isHead'] = ZENARIO_IS_HEAD;

		$logosAndBrandingSiteSettingLink = ze\link::absolute() . 'organizer.php#zenario__administration/panels/site_settings//logos_and_branding~.site_settings~tadmin_login~k{"id"%3A"logos_and_branding"}';
		$linkStart = "<a href='" . $logosAndBrandingSiteSettingLink . "' target='_blank'>";
		$linkEnd = '</a>';
		ze\lang::applyMergeFields(
			$fields['site/site_disabled_note']['note_below'],
			['link_start' => $linkStart, 'link_end' => $linkEnd]
		);
		
		$values['site/site_enabled'] = (bool) ze::setting('site_enabled');
		
		$devModeSetting = \ze::setting('site_in_dev_mode');
		
		if (is_numeric($devModeSetting)) {
			$devModeSetting = (int) $devModeSetting - time();
			
			if ($devModeSetting < 1) {
				$devModeSetting = 0;
			} else {
				
				$label = ze\admin::phrase('Site is in developer mode for the next');
				
				$showSeconds = true;
				$showMinutes = true;
				
				if ($devModeSetting > 86400) {
					$days = (int) floor($devModeSetting / 86400);
					$devModeSetting -= $days * 86400;
					$label .= ' '. ze\admin::nPhrase('1 day', '[[count]] days', $days);
					$showMinutes = false;
				}
				
				if ($devModeSetting > 3600) {
					$hours = (int) floor($devModeSetting / 3600);
					$devModeSetting -= $hours * 3600;
					$label .= ' '. ze\admin::nPhrase('1 hour', '[[count]] hours', $hours);
					$showSeconds = false;
				}
				
				if ($showMinutes && $devModeSetting > 60) {
					$minutes = (int) floor($devModeSetting / 60);
					$devModeSetting -= $minutes * 60;
					$label .= ' '. ze\admin::nPhrase('1 minute', '[[count]] minutes', $minutes);
					$showSeconds = false;
				}
				
				if ($showSeconds) {
					$label .= ' '. ze\admin::nPhrase('1 second', '[[count]] seconds', $devModeSetting);
				}
				
				$devModeSetting = 'timed';
			
				$fields['site/site_in_dev_mode']['values']['timed']['hidden'] = false;
				$fields['site/site_in_dev_mode']['values']['timed']['label'] = $label;
			}
		}
		
		$values['site/enable_dev_mode'] = (bool) $devModeSetting;
		$values['site/site_in_dev_mode'] = $devModeSetting;
	}

	public function formatAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		
		$box['tabs']['site']['notices']['checked']['show'] = false;
		if (!empty($fields['site/clear_cache']['pressed'])) {
			$box['tabs']['site']['notices']['checked']['show'] = true;
			
			ze\skinAdm::clearCache();
			
			$box['tabs']['site']['show_errors_after_field'] = 'desc3';
		} else {
			unset($box['tabs']['site']['show_errors_after_field']);
		}
	}


	public function validateAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes, $saving) {
		
		if ($values['site/site_enabled']) {
		 	$moduleErrors = '';
			if (ze\dbAdm::checkIfUpdatesAreNeeded($moduleErrors, $andDoUpdates = false)) {
				$box['tabs']['site']['errors'][] =
					ze\admin::phrase('You must apply database updates before you can enable your site.');
			}
			
			if (!ze\row::exists('languages', [])) {
				//Catch the edge-case where someone does a site reset, has not added any languages, and tries to enable dev mode.
				//Allow them to do this without raising an error.
			
			//If the site isn't currently live, force people to review and publish all of the special pages before doing so
			} elseif (!ze::setting('site_enabled')) {
				$tags = '';
				
				$result = ze\row::query(
					'special_pages',
					['equiv_id', 'content_type'],
					['logic' => ['create_and_maintain_in_default_language'], 'allow_hide' => 0],
					['page_type']);
				
				while ($row = ze\sql::fetchAssoc($result)) {
					if (!ze\row::get('content_items', 'visitor_version', ['id' => $row['equiv_id'], 'type' => $row['content_type']])) {
						$tags .= ($tags? ', ' : ''). '"'. ze\content::formatTag($row['equiv_id'], $row['content_type']). '"';
					}
				}
				
				if ($tags) {
					$box['tabs']['site']['errors'][] =
						ze\admin::phrase('You must publish all special pages before you can enable your site. Please publish the following pages: [[tags]].', ['tags' => $tags]);
				}
			}
		
		}
		
		//Show a confirmation when trying to enable or disable a site
		unset($box['confirm']);
		if (empty($box['tabs']['site']['errors'])) {
			if ($values['site/site_enabled'] && !ze::setting('site_enabled')) {
				$box['confirm'] = [
					'show' => true,
					'message_type' => 'warning',
					'message' => ze\admin::phrase('You are about to enable this site, and make it visible to site visitors. Are you sure you wish to enable the site?'),
					'button_message' => ze\admin::phrase('Enable site'),
				];
			} elseif (!$values['site/site_enabled'] && ze::setting('site_enabled')) {
				$box['confirm'] = [
					'show' => true,
					'message_type' => 'warning',
					'message' => ze\admin::phrase('You are about to disable this site, which will make it no longer visible to site visitors. Are you sure you wish to disable the site?'),
					'button_message' => ze\admin::phrase('Disable site'),
				];
			}
		}
	}
	
	
	public function saveAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
	
		if (ze\priv::check('_PRIV_EDIT_SITE_SETTING')) {
			
			if ($values['site/site_enabled']) {
				ze\site::setSetting('site_enabled', 1);
				$box['key']['id'] = 'site_enabled';
			} else {
				ze\site::setSetting('site_enabled', '');
				$box['key']['id'] = 'site_disabled';
			}
			
			if (!$values['site/enable_dev_mode']) {
				$devModeSetting = 0;
			} else {
				$devModeSetting = $values['site/site_in_dev_mode'];
			}
			
			if ($devModeSetting !== 'timed') {
			
				if ($devModeSetting && is_numeric($devModeSetting)) {
					$devModeSetting = (int) $devModeSetting + time();
				}
			
				ze\site::setSetting('site_in_dev_mode', $devModeSetting);
			}
		}
	}
}
