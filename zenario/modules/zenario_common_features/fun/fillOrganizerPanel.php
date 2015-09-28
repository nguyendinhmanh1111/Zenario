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

switch ($path) {
	
	case 'zenario__menu/panels/sections':
		if (get('refiner__language')) {
			$panel['title'] = adminPhrase('Menu sections (language [[lang]])', array('lang' => getLanguageName(get('refiner__language'))));
		}
		
		foreach ($panel['items'] as $id => &$item) {
			$item['traits'] = array('empty' => !checkRowExists('menu_nodes', array('section_id' => $id)));
		}
		
		break;

	
	case 'zenario__menu/nav/default_language/panel/tree_explorer':
		$panel['html'] = '
			<iframe
				class="zenario_tree_explorer_iframe"
				style="width: 100%; height: 100%;"
				src="'. htmlspecialchars(
					absCMSDirURL(). 'zenario/admin/tree_explorer/index.php'.
						'?language='. urlencode(FOCUSED_LANGUAGE_ID__NO_QUOTES).
						'&type='. urlencode($refinerName).
						'&id='. urlencode($refinerId).
						'&og=1'
			). '"></iframe>';
		
		break;
	case 'zenario__content/panels/documents':
		
		if (!setting('enable_document_tags')) {
			unset($panel['collection_buttons']['document_tags']);
		}
		
		if (isset($panel['item_buttons']['autoset'])
		 && !checkRowExists('document_rules', array())) {
			$panel['item_buttons']['autoset']['disabled'] = true;
			$panel['item_buttons']['autoset']['disabled_tooltip'] = adminPhrase('No rules for auto-setting document metadata have been created');
		}
		
		foreach ($panel['items'] as &$item) {
			$filePath = "";
			$fileId = "";
			if ($item['type'] == 'folder') {
				$tempArray = array();
				$item['css_class'] = 'zenario_folder_item';
				$item['traits']['is_folder'] = true;
				$tempArray = getRowsArray('documents', 'id', array('folder_id' => $item['id']));
				$item['folder_file_count'] = count($tempArray);
				if (!$item['folder_file_count']) {
					$item['traits']['is_empty_folder'] = true;
				}
				$item['extract_wordcount'] = 
				$item['privacy'] = '';
			} else {
			
			/* if one document has public link */
				$document = getRow('documents', array('file_id', 'filename'), $item['id']);
				
				$file = getRow('files', 
								array('id', 'filename', 'path', 'created_datetime', 'short_checksum'),
								$document['file_id']);
				
				if($document['filename']) {
					$dirPath = 'public' . '/downloads/' . $file['short_checksum'];
					$frontLink = $dirPath . '/' . $document['filename'];
					$symPath = CMS_ROOT . $frontLink;
					$symFolder =  CMS_ROOT . $dirPath;
					
					if (is_file($symPath)) {
						$item['traits']['public_link'] = true;
						$item['frontend_link'] = $frontLink;
						$publicLink = true;
					}
				}
				
				//change icon
				$item['css_class'] = 'zenario_file_item';
				$sql = "
					SELECT
						file_id,
						extract_wordcount,
						SUBSTR(extract, 1, 40) as extract
					FROM ".  DB_NAME_PREFIX. "documents
					WHERE id = ". (int) $item['id'];
				
				$result = sqlSelect($sql);
				$documentDetails = sqlFetchAssoc($result);
				if (!empty($documentDetails['extract_wordcount'])) {
					$documentDetails['extract_wordcount'] .= ', ';
				}
				$item['plaintext_extract_details'] = 'Word count: '.$documentDetails['extract_wordcount'].$documentDetails['extract'];
				$fileId = $documentDetails['file_id'];
				if ($fileId && empty($item['frontend_link'])) {
					$filePath = fileLink($fileId);
					$item['frontend_link'] = $filePath;
				}
				$filenameInfo = pathinfo($item['name']);
				if(isset($filenameInfo['extension'])) {
					$item['type'] = $filenameInfo['extension'];
				}
			}
			$item['tooltip'] = $item['name'];
			if (strlen($item['name']) > 50) {
				$item['name'] = substr($item['name'], 0, 25) . "..." .  substr($item['name'], -25);
			}
			if ($fileId && docstoreFilePath($fileId)) {
				$item['filesize'] = fileSizeConvert(filesize(docstoreFilePath($fileId)));
			}
			$item['css_class'] .= ' zenario_document_privacy_' . $item['privacy'];
			
			if ($item['date_uploaded']) {
				$item['date_uploaded'] = adminPhrase('Uploaded [[date]]', array('date' => formatDateTimeNicely($item['date_uploaded'], '_MEDIUM')));
			}
			if ($item['extract_wordcount']) {
				$item['extract_wordcount'] = nAdminPhrase(
					'[[extract_wordcount]] word', 
					'[[extract_wordcount]] words',
					$item['extract_wordcount'],
					$item
				);
			} else {
				$item['extract_wordcount'] = '';
			}
		}
		
		if (count($panel['items']) <= 0) {
			unset($panel['collection_buttons']['reorder_root']);
		}
		
		break;
	
	
	case 'zenario__menu/panels/menu_position':
	
		foreach ($panel['items'] as $id => &$item) {
			
			
			if ($item['is_dummy_child']) {
				$item['css_class'] = 'zenario_menunode_unlinked ghost';
				$item['name'] = adminPhrase('[ Put menu node here ]');
				$item['target'] =
				$item['target_loc'] =
				$item['internal_target'] =
				$item['redundancy'] = '';
			
			} elseif ($item['menu_id']) {
				if ($item['target_loc'] == 'int' && $item['internal_target']) {
					
					if (isMenuNodeUnique($item['redundancy'], $item['equiv_id'], $item['content_type'])) {
						$item['redundancy'] = 'unique';
					}
					
					if ($item['redundancy'] == 'unique') {
						$item['css_class'] = 'zenario_menunode_internal_unique';
					} elseif ($item['redundancy'] == 'primary') {
						$item['css_class'] = 'zenario_menunode_internal';
					} else {
						$item['css_class'] = 'zenario_menunode_internal_secondary';
					}

				} elseif ($item['target_loc'] == 'ext' && $item['target']) {
					$item['css_class'] = 'zenario_menunode_external';

				} else {
					$item['css_class'] = 'zenario_menunode_unlinked';
				}

				if (empty($item['parent_id'])) {
					$item['css_class'] .= ' zenario_menunode_toplevel';
				}
			
			} else {
				$item['css_class'] = 'menu_section';
			}
		}
		
		break;
	
	
	case 'zenario__menu/panels/menu_nodes':
		return require funIncPath(__FILE__, 'menu_nodes.fillOrganizerPanel');

	
	case 'zenario__content/panels/slots':
		return require funIncPath(__FILE__, 'slots.fillOrganizerPanel');
	
	
	case 'zenario__layouts/panels/template_families':

		foreach ($panel['items'] as $family => &$item) {
			$item['path'] = CMS_ROOT. zenarioTemplatePath($item['name']);
			
			if (is_dir($item['path'])) {
				$item['files'] = 0;
				foreach (scandir($item['path']) as $file) {
					if (substr($file, 0, 1) != '.' && substr($file, -8) == '.tpl.php' && is_file($item['path']. $file)) {
						++$item['files'];
					}
				}
			}
		}
		
		break;
	
	
	case 'zenario__layouts/panels/layouts':
		require_once CMS_ROOT. 'zenario/admin/grid_maker/grid_maker.inc.php';
		
		$panel['key']['disableItemLayer'] = true;
		
		if ($refinerName == 'content_type') {
			$panel['title'] = adminPhrase('Layouts available for the "[[name]]" content type', array('name' => getContentTypeName($refinerId)));
			$panel['no_items_message'] = adminPhrase('There are no layouts available for the "[[name]]" content type', array('name' => getContentTypeName($refinerId)));
		
		} elseif (get('refiner__module_usage')) {
			$mrg = array(
				'name' => getModuleDisplayName(get('refiner__module_usage')));
			$panel['title'] = adminPhrase('Layouts on which the module "[[name]]" is used (layout layer)', $mrg);
			$panel['no_items_message'] = adminPhrase('There are no layouts using the module "[[name]]".', $mrg);
		
		} elseif (get('refiner__plugin_instance_usage')) {
			$mrg = array(
				'name' => getPluginInstanceName(get('refiner__plugin_instance_usage')));
			$panel['title'] = adminPhrase('Layouts on which the plugin "[[name]]" is used (layout layer)', $mrg);
			$panel['no_items_message'] = adminPhrase('There are no layouts using the plugin "[[name]]".', $mrg);
		
		}
		
		$panel['columns']['content_type']['values'] = array();
		foreach (getContentTypes() as $cType) {
			$panel['columns']['content_type']['values'][$cType['content_type_id']] = $cType['content_type_name_en'];
		}
		
		$foundPaths = array();
		$defaultLayouts = getRowsArray('content_types', 'default_layout_id', array());
		
		$templatePreview = '';
		
		foreach ($panel['items'] as $id => &$item) {
			$item['traits'] = array();
			
			
			//For each Template file that's not missing, check its size and check the contents
			//to see if it has grid data saved inside it.
			//Multiple layouts could use the same file, so store the results of this to avoid
			//wasting time scanning the same file more than once.
			if (empty($item['missing']) && !isset($foundPaths[$item['path']])) {
				if ($fileContents = @file_get_contents($item['path'])) {
					$foundPaths[$item['path']] = array(
						'filesize' => strlen($fileContents),
						'checksum' => md5($fileContents),
						'grid' => zenario_grid_maker::readCode($fileContents, true, true)
					);
				} else {
					$foundPaths[$item['path']] = false;
				}
			}
			unset($fileContents);
			
			if (empty($item['missing']) && !empty($foundPaths[$item['path']])) {
				$item['filesize'] = $foundPaths[$item['path']]['filesize'];
				
				if ($foundPaths[$item['path']]['grid']) {
					$item['traits']['grid'] = true;
				}
			} else {
				$item['missing'] = 1;
				$item['usage_status'] = 'missing';
			}
			
			
			//Numeric ids are Layouts
			if (is_numeric($id)) {
				
				if ($item['family_name'] == 'grid_templates') {
					$layoutDetails = zenario_grid_maker::readLayoutCode($id);
					$summary = 'Gridmaker layout / ';
					if ($layoutDetails['fluid']) {
						$summary .= 'Fluid ';
					} else {
						$summary .= 'Fixed width ';
					}
					if ($layoutDetails['responsive']) {
						$summary .= '/ Responsive ';
					}
					$summary .= '/ '.$layoutDetails['gCols'].' columns';
				} else {
					$summary = 'Static';
				}
				$item['summary'] = $summary;
				
				if (!checkRowExists('content_types', array('default_layout_id' => $id)) && !checkRowExists('content_item_versions', array('layout_id' => $id))) {
					$item['traits']['deletable'] = true;
				
				}
				
				$item['usage_status'] = $item['usage_count'];
				
				// Try to automatically add a thumbnail
				if (!empty($foundPaths[$item['path']])) {
					$item['image'] = 'zenario/admin/grid_maker/ajax.php?thumbnail=1&width=180&height=130&loadDataFromLayout='. $id. '&checksum='. $foundPaths[$item['path']]['checksum'];
					$item['list_image'] = 'zenario/admin/grid_maker/ajax.php?thumbnail=1&width=24&height=23&loadDataFromLayout='. $id. '&checksum='. $foundPaths[$item['path']]['checksum'];
				}
				
			//Non-numeric ids are the Family and Filenames of Template Files that have no layouts created
			} else {
				$item['name'] = str_replace('.tpl.php', '', $item['template_filename']);
				$item['usage_status'] = $item['status'];
				$item['traits']['unregistered'] = true;
			}
		}
		
		break;
	
	
	case 'zenario__layouts/panels/skins':
		require_once CMS_ROOT. 'zenario/admin/grid_maker/grid_maker.inc.php';
		
		if (($refinerName == 'template_family' || $refinerName == 'template_family__panel_above')
		 && $templateFamily = decodeItemIdForStorekeeper(get('refiner__template_family'))) {
			$panel['title'] = adminPhrase('Skins in the template directory "[[family]]"', array('family' => $templateFamily));
			$panel['no_items_message'] = adminPhrase('There are no skins for this template directory.');
			unset($panel['columns']['family_name']['title']);
		
		} elseif ($refinerName == 'usable_in_template_family'
		 && $templateFamily = decodeItemIdForStorekeeper(get('refiner__usable_in_template_family'))) {
			$panel['title'] = adminPhrase('Skins in the template directory "[[family]]"', array('family' => $templateFamily));
			$panel['no_items_message'] = adminPhrase('There are no usable skins for this template directory.');
			unset($panel['columns']['family_name']['title']);
		}
		
		foreach ($panel['items'] as &$item) {
			$status = '';
			if ($item['missing'] && $item['usage_layouts']) {
				$status = adminPhrase('Skin is missing from the file system but is referred to by some layouts');
			} elseif (!$item['missing'] && $item['usage_layouts']) {
				$status = adminPhrase('Skin was found in the file system and is referred to by some layouts');
			} elseif ($item['missing'] && !$item['usage_layouts']) {
				$status = adminPhrase('Skin is missing from the file system and is not referred to by any layouts');
			} elseif (!$item['missing'] && !$item['usage_layouts']) {
				$status = adminPhrase('Skin was found in the file system but is not referred to by any layouts');
			}
			$item['status'] = $status;
			if (!$item['display_name']) {
				$item['display_name'] = $item['name'];
			}
		}
		
		break;
	
	
	case 'zenario__layouts/panels/skin_files':
		
		//Copy the contents of the readme to the help button
		require_once CMS_ROOT. 'zenario/libraries/mit/parsedown/Parsedown.php';
		$markdown = file_get_contents(CMS_ROOT. 'zenario/api/sample_skin_readme/README.txt');
		$markdownToHTML = new Parsedown();
		$panel['collection_buttons']['help']['help']['message'] = $markdownToHTML->text($markdown);
		
		
		if ($skin = getSkinFromId(get('refiner__skin'))) {
			
			$dir = getSkinPath($skin['family_name'], $skin['name']);
			$skin['subpath'] = '';
			
			if (($skin['subpath'] = get('refiner__subpath')) && ($skin['subpath'] = decodeItemIdForStorekeeper($skin['subpath'])) && (strpos($skin['subpath'], '..') === false)) {
				$panel['title'] = adminPhrase('Files for the skin "[[display_name]]" in the template directory "[[family_name]]" in the sub-directory "[[subpath]]"', $skin);
				$skin['subpath'] .= '/';
				$dir .= $skin['subpath'];
			
			} else {
				$skin['subpath'] = '';
				$panel['title'] = adminPhrase('Files for the skin "[[display_name]]" in the template directory "[[family_name]]"', $skin);
			}
			
			
			$panel['items'] = array();
			if (is_dir(CMS_ROOT. $dir)) {
				foreach (scandir(CMS_ROOT. $dir) as $file) {
					if (substr($file, 0, 1) != '.') {
						$item = array(
							'name' => $file,
							'href' => $dir. $file,
							'path' => CMS_ROOT. $dir. $file,
							'filesize' => filesize(CMS_ROOT. $dir. $file));
						
						if (is_file(CMS_ROOT. $dir. $file)) {
							if (substr($file, -4) == '.gif'
							  || substr($file, -4) == '.jpg'
							  || substr($file, -5) == '.jpeg'
							  || substr($file, -4) == '.png') {
								if ($item['filesize'] < 15000) {
									$item['list_image'] = $dir. $file;
								} else {
									$item['css_class'] = 'media_image';
								}
							}
						}
						
						if (is_dir(CMS_ROOT. $dir. $file)) {
							$item['traits']['subdir'] = true;
							$item['css_class'] = 'dropbox_files';
						} else {
							$item['link'] = false;
						}
						
						$panel['items'][encodeItemIdForStorekeeper($skin['subpath']. $file)] = $item;
					}
				}
			}
		}
		
		break;

	
	case 'zenario__content/panels/languages':
		return require funIncPath(__FILE__, 'languages.fillOrganizerPanel');
	
	
	case 'zenario__content/panels/content_types':
		foreach ($panel['items'] as $id => &$item) {
			$item['css_class'] = 'content_type_'. $item['content_type_id'];
			
			if ($item['not_enabled']) {
				$item['not_enabled'] = ' '. adminPhrase('(not enabled)');
			} else {
				$item['not_enabled'] = '';
			}
		}
		
		break;
	
	
	case 'zenario__content/panels/categories':
		$langs = getLanguages();
		foreach($langs as $lang) {
			$panel['columns']['lang_'. $lang['id']] = array('title' => $lang['id']);
		}
		
		
		foreach ($panel['items'] as $id => &$item) {
			$item['traits'] = array();
			
			if ($item['public']) {
				$item['traits']['public'] = true;
				
				foreach($langs as $lang) {
						$item['lang_'. $lang['id']] =
							getRow('visitor_phrases', 'local_text',
										array('language_id' => $lang['id'], 'code' => '_CATEGORY_'. (int) $id, 'module_class_name' => 'zenario_common_features'));
				}
				
				if ($item['landing_page_equiv_id'] && $item['landing_page_content_type']) {
					$item['landing_page'] = $item['landing_page_content_type']. '_'. $item['landing_page_equiv_id'];
					$item['frontend_link'] = linkToItem($item['landing_page_equiv_id'], $item['landing_page_content_type'], false, 'zenario_sk_return=navigation_path');
				}
			}
			
			$item['children'] = countCategoryChildren($id);
			$item['path'] = getCategoryPath($id);
		}
		
		
		if (get('refiner__parent_category')) {
			$mrg = array(
				'category' => getCategoryName(get('refiner__parent_category')));
			$panel['title'] = adminPhrase('Sub-categories of category "[[category]]"', $mrg);
			$panel['no_items_message'] = adminPhrase('Category "[[category]]" has no sub-categories.', $mrg);
		}
				
		break;
	
	
	case 'zenario__content/hidden_nav/sitemap/panel':
		foreach ($panel['items'] as &$item) {
			$item['loc'] = linkToItem($item['id'], $item['type'], true, '', $item['alias'], false, true);
			$item['lastmod'] = substr($item['lastmod'], 0, 10);
			$item['xml_tag_name'] = 'url';
			unset($item['id']);
			unset($item['type']);
			unset($item['alias']);
		}
		
		break;
	
	
	case 'zenario__content/panels/content':
	case 'zenario__content/panels/chained':
	case 'zenario__content/panels/language_equivs':
		return require funIncPath(__FILE__, 'content.fillOrganizerPanel');
	
	
	
	
	case 'zenario__content/panels/image_library':
		if (in($mode, 'full', 'select', 'quick')) {
			$panel['columns']['tags']['tag_colors'] =
			$panel['columns']['filename']['tag_colors'] = getImageTagColours($byId = false, $byName = true);
			
			foreach ($panel['items'] as $id => &$item) {
				$text = '';
				$comma = false;
				
				if ($item['in_use_anywhere']) {
					$mrg = array('used_on' => 'Used on');
				} else {
					$mrg = array('used_on' => 'Attached to');
				}
				
				$usage_content = (int)$item['usage_content'];
				$usage_plugins = (int)$item['usage_plugins'];
				$usage_menu_nodes = (int)$item['usage_menu_nodes'];
				$contentUsage = $usage_content + $usage_plugins + $usage_menu_nodes;
				if ($contentUsage === 1) {
					if ($usage_content === 1) {
						$sql = '
							SELECT 
								foreign_key_id AS id, 
								foreign_key_char AS type
							FROM ' . DB_NAME_PREFIX . 'inline_images
							WHERE image_id = ' . (int)$item['id'] . '
							AND foreign_key_to = "content"
							AND archived = 0';
						$result = sqlSelect($sql);
						$row = sqlFetchAssoc($result);
						
						$mrg['tag'] = formatTag($row['id'], $row['type']);
						$text .= adminPhrase('[[used_on]] "[[tag]]"', $mrg);
					
					} elseif ($usage_plugins === 1) {
						$sql = '
							SELECT p.name, m.display_name
							FROM ' . DB_NAME_PREFIX . 'inline_images pii
							INNER JOIN ' . DB_NAME_PREFIX . 'plugin_instances p
								ON pii.foreign_key_id = p.id
								AND pii.image_id = ' . (int)$item['id'] . '
								AND pii.foreign_key_to = "library_plugin"
								AND pii.foreign_key_id != 0
							INNER JOIN ' . DB_NAME_PREFIX . 'modules m
								ON p.module_id = m.id';
						$result = sqlSelect($sql);
						$row = sqlFetchAssoc($result);
						if ($row['name'] && $row['display_name']) {
							$text = adminPhrase('Used on plugin "[[name]]" of the module "[[display_name]]"', $row);
						} else {
							$text = adminPhrase('Used on 1 plugin');
						}
					} else {
						$text = adminPhrase('Used on 1 menu node');
					}
				} elseif ($contentUsage > 1) {
					$text .= $mrg['used_on']. ' ';
					if ($usage_content > 0) {
						$text .= nAdminPhrase(
							'[[count]] content item',
							'[[count]] content items',
							$usage_content,
							array('count' => $usage_content)
						);
						$comma = true;
					}
					if ($usage_plugins > 0) {
						if ($comma) {
							$text .= ', ';
						}
						$text .= nAdminPhrase(
							'[[count]] plugin',
							'[[count]] plugins',
							$usage_plugins,
							array('count' => $usage_plugins)
						);
						$comma = true;
					}
					if ($usage_menu_nodes > 0) {
						if ($comma) {
							$text .= ', ';
						}
						$text .= nAdminPhrase(
							'[[count]] menu node',
							'[[count]] menu nodes',
							$usage_menu_nodes,
							array('count' => $usage_menu_nodes)
						);
					}
				}
				$item['all_usage_content'] = $text;
				
				$text = '';
				$usage_email_templates = (int)$item['usage_email_templates'];
				if ($usage_email_templates === 1) {
					$sql = '
						SELECT 
							e.template_name
						FROM ' . DB_NAME_PREFIX . 'inline_images ii
						INNER JOIN ' . DB_NAME_PREFIX . 'email_templates e
							ON ii.foreign_key_id = e.id
							AND ii.foreign_key_to = "email_template"
						WHERE image_id = ' . $item['id'] . '
						AND archived = 0';
					$result = sqlSelect($sql);
					$row = sqlFetchAssoc($result);
					$mrg['template_name'] = $row['template_name'];
					$text .= adminPhrase('[[used_on]] "[[template_name]]"', $mrg);
				
				} elseif ($usage_email_templates > 1) {
					$mrg['count'] = $usage_email_templates;
					$text = adminPhrase('[[used_on]] [[count]] email templates', $mrg);
				}
				$item['usage_email_templates'] = $text;
			}
		}
		
	case 'generic_image_panel':
	case 'zenario__content/panels/background_images':
	case 'zenario__content/panels/inline_images_for_content':
		
		foreach ($panel['items'] as $id => &$item) {
			
			$img = 'zenario/file.php?c='. $item['checksum'];
			
			if (!empty($panel['key']['usage']) && $panel['key']['usage'] != 'image') {
				$img .= '&usage='. rawurlencode($panel['key']['usage']);
			}
			
			if ($path == 'zenario__content/panels/image_library') {
				$item['list_image'] = $img. '&ogt=1';
			} else {
				$item['list_image'] = $img. '&ogl=1';
			}
			$item['image'] = $img. '&og=1';
			
			$classes = array();
			if (!empty($item['sticky_flag'])) {
				$classes[] = 'zenario_sticky';
			}
			if (!empty($item['privacy'])) {
				switch ($item['privacy']) {
					case 'auto':
						$classes[] = 'zenario_image_privacy_auto';
						break;
					case 'public':
						$classes[] = 'zenario_image_privacy_public';
						break;
					case 'private':
						$classes[] = 'zenario_image_privacy_private';
						break;
				}
			}
			if (!empty($classes)) {
				$item['row_css_class'] = implode(' ', $classes);
			}
			
			if (!empty($item['filename'])
			 && !empty($item['short_checksum'])
			 && !empty($item['duplicate_filename'])) {
				$item['filename'] .= ' ['. $item['short_checksum']. ']';
			}
		}
		
		break;
		
	
	case 'zenario__modules/panels/modules':
		return require funIncPath(__FILE__, 'modules.fillOrganizerPanel');

	
	case 'zenario__modules/panels/plugins':
		
		if (get('refiner__plugin') && !isset($_GET['refiner__all_instances'])) {
			$panel['title'] =
			$panel['select_mode_title'] =
				adminPhrase('"[[name]]" plugins in the library', array('name' => getModuleDisplayName(get('refiner__plugin'))));
			$panel['no_items_message'] =
				adminPhrase('There are no "[[name]]" plugins in the library. Click the "Create" button to create one.', array('name' => getModuleDisplayName(get('refiner__plugin'))));
		}
		
		foreach ($panel['items'] as $id => &$item) {
			$item['traits'] = array();
		
			if ($item['checksum']) {
				$img = '&c='. $item['checksum'];
				$item['traits']['has_image'] = true;
				$item['image'] = 'zenario/file.php?og=1'. $img;
				$item['list_image'] = 'zenario/file.php?ogl=1'. $img;
			} else {
				$item['image'] = getModuleIconURL($item['module_class_name']);
			}
			
			//Should archived layouts trigger the "in use" flag..?
			if ($item['usage_item']
			 || $item['usage_layouts']
			 || $item['usage_archived_layouts']) {
				$item['traits']['in_use'] = true;
			} else {
				$item['traits']['unused'] = true;
			}
			
			if ($item['usage_archived_layouts']) {
				$item['usage_layouts'] = adminPhrase('[[usage_layouts]] (and [[usage_archived_layouts]] archived)', $item);
			}

		}
		
		break;

	
	case 'zenario__modules/panels/modules/hidden_nav/view_frameworks/panel':
		
		if ($refinerName == 'module' && ($module = getModuleDetails(get('refiner__module')))) {
			$panel['title'] =
				adminPhrase('Frameworks for the Module "[[name]]"', array('name' => $module['display_name']));
			
			$panel['items'] = array();
			foreach (listModuleFrameworks($module['class_name']) as $dir => $framework) {
				$panel['items'][encodeItemIdForStorekeeper($dir)] = $framework;
			}
		}
		
		break;

	
	case 'zenario__languages/panels/languages':
		if ($mode != 'xml') {
			
			$enabledCount = 0;
			foreach ($panel['items'] as $id => &$item) {
				
				//If we're looking up a Language Name, we can't rely on the formatting that Storekeeper provides and must use the actual Language Name
				if ($mode == 'get_item_name') {
					$item['name'] = getLanguageName($id, $addIdInBracketsToEnd = true);
				
				} elseif (!$item['enabled']) {
					$item['traits'] = array('not_enabled' => true);
				
				} else {
					$item['traits'] = array('enabled' => true);
					++$enabledCount;
					
					if (allowDeleteLanguage($id)) {
						$item['traits']['can_delete'] = true;
					}
					
					$cID = $cType = false;
					if (langSpecialPage('zenario_home', $cID, $cType, $id, true)) {
						$item['frontend_link'] = linkToItem($cID, $cType, false, 'zenario_sk_return=navigation_path');
						$item['homepage_id'] = $cType. '_'. $cID;
						$item['traits']['has_homepage'] = true;
					}
				}
			}
			
			if ($enabledCount < 2) {
				unset($panel['collection_buttons']['default_language']);
			}
			
			//If a language specific domain is in use, show that column by default. Otherwise hide it.
			$langSpecificDomainsUsed = checkRowExists('languages', array('domain' => array('!' => '')));
			if ($langSpecificDomainsUsed) {
				$panel['columns']['domain']['show_by_default'] = true;
			}
			
			
			
			$maxEnabledLanguageCount = siteDescription('max_enabled_languages');
			$enabledLanguages = getLanguages();
			if ($maxEnabledLanguageCount && (count($enabledLanguages) >= $maxEnabledLanguageCount)) {
				if ($maxEnabledLanguageCount == 1) {
					unset($panel['collection_buttons']['create']);
				}
				if (isset($panel['collection_buttons']['add'])) {
					$panel['collection_buttons']['add']['css_class'] = '';
					$panel['collection_buttons']['add']['disabled'] = true;
					if ($maxEnabledLanguageCount == 1) {
						$message = adminPhrase('
						<p>
							This Community CMS allows one language per site. To make this site multi-lingual, please upgrade to Pro. 
						</p><p>
							Otherwise you will need to reset your site if you want to change to a different language.
						</p>'
						);
					} else {
						$message = adminPhrase('The maximun number of enabled languages on this site is [[count]]', array('count' => $maxEnabledLanguageCount));
					}
					$panel['collection_buttons']['add']['disabled_tooltip'] = $message;
				}
				unset($panel['item_buttons']['add_language']);
			}
		}
		
		break;
		
	
	case 'zenario__languages/panels/phrases':
		$languages = getLanguages(false, true, true);
		$additionalLanguages = count($languages) - 1;
		
		foreach ($panel['items'] as $id => &$item) {
			
			//For each item, check to see if there is a translation for each language
			$translations = false;
			$missingTranslations = false;
			foreach ($languages as $langId => $lang) {
				if (isset($item[$langId]) && $item[$langId] != '') {
					$translations = true;
				
				//If a language does not have the translate_phrases flag set, then as long as this isn't
				//a phrase code, all it to just use the phrase untranslated.
				} elseif (empty($lang['translate_phrases']) && substr($item['code'], 0, 1) != '_') {
					$item[$langId] = $item['code'];
				
				} else {
					$missingTranslations = true;
				}
			}
			
			//Task #9611 Change the icon in the phrases panel to help when creating a module's phrase
			if ($additionalLanguages) {
				if ($missingTranslations) {
					if ($translations) {
						$item['css_class'] = 'phrase_partially_translated';
						$item['tooltip'] = adminPhrase('This phrase has been translated into some site languages, click "Edit phrase" to add missing translations.');
		
					} else {
						$item['css_class'] = 'phrase_not_translated';
						$item['tooltip'] = adminPhrase('This phrase has not been translated into all site languages, click "Edit phrase" to add translations.');
					}
				} else {
					$item['css_class'] = 'phrase_translated';
					$item['tooltip'] = adminPhrase('This phrase has been translated into all site languages.');
				}
			}
		}
		
		break;
		
	
	case 'zenario__users/panels/administrators':
		foreach ($panel['items'] as $id => &$item) {
			
			$item['traits'] = array();
			$item['has_permissions'] = checkRowExists('action_admin_link', array('admin_id' => $id));
			
			if ($id == session('admin_userid')) {
				$item['traits']['current_admin'] = true;
			}
			
			if ($item['authtype'] == 'super') {
				$item['traits']['super'] = true;
			} else {
				$item['traits']['local'] = true;
			}
			
			if ($item['status'] == 'active') {
				$item['traits']['active'] = true;
			} else {
				$item['traits']['trashed'] = true;
			}
			
			if (!empty($item['checksum'])) {
				$item['traits']['has_image'] = true;
				$img = '&usage=admin&c='. $item['checksum'];
	
				$item['image'] = 'zenario/file.php?og=1'. $img;
				$item['list_image'] = 'zenario/file.php?ogl=1'. $img;
			}
			
		}
		
		if ($refinerName == 'trashed') {
			$panel['trash'] = false;
			$panel['title'] = adminPhrase('Trashed Administrators');
			$panel['no_items_message'] = adminPhrase('No Administrators have been Trashed.');
		
		} else {
			$panel['trash']['empty'] = !checkRowExists('admins', array('status' => 'deleted'));
		}
		
		if (!self::canCreateAdditionalAdmins()) {
			$tooltip = adminPhrase('The maximum number of client administrators has been reached ([[i]])', array('i' => siteDescription('max_local_administrators')));
			$panel['collection_buttons']['create']['disabled'] = 
			$panel['item_buttons']['restore_admin']['disabled'] = true;
			$panel['collection_buttons']['create']['disabled_tooltip'] = 
			$panel['item_buttons']['restore_admin']['disabled_tooltip'] = $tooltip;
			$panel['collection_buttons']['create']['css_class'] = '';
		}
		
		break;


	case 'zenario__administration/panels/backups':
		
		if ($errorsAndWarnings = initialiseBackupFunctions(true)) {
			$panel['no_items_message'] = '';
			foreach ($errorsAndWarnings as $errorOrWarning) {
				$panel['no_items_message'] .= $errorOrWarning . '<br />';
			}
			
			$panel['no_items_message'] = str_replace('<br />', "\n", $panel['no_items_message']);
			$panel['collection_buttons'] = false;
			return;
		}
		
		if (file_exists($dirpath = setting('backup_dir'))) {
			$panel['items'] = array();
			foreach (scandir($dirpath) as $i => $file) {
				if (is_file($dirpath. '/'. $file) && substr($file, 0, 1) != '.') {
					$panel['items'][encodeItemIdForStorekeeper($file)] = array('filename' => $file, 'size' => filesize($dirpath. '/'. $file));
				}
			}
		}
		
		break;


	case 'zenario__administration/panels/file_types':
		foreach ($panel['items'] as &$item) {
			if ($item['custom']) {
				$item['traits'] = array('custom' => true);
			}
		}
		
		break;

}

return false;
