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





class zenario_anonymous_comments extends ze\moduleBaseClass {
	
	protected $show = true;
	protected $mode = 'showPosts';
	protected $form_encode_type = null;
	var $reloadPage = false;
	var $modPrivs = false;
	var $postPrivs = false;
	var $newThreadPrivs = false;
	
	var $forum = [];
	var $thread = [];
	var $posts = false;
	var $post = [];
	var $page = 1;
	
	var $mergeFields = [];
	var $sections = [];
	var $postingErrors = [];
	
	var $editorId = false;
	
	protected $pageSize;
	
	public function getEditorId() {
		if ($this->editorId === false) {
			$this->editorId = 'editor__'. preg_replace('@\D@', '', microtime() ?: time());
		}
		return $this->editorId;
	}
	
	
	public static function sanitiseHTML($html, $allowImages, $allowLinks) {
		
		//disallowed: <h1><h2><h3><h4><h5><h6><div>
		$allowable_tags = '<br><p><pre><blockquote><code><em><strong><span><sup><sub><ul><li><ol>';
		
		if ($allowLinks) {
			$allowable_tags .= '<a>';
		}
		if ($allowImages) {
			$allowable_tags .= '<img>';
		}
		
		$allowedStyles = ['padding-left' => true, 'text-decoration' => true];
		
		return ze\ring::sanitiseHTML($html, $allowable_tags, $allowedStyles);
	}
	
	public function addToPageHead() {
		//Ensure that the toolbar is always visible
		echo '
			<style type="text/css">
				div.zenario_tinymce_toolbar_container > .mce-panel {
					display: block !important;
				}
			</style>';
		if ($this->enableCaptcha()) {
			$this->loadCaptcha2Lib();
		}
	}
	
	
	
	
	
