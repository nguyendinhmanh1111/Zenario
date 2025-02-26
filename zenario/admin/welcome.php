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

if (version_compare(phpversion(), '7.2.0', '<')) {
	echo '
		<h1>System Requirements</h1>
		<p>It looks like your server doesn\'t meet the requirements for Zenario.</p>
		<p>
			Zenario needs PHP version 7.2 or later to run (<em>you have version ', htmlspecialchars(phpversion()), '</em>).
		</p>';
	exit;
}


//Attempt to include the basic header
//We need to check two different paths, as this file can be accessed from two different ways,
//depending on how the friendly URLs have been set up.
if (is_file('zenario/basicheader.inc.php')) {
	require 'zenario/basicheader.inc.php';
} else {
	require '../basicheader.inc.php';
}
header('Content-Type: text/html; charset=UTF-8');


//Catch the case where someone comes in on the old "unfriendly" URL (i.e. zenario/admin/welcome.php),
//and redirect them to the new "friendly" one (i.e. admin.php).
$uri = explode('?', $_SERVER['REQUEST_URI'] ?? '', 2)[0];
if (false !== ze\ring::chopSuffix($uri, 'zenario/admin/welcome.php')) {
	header ('Location: ../../admin.php?'. $_SERVER['QUERY_STRING']);
	exit;
}


//Check to see if Zenario is installed, and connect to the database if so
$freshInstall = false;
$installStatus = 0;
$installed =
	ze\welcome::checkConfigFileExists()
 && ($installStatus = 1)
 && (defined('DBHOST'))
 && (defined('DBNAME'))
 && (defined('DBUSER'))
 && (defined('DBPASS'))
 && (ze::$dbL = new \ze\db(DB_PREFIX, DBHOST, DBNAME, DBUSER, DBPASS, DBPORT, false))
 && (ze::$dbL->con)
 && ($result = @ze::$dbL->con->query("SHOW TABLES LIKE '". DB_PREFIX. "site_settings'"))
 && ($installStatus = 2)
 && ($result->num_rows)
 && ($installStatus = 3);

if (!$installed) {
	ze::$dbL = null;
}


//If it is defined, check that the SUBDIRECTORY is correct and warn the admin if not
if (defined('SUBDIRECTORY')) {
	//Get the original included filepath
	$file = substr($_SERVER['SCRIPT_FILENAME'], strlen(CMS_ROOT));
	
	//Get the included location
	$self = $_SERVER['PHP_SELF'];
	
	//If the two don't match up, try chopping the filenames off the ends of the path
	if (substr($self, -strlen($file)) != $file) {
		$pos = max(strrpos($file, '/'), strrpos($file, '\\'), -1);
		$file = substr($file, 0, $pos? $pos + 1 : 0);
		
		$pos = max(strrpos($self, '/'), strrpos($self, '\\'), -1);
		$self = substr($self, 0, $pos? $pos + 1 : 0);
		
		unset($pos);
	}
	
	//Trim the included location by the filepath to get the current SUBDIRECTORY
	if (strlen($file)) {
		$subdir = substr($self, 0, -strlen($file));
	} elseif ($self) {
		$subdir = $self;
	} else {
		$subdir = '/';
	}
	
	if (SUBDIRECTORY != $subdir) {
		echo htmlspecialchars(
			'The SUBDIRECTORY constant is not correctly defined in the zenario_siteconfig.php file. It is set to "'. SUBDIRECTORY. '"; it should be set to "'. $subdir. '".');
		exit;
	}
}

//Check to see that the Admin has not copied Zenario on-top of an older version
if (is_dir('zenario/admin/db_updates/copy_over_top_check/')) {
	foreach (scandir('zenario/admin/db_updates/copy_over_top_check/') as $file) {
		if (substr($file, 0, 1) != '.' && $file != ZENARIO_MAJOR_VERSION. '.'. ZENARIO_MINOR_VERSION. '.txt') {
			echo '
				<p>
					You are seeing this message because you have attempted to update Zenario
					by copying the new version over the top of your existing version.
				</p><p>
					This will not work, as there are some files in the older version that need to be removed.
					You should replace your <code>'. CMS_ROOT. 'zenario/</code> directory with the
					<code>zenario/</code> directory from the new copy of Zenario.
				</p><p>
					Please see the <a href="https://zenar.io/quick-upgrade.html">zenar.io/quick-upgrade.html</a> guide
					or the <a href="https://zenar.io/cautious-upgrade.html">zenar.io/cautious-upgrade.html</a> guide
					on <a href="https://zenar.io">zenar.io</a> for more information.
				</p>';
			exit;
		}
	}
}









