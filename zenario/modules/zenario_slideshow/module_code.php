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

class zenario_slideshow extends zenario_plugin_nest {
	
	var $lastTabNum = 0;
	
	public function init() {
		//Flag that this plugin is actually a slideshow
		ze::$slotContents[$this->slotName]['is_nest'] = true;
		ze::$slotContents[$this->slotName]['is_slideshow'] = true;
		
		$this->allowCaching(
			$atAll = true, $ifUserLoggedIn = false, $ifGetSet = false, $ifPostSet = false, $ifSessionSet = true, $ifCookieSet = true);
		$this->clearCacheBy(
			$clearByContent = false, $clearByMenu = false, $clearByUser = false, $clearByFile = false, $clearByModuleData = false);
		
		//Revert to normal nest behaviour when showing one specific Egg for the showFloatingBox/showRSS methods
		if ($this->specificEgg()) {
			return zenario_plugin_nest::init();
		
		//When a Nest is first inserted, it will be empty.
		//If the Nest is empty, call the resyncNest function just in case being empty is not a valid state.
		} elseif (ze\priv::check() && !ze\row::exists('nested_plugins', ['instance_id' => $this->instanceId])) {
			self::resyncNest($this->instanceId);
		}
		
		
		$this->loadTabs();
		
		if (empty($this->slides)) {
			//Don't show anything if not slides have been created
			return false;
		}
		
		foreach ($this->slides as &$slide) {
			if ($this->loadTab($slide['slide_num'])) {
				$this->show = true;
				$this->slideNum =
				$this->lastTabNum = $slide['slide_num'];
			}
		}
		
		
		$firstTabNum = $this->editingTabNum? $this->editingTabNum : false;
		
		$tabOrd = 0;
		foreach ($this->slides as &$slide) {
			++$tabOrd;
			
			$link = $this->tabLink($tabOrd);
			
			if (!isset($this->sections['Tab'])) {
				$this->sections['Tab'] = [];
			}
			
			$this->sections['Tab'][$slide['slide_num']] = [
				'TAB_ORDINAL' => $tabOrd,
				'Class' => 'tab_'. $tabOrd. ' tab',
				'Slide_Class' => 'slide_'. $slide['slide_num']. ' '. $slide['css_class'],
				'Tab_Link' => $link,
				'Tab_Name' => $this->formatTitleText($slide['slide_label'], true)
			];
			
			if (!$firstTabNum) {
				$firstTabNum = $slide['slide_num'];
			}
		}
		
		if (isset($this->sections['Tab'][$firstTabNum]['Class'])) {
			$this->sections['Tab'][$firstTabNum]['Class'] .= '_on';
		}
		
		//Catch the unusual case where someone wants a slideshow, but has only defined one slide,
		//so there are no transistions to animate
		if ($tabOrd < 2) {
			$this->mergeFields['Next_Link'] = '';
			$this->mergeFields['Next_Disabled'] = '_disabled';
			$this->mergeFields['Prev_Link'] = '';
			$this->mergeFields['Prev_Disabled'] = '_disabled';
		} else {
			$this->mergeFields['Next_Link'] = 'href="#" onclick="return zenario_slideshow.next(this);"';
			$this->mergeFields['Next_Disabled'] = '';
			$this->mergeFields['Prev_Link'] = 'href="#" onclick="return zenario_slideshow.prev(this);"';
			$this->mergeFields['Prev_Disabled'] = '';
			$this->startSlideshow();
		}
		
		$this->showInFloatingBox(false);
		
		return $this->show;
	}
	
	protected function tabLink($tabOrd) {
		$link = 'href="#"';
		
		if ($this->setting('use_tab_clicks')) {
			$link .= ' onclick="return zenario_slideshow.page(this, '. ($tabOrd-1). ');"';
		} else {
			$link .= ' onclick="return false;"';
		}
		
		if ($this->setting('use_tab_hover')) {
			$link .= ' onmouseover="zenario_slideshow.page(this, '. ($tabOrd-1). ', true);"';
			
			if ($this->setting('use_timeout')) {
				$link .= ' onmouseout="zenario_slideshow.resume(this);"';
			}
		}
		
		return $link;
	}
	
