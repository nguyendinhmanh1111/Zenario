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

header('Content-Type: text/css; charset=UTF-8');
require '../basicheader.inc.php';


//Get a list of module ids from the url
$modules = array_unique(explode(',', $_GET['ids'] ?? ''));
$moduleDetails = [];

//Ensure that the site name and subdirectory are part of the ETag, as modules can have different ids on different servers
$ETag = 'zenario-module-css-'. LATEST_REVISION_NO. '--'. $_SERVER["HTTP_HOST"]. '-';

//Add the id of each running Plugin to the ETag
foreach ($modules as $moduleId) {
	if ($moduleId) {
		$ETag .= '-'. (int) $moduleId;
	}
}

if (isset($_GET['admin']) && $_GET['admin']) {
	$ETag .= '--admin';
}

//Cache this combination of running Module CSS
ze\cache::useBrowserCache($ETag);
ze\cache::start();


//Run pre-load actions

if (ze::$canCache) require CMS_ROOT. 'zenario/includes/wrapper.pre_load.inc.php';


ze\db::loadSiteConfig();


//Get a list of each Module
foreach ($modules as $moduleId) {
	if ($moduleId) {
		$module = ze\module::details($moduleId);
		$moduleDetails[$module['class_name']] = $module;
	}
}

//Add Plugin inheritances as well
foreach (array_keys($moduleDetails) as $moduleClassName) {
	foreach (ze\module::inheritances($moduleDetails[$moduleClassName]['class_name'], 'include_javascript', false) as $inheritanceClassName) {
		if (empty($moduleDetails[$inheritanceClassName])) {
			$moduleDetails[$inheritanceClassName] = ze\module::details($inheritanceClassName, $fetchBy = 'class');
		}
	}
}

//Try to put the modules in dependency order (this is needed, as the order of CSS rules can be important)
$moduleDetails = array_reverse($moduleDetails, true);

if (!empty($_GET['admin_frontend'])) {
	$files = ['admin_frontend.css'];

} elseif (!empty($_GET['organizer'])) {
	$files = ['storekeeper.css', 'organizer.css'];

} else {
	exit;
}


//Add JavaScript support elements for each Plugin on the page
foreach ($moduleDetails as $module) {
	
	$path = ze::moduleDir($module['class_name'], 'adminstyles/');
	if ($chop = ze\ring::chopPrefix('zenario/', $path)) {
		$url = '../'. $chop;
	
	} else
	if (ze\ring::chopPrefix('zenario_extra_modules/', $path)
	 || ze\ring::chopPrefix('zenario_custom/modules/', $path)) {
		$url = '../../'. $path;
	} else {
		$url = false;
	}
	
	foreach ($files as $file) {
		//Check if there's a stylesheet there
		if (is_file($filePath = CMS_ROOT. $path. $file)) {
			//Include the contents of the file, being careful to correct for the fact that the relative path for images will be wrong
			if ($url) {
				echo preg_replace('/url\(([\'\"]?)([^:]+?)\)/i', 'url($1'. $url. '$2)', file_get_contents($filePath));
			} else {
				echo file_get_contents($filePath);
			}
		
			echo "\n/**/\n";
		}
	}
}


//Run post-display actions
if (ze::$canCache) require CMS_ROOT. 'zenario/includes/wrapper.post_display.inc.php';