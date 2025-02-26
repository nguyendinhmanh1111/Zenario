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


class zenario_common_features__admin_boxes__setup_module extends ze\moduleBaseClass {

	public function fillAdminBox($path, $settingGroup, &$box, &$fields, &$values) {
		
		if ((!$module = ze\module::details($box['key']['id']))
		 || ($module['status'] == 'module_is_abstract')) {
			exit;
		}



		switch ($module['status']) {
			case 'module_suspended':
				if ($error = ze\moduleAdm::run($box['key']['id'], $test = true)) {
					echo $error;
					exit;
				}
		
				$box['title'] = ze\admin::phrase('Starting the module "[[class_name]]" ([[display_name]])', $module);
				$box['save_button_message'] = ze\admin::phrase('Start module');
		
				$box['tabs']['confirm']['notices']['are_you_sure']['show'] = true;
		
				break;
		
			case 'module_running':
				echo ze\admin::phrase('This module is already running!');
				exit;
	
			case 'module_not_initialized':
				if ($error = ze\moduleAdm::run($box['key']['id'], $test = true)) {
					echo $error;
					exit;
				}
		
				$box['title'] = ze\admin::phrase('Starting the module "[[class_name]]" ([[display_name]])', $module);
				$box['save_button_message'] = ze\admin::phrase('Start module');
		
				//$box['tabs']['confirm']['notices']['are_you_sure']['show'] = true;
		}

		$box['tabs']['confirm']['hidden'] = false;
		$box['tabs']['confirm']['fields']['module_start_desc']['hidden'] = false;
		$box['tabs']['confirm']['fields']['module_start_desc']['snippet']['p'] = ze\admin::phrase('Start the module "[[class_name]]" ([[display_name]])?', $module);

		$desc = false;
		if (ze\moduleAdm::loadDescription($module['class_name'], $desc)) {
			$box['tabs']['confirm']['fields']['module_description_or_help']['snippet']['html'] = '<div class="zenario_fbInfo">';
			$box['tabs']['confirm']['fields']['module_description_or_help']['snippet']['html'] .= $desc['description'];
			$box['tabs']['confirm']['fields']['module_description_or_help']['snippet']['html'] .= '</div>';
			$box['tabs']['confirm']['fields']['module_description_or_help']['hidden'] = false;
		}
		
		
		$perms = $labels = [];
		if ($module['status'] == 'module_not_initialized'
		 && (ze\moduleAdm::scanPermissionsFromDescription($module['class_name'], $perms, $labels))) {
			$box['tabs']['confirm']['fields']['grant_perms_desc']['hidden'] = false;
	
			$desc = '';
			
			if (count($perms) == 1) {
				$desc = ze\admin::phrase('This module defines a new administrator permission:');
			} else {
				$desc = ze\admin::phrase('This module defines new administrator permissions:');
			}
			
			$desc .= '<ul>';
			foreach ($labels as $perm => $label) {
				$desc .= '<li>'. htmlspecialchars($label). '</li>';
			}
			$desc .= '</ul>';
			
			$fields['confirm/grant_perms_desc']['snippet']['html'] = $desc;
			
			$countOfAdminsWithSpecificPerms =
				ze\row::count('admins',
					['authtype' => 'local', 'status' => 'active', 'permissions' => 'specific_actions']
				);
			$countOfAdminsWithAllPerms =
				ze\row::count('admins',
					['authtype' => 'local', 'status' => 'active', 'permissions' => 'all_permissions']
				);
			
			if ($countOfAdminsWithSpecificPerms) {
				$box['tabs']['confirm']['fields']['grant_perms']['hidden'] = false;
			
				$fields['confirm/grant_perms']['values']['site_admins']['label'] =
					ze\admin::phrase('All current administrators ([[n]])',
						['n' => $countOfAdminsWithSpecificPerms]
					);
			
				$fields['confirm/grant_perms_desc_2']['snippet']['html'] =
					ze\admin::phrase('Administrators with &quot;all permissions&quot; ([[n]] people) will be able to use the module. For control over individual administrators, please go to the <a href="[[link]]" target="blank">Administrators panel</a>.',
						[
							'n' => $countOfAdminsWithAllPerms,
							'link' => htmlspecialchars(ze\link::absolute(). 'organizer.php#zenario__administration/panels/administrators')
						]
					);
			}
			
			if (($_SESSION['admin_global_id'] ?? false) || ($_SESSION['admin_permissions'] == 'all_permissions')) {
				unset($box['tabs']['confirm']['fields']['grant_perms']['values']['myself']);
			}
		}
	}

