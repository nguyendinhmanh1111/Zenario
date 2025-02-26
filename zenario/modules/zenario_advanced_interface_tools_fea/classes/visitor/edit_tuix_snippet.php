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


class zenario_advanced_interface_tools_fea__visitor__edit_tuix_snippet extends zenario_advanced_interface_tools_fea {
	
	protected $idVarName = 'id';

	public function init() {
		
		$this->registerPluginPage();
	    
	    if (ze\user::can('edit', 'pluginSetting')) {
			$this->includeEditor();
			$this->runVisitorTUIX();
			return true;
		} else {
			return ZENARIO_403_NO_PERMISSION;
		}
	}
	
	
	public function returnVisitorTUIXEnabled($path) {
		return true;
	}
	
	
	public function fillVisitorTUIX($path, &$tags, &$fields, &$values) {
		parent::fillVisitorTUIX($path, $tags, $fields, $values);
		$this->translatePhrasesInTUIX($tags, $path);
		
		
		switch ($this->getMode()) {
			case 'create_tuix_snippet':
				$tags['key']['id'] = false;
				break;
			case 'edit_tuix_snippet':
				
				$tags['key']['id'] = ze::$vars[$this->idVarName];
				$details = ze\row::get('tuix_snippets', true, $tags['key']['id']);
				
				$values['details/name'] = $details['name'];
				$values['details/custom_yaml'] = $details['custom_yaml'];
				$values['details/custom_json'] = $details['custom_json'];
				
				$values['details/last_updated'] = ze\user::formatLastUpdated($details);
				
				break;
		}
	}
	
	public function formatVisitorTUIX($path, &$tags, &$fields, &$values, &$changes) {
		//...
	}
	
	public function validateVisitorTUIX($path, &$tags, &$fields, &$values, &$changes, $saving) {
		
		//Compile the YAML that the user entered into JSON
		//(N.b. JSON is a lot quicker to parse and read than YAML,
		// so I'm saving a JSON copy for efficiency's sake.)
		if (empty(trim($values['details/custom_yaml']))) {
			$values['details/custom_json'] = '';
		
		
		} elseif (ze\tuix::mixesTabsAndSpaces($values['details/custom_yaml'])) {
			$tags['tabs']['details']['errors'][] = $this->phrase('Your YAML code contains a mixture of tabs and spaces for indentation and cannot be read.');
		
		} else {
			//Hack to try and catch any errors/notices in Spyc by grabbing the output
			ob_start();
				try {
					$tuix = \Spyc::YAMLLoadString(trim($values['details/custom_yaml']));
					$values['details/custom_json'] = json_encode($tuix, JSON_FORCE_OBJECT);
			
				} catch (\Exception $e) {
					$tags['tabs']['details']['errors'][] = $e->getMessage();
					$values['details/custom_json'] = '';
				}
			
			if ($error = ob_get_clean()) {
				$tags['tabs']['details']['errors'][] = $error;
			}
		}
		
		//Check the name is unique
		if ($values['details/name'] && ze\row::exists('tuix_snippets', ['name' => $values['details/name'], 'id' => ['!' =>  $tags['key']['id']]])) {
			$fields['details/name']['error'] = $this->phrase('Please enter a unique name; [[name]] is already in use.', $values);
		}
	}
	
	public function saveVisitorTUIX($path, &$tags, &$fields, &$values, &$changes) {
	
		$details = [
			'name' => $values['details/name'],
			'custom_yaml' => $values['details/custom_yaml'],
			'custom_json' => $values['details/custom_json']
		];
		
		ze\user::setLastUpdated($details, !$tags['key']['id']);
		
		$tuixSnippetId = ze\row::setAndMarkNew('tuix_snippets', $details, $tags['key']['id']);
		
		$tags['key']['id'] = $tuixSnippetId;
		
		if (!empty($fields['details/submit']['pressed'])) {
			$tags['go'] = [
				'mode' => 'list_tuix_snippets',
				'command' => ['submit', 'back'],
				$this->idVarName => $tags['key']['id']
			];
		}
	}
	
	
}