if ($installed) {
	//If Zenario is installed, move on to the login check and then database updates
	if (!defined('SHOW_SQL_ERRORS_TO_VISITORS')) {
	   
		define('SHOW_SQL_ERRORS_TO_VISITORS', true);
		
	}
	ze\db::connectLocal();
	
	
	//From version 8 of Zenario, we'll only support updating from version 7.5 onwards.
	//Check for versions of Tribiq CMS/Zenario before 7.5
	$sql = "
		SELECT 1
		FROM ". DB_PREFIX. "local_revision_numbers
		WHERE path IN ('admin/db_updates/step_1_update_the_updater_itself', 'admin/db_updates/step_2_update_the_database_schema', 'admin/db_updates/step_4_migrate_the_data')
		  AND patchfile IN ('updater_tables.inc.php', 'admin_tables.inc.php', 'content_tables.inc.php', 'user_tables.inc.php')
		  AND revision_no < ". (int) EARLIEST_SUPPORTED_MIGRATION. "
		LIMIT 1";

	if (ze\sql::fetchRow(ze\sql::select($sql))) {
		//If this looks like a very old version of Zenario, direct people to update to at least 8.2 first
		echo '
			<p>
				You are seeing this message because your database contains an installation
				of Zenario running from before version 8.2.
			</p><p>
				To update to version 9 of Zenario, you must first update your database
				to at least version 8.2.
			</p><p>
				Please download the latest package for the 8.x branch from our website at
				<a href="https://zenar.io">https://zenar.io</a>, and update to that version first.
			</p>';
		exit;
	}
	
	//Catch the case where someone has accidentally pointed a database running a later version of Zenario
	//at a previous version of the software.
	$sql = "
		SELECT revision_no
		FROM ". DB_PREFIX. "local_revision_numbers
		WHERE path IN ('admin/db_updates/step_1_update_the_updater_itself', 'admin/db_updates/step_2_update_the_database_schema', 'admin/db_updates/step_4_migrate_the_data')
		  AND patchfile IN ('updater_tables.inc.php', 'admin_tables.inc.php', 'content_tables.inc.php', 'user_tables.inc.php')
		  AND revision_no > ". (int) LATEST_REVISION_NO. "
		LIMIT 1";

	if (false && $dbRev = ze\sql::fetchRow(ze\sql::select($sql))) {
		
		
		$sql = "
			SELECT value
			FROM ". DB_PREFIX. "site_settings
			WHERE name = 'zenario_version'";
		$dbVer = ze\sql::fetchRow(ze\sql::select($sql));
		
		
		echo '
			<p>
				You are seeing this message because your database contains an installation of Zenario from a later version.
			</p><p>
				This software is version <code>'. ZENARIO_VERSION. '</code> of Zenario, the highest supported DB revision is <code>#'. LATEST_REVISION_NO. '</code>.
			</p><p>';
		
		if ($dbVer) {
			$dbVer = preg_replace('@^([\d\\.]*).*@', '$1', trim($dbVer[0]));
			echo '
				Your database contains version <code>'. htmlspecialchars($dbVer). '</code> of Zenario, at DB revision <code>#'. (int) $dbRev[0]. '</code>.';
		} else {
			echo '
				Your database is at DB revision <code>#'. (int) $dbRev[0]. '</code>.';
		}
		
		echo '
			</p>';
		exit;
	}
	
	//Check for any out of date YAML files and clear the cache if needed.
	ze\miscAdm::checkForChangesInYamlFiles();
}


echo
'<!DOCTYPE HTML>
<html>
<head>
	<title>', ze\admin::phrase('Welcome to Zenario'), '</title>
	<meta name="viewport" content="initial-scale=0.5">
	<base href="', ze\link::absolute(), '">';

$v = ze\db::codeVersion();
ze\content::pageHead('zenario/', 'welcome');

echo '
	<link rel="stylesheet" type="text/css" href="zenario/styles/admin_welcome.min.css?v=', $v, '" media="screen" />
	<style type="text/css">
		
		#welcome,
		#no_something,
		#no_cookies,
		#no_script {
			display: none;
		}
		
		body.no_js #no_something {
			display: block;
		}
		
		body.no_js #no_script {
			display: inline;
		}
	</style>
</head>';


ze\content::pageBody();
ze\content::pageFoot('zenario/', 'welcome', false, false);

$logoURL = $logoWidth = $logoHeight = false;
if (ze::$dbL
 && ze::setting('brand_logo') == 'custom'
 && (ze\file::imageLink($logoWidth, $logoHeight, $logoURL, ze::setting('custom_logo'), 500, 250, $mode = 'resize', $offset = 0, $retina = true))) {
	
	if (strpos($logoURL, '://') === false) {
		$logoURL = ze\link::absolute(). $logoURL;
	}
} else {
	$logoURL = 'zenario/admin/images/zenario-logo-black.svg';
	$logoWidth = 142;
	$logoHeight = 57;
}


$allowedTasks = [
	'change_password' => 'change_password',
	'new_admin' => 'new_admin',
	'diagnostics' => 'diagnostics',
	'reload_sk' => 'reload_sk',
	'end' => 'logout',
	'logout' => 'logout',
	'restore' => 'restore',
	'site_reset' => 'site_reset'];