	public function formatAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		
	}


	public function validateAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes, $saving) {
		
	}
	
	
	public function saveAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		
		ze\priv::exitIfNot('_PRIV_RUN_MODULE');

		if (!$module = ze\module::details($box['key']['id'])) {
			exit;
		}


		if ($module['status'] != 'module_running') {
			if ($error = ze\moduleAdm::run($box['key']['id'], $test = false)) {
				echo $error;
				exit;
			}
		}
		
		//The new module may add new features to several places so we need to completely clear the cache
		ze\skinAdm::clearCache();

		//Update the special pages, creating new ones if needed
		ze\contentAdm::addNeededSpecialPages();

		if ($module['status'] == 'module_not_initialized') {
			//Check to see if the module just made any special pages
			$sql = "
				SELECT c.id, c.type, c.alias, c.language_id
				FROM ". DB_PREFIX. "special_pages AS sp
				INNER JOIN ". DB_PREFIX. "content_items AS c
				   ON c.equiv_id = sp.equiv_id
				  AND c.type = sp.content_type
				WHERE sp.module_class_name = '". ze\escape::asciiInSQL($module['class_name']). "'
				ORDER BY c.type, c.equiv_id, c.id";
	
			$contentItems = [];
			$result = ze\sql::select($sql);
			while ($row = ze\sql::fetchAssoc($result)) {
				$contentItems[] = $row;
			}
	
			if (!empty($contentItems)) {
				if (count($contentItems) < 2) {
					$toastMessage =
						ze\admin::phrase('&quot;[[tag]]&quot; was created by the [[name]] module. You should review and publish this content item.',
							[
								'name' => htmlspecialchars($module['display_name']),
								'tag' => htmlspecialchars(ze\content::formatTag($contentItems[0]['id'], $contentItems[0]['type'], $contentItems[0]['alias'], $contentItems[0]['language_id']))]);
		
				} else {
					$toastMessage =
						ze\admin::phrase('The following content items were created by the [[name]] module, you should review and publish them:',
							['name' => htmlspecialchars($module['display_name'])]).
						'<ul>';
			
					foreach ($contentItems as $contentItem) {
						$toastMessage .= '<li>'. htmlspecialchars(ze\content::formatTag($contentItem['id'], $contentItem['type'], $contentItem['alias'], $contentItem['language_id'])). '</li>';
					}
			
					$toastMessage .= '</ul>';
				}
		
				$box['toast'] = [
					'message' => $toastMessage,
					'options' => ['timeOut' => 0, 'extendedTimeOut' => 0]];
			}
	
	
			$perms = $labels = [];
			if (!ze\ring::engToBoolean($box['tabs']['confirm']['fields']['grant_perms']['hidden'])
			 && (($values['confirm/grant_perms'] == 'myself' && !($_SESSION['admin_global_id'] ?? false)) || $values['confirm/grant_perms'] == 'site_admins')
			 && (ze\moduleAdm::scanPermissionsFromDescription($module['class_name'], $perms, $labels))) {
		
				if ($values['confirm/grant_perms'] == 'myself') {
					ze\adminAdm::savePerms(ze\admin::id(), $perms);
				} else {
					foreach (ze\row::query('admins',
						'id',
						['authtype' => 'local', 'status' => 'active', 'permissions' => 'specific_actions']
					) as $admin) {
						ze\adminAdm::savePerms($admin['id'], $perms);
					}
				}
		
				if (!($_SESSION['admin_global_id'] ?? false)) {
					ze\admin::setSession(ze\admin::id());
				}
			}
		}
		
		//Modules that change Organizer will require a Organizer reload.
		if (ze\dbAdm::needToReloadOrganizerWhenModuleIsInstalled($module['class_name'])) {
			$this->needReload = true;
		}
	}
	
	private $needReload = false;
	
	public function adminBoxSaveCompleted($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		if ($this->needReload) {
			ze\tuix::closeWithFlags(['reload_organizer' => true]);
			exit;
		}
	}
}
