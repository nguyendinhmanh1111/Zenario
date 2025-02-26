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


namespace ze;

class moduleAPI {
	
	
	  ////////////////////////////////////
	 //  Plugin Environment Variables  //
	////////////////////////////////////
	
	protected $cID;
	protected $containerId;
	protected $cType;
	protected $cVersion;
	protected $eggId;
	protected $fieldInfo;
	protected $instanceId;
	protected $instanceName;
	protected $inLibrary;
	protected $isVersionControlled;
	protected $isWireframe;
	protected $beingDisplayed;
	protected $moduleId;
	protected $moduleClassName;
	protected $moduleClassNameForPhrases;
	protected $slotName;
	protected $slotLevel;
	protected $slideId;
	protected $parentNest;
	private $slotNameNestId;
	
	
	
	
	  //////////////////////////////////
	 //  Core Environment Variables  //
	//////////////////////////////////

	//These are in the ze class, e.g. \ze::$cID
//	public static $equivId;
//	public static $cID;
//	public static $cType;
//	public static $cVersion;
//	public static $adminVersion;
//	public static $visitorVersion;
//	public static $isDraft;
//	public static $locked;
//	public static $alias;
//	public static $status;
//	public static $langId;
//	public static $adminId;
//	public static $userId;
//	public static $skinId;
//	public static $layoutId;
	
	
	
	
	  /////////////////////////////
	 //  Environment Functions  //
	/////////////////////////////
	
	public final function allowCaching(
		$atAll, $ifUserLoggedIn = true, $ifGetSet = true, $ifPostSet = true, $ifSessionSet = true, $ifCookieSet = true
	) {
		$vs = &\ze::$slotContents[$this->slotNameNestId]['cache_if'];
		
		foreach (['a' => $atAll, 'u' => $ifUserLoggedIn, 'g' => $ifGetSet, 'p' => $ifPostSet, 's' => $ifSessionSet, 'c' => $ifCookieSet] as $if => $set) {
			if (!isset($vs[$if])) {
				$vs[$if] = true;
			}
			$vs[$if] = $vs[$if] && $vs['a'] && $set;
		}
	}
	
	public final function clearCacheBy(
		$clearByContent = false, $clearByMenu = false, $clearByUser = false, $clearByFile = false, $clearByModuleData = false
	) {
		$vs = &\ze::$slotContents[$this->slotNameNestId]['clear_cache_by'];
		
		foreach (['content' => $clearByContent, 'menu' => $clearByMenu, 'user' => $clearByUser, 'file' => $clearByFile, 'module' => $clearByModuleData] as $if => $set) {
			if (!isset($vs[$if])) {
				$vs[$if] = false;
			}
			$vs[$if] = $vs[$if] || $set;
		}
	}
	
	public final function callScriptBeforeAJAXReload($className, $scriptName /*[, $arg1 [, $arg2 [, ... ]]]*/) {
		$args = func_get_args();
		$this->zAPICallScriptWhenLoaded(0, $args);
	}
	
	public final function callScriptBeforeFoot($className, $scriptName /*[, $arg1 [, $arg2 [, ... ]]]*/) {
		$args = func_get_args();
		$this->zAPICallScriptWhenLoaded(1, $args);
	}
	
	public final function callScript($className, $scriptName /*[, $arg1 [, $arg2 [, ... ]]]*/) {
		$args = func_get_args();
		$this->zAPICallScriptWhenLoaded(2, $args);
	}
	
	public final function jQueryBeforeAJAXReload(/*[, $arg1 [, $arg2 [, ... ]]]*/) {
		$args = func_get_args();
		array_unshift($args, '.');
		$this->zAPICallScriptWhenLoaded(0, $args);
	}
	
	public final function jQueryBeforeFoot(/*[, $arg1 [, $arg2 [, ... ]]]*/) {
		$args = func_get_args();
		array_unshift($args, '.');
		$this->zAPICallScriptWhenLoaded(1, $args);
	}
	
	public final function jQuery(/*[, $arg1 [, $arg2 [, ... ]]]*/) {
		$args = func_get_args();
		array_unshift($args, '.');
		$this->zAPICallScriptWhenLoaded(2, $args);
	}
	
	public final function requireJsLib($lib, $stylesheet = null) {
		\ze::requireJsLib($lib, $stylesheet);
	}
	
	//Deprecated, please use one of the above
	protected final function callScriptAdvanced($beforeAJAXReload, $className, $scriptName /*[, $arg1 [, $arg2 [, ... ]]]*/) {
		$args = func_get_args();
		array_splice($args, 0, 1);
		$this->zAPICallScriptWhenLoaded($beforeAJAXReload? 0 : 2, $args);
	}
	