 /**
  * The clearRequest() method removes an entry from the $_POST and the $_GET
  */
	protected final function clearRequest($name) {
		if (isset($_GET[$name])) {
			unset($_GET[$name]);
		}
		if (isset($_POST[$name])) {
			unset($_POST[$name]);
		}
		if (isset($_REQUEST[$name])) {
			unset($_REQUEST[$name]);
		}
	}
	
	
	function init() {
		if (!$this->setting('show_user_online_status')) {
			$this->allowCaching(
				$atAll = true, $ifUserLoggedIn = false, $ifGetSet = false, $ifPostSet = false, $ifSessionSet = true, $ifCookieSet = true);
			$this->clearCacheBy(
				$clearByContent = false, $clearByMenu = false, $clearByUser = true, $clearByFile = true, $clearByModuleData = true);
		}
		
		if (ze::in(ze\content::isSpecialPage($this->cID, $this->cType), 'zenario_no_access', 'zenario_not_found')) {
			return $this->show = false;
		}
		
		ze::requireJsLib('zenario/libs/manually_maintained/mit/colorbox/jquery.colorbox.min.js');
		
		$this->registerGetRequest('comm_page', 1);
		$this->page = (int) ($_REQUEST['comm_page'] ?? 1) ?: 1;

		//Require the phrases
		ze::requireJsLib('zenario/modules/zenario_anonymous_comments/js/editor_phrases.js.php?langId='. ze::$visLang);
		
		$this->runCheckPrivs();
		
		$this->loadThreadInfo();
		$this->loadPagination();
		$this->loadPosts();
		
		$this->threadActionHandler();
		
		$this->threadSelectMode();

		$this->mergeFields['User_is_admin'] = ze\admin::id();
		
		return $this->show = true;
	}


	
	function showSlot() {
		if (!$this->show) {
			return;
		}
		
		$mode = $this->mode;
		$this->$mode();
	}
	
	
	
	
	//Add a comment onto the thread
	function addReply($userId, &$messageText, $firstPost = 0, $name = '', $email = '') {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	function defaultReplyStatus() {
		return $this->setting('comments_require_approval') ? 'pending' : 'published';
	}
	
	function canDeleteThread() {
		return false;
	}
	
	function canApprovePost() {
		return $this->modPrivs;
	}
	
	function canDeletePost(&$post) {
		if (isset($post['first_post']) && $post['first_post']) {
			return false;
		} else {
			return $this->modPrivs;
		}
	}
	
	
	function canEditFirstPost(&$post) {
		return false;
	}
	
	
	function canEditPost(&$post) {
		if (isset($post['first_post']) && $post['first_post']) {
			return false;
		} else {
			return $this->modPrivs;
		}
	}


	function postHasTooManyHyperlinks($post, $hyperlinkLimit) {
		if ($hyperlinkLimit > 0) {
			//Limit set
			return (substr_count($post, '<a') > $hyperlinkLimit);
		} else {
			//Limit disabled
			return false;
		}
	}

	function addNofollowToHyperlinks(&$post) {
		if (!empty($post)) {
			$doc = new DOMDocument();
			$doc->formatOutput = false;
			$doc->loadHTML($post, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$links = $doc->getElementsByTagName('a');
			
			foreach ($links as $link) {
				$link->setAttribute('rel', 'nofollow');
			}

			$post = $doc->saveHTML();
		}
	}
	
	
	function canLockThread() {
		return $this->modPrivs && !$this->locked();
	}
	
	
	function canMakeThread() {
		return false;
	}
	
	
	function canMoveThread() {
		return false;
	}
	
	
	function canSubsThread() {
		return false;
	}
	
	function couldSubsThread() {
		return false;
	}
	
	function hasSubsThread() {
		return false;
	}
	
	
	function canMakePost() {
		return !$this->locked() && $this->postPrivs;
	}
	
	
	function canQuotePost() {
		return $this->canMakePost() && $this->setting('enable_reply_with_quote');
	}
	
	
	function canReportPost() {
		return $this->setting('enable_report_a_post') && $this->setting('email_template_for_reports');
	}
	
	
	function canUnlockThread() {
		return $this->modPrivs && $this->locked();
	}
	
	
	
	//Add action buttons to a post, as appropriate
	function checkPostActions(&$post, &$mergeFields, &$sections) {
		$controls = false;
		
		if ($this->canQuotePost() && ($post['status']=='published') ) {
			$controls = true;
			$sections['Quote_Post'] = true;
			$mergeFields['Quote_Post_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_request=post_reply&comm_enter_text=1&comm_post='. $post['id']. '&forum_thread='. $this->thread['id']);
		}

		if ($this->canEditFirstPost($post)) {
			$dateTime = new DateTime($post['date_posted']);
			$dateTime->modify('+1 hour');
			$timestampPostedPlus1Hr = $dateTime->getTimestamp();
			unset($dateTime);
			
			$timestampNow = strtotime(ze\date::now());
			
			if (ze\admin::id() || ($timestampPostedPlus1Hr >= $timestampNow)) {
				$controls = true;
				$sections['Edit_Post'] = true;
				$mergeFields['Edit_Post_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_request=edit_first_post&comm_enter_text=1&comm_post='. $post['id']. '&forum_thread='. $this->thread['id']);
			}
		
		} elseif ($this->canEditPost($post)) {
			$dateTime = new DateTime($post['date_posted']);
			$dateTime->modify('+1 hour');
			$timestampPostedPlus1Hr = $dateTime->getTimestamp();
			unset($dateTime);
			
			$timestampNow = strtotime(ze\date::now());
			
			if (ze\admin::id() || ($timestampPostedPlus1Hr >= $timestampNow)) {
				$controls = true;
				$sections['Edit_Post'] = true;
				$mergeFields['Edit_Post_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_request=edit_post&comm_enter_text=1&comm_post='. $post['id']. '&forum_thread='. $this->thread['id']);
			}
		}

		if ($this->canDeletePost($post)) {
			$controls = true;
			$sections['Delete_Post'] = true;
			$mergeFields['Delete_Post_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_request=delete_post&comm_confirm=1&comm_post='. $post['id']. '&forum_thread='. $this->thread['id']);
		}

		if (($post['status'] ?? false)=='pending' && $this->canApprovePost()) {
			$controls = true;
			$sections['Approve_Post'] = true;
			$mergeFields['Approve_Post_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_request=approve_post&comm_confirm=1&comm_post='. $post['id']. '&forum_thread='. $this->thread['id'] . '&checksum=' . md5($post['message_text'] ?? $this->thread['title']));
		}

		if ($this->canReportPost() && ($post['status']=='published') ) {
			$controls = true;
			$sections['Report_Post'] = true;
			$mergeFields['Report_Post_Link'] =
				' onclick="'.
					$this->refreshPluginSlotJS('&comm_page='. $this->page. '&comm_request=report_post&comm_enter_text=1&comm_post='. $post['id']. '&forum_thread='. $this->thread['id']).
					' return false;"';

			
		}
		
		return $controls;
	}
	
	
	function runCheckPrivs() {
		
		$this->modPrivs = ze\priv::check('_PRIV_MODERATE_USER_COMMENTS');
		
		$this->postPrivs = true;
		
		if (empty($_SESSION['confirm_key'])) {
			$_SESSION['confirm_key'] = ze\ring::random();
		}
	}
	
	
	//Remove a comment from the thread
	function deletePost() {
		
		//Remove the comment
		$sql = "
			DELETE FROM ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "user_comments
			WHERE content_id = ". (int) $this->cID. "
			  AND content_type = '". ze\escape::asciiInSQL($this->cType). "'
			  AND id = ". (int) $this->post['id'];
		
		$result = ze\sql::update($sql);
		
		//Update the post count for the thread
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "comment_content_items SET
				post_count = post_count - 1
			WHERE post_count > 0
			  AND content_id = ". (int) $this->cID. "
			  AND content_type = '". ze\escape::asciiInSQL($this->cType). "'";
		
		$result = ze\sql::update($sql);
	}

	function deletePostById($id) {
		$contentId = ze\row::get(ZENARIO_ANONYMOUS_COMMENTS_PREFIX . 'user_comments', 'content_id', ['id' =>  $id ]);
		$contentType = ze\row::get(ZENARIO_ANONYMOUS_COMMENTS_PREFIX . 'user_comments', 'content_type', ['id' =>  $id ]);

		ze\row::delete(ZENARIO_ANONYMOUS_COMMENTS_PREFIX . 'user_comments', ['id' =>  $id ]);
		
		//Update the post count for the thread
		$sql = "
			UPDATE "
				. DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "comment_content_items 
			SET	
				post_count = post_count - 1
			WHERE 
					post_count > 0
				AND content_id = ". (int) $contentId . "
				AND content_type = '".$contentType . "'";
		
		$result = ze\sql::update($sql);
	}

	function approvePost() {
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "user_comments
				SET status='published'
			WHERE content_id = ". (int) $this->cID. "
			  AND content_type = '". ze\escape::asciiInSQL($this->cType). "'
			  AND id = ". (int) $this->post['id'];
		
		$result = ze\sql::update($sql);

		$this->sendEmailNotification((int) $this->post['id']);
		
		
	}
	
	function approvePostById($id) {

		$sql = "
			UPDATE "
				. DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "user_comments
			SET 
				status='published'
			WHERE 
				id = ". (int) $id;
		
		$result = ze\sql::update($sql);

		$this->sendEmailNotification((int) $id);
		
	}

	//Edit a comment
	function editPost($userId, $messageText, $posterName = "") {
		
		//Add the comment
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "user_comments SET
				message_text = '". ze\escape::sql(zenario_anonymous_comments::sanitiseHTML($messageText, $this->setting('enable_images'), $this->setting('enable_links'))). "',";
		if ($posterName) {
			$sql .= "poster_name = '" . ze\escape::sql($posterName) . "',";
		}
		$sql .= "date_updated = NOW(),
				updater_id = ". (int) $userId. "
			WHERE content_id = ". (int) $this->cID. "
			  AND content_type = '". ze\escape::asciiInSQL($this->cType). "'
			  AND id = ". (int) $this->post['id'];
		
		$result = ze\sql::update($sql);
		
		$this->sendEmailNotification((int) $this->post['id'], false);
	}
	
	//Get a user's screen_name, if we're showing screennames
	function getUserScreenName($userId) {
		if (!$userId) {
			return $this->phrase('Anonymous');
		} elseif (ze::setting('user_use_screen_name')) {
			return ze\user::screenName($userId);
		} else {
			return '';
		}
	}
	
	//Get a user's screen name, and add a Storekeeper Link if in Admin mode with the correct Perms
	function getUserScreenNameLink($userId, $screenName = false, $alwaysShowLink = false) {
		if ($screenName === false) {
			$screenName = $this->getUserScreenName($userId);
		}
		
		if (!$screenName) {
			return '';
		} elseif ($userId && ($alwaysShowLink || ze\priv::check('_PRIV_VIEW_USER'))) {
			return '<a href="'. ze\link::absolute(). 'organizer.php#zenario__users/panels/users//'. $userId. '/" target="_blank">'. htmlspecialchars($screenName). '</a>';
		} else {
			return htmlspecialchars($screenName);
		}
	}
	
	
	function loadThreadInfo() {
		
		//Get information on comments for this content item from the mirror table
		$sql = "
			SELECT
				0 AS id,
				date_updated,
				updater_id,
				post_count,
				locked
			FROM ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "comment_content_items
			WHERE content_id = ". (int) $this->cID. "
			  AND content_type = '". ze\escape::asciiInSQL($this->cType). "'";
		
		$result = ze\sql::select($sql);
		
		if ($this->thread = ze\sql::fetchAssoc($result)) {
			return;
		}
		
		//If we didn't find a row, then add one in
		$sql = "
			INSERT INTO ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "comment_content_items SET
				content_id = ". (int) $this->cID. ",
				content_type = '". ze\escape::asciiInSQL($this->cType). "'";
		ze\sql::update($sql);
		
		$this->thread = [
			'id' => 0,
			'date_updated' => null,
			'updater_id' => 0,
			'post_count' => 0,
			'locked' => 0];
	}
	
	
	//Setup and generate Pagination
	function loadPagination() {
		
		if ($this->mode == 'showPosts') {
			$this->pageSize = (int) $this->setting('page_size_posts') ?: 12;
			
			//Don't show pagination if a specific post id is beind displayed
			if (ze::request('comm_post')) {
				return;
			}
			
			$pageCount = (int) ceil($this->thread['post_count'] / $this->pageSize);
			
			//Show the last page in the thread when adding a new reply
			if (ze::request('comm_enter_text') || $this->page == -1) {
				$this->page = $pageCount;
			}
			
			//Don't show pagination when the enter-reply box is displayed
			if (ze::request('comm_enter_text')) {
				return;
			}
			
			$paginationStyleSettingName = 'pagination_style_posts';
		
		} elseif ($this->mode == 'showThreads') {
			$this->pageSize = (int) $this->setting('page_size_threads') ?: 12;
			$pageCount = (int) ceil($this->forum['thread_count'] / $this->pageSize);
			
			$paginationStyleSettingName = 'pagination_style_threads';
		
		} elseif ($this->mode == 'showSearch' && $this->results) {
			$this->pageSize = (int) $this->setting('page_size_search') ?: 12;
			$pageCount = (int) ceil($this->results / $this->pageSize);
			
			$paginationStyleSettingName = 'pagination_style_search';
		
		} else {
			return;
		}
		
		
		$pages = [];
		for ($i=1; $i <= $pageCount; ++$i) {
			$pages[$i] = '&comm_page='. $i;
			
			if ($this->mode == 'showPosts' && ze::request('forum_thread')) {
				$pages[$i] .= '&forum_thread='. (int) ze::request('forum_thread');
			}
			
			if ($this->mode == 'showSearch') {
				$pages[$i] .= '&searchString='. rawurlencode(ze::request('searchString'));
			}
		}
		
		$this->mergeFields['Pagination'] = '';
		$this->pagination($paginationStyleSettingName, $this->page, $pages, $this->mergeFields['Pagination']);
	}
	
	
	//Load all of the comments/posts within our current view range
	function loadPosts() {
		
		$this->posts = [];
	
		$sql = "
			SELECT
				id,
				date_posted,
				date_updated,
				status,
				poster_id,
				poster_name,
				poster_email,
				updater_id,
				message_text,
				rating
			FROM ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "user_comments
			WHERE content_id = ". (int) $this->cID. "
			  AND content_type = '". ze\escape::asciiInSQL($this->cType). "'";

		if ($this->setting('comments_require_approval') && !$this->modPrivs) {
			$sql .= "
				AND (
						status='published'";
			
			//As of 16 Nov 2022, for point 8 of the task T12234, Improve Comments (extranet user),
			//if a visitor is NOT logged in and NOT and admin, they will NOT see pending comments.
			$adminId = ze\admin::id();
			$userId = ze\user::id();
			
			if ($adminId || $userId) {
				$sql .= "
						OR	(
								status='pending'";
			
				if ($userId) {
					$sql .= "
							AND poster_id = ". (int) $userId;
				}
				
				$sql .= "
					)";
			}
			
			$sql .= "
				)";
		}
		
		//Have the option to just display a specific post
		if (ze::request('comm_post')) {
			$sql .= "
			  AND id = ". (int) ze::request('comm_post');
		} else {
			$sql .= "
			ORDER BY id";
			
			//Normally, display posts in order, unless the MOST_RECENT_FIRST option is checked and we're not making a reply.
			if ($this->setting('order') == 'MOST_RECENT_FIRST' && !ze::request('comm_enter_text')) {
				$sql .= " DESC";
			}
			
			if ($this->setting('order') == 'MOST_RECENT_FIRST' && ze::request('comm_enter_text')) {
				$sql .= "
				LIMIT ". max(0, ($this->thread['post_count'] - $this->pageSize)). ", ". (int) $this->pageSize;
			} else {
				$sql .= ze\sql::limit($this->page, $this->pageSize);
			}
		}
		
		$result = ze\sql::select($sql);

		if (ze::request('comm_post')) {
			//Attempt to get information on a specific post. If it doesn't exist, clear the request and reload the page
			if (!$this->posts[] = $this->post = ze\sql::fetchAssoc($result)) {
				$this->clearRequest('comm_post');
				$this->clearRequest('comm_request');
				$this->clearRequest('comm_confirm');
				$this->clearRequest('comm_enter_text');
				
				$this->loadPagination();
				$this->loadPosts();
			}
		
		} else {
			while($row = ze\sql::fetchAssoc($result)) {
				$this->posts[] = $row;
			}
		}
	}
	
	
	function locked() {
		return $this->thread['locked'];
	}
	
	
	function lockThread($lock) {
		
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "comment_content_items SET
				locked = ". (int) $lock. "
			WHERE content_id = ". (int) $this->cID. "
			  AND content_type = '". ze\escape::asciiInSQL($this->cType). "'";
		
		$result = ze\sql::update($sql);
	}
	
	
	//Send an email notification for a reported post
	function reportPost() {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
		
	function sendApproveRequest($userId, $messageText, $name = '', $email = '') {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}

	protected function sendEmailNotification($commentId, $newPost = true) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
		if (ze\module::inc('zenario_comments')) {
			zenario_comments::sendEmailNotificationToSubscribers($commentId, $newPost);
		}
	}
	
	protected function subs($subs, $thread = true) {
	}
	
	
	//Show an "are you sure" box
	function showConfirmBox($message, $submitButtonText) {

		$this->sections['Confirmation_Box'] = [];
		$this->sections['Confirmation_Box']['Submit_Button_Text'] = $submitButtonText;
		$this->sections['Confirmation_Box']['Confirmation_Message'] = $message;
		$this->sections['Confirmation_Box']['Cancel_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&forum_thread='. ze\ray::value($this->thread, 'id'), false);
		
		$this->sections['Confirmation_Box']['Open_Form'] = $this->openForm('', 'class="'. htmlspecialchars(ze::request('comm_request')). '"'). 
			$this->remember('comm_request').
			$this->remember('comm_page').
			$this->remember('comm_confirm').
			$this->remember('comm_post').
			$this->remember('forum_thread') .
			$this->remember('checksum', md5($this->post['message_text'] ?? ($this->thread['title'] ?? '')));
		
		$this->sections['Confirmation_Box']['Close_Form'] = $this->closeForm();
	}
	
	
	function showPosts() {
		
		$this->mergeFields['Post_Class'] = 'list_of_comments';
		
		if ($this->posts) {
			$this->sections['Post'] = [];
			$first = true;
			foreach($this->posts as &$post) {
				
				$mergeFields = [];
				
				if ($first && !empty($this->thread['title'])) {
					$first = false;
					$mergeFields['Show_Thread_Title'] = true;
					$mergeFields['Thread_Title'] = htmlspecialchars($this->thread['title']);
				}
				
				$mergeFields['Date_Posted'] = ze\date::formatDateTime($post['date_posted'], $this->setting('date_format'));
				$mergeFields['Post_Text'] = $post['message_text'];
				
				if ($post['status'] == 'pending') {
					$mergeFields['Pending_Post'] = true;
				}
				
				$this->showUserInfo($mergeFields, $mergeFields, $post['poster_id'], $post);
				
				$this->getExtraPostInfo($post, $mergeFields, $mergeFields /*, ze::request('comm_request') == 'edit_post'*/);
				
				if (ze::request('comm_confirm') || ze::request('comm_enter_text') || !($this->checkPostActions($post, $mergeFields, $mergeFields))) {
				} else {
					$mergeFields['Post_Controls'] = true;
				}
				
				$this->sections['Post'][] = $mergeFields;
			}
		}
		
		$this->framework('Posts', $this->mergeFields, $this->sections);
	}
	
	protected function getExtraPostInfo(&$post, &$mergeFields, &$sections, $to_edit=false){
	}
	
	
	//Show a post entry screen, with a TinyMCE box for inputting a message
	//Called with different options, it can also be used for quoting a post, editing a post or making a new thread
	function showPostScreen($labelText, $submitButtonText, $quoteMode, $titleText = false, $onSubmit = '') {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	
	function showPostScreenTopFields($titleText) {
		if ($this->setting('show_name')) {
			$this->sections['Show_Post_Name'] = true;
			$this->sections['Post_Message']['Post_Name'] = '';
			if (isset($_POST['comm_name']) && !is_array($_POST['comm_name'])) {
				//Make sure this is a string.
				$this->sections['Post_Message']['Post_Name'] = htmlspecialchars($_POST['comm_name']);
			
			} elseif (ze\user::screenName()) {
				$this->sections['Post_Message']['Post_Name'] =  htmlspecialchars(ze\user::screenName());
			
			} else {
				$sql = "
					SELECT poster_name
					FROM ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "user_comments
					WHERE poster_session_id = '". ze\escape::sql(ze\user::hashPassword(ze\link::primaryDomain(), session_id())). "'
					ORDER BY date_posted DESC
					LIMIT 1";
				
				if (($result = ze\sql::select($sql))
				 && ($row = ze\sql::fetchRow($result))) {
					$this->sections['Post_Message']['Post_Name'] =  htmlspecialchars($row[0]);
				}
			}
			
			if (!$this->sections['Post_Message']['Post_Name'] && ($adminId = ze\admin::id())) {
				$adminDetails = ze\admin::details($adminId);
				$this->sections['Post_Message']['Post_Name'] = $adminDetails['first_name'] . ' ' . $adminDetails['last_name'];
			}
			
			$this->sections['Post_Message']['Post_Name'] = '<input type="text" id="comm_name" name="comm_name" maxlength="50" value="'. $this->sections['Post_Message']['Post_Name']. '"/>';
		}
		
		if ($this->setting('show_email')) {
			$this->sections['Show_Post_Email'] = true;
			
			if (isset($_POST['comm_email']) && !is_array($_POST['comm_email'])) {
				//Make sure this is a string.
				$this->sections['Post_Message']['Post_Email'] = htmlspecialchars($_POST['comm_email']);
			
			} elseif (ze\user::email()) {
				$this->sections['Post_Message']['Post_Email'] =  htmlspecialchars(ze\user::email());
			
			} else {
				$sql = "
					SELECT poster_email
					FROM ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "user_comments
					WHERE poster_session_id = '". ze\escape::sql(ze\user::hashPassword(ze\link::primaryDomain(), session_id())). "'
					ORDER BY date_posted DESC
					LIMIT 1";
				
				if (($result = ze\sql::select($sql))
				 && ($row = ze\sql::fetchRow($result))) {
					$this->sections['Post_Message']['Post_Email'] =  htmlspecialchars($row[0]);
				}
			}
			
			$this->sections['Post_Message']['Post_Email'] = '<input type="email" id="comm_email" name="comm_email" maxlength="100" value="'. ($this->sections['Post_Message']['Post_Email'] ?? '') . '"/>';
		}
	}
	
	
	//Show/hide the action buttons for the current thread
	function showThreadActions() {
		$loginCID = $loginCType = false;
		
		if ($this->canMakePost()) {
			$this->sections['Add_Reply'] = true;
			$this->mergeFields['Add_Reply_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_request=post_reply&comm_enter_text=1&forum_thread='. $this->thread['id'], false);
			
		} elseif ($this->locked()) {
			if (!$this->canUnlockThread()) {
				$this->sections['Thread_Locked'] = true;
			}
			
		} elseif (empty($_SESSION['extranetUserID']) && ze\content::langSpecialPage('zenario_login', $loginCID, $loginCType)) {
			$this->sections['Login_To_Post'] = true;
			$this->mergeFields['Login_To_Post_Link'] = $this->linkToItemAnchor($loginCID, $loginCType);
		}
		
		if ($this->canSubsThread()) {
			if ($this->hasSubsThread()) {
				$this->sections['Unsubs_To_Thread'] = true;
				$this->sections['Subscribed_To_Thread'] = true;
				$this->mergeFields['Unsubs_To_Thread_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_request=unsubs_thread&comm_confirm=1&forum_thread='. $this->thread['id'], false);
			} else {
				$this->sections['Subs_To_Thread'] = true;
				$this->mergeFields['Subs_To_Thread_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_request=subs_thread&comm_confirm=1&forum_thread='. $this->thread['id'], false);
			}
		
		} elseif ($this->couldSubsThread()) {
			$this->sections['Login_To_Subs_To_Thread'] = true;
			
			if (empty($this->mergeFields['Login_To_Post_Link'])) {
				$this->mergeFields['Login_To_Post_Link'] = $this->linkToItemAnchor($loginCID, $loginCType);
			}
		}
		
		if ($this->canLockThread()) {
			$this->sections['Lock_Thread'] = true;
			$this->mergeFields['Lock_Thread_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_request=lock_thread&comm_confirm=1&forum_thread='. $this->thread['id'], false);
			
		} elseif ($this->canUnlockThread()) {
			$this->sections['Unlock_Thread'] = true;
			$this->mergeFields['Unlock_Thread_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_request=unlock_thread&comm_confirm=1&forum_thread='. $this->thread['id'], false);
		}
		
		if ($this->canMoveThread()) {
			$this->sections['Move_Thread'] = true;
			$this->mergeFields['Move_Thread_Link'] = 'href="#" onclick="'. $this->moduleClassName. ".moveThread('". $this->slotName. "', '". ze\admin::phrase('Move thread to a different forum'). "', ". $this->forumId. ", ". $this->thread['id']. ", '". ($_SESSION['confirm_key'] ?? false). "'); return false;". '"';
		}
		
		if ($this->canDeleteThread()) {
			$this->sections['Delete_Thread'] = true;
			$this->mergeFields['Delete_Thread_Link'] = $this->refreshPluginSlotAnchor('&comm_request=delete_thread&comm_confirm=1&forum_thread='. $this->thread['id'], false);
		}
	}
	
	
	//Show detailed information on one user, to appear next to their post
	function showUserInfo(&$mergeFields, &$sections, $userId, $post = false) {
		
		$postScreen = empty($post);
		if (!$postScreen) {
			if ($this->setting('show_name')) {
				$mergeFields['Username_Link'] = htmlspecialchars($post['poster_name'] ?: $this->phrase('Anonymous'));
			}
			if ($this->setting('show_email')) {
				$mergeFields['Email'] = str_replace('@', '<span class="zenario_dn">ie</span>@<span class="zenario_dn">the</span>', htmlspecialchars($post['poster_email']));
			}
		}
	
		return;
	}
	
	//Handle any requests the users ask for
	function threadActionHandler() {
		if (ze::post('comm_request')) {
			require ze::funIncPath(__FILE__, __FUNCTION__);
		}
	}
	
	
	function threadSelectMode() {
		
		if (ze::request('comm_confirm')) {
			if (ze::request('comm_request') == 'delete_post' && $this->canDeletePost($this->post)) {
				$this->showConfirmBox($this->phrase('Are you sure that you wish to delete this comment?'), $this->phrase('Delete comment'));
				
			} elseif (ze::request('comm_request') == 'approve_post' && $this->canApprovePost()) {
				if (ze::request('checksum') == md5($this->post['message_text'] ?? $this->thread['title'])) {
					$this->showConfirmBox($this->phrase('Are you sure that you wish to approve this comment?'), $this->phrase('Approve comment'));
				} else {
					$this->showConfirmBox($this->phrase('The comment has just been edited. Please review it again.'), $this->phrase('Approve modified comment'));
				}
				
			} elseif (ze::request('comm_request') == 'delete_thread' && $this->canDeleteThread()) {
				$this->showConfirmBox($this->phrase('Are you sure that you wish to delete this thread?'), $this->phrase('Delete thread'));
				
			} elseif (ze::request('comm_request') == 'lock_thread' && $this->canLockThread()) {
				$this->showConfirmBox($this->phrase('Are you sure that you wish to disallow further comments?'), $this->phrase('Disallow further comments'));
				
			} elseif (ze::request('comm_request') == 'unlock_thread' && $this->canUnlockThread()) {
				$this->showConfirmBox($this->phrase('Are you sure that you wish to allow further comments?'), $this->phrase('Allow further comments'));
				
			} elseif (ze::request('comm_request') == 'subs_thread' && $this->canSubsThread()) {
				$this->showConfirmBox($this->phrase('Are you sure you wish to subscribe? A notification email will be sent to &quot;[[email]]&quot; when a new comment is made on this page.', ['email' => htmlspecialchars(ze\user::email())]), $this->phrase('Subscribe'));
				
			} elseif (ze::request('comm_request') == 'unsubs_thread' && $this->canSubsThread()) {
				$this->showConfirmBox($this->phrase('Are you sure you wish to unsubscribe, and no longer be notified of new comments on this page?'), $this->phrase('Unsubscribe'));
			}
			
		} elseif (ze::request('comm_enter_text')) {
			if (ze::request('comm_request') == 'edit_first_post' && $this->canEditFirstPost($this->post)) {
				$this->showPostScreen($this->phrase('Edit comment:'), $this->phrase('Save comment'), 'edit', $this->phrase('Edit title:'));
			
			} elseif (ze::request('comm_request') == 'edit_post' && $this->canEditPost($this->post)) {
				$this->showPostScreen($this->phrase('Edit comment:'), $this->phrase('Save comment'), 'edit');
			
			} elseif (ze::request('comm_request') == 'post_reply' && $this->canMakePost()) {
				$this->showPostScreen($this->phrase('Enter a comment:'), $this->phrase('Add comment'), 'quote');
			
			} elseif (ze::request('comm_request') == 'report_post' && $this->canReportPost()) {
				$this->showPostScreen($this->phrase('Report the comment above as offensive:'), $this->phrase('Report this comment'), 'none');
			}
			
		} else {
			$this->showThreadActions();
		}
	}
	
	function enableCaptcha() {
		return $this->setting('enable_captcha') && ze::setting('google_recaptcha_site_key') && ze::setting('google_recaptcha_secret_key');
	}
	
	
	
	
	
	
	
	
	
	
	public static function commentsOnPage($cID, $cType = 'html') {
		return (int) ze\row::get(
			ZENARIO_ANONYMOUS_COMMENTS_PREFIX. 'comment_content_items',
			'post_count',
			['content_id' => (int) $cID, 'content_type' => $cType]);
	}
	
	public static function commentsOnPage_framework(&$mergeFields) {
		$cID = ze\ray::grabValue($mergeFields, 'cid', 'contentid', 'id');
		$cType = ze\ray::grabValue($mergeFields, 'ctype', 'contenttype', 'type');
		
		return zenario_anonymous_comments::commentsOnPage($cID, $cType);
	}
	
	public static function commentsOnThisPage_framework() {
		return zenario_anonymous_comments::commentsOnPage(ze::$cID, ze::$cType);
	}
	
	public function fillOrganizerPanel($path, &$panel, $refinerName, $refinerId, $mode) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}

	public function handleOrganizerPanelAJAX($path, $ids, $ids2, $refinerName, $refinerId) {	
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	public function fillAdminBox($path, $settingGroup, &$box, &$fields, &$values) {
		switch ($path) {
			case 'plugin_settings':
				if (!ze::setting('google_recaptcha_site_key') || !ze::setting('google_recaptcha_secret_key')) {
					//Show warning
					$recaptchaLink = "<a href='organizer.php#zenario__administration/panels/site_settings//api_keys~.site_settings~tcaptcha_picture~k{\"id\"%3A\"api_keys\"}' target='_blank'>site settings</a>";
					$box['tabs']['posting']['notices']['captcha_keys_not_set']['message'] = $this->phrase(
						"Recaptcha keys are not set. To show a captcha you must set the recaptcha [[recaptcha_link]].",
						['recaptcha_link' => $recaptchaLink]
					);
					$box['tabs']['posting']['notices']['captcha_keys_not_set']['show'] = true;
				}

				if (empty($fields['moderation/notification_email_template']['value'])) {
					$fields['moderation/notification_email_template']['value'] = 'zenario_comments__to_admin_comment_notification_en';
				}

				if (empty($fields['moderation/email_template_for_reports']['value'])) {
					$fields['moderation/email_template_for_reports']['value'] = 'zenario_comments__comment_report';
				}

				if (empty($fields['moderation/email_template_for_approve_requests']['value'])) {
					$fields['moderation/email_template_for_approve_requests']['value'] = 'zenario_comments__comment_awaiting_approval';
				}

				$box['tabs']['order']['fields']['pagination_style_posts']['values'] = 
					ze\pluginAdm::paginationOptions();
				
				if (isset($box['tabs']['order']['fields']['pagination_style_threads'])) {
					$box['tabs']['order']['fields']['pagination_style_threads']['values'] = 
						ze\pluginAdm::paginationOptions();
				}
				
				if (isset($box['tabs']['moderation']['fields']['email_address_for_reports']) && !$box['key']['instanceId']) {
					$box['tabs']['moderation']['fields']['email_address_for_reports']['value'] = ze::setting('email_address_admin');
				}
				
				
				$moderators =
					isset($box['tabs']['moderation']['fields']['moderators']);
				$restrict_posting_to_group =
					isset($box['tabs']['posting']['fields']['restrict_posting_to_group']);
				$restrict_new_thread_to_group =
					isset($box['tabs']['posting']['fields']['restrict_new_thread_to_group']);
				
				if ($moderators
				 || $restrict_posting_to_group
				 || $restrict_new_thread_to_group) {
					
					$groups = ze\datasetAdm::listCustomFields('users', $flat = false, $filter = 'groups_only', true, true);
					//ze\datasetAdm::listCustomFields($dataset, $flat = true, $filter = false, $customOnly = true, $useOptGroups = false)
					
					if ($moderators) {
						$box['tabs']['moderation']['fields']['moderators']['values'] = $groups;
					}
					if ($restrict_posting_to_group) {
						$box['tabs']['posting']['fields']['restrict_posting_to_group']['values'] = $groups;
					}
					if ($restrict_new_thread_to_group) {
						$box['tabs']['posting']['fields']['restrict_new_thread_to_group']['values'] = $groups;
					}
				}
				
				foreach ($fields as &$field) {
					if (!empty($field['note_below'])
					 && $field['note_below'] == '_insert_email_template_note_here_') {
						$field['note_below'] =
							ze\admin::phrase('Please see the <a href="[[link]]" target="_blank">module description</a> to get a full list of merge fields which can be used in the selected email template.',
								['link' => htmlspecialchars(
									ze\link::absolute().
									'organizer.php#zenario__modules/panels/modules//'. $box['key']['moduleId']. '/')]);
						
						
					}
				}
				
				break;
		}
	}
	
	public function formatAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		//...
	}
}