	protected function startSlideshow() {
		
		if ($animationLibrary = $this->setting('animation_library')) {
			
			if ($animationLibrary == 'cycle2') {
				ze::requireJsLib('zenario/libs/yarn/jquery-cycle2/build/jquery.cycle2.min.js');
			}
			
			$opt = [
				'timeout' => $this->setting('use_timeout')? (int) $this->setting('timeout') : 0,
				'pause' => $this->setting('use_timeout')? (int) $this->setting('pause') : 0,
				'next_prev_buttons_loop' => (bool) $this->setting('next_prev_buttons_loop'),
				$this->editingTabNum !== false? $this->editingTabNum - 1 : 0
			];
			
			switch ($animationLibrary) {
				case 'cycle2':
					$opt['fx'] = $this->setting('cycle2_fx');
					$opt['sync'] = (bool) $this->setting('cycle2_sync');
					$opt['speed'] = (int) $this->setting('cycle2_speed');
					break;
				
				case 'roundabout':
					$opt['shape'] = $this->setting('shape');
					$opt['tilt'] = (float) $this->setting('tilt');
					$opt['speed'] = (int) $this->setting('roundabout_speed');
					break;
			}
			
				
			$this->callScript('zenario_slideshow', 'show',
				'zenario_'. $animationLibrary. '_interface',
				$this->containerId,
				$opt,
				$this->editingTabNum !== false? $this->editingTabNum - 1 : 0
			);
		}
	}

	
	public function showSlot() {
		
		//Show a single plugin in the nest
		if ($this->checkShowInFloatingBoxVar()) {
			if ($this->show) {
				
				$ord = 0;
				foreach ($this->modules[$this->slideNum] as $id => $slotNameNestId) {
					$this->mergeFields['PLUGIN_ORDINAL'] = ++$ord;
					
					if (!empty(ze::$slotContents[$slotNameNestId]['class'])) {
						if (ze::$slotContents[$slotNameNestId]['class']->checkShowInFloatingBoxVar()) {
							$this->showPlugin($slotNameNestId);
						}
					}
				}
			}
		
		//Show all of the plugins on this slide
		} else {
			
			$this->mergeFields['Tabs'] = $this->sections['Tab'] ?? null;
			
			if ($this->show) {
				$hide = false;
				foreach ($this->slides as &$slide) {
					$slideNum = $slide['slide_num'];
					$this->mergeFields['Tabs'][$slideNum]['Plugins'] = $this->modules[$slideNum];
					$this->mergeFields['Tabs'][$slideNum]['Hidden'] = $hide;
					
					if ($this->setting('animation_library')) {
						//Hide the slides after slide one, until the jQuery slideshow Plugin kicks in and overrides this.
						$hide = true;
					}
				}
			}
			$this->twigFramework($this->mergeFields);
		}
	}
	
	
	
	public function fillAdminSlotControls(&$controls) {
		zenario_plugin_nest::fillAdminSlotControls($controls);
	}






	public function returnWhatThisEggIs() {
		return \ze\admin::phrase('This is a plugin in a slideshow');
	}
	
	public function returnWhatThisIs() {
		if (isset($this->parentNest)) {
			return $this->parentNest->returnWhatThisEggIs();
		
		//Don't show a description for the slideshow if there are already plugins in it
		} elseif (!empty($this->modules[$this->slideNum])) {
			return '';
		
		} elseif ($this->slotLevel == 2) {
			return \ze\admin::phrase('This is a slideshow on the layout');
		
		} else {
			return \ze\admin::phrase('This is a slideshow on the content item');
		}
	}
}