	public final function cache($methodName, $expiryTimeInSeconds = 600, $request = '') {
		return require \ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	public final function checkPostIsMine() {
		return !empty($_POST) && (empty($_POST['containerId']) || $_POST['containerId'] == $this->containerId);
	}
	public final function checkRequestIsMine() {
		return !empty($_REQUEST) && (empty($_REQUEST['containerId']) || $_REQUEST['containerId'] == $this->containerId);
	}

	public final function clearCache($methodName, $request = '', $useLike = false) {
		require \ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	public final function getCIDAndCTypeFromSetting(&$cID, &$cType, $setting, $getLanguageEquivalent = true) {
		if (\ze\content::getCIDAndCTypeFromTagId($cID, $cType, $this->setting($setting))) {
			if ($getLanguageEquivalent) {
				$inId = $cID;
				$inType = $cType;
				if (\ze\content::langEquivalentItem($cID, $cType, false, true)) {
					return true;
				}
				$cID = $inId;
				$cType = $inType;
			}
			
			return (\ze::isAdmin() || \ze\content::publishedVersion($cID, $cType));
		}
		
		return false;
	}
	
	public final function setting($name) {
		return isset($this->zAPISettings[$name])? $this->zAPISettings[$name] : false;
	}
	
	protected final function setSetting(
		$name, $value,
		$changeInDB, $isContent = false, $format = 'text',
		$foreignKeyTo = null, $foreignKeyId = 0, $foreignKeyChar = '', $danglingCrossReferences = 'remove'
	) {
		$this->zAPISettings[$name] = $value;
		
		if ($changeInDB) {
			\ze\row::set('plugin_settings',
				[
					'value' => $value,
					'is_content' => $this->isVersionControlled? ($isContent? 'version_controlled_content' : 'version_controlled_setting') : 'synchronized_setting',
					'format' => $format,
					'foreign_key_to' => $foreignKeyTo,
					'foreign_key_id' => $foreignKeyId,
					'foreign_key_char' => $foreignKeyChar,
					'dangling_cross_references' => $danglingCrossReferences],
				['name' => $name, 'instance_id' => $this->instanceId, 'egg_id' => $this->eggId]);
		}
	}
	
	public final function methodCallIs($method) {
		return isset($_REQUEST['method_call']) && $_REQUEST['method_call'] == $method;
	}
	
	public final function isAJAXReload() {
		return $this->methodCallIs('refreshPlugin');
	}
	
	
	
	
	  ///////////////////////////
	 //  Framework Functions  //
	///////////////////////////
	
	public final function frameworkDir() {
		if ($this->frameworkPath) {
			return dirname($this->frameworkPath). '/';
		} else {
			return false;
		}
	}
	
	public final function frameworkBaseDir() {
		if ($this->frameworkPath) {
			return dirname($this->frameworkPath, 2). '/';
		} else {
			return false;
		}
	}
	
	private $twigEnvVars = null;
	
	//New Twig version of zenario frameworks
	protected final function twigFramework($vars = [], $return = false, $fromString = false, $fromFile = false, $addThisVar = null) {
		
		$output = null;
		
		//Get the environment variables
		if ($this->twigEnvVars === null) {
			$this->twigEnvVars = [
				//Add this plugin's environment variables
				'containerId' => $this->containerId,
				'instanceId' => $this->instanceId,
				'isVersionControlled' => $this->isVersionControlled,
				'moduleId' => $this->moduleId,
				'moduleClassName' => $this->moduleClassName,
				'slotName' => $this->slotName,
				'slotLevel' => $this->slotLevel,
				'parentNest' => $this->parentNest,
				
				//Add the CMS' environment variables
				'equivId' => \ze::$equivId,
				'cID' => \ze::$cID,
				'cType' => \ze::$cType,
				'cVersion' => \ze::$cVersion,
				'isDraft' => \ze::$isDraft,
				'alias' => \ze::$alias,
				'langId' => \ze::$langId,
				'adminId' => \ze::$adminId,
				'userId' => \ze::$userId,
				'vars' => \ze::$vars
			];
		}
		
		//Add the environment variables to the input variables
		if (empty($vars)) {
			$vars = $this->twigEnvVars;
		} else {
			$vars = array_merge($this->twigEnvVars, $vars);
		}
		
		//Should we add the "this" variable?
		//By default, allow it in frameworks, but not in things like twig snippets
		if ($addThisVar === null) {
			$addThisVar = !$fromString && !$fromFile;
		}
		if ($addThisVar) {
			if (!isset($vars['this'])) {
				$vars['this'] = $this;
			}
			//Add any modules that said they should be available in Twig
			foreach (\ze::$twigModules as $className => &$class) {
				if (!isset($vars[$className])) {
					$vars[$className] = $class;
				}
			}
		}
		
		
		\ze::$isTwig = true;
		\ze::$plugin = &$this;
		$this->currentTwigVars = $vars;
		
		\ze::ignoreErrors();
			try {
				if ($fromString === false) {
					if ($fromFile === false) {
						if ($this->frameworkPath) {
							$output = \ze\twig::render($this->frameworkPath, $vars);
						} elseif ($this->framework) {
							$output = \ze\admin::phrase(
								'[[htmlRedClassStart]]Cannot display; the file [[moduleClassDir]]frameworks/[[framework]]/framework.twig.html is missing.[[htmlRedClassEnd]]',
								[
									'framework' => $this->framework,
									'moduleClassDir' => \ze::moduleDir($this->moduleClassName),
									'htmlRedClassStart' => '<span class="red">',
									'htmlRedClassEnd' => '</span>'
								]
							);
						} else {
							$output = \ze\admin::phrase("Cannot display; no framework was selected.");
						}
					} else {
						$output = \ze\twig::render($fromFile, $vars);
					}
				} else {
					$output = \ze\twig::render("\n". $fromString, $vars);
				}

				if (!$return) {
					echo $output;
					$output = null;
				}
			
			} catch (\Exception $e) {
				\ze::$canCache = false;
			
				if (\ze::isAdmin()) {
					echo
						\ze\admin::phrase('[[moduleClassName]] in [[slotName]]: [[error]] (In [[framework]] at line [[line]].)', [
							'error' => htmlspecialchars($e->getMessage()),
							'framework' => $this->frameworkPath,
							'moduleClassName' => $this->moduleClassName,
							'slotName' => $this->slotName,
							'line' => $e->getTemplateLine()
						]);
			
				} else {
					\ze\db::reportError('Twig syntax error at', $e->getMessage());
	
					if (!defined('SHOW_SQL_ERRORS_TO_VISITORS') || SHOW_SQL_ERRORS_TO_VISITORS !== true) {
						echo 'A syntax error has occured in a framework on this section of the site. Please contact a site Administrator.';
						exit;
					}
				}
			}
		\ze::noteErrors();
		
		unset($this->currentTwigVars, $this->zAPIOddOrEven);
		\ze::$isTwig = false;
		return $output;
	}
	
	private $zAPIOddOrEven;
	public function oddOrEven() {
		if ($this->zAPIOddOrEven === 'odd') {
			return $this->zAPIOddOrEven = 'even';
		} else {
			return $this->zAPIOddOrEven = 'odd';
		}
	}
	
	private $currentTwigVars;
	
	//Deprecated, frameworks are all Twig frameworks now
	protected final function frameworkIsTwig() {
		return true;
	}
	
	public final function slideNum() {
		if (isset(\ze::$slotContents[$this->slotNameNestId]['slide_num'])) {
			return \ze::$slotContents[$this->slotNameNestId]['slide_num'];
		}
	}
	
	public final function eggOrd() {
		if (isset(\ze::$slotContents[$this->slotNameNestId]['egg_ord'])) {
			return \ze::$slotContents[$this->slotNameNestId]['egg_ord'];
		}
	}
	
	public final function closeForm() {
		return '
				</form>';
	}
	
	public final function openForm(
		$onSubmit = '', $extraAttributes = '', $action = false,
		 $scrollToTopOfSlot = false, $fadeOutAndIn = true,
		 $usePost = true, $autoAddRequests = true
	) {
		
		//Don't attempt to show forms if we're not on the correct domain,
		//this will just trigger the XSS prevention script.
		//Instead, try to do a header redirect to the correct URL.
		if (\ze::$wrongDomain) {
			$req = $_GET;
			unset($req['cID'], $req['cType'], $req['instanceId'], $req['method_call'], $req['slotName']);
			$this->headerRedirect(\ze\link::toItem(\ze::$cID, \ze::$cType, true, $req));
		}
		
		$html = '
				<form method="'. ($usePost? 'post' : 'get'). '" '. $extraAttributes. '
				  onsubmit="'. htmlspecialchars($onSubmit). ' return zenario.formSubmit(this, '. \ze\ring::engToBoolean($scrollToTopOfSlot). ', '. (is_bool($fadeOutAndIn)? \ze\ring::engToBoolean($fadeOutAndIn) : ('\'' . \ze\escape::js($fadeOutAndIn) . '\'')). ', \''. \ze\escape::js($this->slotName). '\');"
				  action="'. htmlspecialchars($action ?: \ze\link::toItem(\ze::$cID, \ze::$cType, false, '', \ze::$alias, true)). '">
					'. $this->remember('cID', $this->cID). '
					'. $this->remember('slideId', $this->slideId). '
					'. $this->remember('cType', $this->cType). '
					'. $this->remember('slotName', $this->slotName). '
					'. $this->remember('instanceId', $this->instanceId). '
					'. $this->remember('containerId', $this->containerId);
		
		if ($autoAddRequests) {
			//Add important requests to the URL
			foreach(\ze::$importantGetRequests as $getRequest => $defaultValue) {
				if (isset($_REQUEST[$getRequest]) && $_REQUEST[$getRequest] != $defaultValue) {
					$html .= $this->remember($getRequest);
				}
			}
		
			//Add anything from the \ze::$vars, if they were missed from the \ze::$importantGetRequests
			foreach(\ze::$vars as $getRequest => $value) {
				if (!isset(\ze::$importantGetRequests[$getRequest]) && $value) {
					$html .= $this->remember($getRequest, $value);
				}
			}
		}
		
		return $html;
	}
	
	protected final function pagination($paginationStyleSettingName, $currentPage, $pages, &$html, &$links = [], $extraAttributes = []) {
		//Attempt to check if the named class exists, and fall back to 'pagCloseWithNPIfNeeded' if not
		$classAndMethod = explode('::', $this->setting($paginationStyleSettingName) ?: $paginationStyleSettingName, 2);
		
		if (!empty($classAndMethod[0]) && !empty($classAndMethod[1]) && \ze\module::inc($classAndMethod[0]) && method_exists($classAndMethod[0], $classAndMethod[1])) {
			$class = new $classAndMethod[0];
			$method = $classAndMethod[1];
		} else {
			$class = new \zenario_common_features;
			$method = 'pagCloseWithNPIfNeeded';
		}
		
		$class->setInstanceVariables([
			$this->cID, $this->cType, $this->cVersion, $this->slotName,
			$this->instanceName, $this->instanceId,
			$this->moduleClassName, $this->moduleClassNameForPhrases,
			$this->moduleId,
			$this->framework,
			$this->cssClass,
			$this->slotLevel, $this->isVersionControlled],
			$this->eggId, $this->slideId, $this->beingDisplayed);
		$class->$method($currentPage, $pages, $html, $links, $extraAttributes);
	}
	
	protected final function translatePhrasesInTUIX(&$tags, $path, $languageId = false, $scan = false) {
		if (empty($tags['__phrases_translated'])) {
			\ze\tuix::translatePhrases($tags, $this->zAPISettings, $path, $this->moduleClassNameForPhrases, $languageId, $scan);
			$tags['__phrases_translated'] = true;
		}
	}
	
	protected final function translatePhrasesInTUIXObjects($tagNames, &$tags, $path, $languageId = false, $scan = false) {
		\ze\tuix::translatePhrasesInObjects($tagNames, $tags, $this->zAPISettings, $path, $this->moduleClassNameForPhrases, $languageId, $scan);
	}
		
	public final function phrase($text, $replace = []) {
		
		if (isset($this->zAPISettings['phrase.framework.'. $text])) {
			$text = $this->zAPISettings['phrase.framework.'. $text];
		}
		
		return \ze\lang::phrase($text, $replace, $this->moduleClassNameForPhrases, \ze::$visLang);
	}
	
	public final function nphrase($text, $pluralText = false, $n = 1, $replace = []) {
		
		if (isset($this->zAPISettings['phrase.framework.'. $text])) {
			$text = $this->zAPISettings['phrase.framework.'. $text];
		}
		
		return \ze\lang::nphrase($text, $pluralText, $n, $replace, $this->moduleClassNameForPhrases, \ze::$visLang);
	}
	
	public final function nzphrase($zeroText, $text, $pluralText = false, $n = 1, $replace = []) {
		
		if (isset($this->zAPISettings['phrase.framework.'. $text])) {
			$text = $this->zAPISettings['phrase.framework.'. $text];
		}
		
		return \ze\lang::nzphrase($zeroText, $text, $pluralText, $n, $replace, $this->moduleClassNameForPhrases, \ze::$visLang);
	}
	
	public final function refreshPluginSlotAnchor($requests = '', $scrollToTopOfSlot = true, $fadeOutAndIn = true) {
		return
			$this->linkToItemAnchor($this->cID, $this->cType, $fullPath = false, '&slotName='. $this->slotName. ($this->slideId? '&slideId='. $this->slideId : ''). \ze\ring::urlRequest($requests)).
			' onclick="'.
				$this->refreshPluginSlotJS($requests, $scrollToTopOfSlot, $fadeOutAndIn).
				' return false;"';
	}
	
	public final function refreshPluginSlotAnchorAndJS($requests = '', $scrollToTopOfSlot = true, $fadeOutAndIn = true) {
		return [$this->refreshPluginSlotAnchor($requests, $scrollToTopOfSlot), $this->refreshPluginSlotJS($requests, $scrollToTopOfSlot, $fadeOutAndIn)];
	}
	
	public final function refreshPluginSlotJS($requests = '', $scrollToTopOfSlot = true, $fadeOutAndIn = true) {
		return 
			$this->moduleClassName.'.refreshPluginSlot('.
				'\''. $this->slotName. '\', '.
				'\''. \ze\escape::jsOnClick(\ze\ring::urlRequest($requests)). '\', '.
				($scrollToTopOfSlot? 1 : 0). ', '.
				($fadeOutAndIn? 1 : 0). ');';
	}
	
	public final function remember($name, $value = false, $htmlId = false, $type = 'hidden') {
		
		if ($value === false) {
			$value = $_REQUEST[$name] ?? false;
		}
		
		if ($htmlId === true) {
			$htmlId = $name;
		}
		
		if ($htmlId) {
			$htmlId = ' id="'. htmlspecialchars($htmlId). '"';
		}
		
		return '<input type="'. $type. '"'. $htmlId. ' name="'. htmlspecialchars($name). '" value="'. htmlspecialchars($value). '" />';
	}
	
	protected final function replacePhraseCodesInString(&$string) {
		\ze\lang::replacePhraseCodesInString($string, $this->moduleClassNameForPhrases, $languageId = false, $backtraceOffset = 3);
	}
	
	public function returnGlobalName() {
		return $this->moduleClassName. '_'. str_replace('-', '__', $this->containerId);
	}
	
	public final function returnClassName() {
		return $this->moduleClassName;
	}
	
	
	
	//The \ze::setting function was removed from Twig because we didn't want site admins to be able to access
	//it when writing Twig code in Twig Snippets.
	//However we still want plugin developers to be able to call it from their frameworks, so we'll provide
	//access to it through this class function.
	public final function siteSetting($settingName) {
		return \ze::setting($settingName);
	}
	
	
	
	
	  ////////////////////////////////
	 //  Initialization Functions  //
	////////////////////////////////
	
	//Get HTML for google reCaptcha 2.0 and init
	protected final function captcha2() {
		\ze::requireJsLib('https://www.google.com/recaptcha/api.js?render=explicit');
		$captchaId = $this->containerId . '_google_recaptcha';
		$this->callScript('zenario', 'grecaptcha', $captchaId);
		return '<div id="'. $captchaId . '"></div>';
	}
	
	//Put the google reCaptcha 2.0 library on the page and the module callback
	protected final function loadCaptcha2Lib() {
		\ze::requireJsLib('https://www.google.com/recaptcha/api.js?render=explicit');
	}
	
	//Validate google reCaptcha 2.0
	protected final function checkCaptcha2() {
		$recaptchaResponse = $_POST['g-recaptcha-response'] ?? false;
		if ($recaptchaResponse) {
			$secretKey = \ze::setting('google_recaptcha_secret_key');
			$URL = "https://www.google.com/recaptcha/api/siteverify?secret=". rawurlencode($secretKey). "&response=". rawurlencode($recaptchaResponse);
		
			$request = file_get_contents($URL);
			$response = json_decode($request, true);
		
			return is_array($response) && !empty($response['success']);
		}
		return false;
	}
	
	//Get HTML for securimage captcha
	protected final function mathCaptcha() {
		return '
			<p>
				<img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="zenario/libs/manually_maintained/mit/securimage/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="left">
				&nbsp;
				<a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById(\'siimage\').src = \'zenario/libs/manually_maintained/mit/securimage/securimage_show.php?sid=\' + Math.random(); this.blur(); return false">
					<img src="zenario/libs/manually_maintained/mit/securimage/images/refresh.png" alt="Reload Image" onclick="this.blur()" align="bottom" border="0">
				</a><br />
				' . $this->phrase('Do the maths:') . '<br />
				<input type="text" name="captcha_code" size="12" maxlength="16" class="math_captcha_input"/>
			</p>';
	}
	
	//Validate securimage captcha
	protected final function checkMathCaptcha() {
		require_once CMS_ROOT. 'zenario/libs/manually_maintained/mit/securimage/securimage.php';
		$securimage = new \Securimage();
		return isset($_POST['captcha_code']) && $securimage->check($_POST['captcha_code']) != false;
	}

	public final function forcePageReload($reload = true) {
		$this->zAPIForcePageReload($reload);
	}

	public final function headerRedirect($link) {
		$this->zAPIHeaderRedirect($link);
	}

	protected final function markSlotAsBeingEdited($beingEdited = true) {
		$this->zAPIMarkSlotAsBeingEdited($beingEdited);
	}

	public final function showInFloatingBox($showInFloatingBox = true, $floatingBoxParams = false) {
		$this->cmsApiShowInFloatingBox($showInFloatingBox, $floatingBoxParams);
	}

	protected final function scrollToTopOfSlot($scrollToTop = true) {
		$this->cmsApiScrollToTopOfSlot($scrollToTop);
	}

	public final function registerGetRequest($request, $defaultValue = '') {
		\ze::$importantGetRequests[$request] = $defaultValue;
	}
	public final function clearRegisteredGetRequest($request) {
		unset(\ze::$importantGetRequests[$request]);
	}
	
	public final function setPageTitle($title) {
		\ze::$slotContents[$this->slotName]['page_title'] = $title;
		\ze::$pageTitle = $title;
	}
	
	public final function setPageDesc($description) {
		\ze::$slotContents[$this->slotName]['page_desc'] = $description;
		\ze::$pageDesc = $description;
	}
	
	public final function setPageImage($imageId) {
		\ze::$slotContents[$this->slotName]['page_image'] = $imageId;
		\ze::$pageImage = $imageId;
	}
	
	public final function setPageKeywords($keywords) {
		\ze::$slotContents[$this->slotName]['page_keywords'] = $keywords;
		\ze::$pageKeywords = $keywords;
	}
	
	public final function setPageOGType($type) {
		\ze::$slotContents[$this->slotName]['page_og_type'] = $type;
		\ze::$pageOGType = $type;
	}

	public final function setMenuTitle($title) {
		\ze::$slotContents[$this->slotName]['menu_title'] = $title;
		\ze::$menuTitle = $title;
	}
	

	protected final function showInMenuMode($shownInMenuMode = true) {
		$this->zAPIShowInMenuMode($shownInMenuMode);
	}
	
	protected final function registerPluginPage($mode = null, $moduleClassName = null) {
		if (\ze::isAdmin() && \ze::$equivId) {
			
			$state = '';
			if (isset($this->parentNest)) {
				$state = $this->parentNest->getState();
			}
			
			\ze\row::set('plugin_pages_by_mode',
				[
					'equiv_id' => \ze::$equivId,
					'content_type' => \ze::$cType,
					'state' => substr(\ze\escape::ascii($state), 0, 2)
				], [
					'module_class_name' => $moduleClassName ?? $this->moduleClassName,
					'mode' => $mode ?? ($this->setting('mode') ?: '')
				]
			);
		}
	}
	
	
	
	
	  ///////////////////////////////
	 //  Link/Path/URL Functions  //
	///////////////////////////////
	
	public final function linkToItem(
		$cID, $cType = 'html', $fullPath = false, $request = '', $alias = false,
		$autoAddImportantRequests = false, $forceAliasInAdminMode = false
	) {
		return \ze\link::toItem($cID, $cType, $fullPath, $request, $alias, $autoAddImportantRequests, $forceAliasInAdminMode);
	}
	
	public final function linkToItemAnchor(
		$cID, $cType = 'html', $fullPath = false, $request = '', $alias = false,
		$autoAddImportantRequests = false, $forceAliasInAdminMode = false, $stayInCurrentLanguage = false
	) {
		return ' href="'. htmlspecialchars(\ze\link::toItem($cID, $cType, $fullPath, $request, $alias, $autoAddImportantRequests, $forceAliasInAdminMode, false, false, $stayInCurrentLanguage)). '"';
	}
	
	public final function linkToItemAnchorAndJS(
		$cID, $cType = 'html', $fullPath = false, $request = '', $alias = false,
		$autoAddImportantRequests = false, $forceAliasInAdminMode = false
	) {
		return [$this->linkToItemAnchor($cID, $cType, $fullPath, $request, $alias, $autoAddImportantRequests, $forceAliasInAdminMode), $this->linkToItemJS($cID, $cType, $request)];
	}
	
	public final function linkToItemJS($cID, $cType = 'html', $request = '') {
		return $this->moduleClassName. '.goToItem(\''. \ze\escape::jsOnClick($cID). '\', \''. \ze\escape::jsOnClick($cType). '\', \''. \ze\escape::jsOnClick($request). '\');';
	}
	
	public final function moduleDir($subDir = '') {
		return \ze::moduleDir($this->moduleClassName, $subDir);
	}
	
	public final function AJAXLink($requests = '') {
		return 'zenario/ajax.php?moduleClassName='. $this->moduleClassName. '&method_call=handleAJAX'. \ze\ring::urlRequest($requests);
	}
	
	//Old name of the above function, deprecated
	protected final function moduleAJAXURL($requests = '') {
		return $this->AJAXLink($requests);
	}
	
	public final function pluginAJAXLink($requests = '', $methodCall = 'handlePluginAJAX') {
		return
			\ze\link::protocol(). \ze\link::host(). SUBDIRECTORY.
			'zenario/ajax.php?moduleClassName='. $this->moduleClassName. '&method_call='. $methodCall.
			'&cID='. $this->cID.
			'&cType='. $this->cType.
			$this->pluginRequestVars().
			\ze\ring::urlRequest($requests);
	}
	
	public final function showFileLink($requests = '') {
		return
			\ze\link::protocol(). \ze\link::host(). SUBDIRECTORY.
			'zenario/ajax.php?moduleClassName='. $this->moduleClassName. '&method_call=showFile'.
			\ze\ring::urlRequest($requests);
	}
	
	public final function pluginShowFileLink($requests = '') {
		return $this->pluginAJAXLink($requests, 'showFile');
	}
	
	public final function showImageLink($requests) {
		return
			\ze\link::protocol(). \ze\link::host(). SUBDIRECTORY.
			'zenario/ajax.php?moduleClassName='. $this->moduleClassName. '&method_call=showImage'.
			\ze\ring::urlRequest($requests);
	}
	
	public final function pluginShowImageLink($requests = '') {
		return $this->pluginAJAXLink($requests, 'showImage');
	}
	
	public final function showStandalonePageLink($requests) {
		return
			\ze\link::protocol(). \ze\link::host(). SUBDIRECTORY.
			'zenario/ajax.php?moduleClassName='. $this->moduleClassName. '&method_call=showStandalonePage'.
			\ze\ring::urlRequest($requests);
	}
	
	public final function pluginShowStandalonePageLink($requests = '') {
		return $this->pluginAJAXLink($requests, 'showStandalonePage');
	}
	
	public final function showFloatingBoxLink($requests = '') {
		return
			\ze\link::protocol(). \ze\link::host(). SUBDIRECTORY.
			'zenario/ajax.php?moduleClassName='. $this->moduleClassName. '&method_call=showFloatingBox'.
			'&cID='. $this->cID.
			'&cType='. $this->cType.
			$this->pluginRequestVars().
			\ze\ring::urlRequest($requests);
	}
	
	public final function showSingleSlotLink($requests = '', $hideLayout = true) {
		return
			$this->linkToItem($this->cID, $this->cType, false, 
				'&method_call=showSingleSlot'.
				$this->pluginRequestVars().
				($hideLayout? '&hideLayout=1' : '').
				\ze\ring::urlRequest($requests),
			\ze::$alias);
	}
	

	
	public final function visitorTUIXLink($callbackFromScriptTags, $path, $requests = '', $mode = 'fill') {
		return
			\ze\link::protocol(). \ze\link::host(). SUBDIRECTORY.
			'zenario/ajax.php?moduleClassName='. $this->moduleClassName.
			'&method_call='. ($mode == 'format' || $mode == 'validate' || $mode == 'save'? $mode : 'fill'). 'VisitorTUIX'.
			'&path='. urlencode($path).
			(isset($this->parentNest)? '&state='. $this->parentNest->getState() : '&no=work').
			'&_script='. \ze\ring::engToBoolean($callbackFromScriptTags).
			\ze\ring::urlRequest($requests, true);
	}
	
	public final function pluginVisitorTUIXLink($callbackFromScriptTags, $path, $requests = '', $mode = 'fill') {
		return
			$this->visitorTUIXLink($callbackFromScriptTags, $path, $requests, $mode).
			'&cID='. $this->cID.
			'&cType='. $this->cType.
			$this->pluginRequestVars();
	}
	
	
	//Old deprecated link
	protected final function showIframeLink($requests = '', $hideLayout = false) {
		return $this->showSingleSlotLink($requests, $hideLayout);
	}
	
	public final function showRSSLink($allowFriendlyURL = false, $overwriteFriendlyURL = true) {
		$request = 'method_call=showRSS';
		
	 	//Attempt to check whether we can use Friendly URLs for the RSS links
	 	//Each page has one friendly URL to use for an RSS link.
	 	//If only one Plugin on a page uses RSS links, that Plugin will have the friendly URL.
	 	//If two Plugins on a page use RSS links, the first to call this function will have a friendly URL.
		
		if (!$allowFriendlyURL
			//Only allow the first Plugin to call this function to set the RSS link
		 || !\ze::$rss1st
	 		//Don't attempt to set the RSS link if one of the Plugins was served from the cache, as the logic isn't generated properly in this case
		 || \ze::$cachingInUse
	 		//Nested Plugins on tabs other than the first slide should not be able to set the RSS link
		 || !empty($_REQUEST['slideId'])
		 || !empty($_REQUEST['slideNum'])
	 		//Don't attempt to set the RSS link if we're only showing a specific Plugin on a page that may have more
		 || !empty($_REQUEST['slotName'])
		 || !empty($_REQUEST['instanceId'])) {
			$overwriteFriendlyURL = false;
		}
		
		if ($allowFriendlyURL
		 && (($rss = $this->eggId. '_'. $this->slotName) == \ze::$rss || $overwriteFriendlyURL)) {
			//If we are going to use a friendly URL, record the actual Instance Id and Nested Plugin Id
			\ze::$rss = $rss;
		
		} else {
			$request .= $this->pluginRequestVars();
		}
		
		//Only allow the first Plugin to call this function to set the RSS link
		if ($allowFriendlyURL) {
			\ze::$rss1st = false;
		}
		
		return \ze\link::toItem($this->cID, $this->cType, true, $request, \ze::$alias, false, true);
	}
	
	
	
	  //////////////////////////////////////////////////
	 //  Functions that interact with the conductor  //
	//////////////////////////////////////////////////


	public function conductorEnabled() {
		return isset($this->parentNest) && $this->parentNest->cEnabled();
	}
	public function conductorCommandEnabled($command) {
		return isset($this->parentNest) && $this->parentNest->cCommandEnabled($command);
	}
	public function conductorLink($command, $requests = []) {
		if (isset($this->parentNest)) {
			return $this->parentNest->cLink($command, $requests);
		}
		return false;
	}
	public function conductorOnclick($command, $requests = []) {
		if ($this->conductorCommandEnabled($command)) {
			return 'zenario_conductor.go('. json_encode($this->slotName). ', '. json_encode($command). ', '. json_encode($requests). ');';
		}
		return '';
	}
	
	

	public function conductorBackLink() {
		if (isset($this->parentNest)) {
			return $this->parentNest->cBackLink();
		} else {
			return false;
		}
	}
	
	
	
	  /////////////////////////////////////////////
	 //  Core functions that make the API work  //
	/////////////////////////////////////////////
	
	//These functions provide functionality for the API functions, and help the Core and the API
	//talk to each other.
	//They're included in this file for efficiency reasons, but Module Developers don't need to know
	//about them.
	
	//Plugin Settings
	protected $zAPISettings = [];

	//Disable AJAX Relaod
	private $zAPIForcePageReloadVar = false;
	public final function checkForcePageReloadVar() {
		return $this->zAPIForcePageReloadVar;
	}
	protected final function zAPIForcePageReload($reload) {
		$this->zAPIForcePageReloadVar = $reload;
	}
	
	//Reload to a different location
	private $zAPIHeaderRedirectLocation = false;
	protected final function zAPIHeaderRedirect($location) {
		$this->zAPIHeaderRedirectLocation = $location;
	}
	public final function checkHeaderRedirectLocation() {
		return $this->zAPIHeaderRedirectLocation;
	}

	//How to display after an AJAX reload
	private $zAPIShowInFloatingBox = false;
	private $zAPIFloatingBoxParams = false;
	public final function getFloatingBoxParams() {
		return $this->zAPIFloatingBoxParams;
	}
	public final function checkShowInFloatingBoxVar() {
		return $this->zAPIShowInFloatingBox;
	}
	protected final function cmsApiShowInFloatingBox($showInFloatingBox, $floatingBoxParams) {
		$this->zAPIShowInFloatingBox = $showInFloatingBox;
		$this->zAPIFloatingBoxParams = $floatingBoxParams;
	}
	
	private $zAPIScrollToTop = null;
	public final function checkScrollToTopVar() {
		return $this->zAPIScrollToTop;
	}
	protected final function cmsApiScrollToTopOfSlot($scrollToTop) {
		$this->zAPIScrollToTop = $scrollToTop;
	}

	//A list of JavaScript functions to run
	private $zAPIScripts = [[], [], []];
	public final function zAPICallScriptWhenLoaded($scriptType, &$script) {
		if (isset($this->zAPIMainClass)) {
			$this->zAPIMainClass->zAPICallScriptWhenLoaded($scriptType, $script);
		} else {
			$this->zAPIScripts[$scriptType][] = $script;
		}
	}
	public final function zAPICheckRequestedScripts(&$scripts) {
		$scripts = $this->zAPIScripts;
	}
	
	//Mark this Plugin as Menu-related
	private $zAPIShownInMenuMode;
	public final function shownInMenuMode() {
		return $this->zAPIShownInMenuMode;
	}
	protected final function zAPIShowInMenuMode($shownInMenuMode) {
		$this->zAPIShownInMenuMode = $shownInMenuMode;
	}
	
	//Mark this Plugin as being editing
	private $zAPISlotBeingEdited;
	public final function beingEdited() {
		return $this->zAPISlotBeingEdited;
	}
	protected final function zAPIMarkSlotAsBeingEdited($beingEdited) {
		$this->zAPISlotBeingEdited = $beingEdited;
	}
	
	public final function zAPIGetTabId() {
		return $this->slideId;
	}

	//Framework and Swatch for this plugin.
	protected $framework;
	protected $cssClass;
	
	private $frameworkPath;
	private $frameworkData;
	private $frameworkLoaded = false;
	protected $frameworkOutputted = false;
	
	
	public final function zAPICacheFoot($html) {
		if (isset(\ze::$slotContents[$this->slotName]['cache_path'])) {
			file_put_contents(CMS_ROOT. \ze::$slotContents[$this->slotName]['cache_path']. 'foot.html', $html, FILE_APPEND);
		}
	}
	
	public final function zAPIGetCachableVars(&$a) {
		$a = [
			$this->framework,
			$this->zAPIScripts,
			false, //not used any more
			$this->slideId,
			$this->cssClass,
			$this->eggId,
			$this->slideId];
	}
	
	public final function wrapperClass() {
		
		$cssClass = $this->cssClass;
		
		if (!empty(\ze::$slotContents[$this->slotName]['is_header'])) {
			$cssClass .= ' zenario_slot_in_header';
		
		} elseif (!empty(\ze::$slotContents[$this->slotName]['is_footer'])) {
			$cssClass .= ' zenario_slot_in_footer';
		
		} else {
			$cssClass .= ' zenario_slot_in_body';
		}
		
		if (isset($this->zAPISettings['mode'])) {
			$cssClass .= ' '. $this->moduleClassName. '__in_mode__'. $this->zAPISettings['mode'];
		}
		
		return $cssClass;
	}
	
	public final function zAPISetCachableVars(&$a) {
		if (\ze::$isTwig) return;
		
		$this->framework = $a[0];
		$this->zAPIScripts = $a[1];
		//$a[2] isn't used anymore
		$this->slideId = $a[3];
		$this->cssClass = $a[4];
		$this->eggId = $a[5];
		$this->slideId = $a[6];
	}
	
	
	public final function setInstanceVariables(
		$locationAndInstanceDetails,
		$eggId = 0, $slideId = 0, $beingDisplayed = true, $settings = false, $frameworkPath = false, $mainClass = false
	) {
		if (\ze::$isTwig) return;
		
		//Set the variables above from the array given
		list($this->cID, $this->cType, $this->cVersion, $this->slotName,
			 $this->instanceName, $this->instanceId,
			 $this->moduleClassName, $this->moduleClassNameForPhrases,
			 $this->moduleId,
			 $this->framework,
			 $this->cssClass,
			 $this->slotLevel, $this->isVersionControlled) = $locationAndInstanceDetails;
		
		$this->cID = (int) $this->cID;
		$this->cVersion = (int) $this->cVersion;
		$this->instanceId = (int) $this->instanceId;
		$this->moduleId = (int) $this->moduleId;
		$this->eggId = (int) $eggId;
		$this->slideId = (int) $slideId;
		$this->beingDisplayed = $beingDisplayed;
		$this->inLibrary = !$this->isVersionControlled;
		$this->isWireframe = $this->isVersionControlled; //For backwards compatability
		
		$this->slotName = preg_replace('/[^\w-]/', '', $this->slotName);
		$this->framework = preg_replace('/[^\w-]/', '', $this->framework);
		
		if ($this->slotName) {
			
			//Generate a container id for the plugin
			$slotNameNestId = $this->slotName;
			
			if ($eggId) {
				
				//Come up with a container id for this nested plugin.
				if ($eggId < 0) {
					//If it's from Slide Designer, use the slot name, slide id and ordinal
					$slotNameNestId .= '-'. $slideId. $eggId;
				} else {
					//If it has an id, we can just use that with the slot name.
					$slotNameNestId .= '-'. $eggId;
				}
				
				if (!empty(\ze::$slotContents[$this->slotName]['class'])) {
					$this->parentNest = \ze::$slotContents[$this->slotName]['class'];
				}
			}
			
			$this->slotNameNestId = $slotNameNestId;
			$this->containerId = 'plgslt_'. $slotNameNestId;
		}
		
		if ($settings !== false) {
			$this->zAPISettings = $settings;
		}
		
		if ($frameworkPath !== false) {
			$this->frameworkPath = $frameworkPath;
		}
		
		if ($mainClass !== false) {
			$this->zAPIMainClass = $mainClass;
		}
	}
	
	public final function setErrorMessage($errorMessage) {
		if (!empty(\ze::$slotContents[$this->slotNameNestId])) {
			\ze::$slotContents[$this->slotNameNestId]['error'] = $errorMessage;
		}
	}
	
	public final function mainClass() {
		return $this->zAPIMainClass;
	}
	
	public final function isSubClass() {
		return isset($this->zAPIMainClass);
	}
	
	public final function setInstance($locationAndInstanceDetails = false, $overrideSettings = false, $eggId = 0, $slideId = 0, $beingDisplayed = true) {
		if (\ze::$isTwig) return;
		
		$this->setInstanceVariables($locationAndInstanceDetails, $eggId, $slideId, $beingDisplayed);
		
		//Set up settings for front-end plugins
		if ($this->instanceId) {
			
			if ($this->framework) {
				$this->frameworkPath = \ze\plugin::frameworkPath($this->framework, $this->moduleClassName);
			}
			
			//Look up this plugin's settings, starting with the default values
			//Make sure to get default values if they are defined in extened Modules
			foreach (\ze\module::inheritances($this->moduleClassName, 'inherit_settings') as $className) {
				$sql = "
					SELECT `name`, default_value
					FROM ". DB_PREFIX. "plugin_setting_defs
					WHERE module_class_name = '". \ze\escape::asciiInSQL($className). "'";
				$result = \ze\sql::select($sql);
				
				while($row = \ze\sql::fetchAssoc($result)) {
					if (!isset($this->zAPISettings[$row['name']])) {
						$this->zAPISettings[$row['name']] = $row['default_value'];
					}
				}
			}
			
			//If the $eggId was negative (i.e. is actually a nest-wide plugin) then
			//don't look up and settings from the database.
			if ($eggId >= 0) {
				//Now look up the settings that have been set, and overwrite the defaults
				$sql = "
					SELECT `name`, `value`
					FROM ". DB_PREFIX. "plugin_settings
					WHERE instance_id = ". (int) $this->instanceId. "
					  AND egg_id = ". (int) $eggId;
			
				//Don't load phrase overrides for Reusable Plugins
				//(Phrase overrides will begin with a %)
				if (!$this->isVersionControlled) {
					$sql .= "
					  AND name NOT LIKE '\%%'";
				}
			
				$result = \ze\sql::select($sql);
			
				while($row = \ze\sql::fetchAssoc($result)) {
					$this->zAPISettings[$row['name']] = $row['value'];
				}
			}
			
			
			//Plugin previews get to override these settings on a temporary basis
			if (!empty($overrideSettings)
			 && is_array($overrideSettings)) {
				foreach ($overrideSettings as $name => $value) {
					$this->zAPISettings[$name] = $value;
				}
			}
		}
	}
	
	//Display a Slot and its wrappers
	public final function show($includeAdminControlsIfInAdminMode = true, $showPlaceholderMethod = 'showSlot') {
		if (\ze::$isTwig) return;
		
		$isLayoutPreview = \ze::$cID === -1;
		$isShowSlot = $showPlaceholderMethod == 'showSlot';
		
		$slot = &\ze::$slotContents[$this->slotNameNestId];
		
		//Include the controls if this is admin mode, and if this is not a preview of a layout
		if ($checkPriv = $includeAdminControlsIfInAdminMode && !$isLayoutPreview && \ze::isAdmin()) {
			$this->startIncludeAdminControls();
		}
		
		
		//Experiementing with showing a layout preview when you click on the layout tab
		//N.b. I'd need to do something like this on the AJAX reload too...
		//I'd also need to catch the case where a plugin got overridden, and show the layout preview from the module that was overridden..?
		if ($checkPriv
		 && !$isLayoutPreview
		 && $this->slotLevel > 1
		 && \ze\priv::check('_PRIV_MANAGE_TEMPLATE_SLOT')) {
			if ((bool)\ze\admin::id()) {
				echo '<div id="'. $this->containerId. '-layout_preview" onclick="return zenarioA.adminSlotWrapperClick(\'', htmlspecialchars($this->slotName), '\', event, ', $this->eggId? 1 : 0, ');" class="zenario_slot_layout_preview zenario_slot ', $this->wrapperClass(). '"';
			}	else {
				echo '<div id="'. $this->containerId. '-layout_preview" class="zenario_slot_layout_preview zenario_slot '. $this->wrapperClass(). '"';
			}		
			
			if ($this->shouldShowLayoutPreview()
			 && !$this->eggId
			 && !empty($slot['module_id'])) {
			
				$this->cssClass .= ' zenario_slot_with_layout_preview';
				
				echo '>';
					$this->showLayoutPreview();
				echo '</div>';
			
			} else {
				echo ' style="display: none;"></div>';
			}
		}
		
		echo $this->startInner();
			
			if ($isLayoutPreview && $isShowSlot) {
				$this->showLayoutPreview();
			
			} else {
				//Check whether the plugin's init function returned true
				$status = false;
				if (isset($slot['init'])) {
					$status = $slot['init'];
				}
				
				if ($status) {
					
					if (!$this->eggId) {
						\ze\plugin::preSlot($this->slotName, $showPlaceholderMethod);
					}
					
					$this->$showPlaceholderMethod();
					
					if (!$this->eggId) {
						\ze\plugin::postSlot($this->slotName, $showPlaceholderMethod);
					}
				
				} elseif ($checkPriv) {
					\ze\pluginAdm::showInitialisationError($slot, $status);
				}
			}
		echo $this->endInner();
	}
	
	public function returnWhatThisEggIs() {
		return \ze\admin::phrase('This is a plugin in a nest');
	}
	
	public function returnWhatThisIs() {
		
		$slot = &\ze::$slotContents[$this->slotNameNestId];
		
		if (!empty($slot['isSuspended'])) {
			return \ze\admin::phrase('This module is suspended');
		
		} elseif (empty($slot['init'])) {
			if (empty($slot['error'])) {
				if (empty($slot['module_id'])) {
					if (!empty($slot['is_header'])) {
						return \ze\admin::phrase('This is an empty slot on the site-wide header');
	
					} elseif (!empty($slot['is_footer'])) {
						return \ze\admin::phrase('This is an empty slot on the site-wide footer');
					} else {
						return \ze\admin::phrase('This is an empty slot');
					}
				} else {
					return \ze\admin::phrase('This is a plugin (its current settings result in no output)');
				}
			} else {
				return \ze\admin::phrase('This slot is set to show nothing on this content item');
			}
		
		} elseif (isset($this->parentNest)) {
			return $this->parentNest->returnWhatThisEggIs();
			
		} elseif ($this->isVersionControlled) {
			return \ze\admin::phrase('This is a version-controlled editable area on the content item, double-click to edit');
		
		} elseif ($this->slotLevel == 3) {
			if (!empty($slot['is_header'])) {
				return \ze\admin::phrase('This is a plugin on the site-wide header');
		
			} else {
				return \ze\admin::phrase('This is a plugin on the site-wide footer');
			}
		
		} elseif ($this->slotLevel == 2) {
			return \ze\admin::phrase('This is a plugin on the layout');
		
		} else {
			return \ze\admin::phrase('This is a plugin on the content item');
		}
	}
	
	//Display the starting wrapper of a slot
	public final function start($awClass = 'zenario_slotOuter') {
		if (\ze::$isTwig) return;
		
		//Put a section around the slot and the slot controls in admin mode.
		//This lets us adjust the look of the slot and the slot controls using CSS.
		if (\ze::isAdmin()) {
			
			$tuixSnippetId = $this->zAPISettings['~tuix_snippet~'] ?? null;
			$em = self::$zAPIExtendingModules[$this->containerId] ?? null;
			
			echo '
				<x-zenario-admin-slot-wrapper id="', $this->containerId, '-wrap" data-tooltip-options = "{\'tooltipClass\': \'zenario_whatsInSlotTooltip zenario_admin_tooltip\'}" title = "'. htmlspecialchars($this->returnWhatThisIs()). '"  class="',
					$awClass,
					$this->instanceId? ' zenario_slotWithContents' : ' zenario_slotWithNoContents',
					$tuixSnippetId? ' zenario_slot_with_tuix_snippet' : '',
					$em? ' zenario_slot_with_extending_modules' : '',
				'"';
			
			if ($tuixSnippetId && ($custom = \ze\sql::fetchValue('SELECT name FROM '. DB_PREFIX. 'tuix_snippets WHERE id = '. (int) $tuixSnippetId))) {
				echo ' data-tuix_snippet="', htmlspecialchars($custom. ' (ID '. $tuixSnippetId. ')'), '"';
			}
			if ($em && ($names = \ze\sql::fetchValues('SELECT CONCAT(class_name, \' (\', display_name, \')\') FROM '. DB_PREFIX. 'modules WHERE class_name IN ('. \ze\escape::in(array_keys($em), 'asciiInSQL'). ')'))) {
				echo ' data-extnames="', htmlspecialchars(implode(', ', $names)), '"';
			}
			
			echo '>';
		}
	}
	
	//Display the admin controls for a slot
	private function startIncludeAdminControls() {
		require \ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	//Put a div around the slot, so we can reload the contents
	public final function startInner() {
		if (\ze::$isTwig) return;
		
		$html = '

					<div id="'. $this->containerId. '"  class="zenario_slot '. $this->wrapperClass(). '"';
		
		if (\ze::isAdmin()) {
			$html .= ' onclick="return zenarioA.adminSlotWrapperClick(\''. htmlspecialchars($this->slotName). '\', event, '. ($this->eggId? 1 : 0). ');"';
		}
		
		return $html. '>';
	}
	
	protected final function noModeSelected() {
		if (\ze::isAdmin()) {
			echo
				'<p class="zenario_inactive_mode">'.
					\ze\admin::phrase('This plugin is inactive. Please edit its settings to make it active.').
				'</p>';
		}
	}
	
	//Close the admin controls for a slot.
	public final function endInner() {
		if (\ze::$isTwig) return;
		
		$padding = '';
		if (\ze::isAdmin()) {
			if ($this->instanceId && !$this->frameworkOutputted) {
				$padding = '
					<span class="zenario_slot_padding">&nbsp;</span>';
			}
		}
		
		return $padding. '
					</div>';
	}
	
	//Close the wrapper for a slot.
	public final function end() {
		if (\ze::$isTwig) return;
		
		//Display the HTML at the end of a slot when in admin mode
		if (\ze::isAdmin()) {
			echo '
				</x-zenario-admin-slot-wrapper>';
		}
	}
	
	//This is a utility function to deal with the standard image resize options on tuix plugin settings.
	protected function showHideImageOptions(&$fields, $values, $tab, $hidden = false, $fieldPrefix = '', $hasCanvas = true, $sameLineLabel = 'Size (width × height):') {
		require \ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	//Utility function to get system default icons for document extensions
	protected function getStyledExtensionIcon($ext, &$outArray) {
		$styledExtensions = [
				'avi' => 'avi',
				'doc' => 'doc',
				'docx' => 'doc',
				'jpg' => 'jpg',
				'jpeg' => 'jpg',
				'jpe' => 'jpg',
				'gz' => 'gz',
				'pdf' => 'pdf',
				'ppt' => 'ppt',
				'rtf' => 'rtf',
				'txt' => 'txt',
				'xls' => 'xls',
				'xlsx' => 'xls',
				'zip' => 'zip'];
		
		if (isset($styledExtensions[$ext])) {
			$outArray['Icon'] = $styledExtensions[$ext] . '_icon.jpg';
			$outArray['Icon_Class'] = $styledExtensions[$ext] . '_icon';
		} else {
			$outArray['Icon'] = 'unknown_icon.jpg';
			$outArray['Icon_Class'] = 'unknown_icon';
		}
	}
	
	protected function listImagesOnSlotControls(&$controls, $images) {
		require \ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	
	  ////////////
	 //  Misc  //
	////////////
	
	//This is intended as a replacement for the old useThisClassInstead() functionality
	//Rather than put all of your Admin Box/Organizer functionality in one module,
	//this lets you divvy it up into different subclasses.
	private $zAPIrunSubClassSafetyCatch = false;
	private $zAPIMainClass;
	private $zAPISubClasses = [];
	
	public final function runSubClass($filePath, $type = false, $path = false) {
		
		//Add a check to stop subclasses calling themsevles again, which would cause an
		//infinite loop!
		if ($this->zAPIrunSubClassSafetyCatch) {
			return false;
		}
	
		if ($type === false) {
			$type = \ze::$tuixType;
		}
		if ($path === false) {
			$path = \ze::$tuixPath ?: $this->getMode();
		}
		
		//Try to cache these, so multiple calls to the same subclass use the same instance
		$codeName = $type. '`'. $this->getModeFromPath($path);
			//To do: This allows for multiple different sub-classes to be cached... but is there ever more than one sub class?
			//I could do away with this and always use the previous subclass if it exists!
		
		if (isset($this->zAPISubClasses[$codeName])) {
			return $this->zAPISubClasses[$codeName];
		
		} elseif ($className = \ze\module::incSubclass($filePath, $type, $path)) {
			$this->zAPISubClasses[$codeName] = new $className;
			$this->zAPISubClasses[$codeName]->zAPIrunSubClassSafetyCatch = true;
			$this->zAPISubClasses[$codeName]->setInstanceVariables([
				$this->cID, $this->cType, $this->cVersion, $this->slotName,
				$this->instanceName, $this->instanceId,
				$this->moduleClassName, $this->moduleClassNameForPhrases,
				$this->moduleId,
				$this->framework,
				$this->cssClass,
				$this->slotLevel, $this->isVersionControlled],
				$this->eggId, $this->slideId, $this->beingDisplayed,
				$this->zAPISettings, $this->frameworkPath, $this);
			
			return $this->zAPISubClasses[$codeName];
		
		} else {
			return $this->zAPISubClasses[$codeName] = false;
		}
	}
	
	
	public final function getClassInfo() {
		if ($this->subClass) {
			return $this->subClass->getClassInfo();
		}
		
		return [
			'mode' => $this->getMode()
		];
	}

	protected function redirectToPage($showWelcomePage = true, $redirectBackIfPossible = true, $redirectRegardlessOfPerms = true, $returnDestinationOnly = false) {
		return require \ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	
	
	  ///////////////////////////////////////////
	 //  Old, deprecated Framework Functions  //
	///////////////////////////////////////////
	
	
	protected final function framework(
								$section = 'Outer', $mergeFields = [],
								$allowedSubSections = [], $subSectionDepthLimit = 5,
								$half = false, $halfwayPoint = true
							 ) {
						
		$this->zAPIFramework(
				$section, $mergeFields, $allowedSubSections, $subSectionDepthLimit, $half, $halfwayPoint);
	}

	protected final function frameworkHead(
								$section = 'Outer',
								$halfwayPoint = true,
								$mergeFields = [],
								$allowedSubSections = [], $subSectionDepthLimit = 5) {
		$this->zAPIFramework(
				$section, $mergeFields, $allowedSubSections, $subSectionDepthLimit, 1, $halfwayPoint);
	}
	
	protected final function frameworkFoot(
								$section = 'Outer',
								$halfwayPoint = true,
								$mergeFields = [],
								$allowedSubSections = [], $subSectionDepthLimit = 5) {
		$this->zAPIFramework(
				$section, $mergeFields, $allowedSubSections, $subSectionDepthLimit, 2, $halfwayPoint);
	}
	
	protected $frameworkFields = [];
	
	public final function frameworkField($attributes) {
		return $this->zAPIFrameworkField($attributes);
	}
	
	private function zAPIFrameworkField($attributes, $i = false, $lov = false, $readonly = false, $saveVal = null, $dispVal = null) {
		return require \ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	private function zAPIFrameworkLOV($type, &$attributes, &$lov) {
		//Load the List of Values for a field
		if ($type == 'checkbox' || $type == 'radio' || $type == 'select' || $type == 'toggle' || $type == 'text') {
			if (!empty($attributes['source_module']) && !empty($attributes['source_method']) && \ze\module::inc($attributes['source_module'])) {
			
				//Old "source_param_" logic, still included for backwards compatability
				$i = 0;
				$args = [];
				while (isset($attributes['source_param_'. ++$i])) {
					$args[] = $attributes['source_param_'. $i];
				}
				
				if (!empty($args)) {
					$lov = call_user_func_array([$attributes['source_module'], $attributes['source_method']], $args);
				} else {
					//New logic where the whole $attributes array is passed in
					$lov = call_user_func([$attributes['source_module'], $attributes['source_method']], $attributes);
				}
				
				//Disallow caching for programatically generated lists of values
				\ze::$slotContents[$this->slotName]['disallow_caching'] = true;
			
			//Generate a LOV by calling one of the Plugin's own methods non-statically
			} elseif (!empty($attributes['source_method'])) {
				if (!empty($args)) {
					$lov = call_user_func_array([$this, $attributes['source_method']], $args);
				} else {
					//New logic where the whole $attributes array is passed in
					$lov = call_user_func([$this, $attributes['source_method']], $attributes);
				}
				
			
			} elseif (isset($attributes['value_1'])) {
				
				$i = 0;
				$lov = [];
				while (isset($attributes['value_'. ++$i])) {
					$lov[$attributes['value_'. $i]] =
						$this->phrase(
							isset($attributes['display_'. $i])?
								$attributes['display_'. $i]
							:	$attributes['value_'. $i]
						);
				}
			}
		}
	}
	
	protected final function zAPIFramework(
								$section = 'Outer', $mergeFields = [],
								$allowedSubSections = [], $subSectionDepthLimit = 5,
								$half = false, $halfwayPoint = true, $recursing = false
							 ) {
		
		//Where a module is still calling the framework functions for the old "Tribiq" frameworks,
		//attempt to convert this to calls to the Twig framework functions.
		
		//If the Module is outputting its entire framework at once, this will work well.
		//If the Module is designed to call indivudal sections from the framework, bit-by-bit,
		//then this will not work well, and the module will need some adjusting/reworking.
		
		//Ignore any calls to frameworkFoot().
		if ($half === 2) {
			return;
		}
		
		//Combine the sections and mergeFields arrays
		if (!isset($mergeFields[$section])) {
			$mergeFields[$section] = true;
		}
		if (is_array($allowedSubSections)) {
			foreach ($allowedSubSections as $sectionName => $sectionData) {
				if (!isset($mergeFields[$sectionName])) {
					$mergeFields[$sectionName] = $sectionData;
				}
			}
		}
		
		//Call Twig.
		$this->twigFramework($mergeFields);
	}
	
	private static $zAPIExtendingModules = [];
	public final function zAPISetExtendingModules($ems) {
		self::$zAPIExtendingModules[$this->containerId] = $ems;
	}
	
	
	
	
	
	
	protected $subClass = false;
	
	protected function getPathFromMode($mode) {
		return 'zenario_' . $mode;
	}
	
	protected function getModeFromPath($path) {
		return str_replace('zenario_', '', $path);
	}
	
	protected function getMode() {
		//From version 7.6, if you have a plugin, we'll only allow the plugin to run in the mode chosen in the plugin settings.
		//If you want extra modes then you'll either need to make links in the conductor, or links to other content items.
		if ($this->instanceId && ($mode = $this->setting('mode'))) {
			return $mode;
		
		//Otherwise check the mode in the request
		} elseif (!empty($_REQUEST['mode'])) {
			return $_REQUEST['mode'];
		
		//Otherwise check the path in the request
		} elseif (!empty($_REQUEST['path'])) {
			return $this->getModeFromPath($_REQUEST['path']);
		}
	}
	
	protected function pluginRequestVars() {
		$vars = 
			'&instanceId='. $this->instanceId.
			'&slotName='. $this->slotName;
		
		if (\ze::isAdmin()) {
			$vars .= '&cVersion='. $this->cVersion;
		}
		
		if ($this->eggId) {
			$vars .= '&eggId='. $this->eggId;
		}
		
		if ($this->slideId) {
			$vars .= '&slideId='. $this->slideId;
		}
		
		return $vars;
	}
}







/**
 * This class contains core plugin functions that the CMS calls in order to display modules
 * They're not really part of the plugin API;
 * plugin developers don't need to be aware of them and should never call them from within a plugin.
 */

//N.b. the name uses snake-case not camel case.
//This is a hold-over from versions 6 & 7, and I don't want to ask everyone to rewrite their
//modules to change it.

class moduleBaseClass extends moduleAPI {
	
	
	  /////////////////////////////////
	 //  Methods called for Plugins  //
	/////////////////////////////////
	
	public function addToPageHead() {
		
		//...your PHP code...//
	}

	public function addToPageFoot() {
		
		//...your PHP code...//
	}

	public function init() {
		
		//...your PHP code...//
		
		return true;
	}

	public function showSlot() {
		
		//...your PHP code...//
	}

	public function generateSmartBreadcrumbs() {
		
		//...your PHP code...//
	}
	
	public function shouldShowLayoutPreview() {
		return $this->isVersionControlled && $this->slotLevel > 1;
	}
	
	public function showLayoutPreview() {
		if (!$this->shouldShowLayoutPreview()) {
			$this->showSlot();
		} elseif (!$this->moduleId) {
			echo \ze\admin::phrase('[Empty Slot]');
		} else {
			echo '[', htmlspecialchars(\ze\module::getModuleDisplayNameByClassName($this->moduleClassName)), ']';
		}
	}
	
	
	
	  /////////////////////////////////////////////////
	 //  Methods called for Plugins when linked to  //
	/////////////////////////////////////////////////

	public function handlePluginAJAX() {
		
		//...your PHP code...//
	}

	public function showRSS() {
		
		//...your PHP code...//
	}

	public function showFloatingBox() {
		
		//...your PHP code...//
	}
	
	
	
	
	  /////////////////////////////////////
	 //  Methods called when linked to  //
	/////////////////////////////////////
	
	public function handleAJAX() {
		
		//...your PHP code...//
	}

	public function showFile() {
		
		//...your PHP code...//
	}

	public function showImage() {
		
		//...your PHP code...//
	}

	public function showStandalonePage() {
		
		//...your PHP code...//
	}
	
	
	
	
	  ///////////////////////////////////////////
	 //  Methods called by the Admin Toolbar  //
	///////////////////////////////////////////
	
	
	public function fillAdminToolbar(&$adminToolbar, $cID, $cType, $cVersion) {
		
		//...your PHP code...//
	}
	
	public function handleAdminToolbarAJAX($cID, $cType, $cVersion, $ids) {
		
		//...your PHP code...//
	}
	
	
	
	
	  ///////////////////////////////////////////////
	 //  Methods called by TUIX apps for visiors  //
	///////////////////////////////////////////////
	
	

	public function returnVisitorTUIXEnabled($path) {
		if (\ze::$isTwig) return;
		
		if ($this->subClass || ($this->subClass = $this->runSubClass(static::class, false, $path))) {
			return $this->subClass->returnVisitorTUIXEnabled($path);
		}
		return false;
	}
	
	public function fillVisitorTUIX($path, &$tags, &$fields, &$values) {
		if (\ze::$isTwig) return;
		
		if ($this->subClass || ($this->subClass = $this->runSubClass(static::class, false, $path))) {
			return $this->subClass->fillVisitorTUIX($path, $tags, $fields, $values);
		}
	}
	
	public function formatVisitorTUIX($path, &$tags, &$fields, &$values, &$changes) {
		if (\ze::$isTwig) return;
		
		if ($this->subClass || ($this->subClass = $this->runSubClass(static::class, false, $path))) {
			return $this->subClass->formatVisitorTUIX($path, $tags, $fields, $values, $changes);
		}
	}
	
	public function validateVisitorTUIX($path, &$tags, &$fields, &$values, &$changes, $saving) {
		if (\ze::$isTwig) return;
		
		if ($this->subClass || ($this->subClass = $this->runSubClass(static::class, false, $path))) {
			return $this->subClass->validateVisitorTUIX($path, $tags, $fields, $values, $changes, $saving);
		}
	}
	
	public function saveVisitorTUIX($path, &$tags, &$fields, &$values, &$changes) {
		if (\ze::$isTwig) return;
		
		if ($this->subClass || ($this->subClass = $this->runSubClass(static::class, false, $path))) {
			return $this->subClass->saveVisitorTUIX($path, $tags, $fields, $values, $changes);
		}
	}
	
	public function typeaheadSearchAJAX($path, $tab, $searchField, $searchTerm, &$searchResults) {
		if (\ze::$isTwig) return;
		
		if ($this->subClass || ($this->subClass = $this->runSubClass(static::class, false, $path))) {
			return $this->subClass->typeaheadSearchAJAX($path, $tab, $searchField, $searchTerm, $searchResults);
		}
	}
	
	
	
	
	  /////////////////////////////////////
	 //  Methods called by Admin Boxes  //
	/////////////////////////////////////
	
	
	public function fillAdminBox($path, $settingGroup, &$box, &$fields, &$values) {
		if (\ze::$isTwig) return;
		
		if ($c = $this->runSubClass(static::class, false, $path)) {
			return $c->fillAdminBox($path, $settingGroup, $box, $fields, $values);
		}
	}
	
	public function formatAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		if (\ze::$isTwig) return;
		
		if ($c = $this->runSubClass(static::class, false, $path)) {
			return $c->formatAdminBox($path, $settingGroup, $box, $fields, $values, $changes);
		}
	}
	
	public function validateAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes, $saving) {
		if (\ze::$isTwig) return;
		
		if ($c = $this->runSubClass(static::class, false, $path)) {
			return $c->validateAdminBox($path, $settingGroup, $box, $fields, $values, $changes, $saving);
		}
	}
	
	public function saveAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		if (\ze::$isTwig) return;
		
		if ($c = $this->runSubClass(static::class, false, $path)) {
			return $c->saveAdminBox($path, $settingGroup, $box, $fields, $values, $changes);
		}
	}
	
	public function adminBoxSaveCompleted($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		if (\ze::$isTwig) return;
		
		if ($c = $this->runSubClass(static::class, false, $path)) {
			return $c->adminBoxSaveCompleted($path, $settingGroup, $box, $fields, $values, $changes);
		}
	}
	
	public function adminBoxDownload($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		if (\ze::$isTwig) return;
		
		if ($c = $this->runSubClass(static::class, false, $path)) {
			return $c->adminBoxDownload($path, $settingGroup, $box, $fields, $values, $changes);
		}
	}
	
	
	
	
	  ///////////////////////////////////
	 //  Methods called by Organizer  //
	///////////////////////////////////
	
	public function fillOrganizerNav(&$nav) {
		
		//...your PHP code...//
	}
	
	public function preFillOrganizerPanel($path, &$panel, $refinerName, $refinerId, $mode) {
		if ($c = $this->runSubClass(static::class, false, $path)) {
			return $c->preFillOrganizerPanel($path, $panel, $refinerName, $refinerId, $mode);
		}
	}
	
	public function fillOrganizerPanel($path, &$panel, $refinerName, $refinerId, $mode) {
		if ($c = $this->runSubClass(static::class, false, $path)) {
			return $c->fillOrganizerPanel($path, $panel, $refinerName, $refinerId, $mode);
		}
	}
	
	public function handleOrganizerPanelAJAX($path, $ids, $ids2, $refinerName, $refinerId) {
		if ($c = $this->runSubClass(static::class, false, $path)) {
			return $c->handleOrganizerPanelAJAX($path, $ids, $ids2, $refinerName, $refinerId);
		}
	}	
	public function organizerPanelDownload($path, $ids, $refinerName, $refinerId) {
		if ($c = $this->runSubClass(static::class, false, $path)) {
			return $c->organizerPanelDownload($path, $ids, $refinerName, $refinerId);
		}
	}
	
	
	
	
	  //////////////////////////////////////////
	 //  Other Methods called in Admin Mode  //
	//////////////////////////////////////////
	
	
	public static function nestedPluginName($eggId, $instanceId, $moduleClassName) {
		return \ze\module::getModuleDisplayNameByClassName($moduleClassName);
	}
	
	public function fillAdminSlotControls(&$controls) {
		//...your PHP code...//
	}
	
	public function fillAllAdminSlotControls(
		&$controls,
		$cID, $cType, $cVersion,
		$slotName, $containerId,
		$level, $moduleId, $instanceId, $isVersionControlled
	) {
		//...your PHP code...//
	}

}
