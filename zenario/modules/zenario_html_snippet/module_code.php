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




// This plugin shows some static content


class zenario_html_snippet extends ze\moduleBaseClass {

	protected $mergeFields = [];
	protected $sections = [];
	protected $empty = false;
	protected $raw_html = '';
	protected $enablePhraseCodeReplace = true;
	
	const JS_PREFIX = '(function(slotName, containerId) {';
	const JS_SUFFIX = '})';
	
	//When the plugin is set up, also get the content item's status and the content section to display
	function init() {
		$this->allowCaching(
			$atAll = true, $ifUserLoggedIn = true, $ifGetSet = true, $ifPostSet = true, $ifSessionSet = true, $ifCookieSet = true);
		$this->clearCacheBy(
			$clearByContent = false, $clearByMenu = false, $clearByUser = false, $clearByFile = false, $clearByModuleData = false);
		
		$this->raw_html = $this->setting('html');
		
		//If there is no content then don't display the plugin in visitor mode, except if this is in a Nest
		if (!$this->raw_html && !$this->eggId) {
			$this->empty = true;
			return false;
		}
		
		$this->frameworkOutputted = true;
		
		//Check to see if consent cookies is needed/required
		switch ($this->setting('cookie_consent')) {
			case 'needed':
				if (!ze\cookie::canSet('necessary')) {
					$this->raw_html = '';
					return false;
				}
				break;
			case 'specific_types':
				$cookieType = $this->setting('cookie_consent_specific_cookie_types');
				if (!(ze::in($cookieType, 'functionality', 'analytics', 'social_media') && ze\cookie::canSet($cookieType))) {
					$this->raw_html = '';
					return false;
				}
				break;
		}
		
		if (!$this->isVersionControlled && $this->enablePhraseCodeReplace) {
			$this->replacePhraseCodesInString($this->raw_html);
		}
		
		return true;
	}
	
	
	function showSlot() {
		if (ze::$isTwig) return;
		
		$this->showContent();
	}
	
	
	function showContent() {
		if (ze::$isTwig) return;
		
		$javascript = '';
		if ($this->setting('hide_in_admin_mode') && ze\priv::check()) {
			echo '<p>&nbsp;</p>';
		} else {
			echo $this->raw_html;
			$this->addJSOnAJAX();
		}
		
	}

	public function addToPageFoot() {
		if (ze::$isTwig) return;
		
		$javascript = '';
		if ($this->setting('hide_in_admin_mode') && ze\priv::check()) {
		
		} elseif ($this->hasJS($javascript)) {
			echo '
<script type="text/javascript"';
			
			if (ze::setting('defer_js')) {
				echo ' defer';
			}
			
			echo '>', $javascript, '
</script>';
		}
	}
	
	protected function addJSOnAJAX() {
		if ($this->methodCallIs('refreshPlugin')
		 && $this->hasJS($javascript)) {
			$this->callScript('window', 'eval', $javascript);
		}
	}
	
	protected function hasJS(&$javascript) {
		if ($javascript = trim($this->setting('javascript'))) {
			$javascript = self::JS_PREFIX. "\n". $javascript. "\n". self::JS_SUFFIX;
		
		} else {
			$javascript = '';
			return false;
		}
		
		$javascript .= '('. json_encode($this->slotName). ', '. json_encode($this->containerId). ');';
		return true;
	}
	
	public function formatAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		//...
	}

}
