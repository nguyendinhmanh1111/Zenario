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


if (ze::$canCache) {
	
	function zenarioPageCacheDir(&$requests, $type) {
		$type = str_replace(['.', ' '], '-', $type. '-'. ze\cache::browserBodyClass(). '-');
		
		$text = json_encode($requests);
		return $type. substr(preg_replace('/[^\w_]+/', '-', $text), 1, 33). ze::hash64($text, 16). '-';
	}
	
	
	$type = str_replace(['.wrapper', '.php'], '', basename($_SERVER['PHP_SELF']));
	$chFile = zenarioPageCacheDir($_GET, $type);
	
	if (file_exists(($chPath = 'cache/pages/'. $chFile. '/'). $type)) {
		touch($chPath. 'accessed');
		
		ze\cache::start();
		readfile($chPath. $type);
		echo "\n/* ", $chPath. $type, ' was displayed from the page cache */';
		exit;
	}
	
	unset($type);
	unset($chFile);
	
	//Make sure we're recording the output, if we expect to write it to the cache dorectory later
	ob_start();
}