//T9732, Admin login panel, show warning when a redirect from other URL has occurred
$refererHostWarning = false;
if (!empty($_SERVER['HTTP_REFERER'])
 && ($currentHost = ze\link::hostWithoutPort())
 && ($refererHost = ze\link::hostWithoutPort(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)))
 && ($refererHost != $currentHost)) {
	$refererHostWarning =
		ze\admin::phrase('Your URL has changed. This is the admin login page at "[[currentHost]]", you were previously at "[[refererHost]]".',
			['refererHost' => $refererHost, 'currentHost' => $currentHost]);
}

echo '
	<script type="text/javascript">
		var cb = new zenario.callback();
	</script>';


if (ze::setting('google_recaptcha_site_key')
    && ze::setting('google_recaptcha_secret_key')
    && ze\site::description('enable_captcha_for_admin_logins')
) {
	echo '
		<script defer async src="https://www.google.com/recaptcha/api.js?onload=recaptchaCallback&render=explicit"></script>
		<script type="text/javascript">
			var step1 = cb.add(),
				google_recaptcha = {
					sitekey: "' . \ze\escape::js(\ze::setting('google_recaptcha_site_key')) . '"
				};
			
			function recaptchaCallback() {
				step1.done();
			}
		</script>';
}

echo '
<script type="text/javascript" src="zenario/js/admin_welcome.min.js?v=', $v, '"></script>
<script type="text/javascript">
	zenarioAW.task = "', $allowedTasks[$_REQUEST['task'] ?? false] ?? false, '";
	zenarioAW.getRequest = ', json_encode($_GET), ';
	
	var step2 = cb.add(),
		msg = "', ze\escape::js('<!--Logged_Out-->'. ze\admin::phrase('You have been logged out.')), '";
		
	$(document).ready(function () {
		if (!zenarioA.loggedOutIframeCheck(msg)) {
			try {
				zenarioA.checkCookiesEnabled().after(function(cookiesEnabled) {
					if (cookiesEnabled) {
						step2.done();
						zenarioAW.refererHostWarning(', json_encode($refererHostWarning), ');
					} else {
						zenario.get("no_something").style.display = "block";
						zenario.get("no_cookies").style.display = "inline";
					}
				});
			} catch (e) {
				zenario.get("no_something").style.display = "block";
				zenario.get("no_cookies").style.display = "inline";
			}
		}
	});
	
	cb.after(function() {
		zenarioAW.start();
	});
</script>';


if (strpos(($_SERVER['HTTP_USER_AGENT'] ?? ''), 'MSIE 6') !== false) {
	echo '
		<style type="text/css">
			html {
				overflow: hidden;
			}
		</style>';
}

$revision = false;
if (ZENARIO_IS_BUILD) {
	$revision = ZENARIO_REVISION;
} elseif ($svninfo = ze\welcome::svnInfo()) {
	$revision = $svninfo['Revision'];
}


echo '
<div id="zenario_now_installing" class="zenario_now" style="display: none;">
	<div id="organizer_preloader_circle">
		<div></div>
	</div>
</div>
<div id="zenario_now_loading" class="zenario_now" style="display: none;">
	<h1 style="text-align: center;">', ze\admin::phrase('Now loading'), '
		<div class="bounce1"></div>
		<div class="bounce2"></div>
		<div class="bounce3"></div>
	</h1>
</div>
<div id="welcome_outer">
	<div id="welcome" class="welcome">
		<div class="zenario_version"><p class="version">
			', ze\admin::phrase('Zenario [[version]]', ['version' => ze\site::versionNumber($revision)]), '
		</p></div>
		<div class="welcome_wrap">
			<div class="welcome_inner">
		
				<div class="welcome_header">
					<div class="welcome_header_logo">
						<img src="', htmlspecialchars($logoURL), '" width="', (int) $logoWidth, '" height="', (int) $logoHeight, '"/>
					</div>
				</div>
	
				<div>
					<div id="zenario_abtab"></div>
				</div>
			</div>
		</div>
	</div>
	<div id="no_something" class="welcome">
		<div class="zenario_version"><p class="version">
			', ze\admin::phrase('Zenario [[version]]', ['version' => ze\site::versionNumber($revision)]), '
		</p></div>
		<div class="welcome_wrap">
			<div class="welcome_inner">
		
				<div class="welcome_header">
					<div class="welcome_header_logo">
						<img src="', htmlspecialchars($logoURL), '" width="', (int) $logoWidth, '" height="', (int) $logoHeight, '"/>
					</div>
				</div>
	
				<div>
					<div class="problem">
						<h1>', ze\admin::phrase('Welcome to Zenario'), '</h1>
						<p id="no_cookies">',
							ze\admin::phrase("Unable to start a session! We cannot log you in at the moment.<br/><br/>Please check that cookies are enabled in your browser.<br/><br/>If you've enabled cookies and this message persists, please advise your system administrator to: <ul><li>Check the <code>COOKIE_DOMAIN</code> setting in the <code>zenario_siteconfig.php</code> file to ensure it is not referencing a different domain.</li><li>Check for any problems with caching or session storage on the server.</li></ul>"),
						'</p>
						<p id="no_script">',
							ze\admin::phrase('Please enable JavaScript in your browser to continue.'),
						'</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>';


?>
</body>
</html>