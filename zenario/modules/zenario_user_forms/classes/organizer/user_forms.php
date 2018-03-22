<?php
/*
 * Copyright (c) 2018, Tribal Limited
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

class zenario_user_forms__organizer__user_forms extends ze\moduleBaseClass {
	
	public function preFillOrganizerPanel($path, &$panel, $refinerName, $refinerId, $mode) {
		if ($refinerName == 'archived') {
			$panel['db_items']['where_statement'] = 'WHERE TRUE';
			$panel['no_items_message'] = ze\admin::phrase('No forms have been archived.');
		}
		if (!ze\module::inc('zenario_extranet_profile_edit')) {
			$panel['db_items']['where_statement'] .= '
				AND f.type != "profile"';
			$panel['collection_buttons']['create_profile_form']['hidden'] = true;
		}
	}
	
	public function fillOrganizerPanel($path, &$panel, $refinerName, $refinerId, $mode) {
		if ($refinerName == 'email_address_setting') {
			unset($panel['collection_buttons']);
			$panel['title'] = ze\admin::phrase('Summary of email addresses used by forms');
			$panel['no_items_message'] = ze\admin::phrase('No forms send emails to a specific address.');
		} else {
			unset($panel['columns']['form_email_addresses']);
		}
		
		if ($refinerName == 'type' && $refinerId == 'registration') {
			$panel['no_items_message'] = ze\admin::phrase('There are no registration forms.');
		}
		
		if (!ze::setting('zenario_user_forms_enable_predefined_text')) {
			$panel['item_buttons']['edit_predefined_text']['hidden'] = true;
		}
		
		
		//Get plugins using a form
		$moduleIds = zenario_user_forms::getFormModuleIds();
		$formPlugins = [];
		$sql = '
			SELECT id, name, 0 AS egg_id
			FROM '.DB_NAME_PREFIX.'plugin_instances
			WHERE module_id IN ('. ze\escape::in($moduleIds, 'numeric'). ')
			ORDER BY name';
		$result = ze\sql::select($sql);
		while ($row = ze\sql::fetchAssoc($result)) {
			$formPlugins[$row['id']] = $row['name'];
		}
		$sql = "
			SELECT pi.id, pi.name, np.id AS egg_id
			FROM ". DB_NAME_PREFIX. "nested_plugins AS np
			INNER JOIN ". DB_NAME_PREFIX. "plugin_instances AS pi
			   ON pi.id = np.instance_id
			WHERE np.module_id IN (". ze\escape::in($moduleIds, 'numeric'). ")
			ORDER BY pi.name";
		$result = ze\sql::select($sql);
		while ($row = ze\sql::fetchAssoc($result)) {
			$formPlugins[$row['id']] = $row['name'];
		}
		
		//Get content items with a plugin using a form on
		$formUsage = [];
		$contentItemUsage = [];
		$layoutUsage = [];
		if ($formPlugins) {
			$sql = '
				SELECT pil.content_id, pil.content_type, pil.instance_id
				FROM '.DB_NAME_PREFIX.'plugin_item_link pil
				INNER JOIN '.DB_NAME_PREFIX.'content_items c
					ON (pil.content_id = c.id) AND (pil.content_type = c.type) AND (pil.content_version = c.admin_version)
				WHERE c.status NOT IN (\'trashed\',\'deleted\')
				AND pil.instance_id IN ('. ze\escape::in(array_keys($formPlugins), 'numeric'). ')
				GROUP BY pil.content_id, pil.content_type, pil.instance_id';
			$result = ze\sql::select($sql);
			while ($row = ze\sql::fetchAssoc($result)) {
				$tagId = ze\content::formatTag($row['content_id'], $row['content_type']);
				$contentItemUsage[$row['instance_id']][] = $tagId;
			}
			
			//Get layouts with a plugin using a form on
			$sql = '
				SELECT l.name, pll.instance_id
				FROM '.DB_NAME_PREFIX.'plugin_layout_link pll
				INNER JOIN '.DB_NAME_PREFIX.'layouts l
					ON pll.layout_id = l.layout_id
				WHERE l.status = "active"
				AND pll.instance_id IN (' . ze\escape::in(array_keys($formPlugins), 'numeric') . ')
				GROUP BY l.layout_id, pll.instance_id';
			$result = ze\sql::select($sql);
			while ($row = ze\sql::fetchAssoc($result)) {
				$layoutUsage[$row['instance_id']][] = $row['name'];
			}
		}
		
		foreach($formPlugins as $instanceId => $pluginName) {
			$className = static::getModuleClassNameByInstanceId($instanceId);
			$moduleName = ze\module::getModuleDisplayNameByClassName($className);
			
			if ($formId = ze\row::get('plugin_settings', 'value', ['instance_id' => $instanceId, 'name' => 'user_form'])) {
				$details = ['instanceId' => $instanceId, 'pluginName' => $pluginName, 'moduleName' => $moduleName];
				if (isset($contentItemUsage[$instanceId])) {
					$details['contentItems'] = $contentItemUsage[$instanceId];
				}
				if (isset($layoutUsage[$instanceId])) {
					$details['layouts'] = $layoutUsage[$instanceId];
				}
				$formUsage[$formId][] = $details;
			}
		}
		
		foreach ($panel['items'] as $id => &$item) {
			$pluginUsage = '';
			$contentUsage = '';
			$layoutUsage = '';
			$moduleNames = [];
			if (isset($formUsage[$id]) && !empty($formUsage[$id])) {
				$pluginUsage = 'P'. $formUsage[$id][0]['instanceId']. ' '. $formUsage[$id][0]['pluginName'];
				if (($count = count($formUsage[$id])) > 1) {
					$plural = (($count - 1) == 1) ? '' : 's';
					$pluginUsage .= ' and '.($count - 1).' other plugin'.$plural;
				}
				$contentCount = 0;
				$layoutCount = 0;
				foreach($formUsage[$id] as $plugin) {
					$moduleNames[$plugin['moduleName']] = $plugin['moduleName'];
					if (isset($plugin['contentItems'])) {
						if (empty($contentUsage)) {
							$contentUsage = '"'.$plugin['contentItems'][0].'"';
						}
						$contentCount += count($plugin['contentItems']);
					}
					if (isset($plugin['layouts'])) {
						if (empty($layoutUsage)) {
							$layoutUsage = '"' . $plugin['layouts'][0] . '"';
						}
						$layoutCount += count($plugin['layouts']);
					}
				}
				
				//Multiple content, no layout
				if ($contentCount > 1 && $layoutCount == 0) {
					$plural = (($contentCount - 1) == 1) ? '' : 's';
					$contentUsage .= ' and '.($contentCount - 1).' other item'.$plural;
				//Multiple content, layout
				} elseif ($contentCount > 1 && $layoutCount > 0) {
					$plural = (($contentCount - 1) == 1) ? '' : 's';
					$plural2 = ($layoutCount == 1) ? '' : 's';
					$contentUsage .= ', '.($contentCount - 1).' other item'.$plural . ' and '.$layoutCount. ' layout'.$plural2;
				//Single content, layout
				} elseif ($contentCount == 1 && $layoutCount > 0) {
					$plural2 = ($layoutCount == 1) ? '' : 's';
					$contentUsage .= ' and '.$layoutCount. ' layout'.$plural2;
				//No content, layout
				} elseif (!$contentCount && $layoutCount) {
					$contentUsage = $layoutUsage;
					if ($layoutCount > 1) {
						$plural2 = (($layoutCount - 1) == 1) ? '' : 's';
						$contentUsage .= ' and '.($layoutCount - 1).' other layout'.$plural2;
					}
				}
			}
			$item['plugin_module_name'] = implode(', ', $moduleNames);
			$item['plugin_usage'] = $pluginUsage;
			$item['plugin_content_items'] = $contentUsage;
			
			if ($item['type'] != 'standard') {
				$item['css_class'] = 'form_type_' . $item['type'];
			}
		}
	}
	
	public function handleOrganizerPanelAJAX($path, $ids, $ids2, $refinerName, $refinerId) {
		ze\priv::exitIfNot('_PRIV_MANAGE_FORMS');
		if ($_POST['archive_form'] ?? false) {
			foreach(explode(',', $ids) as $id) {
				ze\row::update(ZENARIO_USER_FORMS_PREFIX . 'user_forms', ['status' => 'archived'], ['id' => $id]);
			}
		} elseif ($_POST['delete_form'] ?? false) {
			foreach (explode(',', $ids) as $formId) {
				$error = zenario_user_forms::deleteForm($formId);
				if (ze::isError($error)) {
					foreach ($error->errors as $message) {
						echo $message . "\n";
					}
				}
				
			}
		} elseif ($_POST['duplicate_form'] ?? false) {
			static::duplicateForm($ids);
		}
	}
	
	public static function duplicateForm($formId) {
		$form = ze\row::get(ZENARIO_USER_FORMS_PREFIX . 'user_forms', true, $formId);
		
		//Add version number to form name
		$formNameArray = explode(' ', $form['name']);
		$formVersion = end($formNameArray);
		if (preg_match('/\((\d+)\)/', $formVersion, $matches)) {
			array_pop($formNameArray);
			$form['name'] = implode(' ', $formNameArray);
		}
		for ($i = 2; $i < 1000; $i++) {
			$name = $form['name'].' ('.$i.')';
			if (!ze\row::exists(ZENARIO_USER_FORMS_PREFIX . 'user_forms', ['name' => $name])) {
				$form['name'] = $name;
				break;
			}
		}
		
		//Use the import/export functions to easily duplicate a form
		$formsJSON = static::getFormsExportJSON($formId);
		$formsJSON['forms'][0]['form']['name'] = $name;
		
		zenario_user_forms::importForms(json_encode($formsJSON));
	}
	
	public static function getFormsExportJSON($formIds) {
		$formIds = ze\ray::explodeAndTrim($formIds);
		$formsJSON = [
			'major_version' => ZENARIO_MAJOR_VERSION,
			'minor_version' => ZENARIO_MINOR_VERSION,
			'forms' => []
		];
		foreach ($formIds as $formId) {
			$formJSON = zenario_user_forms::getFormJSON($formId);
			$formsJSON['forms'][] = $formJSON;
		}
		return $formsJSON;
	}
	
	public function organizerPanelDownload($path, $ids, $refinerName, $refinerId) {
		ze\priv::exitIfNot('_PRIV_MANAGE_FORMS');
		if ($_POST['export_forms'] ?? false) {
			$formsJSON = json_encode(static::getFormsExportJSON($ids));
			
			$filename = tempnam(sys_get_temp_dir(), 'forms_export');
			file_put_contents($filename, $formsJSON);
			//Offer file as download
			header('Content-Type: application/json');
			header('Content-Disposition: attachment; filename="Zenario forms.json"');
			header('Content-Length: ' . filesize($filename));
			readfile($filename);
			//Remove file from temp directory
			@unlink($filename);
			exit;
		}
	}
	
	public static function getModuleClassNameByInstanceId($id) {
		$sql = '
			SELECT class_name
			FROM '.DB_NAME_PREFIX.'modules m
			INNER JOIN '.DB_NAME_PREFIX.'plugin_instances pi
				ON m.id = pi.module_id
			WHERE pi.id = '.(int)$id;
		$result = ze\sql::select($sql);
		$row = ze\sql::fetchRow($result);
		return $row[0];
	}
}