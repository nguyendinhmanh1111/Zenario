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





class zenario_forum extends zenario_comments {
	
	protected $useCannonicalURLs = true;
	protected $siteDefaultLanguage = false;
	protected $forumNotSetUp = false;
	protected $allow_uploads = false;
	static $forum_post_upload_dbkey = 'form_post_upload';
	
	protected $forumId;
	protected $forumLocked;
	protected $threads;
	protected $threadId;
	
	public function fillOrganizerPanel($path, &$panel, $refinerName, $refinerId, $mode) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	public function handleOrganizerPanelAJAX($path, $ids, $ids2, $refinerName, $refinerId) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	
	public function fillAdminSlotControls(&$controls) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	public function handleAJAX() {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	public function fillAdminBox($path, $settingGroup, &$box, &$fields, &$values) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	public function formatAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	public function validateAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes, $saving) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	public function saveAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	
	
	//Check the forum table for the current page, using Translations if needed to check for language equivalent
	protected function checkPageType($field) {
		
		$cID = $cType = false;
		if ($forumId = ze\row::get(ZENARIO_FORUM_PREFIX. "forums", 'id', [$field. '_content_id' => $this->cID, $field. '_content_type' => $this->cType])) {
			return $forumId;
		
		} elseif (ze\content::langEquivalentItem($cID, $cType, ze::$defaultLang) &&
		  ($forumId = ze\row::get(ZENARIO_FORUM_PREFIX. "forums", 'id', [$field. '_content_id' => $this->cID, $field. '_content_type' => $this->cType]))) {
			return $forumId;
		
		} else {
			return false;
		}
	}
	
	
	
	
	public function init() {
		if($this->setting('allow_uploads')) {
			$this->allow_uploads = true;
			$this->form_encode_type = 'multipart/form-data';
			$this->sections['Show_File_Uploads'] = true;
		}
		
		if (!$this->setting('show_user_online_status')) {
			$this->allowCaching(
				$atAll = true, $ifUserLoggedIn = false, $ifGetSet = true, $ifPostSet = false, $ifSessionSet = true, $ifCookieSet = true);
			$this->clearCacheBy(
				$clearByContent = false, $clearByMenu = false, $clearByUser = true, $clearByFile = true, $clearByModuleData = true);
		}

		//Require the phrases
		ze::requireJsLib('zenario/modules/zenario_anonymous_comments/js/editor_phrases.js.php?langId='. ze::$visLang);
		
		if (ze::in(ze\content::isSpecialPage($this->cID, $this->cType), 'zenario_no_access', 'zenario_not_found')) {
			return $this->show = false;
		}
		
		$this->page = (int) ($_REQUEST['comm_page'] ?? 1) ?: 1;
		
		//Check to see if the current content item has a forum
		if ($this->forumId = $this->checkPageType('forum')) {
			if (!$this->loadForumInfo()) {
				return $this->show = false;
			
			} elseif (ze::request('comm_request') == 'add_thread' && $this->newThreadPageIsForumPage() && $this->canMakeThread()) {
				$this->mode = 'showAddThread';
				$this->useCannonicalURLs = false;
				$this->allowCaching(false);
				$_GET['comm_enter_text'] = 1;
			
			} elseif (ze::request('forum_thread') && $this->threadPageIsForumPage() && $this->loadThreadInfo()) {
				$this->mode = 'showPosts';
				$this->useCannonicalURLs = false;
				$this->allowCaching(false);
			
			} else {
				$this->mode = 'showThreads';
			}
			
		//If not, check to see if the current content item is used for a different forum as a thread page...
		} elseif ($this->forumId = $this->checkPageType('thread')) {
			if ($this->loadForumInfo()) {
				if ($this->loadThreadInfo()) {
					$this->mode = 'showPosts';
				} else {
					if (ze::request('forum_thread')) {
						$this->clearRequest('forum_thread');
						if ($this->loadThreadInfo()) {
							$this->mode = 'showPosts';
						} else {
							return $this->show = false;
						}
					} else {
						return $this->show = false;
					}
				}
			} else {
				return $this->show = false;
			}
			
		//...or if the current content item is used for a different forum as a new thread page
		} elseif ($this->forumId = $this->checkPageType('new_thread')) {
			if ($this->loadForumInfo() &&$this->canMakeThread()) {
				$this->mode = 'showAddThread';
				$_GET['comm_enter_text'] = 1;
			
			} else {
				return $this->show = false;
			}
		
		} else {
			$this->forumNotSetUp = true;
			return $this->show = false;
		}
		
		
		if ($this->useCannonicalURLs) {
			$this->registerGetRequest('forum_thread');
			$this->registerGetRequest('comm_request');
			$this->registerGetRequest('comm_page', 1);
		}
		
		
		if ($this->mode == 'showPosts') {
			
			if ($_SESSION['extranetUserID'] ?? false) {
				$this->markThreadCheckUserIsInTable($_SESSION['extranetUserID'] ?? false);
				$this->markThreadAsRead($_SESSION['extranetUserID'] ?? false);
			}
			
			$this->loadPagination();
			$this->loadPosts();
			
			$this->threadActionHandler();
			
			$this->threadSelectMode();
			
			$this->mergeFields['User_is_admin'] = ze\admin::id();
			
			if ($this->useCannonicalURLs) {
				$this->setPageTitle($this->thread['title']);
				$this->setMenuTitle($this->thread['title']);
			}
		
		} elseif ($this->mode == 'showAddThread') {
			
			if ($_SESSION['extranetUserID'] ?? false) {
				$this->markThreadCheckUserIsInTable($_SESSION['extranetUserID'] ?? false);
			}
			
			$failure = false;
			if (isset($_POST['comm_title']) && empty($_POST['comm_title'])) {
				//complain about required fields
				$failure = true;
				$this->postingErrors[] = ['Error' => $this->phrase('Please enter a title.')];
			}
			if (isset($_POST['comm_message']) && empty($_POST['comm_message'])) {
				//complain about required fields
				$failure = true;
				$this->postingErrors[] = ['Error' => $this->phrase('Please enter a message.')];
			}
			
			if (!empty($_POST['comm_title']) && !empty($_POST['comm_message'])) {
				$this->addThread($_SESSION['extranetUserID'] ?? false, $_POST['comm_title'], $_POST['comm_message']);
				
				$this->clearRequest('comm_post');
				$this->clearRequest('comm_request');
				$this->clearRequest('comm_confirm');
				$this->clearRequest('comm_enter_text');
				
				$this->headerRedirect($this->linkToItem($this->forum['thread_content_id'], $this->forum['thread_content_type'], true, '&forum_thread='. $this->threadId));
				return true;
			} else {
				//Show the form again and get the User to enter the required fields
			}
			
			$this->showPostScreen($this->phrase('Enter a Message:'), $this->phrase('Add Thread'), 'none', $this->phrase('Enter a Title for this new Thread:'));
		
		} elseif ($this->mode == 'showThreads') {
			
			if ($_SESSION['extranetUserID'] ?? false) {
				$this->markThreadCheckUserIsInTable($_SESSION['extranetUserID'] ?? false);
			}
			
			$this->forumActionHandler();
			
			$this->loadPagination();
			$this->loadThreads();
			$this->forumSelectMode();
		}
		
		
		
		return true;
	}
	
	function showPostScreenTopFields($titleText) {
		if ($titleText !== false) {
			$this->sections['Show_Post_Title'] = true;
			
			if (isset($_POST['comm_title'])) {
				$this->sections['Post_Message']['Post_Title'] = htmlspecialchars($_POST['comm_title']);
			
			} else {
				$this->sections['Post_Message']['Post_Title'] =  htmlspecialchars(ze\ray::value($this->thread, 'title'));
			}
			
			$this->sections['Post_Message']['Post_Title'] = '<input type="text" id="comm_title" name="comm_title" maxlength="255" value="'. $this->sections['Post_Message']['Post_Title']. '"/>';
			
			
			$this->sections['Post_Message']['Title_Text'] = $titleText;
		}
	}
	
	
	//Add a comment onto the thread
	function addReply($userId, &$messageText, $firstPost = 0, $name = '', $email = '') {
		return require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	//Add a new thread
	function addThread($userId, &$threadTitle, &$messageText) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	function calcForumPostCount($forumId, $quickAdd = false, $userId = false, $firstPost = false) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	function calcThreadPostCount($threadId, $quickAdd = false, $userId = false) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	function calcUserPostCount($userId, $quickAdd = false) {
		
		//Update the user's post count
		if ($quickAdd) {
			$sql = "
				UPDATE ". DB_PREFIX. ZENARIO_COMMENTS_PREFIX. "users SET
					post_count = post_count + 1
				WHERE user_id = ". (int) $userId;
		} else {
			$sql = "
				UPDATE ". DB_PREFIX. ZENARIO_COMMENTS_PREFIX. "users SET
					post_count = (
						SELECT COUNT(*)
						FROM ". DB_PREFIX. ZENARIO_ANONYMOUS_COMMENTS_PREFIX. "user_comments
						WHERE poster_id = ". (int) $userId. "
					) + (
						SELECT COUNT(*)
						FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_posts
						WHERE poster_id = ". (int) $userId. "
					)
				WHERE user_id = ". (int) $userId;
		}
		
		$result = ze\sql::update($sql);
	}
	
	
	function canDeleteThread() {
		return $this->modPrivs;
	}
	
	
	function canEditFirstPost(&$post) {
		if (empty($post['first_post'])) {
			return false;
		} elseif ($this->modPrivs) {
			return true;
		} else {
			return ($_SESSION['extranetUserID'] ?? false)
				&& $this->setting('allow_user_edit_own_post')
				&& $post['poster_id'] == ($_SESSION['extranetUserID'] ?? false)
				&& !$this->locked();
		}
	}
	
	
	function canMakeThread() {
		if (ze::setting('user_use_screen_name')) {
			$confirmed = self::getUserScreenNameConfirmed(ze\user::id());
		} else {
			$confirmed = true;
		}
		return $confirmed && !$this->lockedForum() && $this->newThreadPrivs;
	}
	
	function canMoveThread() {
		return ze\priv::check('_PRIV_MODERATE_USER_COMMENTS')
			&& ($result = ze\row::query(ZENARIO_FORUM_PREFIX. 'forums', ['id'], []))
			&& ($row = ze\sql::fetchAssoc($result))
			&& ($row['id'] != $this->forumId || (
			   ($row = ze\sql::fetchAssoc($result))
			&& ($row['id'] != $this->forumId)));
	}
	
	function canLockThread() {
		return $this->modPrivs && !$this->locked() && !$this->lockedForum();
	}
	
	function canUnlockThread() {
		return $this->modPrivs && $this->locked() && !$this->lockedForum();
	}
	
	function canSubsThread() {
		return ($_SESSION['extranetUserID'] ?? false) && $this->setting('enable_thread_subs');
	}
	
	function canSubsForum() {
		return ($_SESSION['extranetUserID'] ?? false) && $this->setting('enable_forum_subs');
	}
	
	function couldSubsForum() {
		return !($_SESSION['extranetUserID'] ?? false) && $this->setting('enable_forum_subs') && !$this->locked();
	}
	
	function hasSubsThread() {
		return ze\row::exists(
			ZENARIO_COMMENTS_PREFIX. 'user_subscriptions',
			[
				'user_id' => ($_SESSION['extranetUserID'] ?? false),
				'forum_id' => $this->forumId,
				'thread_id' => $this->threadId]);
	}
	
	function hasSubsForum() {
		return ze\row::exists(
			ZENARIO_COMMENTS_PREFIX. 'user_subscriptions',
			[
				'user_id' => ($_SESSION['extranetUserID'] ?? false),
				'forum_id' => $this->forumId,
				'thread_id' => 0]);
	}
	
	function checkConfirmKey() {
		return ze::get('comm_key') && ze::get('comm_key') == ($_SESSION['confirm_key'] ?? false);
	}
	
	
	function runCheckPrivs() {
		
		zenario_comments::runCheckPrivs();
		
		$this->newThreadPrivs =
			($_SESSION['extranetUserID'] ?? false) && (
					$this->modPrivs
				 || !$this->setting('enable_new_thread_restrictions')
				 || !$this->setting('restrict_new_thread_to_group')
				 || ze\user::isInGroup($this->setting('restrict_new_thread_to_group'))
			);
	}

	
	
	function deleteThread() {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	protected function manageOneUpload($postId, $location, $file_name){
	
	
		ze\fileAdm::exitIfUploadError(false, $location, $file_name);
	
		if($this->allow_uploads){
			if(!$location || !strlen($location)) return;
			
			if (!ze\file::isImage(ze\file::mimeType($file_name))) {
				return;
			}
			
			if ($fileId = ze\file::addToDatabase(self::$forum_post_upload_dbkey, $location, $file_name, false, false, true)) {
				$using_ids = ['file_id' => (int)$fileId, 'post_id' => (int)$postId];
				$using_values = $using_ids;
				$using_values['caption'] = ze\escape::sql($file_name);
				ze\row::set(ZENARIO_FORUM_PREFIX . 'user_posts_uploads', $using_values, $using_ids);
			}
		}
	}

	function startswith($haystack, $needle) {
		return substr($haystack, 0, strlen($needle)) === $needle;
	}

	function split_str_value_to_value($haystack, $needle) {
		$key_size = strlen($needle);
		if(substr($haystack, 0, $key_size) === $needle){
			return substr($haystack, $key_size);
		}
		return false;
	}
	
	protected function manageUploads($postId){
		if($this->allow_uploads){
			
			if($postId){
				foreach($_POST as $key => $value){
					$file_id = $this->split_str_value_to_value($key, 'file_caption_');
					if(is_numeric($file_id) && strlen($value)){
						ze\row::set(ZENARIO_FORUM_PREFIX . 'user_posts_uploads', ['caption' => ze\escape::sql($value)], 
							['post_id' => (int)$postId, 'file_id' => (int)$file_id]);
					}
				}
			}
			/*
			for($i=1; $i <= 5; ++$i){
				$upload_field =  'post_upload' . $i;
				if (!empty($_FILES[$upload_field])){
					$this->manageOneUpload($postId, $_FILES[$upload_field]['tmp_name'], $_FILES[$upload_field]['name']);
				}
			}
			*/
			
			//multiple files upload
			if(!empty($_FILES['filesToUpload'])){
				$filesToUpload = &$_FILES['filesToUpload'];
				$files_count = count($filesToUpload['name']);
				for($i=0; $i < $files_count; ++$i){
					if (empty($filesToUpload['error'][$i])) {
						$this->manageOneUpload($postId, $filesToUpload['tmp_name'][$i], $filesToUpload['name'][$i]);
					}
				}
			}
			
			
			if(!empty($_POST['remove_files'])){
				$filesToRemove = &$_POST['remove_files'];
				$files_count = count($filesToRemove);
				for($i=0; $i < $files_count; ++$i){
					$this->deleteOneUpload($postId, $filesToRemove[$i]);
				}
			}
			
		}
	}

	protected function deleteOneUploadFile($file_id){
		if($this->allow_uploads){
			if(!ze\row::get(ZENARIO_FORUM_PREFIX . 'user_posts_uploads', 'file_id', ['file_id' => (int)$file_id])){
				ze\file::delete($file_id);
			}
		}
	}
	
	protected function deleteOneUpload($postId, $file_id){
		if($this->allow_uploads){
			ze\row::delete(ZENARIO_FORUM_PREFIX. 'user_posts_uploads', ['post_id' => (int)$postId, 'file_id' => (int)$file_id]);
			$this->deleteOneUploadFile($file_id);
		}
	}
	
	protected function deleteUploads($postId){
		if($this->allow_uploads){
			
			ze\row::delete(ZENARIO_FORUM_PREFIX . 'user_posts_uploads', ['post_id' => $postId]);
			$file_list = ze\row::getValues(ZENARIO_FORUM_PREFIX . 'user_posts_uploads', 'file_id', ['post_id' => $postId], false, false,
					DB_PREFIX.ZENARIO_FORUM_PREFIX);
			if(is_array($file_list)){
				foreach($file_list as $file_id){
					$this->deleteOneUploadFile($file_id);
				}
			}
		}
	}
	
	protected function getExtraPostInfo(&$post, &$mergeFields, &$sections, $to_edit=false){
		$post_uploads = ze\row::getAssocs(ZENARIO_FORUM_PREFIX . 'user_posts_uploads', ['file_id','caption'], 
				['post_id' => $post['id']], false);
		if(is_array($post_uploads)){
			$mysection = 'Post_Uploads_Links';
			if($to_edit) $mysection .= '_To_Edit';
			
			$upload_links = [];
			foreach($post_uploads as $rec){
				$url = '';
				$width = $this->setting('image_thumbnail_width');
				$height = $this->setting('image_thumbnail_height');
				$file_id = $rec['file_id'];
				$caption = $rec['caption'];
				ze\file::imageLink($width, $height, $url, $file_id, $width, $height);
				
				$file_link = ze\file::link($file_id);
				
				$upload_links[] = ['File_Link' => $file_link, 'File_Thumbnail' => $url, 
						'Caption' => htmlspecialchars($caption), 'File_Id' => $file_id,
						'Post_Id' => $post['id'],
						'File_Extension' => strtoupper(pathinfo($file_link, PATHINFO_EXTENSION))
				];
			}
			$sections[$mysection] = $upload_links;
		}
	}
	
	//Remove a post from the thread
	function deletePost() {
		$post_id = (int) $this->post['id'];
		require ze::funIncPath(__FILE__, __FUNCTION__);
		$this->deleteUploads($post_id);
	}
	
	
	//Update a post in the thread
	function editPost($userId, $messageText, $posterName = "") {
		$post_id = $this->post['id'];
		//Add the comment
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_posts SET
				message_text = '". ze\escape::sql(zenario_anonymous_comments::sanitiseHTML($messageText, $this->setting('enable_images'), $this->setting('enable_links'))). "',
				date_updated = NOW(),
				updater_id = ". (int) $userId. "
			WHERE thread_id = ". (int) $this->threadId. "
			  AND id = ". (int) $post_id;
		
		$result = ze\sql::update($sql);
		
		$this->manageUploads($post_id);
		
		$this->sendEmailNotification((int) $post_id, false);
	}
	
	
	//Update the first post in the thread
	function editFirstPost($userId, $messageText, $threadTitle) {
		
		//Updated the thread's title
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads SET
				title = '". ze\escape::sql($threadTitle). "'
			WHERE id = ". (int) $this->threadId;
		
		$result = ze\sql::update($sql);
		
		$this->editPost($userId, $messageText);
	}
	
	function showPostScreen($labelText, $submitButtonText, $quoteMode, $titleText = false, $onSubmit = '') {
		if($this->allow_uploads){
			$onSubmit .= "
				if (!zenario.tinyMCEGetContent(tinyMCE.get('". ze\escape::js($this->getEditorId()). "'))) {
					alert('". ze\escape::js($this->phrase('Please enter a message.')). "');
					return false;
				}";
		}
		parent::showPostScreen($labelText, $submitButtonText, $quoteMode, $titleText, $onSubmit);
	}
	
	//Handle any requests the users ask for
	function forumActionHandler() {
		$actionTaken = true;
		
		//Note that some forum actions work via GET, so need checkConfirmKey() for extra security
		if (ze::get('comm_request') == 'mark_forum_read' && ($_SESSION['extranetUserID'] ?? false) && $this->checkConfirmKey()) {
			//Mark all of the Threads in a Forum as read for a User
			$this->markForumRead($_SESSION['extranetUserID'] ?? false);
		
		} elseif (ze::get('comm_request') == 'move_thread'  && $this->canMoveThread() && $this->checkConfirmKey()) {
			//Move a Thread from another Forum into this Forum
			$this->moveThreadIntoForum(ze::get('forum_thread_to_move'));
		
		} elseif (ze::post('comm_request') == 'subs_forum' && $this->canSubsForum() && !$this->hasSubsForum()) {
			$this->subs(true, false);
			
		} elseif (ze::post('comm_request') == 'unsubs_forum' && $this->canSubsForum()) {
			$this->subs(false, false);
		
		} else {
			$actionTaken = false;
		}
		
		if ($actionTaken) {
			$this->clearRequest('comm_request');
			$this->clearRequest('comm_confirm');
		}
	}
	
	
	//Check for the most recently created thread so far, and get the last_updated_order
	function getLatestUpdated() {
		
		$sql = "
			SELECT IFNULL(MAX(last_updated_order), 0)
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads
			WHERE forum_id = ". (int) $this->forumId;
		
		$result = ze\sql::select($sql);
		list($latestUpdated) = ze\sql::fetchRow($result);
		return $latestUpdated;
	}
	
	
	//Check for the most recently created thread so far, and get the last_updated_order
	static function getThreadLastUpdated($forumId, $threadId) {
		
		$sql = "
			SELECT last_updated_order
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads
			WHERE forum_id = ". (int) $forumId. "
			  AND id = ". (int) $threadId. "
			LIMIT 1";
		
		$result = ze\sql::select($sql);
		list($threadLastUpdated) = ze\sql::fetchRow($result);
		return $threadLastUpdated;
	}
	
	
	//Get the last_updated_order from a post id rather than a thread id
	function getUpdatedFromPostId($postId) {
		
		$sql = "
			SELECT t.last_updated_order
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads AS t
			INNER JOIN ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_posts AS p
			   ON t.id = p.thread_id
			  AND t.date_updated = p.date_posted
			WHERE t.forum_id = ". (int) $this->forumId. "
			  AND p.forum_id = ". (int) $this->forumId. "
			  AND p.id >= ". (int) $postId. "
			ORDER BY p.id
			LIMIT 1";
		
		$result = ze\sql::select($sql);
		list($threadLastUpdated) = ze\sql::fetchRow($result);
		return $threadLastUpdated;
	}
	
	
	function threadPageIsForumPage() {
		return $this->forum['forum_content_id'] == $this->forum['thread_content_id']
			&& $this->forum['forum_content_type'] == $this->forum['thread_content_type'];
	}
	
	function newThreadPageIsForumPage() {
		return $this->forum['forum_content_id'] == $this->forum['new_thread_content_id']
			&& $this->forum['forum_content_type'] == $this->forum['new_thread_content_type'];
	}
	
	function loadForumInfo() {
		
		//Get information on comments for this content item from the mirror table
		$sql = "
			SELECT
				id,
				date_updated,
				updater_id,
				thread_count,
				post_count,
				locked,
				forum_content_id,
				forum_content_type,
				thread_content_id,
				thread_content_type,
				new_thread_content_id,
				new_thread_content_type
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "forums AS f
			WHERE id = ". (int) $this->forumId;
		
		$result = ze\sql::select($sql);
		
		if ($this->forum = ze\sql::fetchAssoc($result)) {
			ze\content::langEquivalentItem($this->forum['forum_content_id'], $this->forum['forum_content_type']);
			ze\content::langEquivalentItem($this->forum['thread_content_id'], $this->forum['thread_content_type']);
			ze\content::langEquivalentItem($this->forum['new_thread_content_id'], $this->forum['new_thread_content_type']);
			
			if (!$this->forum['thread_content_id']) {
				$this->forum['thread_content_id'] = $this->forum['forum_content_id'];
				$this->forum['thread_content_type'] = $this->forum['forum_content_type'];
			}
			
			if (!$this->forum['new_thread_content_id']) {
				$this->forum['new_thread_content_id'] = $this->forum['forum_content_id'];
				$this->forum['new_thread_content_type'] = $this->forum['forum_content_type'];
			}
			
			$this->forumLocked = (bool) $this->forum['locked'];
			
			$this->runCheckPrivs();
			return true;
		}
		return false;
	}
	
	
	function loadThreadInfo() {
		
		//Get information on comments for this content item from the mirror table
		$sql = "
			SELECT
				id,
				title,
				date_updated,
				updater_id,
				post_count,
				locked,
				forum_id
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads AS t";
		
		if (ze::request('forum_thread')) {
			$sql .= "
			WHERE forum_id = ". (int) $this->forumId. "
			  AND id = ". (int) ze::request('forum_thread');
		} else {
			$sql .= "
			WHERE forum_id = ". (int) $this->forumId. "
			ORDER BY last_updated_order DESC
			LIMIT 1";
		}
		
		$result = ze\sql::select($sql);
		
		if ($this->thread = ze\sql::fetchAssoc($result)) {
			$this->threadId = $this->thread['id'];
			return true;
		}
		return false;
	}
	
	
	function loadThreads() {
		
		$this->threads = [];
	
		$sql = "
			SELECT
				id,
				date_posted,
				date_updated,
				last_updated_order,
				poster_id,
				updater_id,
				view_count,
				post_count,
				title,
				shadow,
				sticky,
				locked,
				rating
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads
			WHERE forum_id = ". (int) $this->forumId. "
			ORDER BY ";
		
		if ($this->setting('order') == 'MOST_RECENTLY_POSTED_FIRST') {
			$sql .= "date_posted DESC";
		} elseif ($this->setting('order') == 'OLDEST_FIRST') {
			$sql .= "date_posted";
		} elseif ($this->setting('order') == 'TITLE') {
			$sql .= "title";
		} else {
			$sql .= "last_updated_order DESC";
		}
		
		$sql .= ze\sql::limit($this->page, $this->pageSize);
		
		
		$result = ze\sql::select($sql);

		while($row = ze\sql::fetchAssoc($result)) {
			$this->threads[] = $row;
		}
	}
	
	
	function loadPosts() {
		
		$this->posts = [];
	
		$sql = "
			SELECT
				id,
				date_posted,
				date_updated,
				'published' AS status,
				first_post,
				poster_id,
				updater_id,
				message_text,
				rating
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_posts
			WHERE thread_id = ". (int) $this->threadId;
		
		if (ze::request('comm_post')) {
			$sql .= "
			  AND id = ". (int) ze::request('comm_post');
		} else {
			$sql .= "
			ORDER BY id".
			ze\sql::limit($this->page, $this->pageSize);
		}
		
		$result = ze\sql::select($sql);

		if (ze::request('comm_post')) {
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
		return !empty($this->thread['locked']) || $this->forumLocked;
	}
	
	
	function lockedForum() {
		return $this->forumLocked;
	}
	
	
	function lockThread($lock) {
		
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads SET
				locked = ". (int) $lock. "
			WHERE id = ". (int) $this->threadId;
		$result = ze\sql::update($sql);
	}
		
	protected function sendEmailNotification($postId, $newPost = true, $newThreadTitle = false) {
	//protected function sendEmailNotification($userId, $messageText, $newPost, $newThreadTitle = false, $name = '', $email = '') {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	protected function subs($subs, $thread = true) {
		$key = [
			'user_id' => ($_SESSION['extranetUserID'] ?? false),
			'forum_id' => $this->forumId];
		
		if ($thread) {
			$key['thread_id'] = $this->threadId;
		} else {
			$key['thread_id'] = 0;
		}
		
		if ($subs) {
			$key['content_id'] = $this->forum['forum_content_id'];
			$key['content_type'] = $this->forum['forum_content_type'];
			
			ze\row::set(
				ZENARIO_COMMENTS_PREFIX. 'user_subscriptions',
				['date_subscribed' => ze\date::now()],
				$key);
		
		} else {
			ze\row::delete(
				ZENARIO_COMMENTS_PREFIX. 'user_subscriptions',
				$key);
		}
	}
	
	
	function markForumRead($readerId) {
		$sql = "
			DELETE FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads
			WHERE unread_from != 0
			  AND unread_to != 0
			  AND forum_id = ". (int) $this->forumId. "
			  AND reader_id = ". (int) $readerId;
		ze\sql::update($sql, false, false);
	}
	
	
	function markOtherThreadsAsLessRecent($threadLastUpdated, $forumId) {
		
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads SET
				last_updated_order = last_updated_order - 1
			WHERE last_updated_order > ". (int) $threadLastUpdated. "
			  AND forum_id = ". (int) $forumId;
		ze\sql::update($sql);
		
		$sql = "
			DELETE FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads
			WHERE unread_to = ". (int) $threadLastUpdated. "
			  AND unread_from = ". (int) $threadLastUpdated. "
			  AND forum_id = ". (int) $forumId;
		ze\sql::update($sql, false, false);
		
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads SET
				unread_from = unread_from - 1
			WHERE unread_from > ". (int) $threadLastUpdated. "
			  AND forum_id = ". (int) $forumId;
		ze\sql::update($sql, false, false);
		
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads SET
				unread_to = unread_to - 1
			WHERE unread_to > ". (int) $threadLastUpdated. "
			  AND forum_id = ". (int) $forumId;
		ze\sql::update($sql, false, false);
	}
	
	
	function markThreadAsMostRecent($latestUpdated) {
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads SET
				last_updated_order = ". (int) $latestUpdated. "
			WHERE id = ". (int) $this->threadId;
		
		ze\sql::update($sql);
	}
	
	
	//Alter the rows in the user_unread_threads table to mark the current thread as read for the user
	function markThreadAsRead($readerId) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	function markThreadAsUnRead($latestUpdated) {
		
		//Extend the spans if possible
		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads SET
				unread_to = ". (int) $latestUpdated. "
			WHERE unread_to = ". ((int) $latestUpdated - 1). "
			  AND forum_id = ". (int) $this->forumId;
		ze\sql::update($sql, false, false);
		
		//Where we couldn't extend a span, we must add a new one.
		//NOT IN must be used in order not to create any duplicates with the query above
		$sql = "
			INSERT INTO ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads(
				unread_from, unread_to, forum_id, reader_id
			)
			SELECT ". (int) $latestUpdated. ", ". (int) $latestUpdated. ", ". (int) $this->forumId. ", reader_id
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads
			WHERE forum_id = ". (int) $this->forumId. "
			  AND unread_to = 0
			  AND reader_id NOT IN (
				SELECT reader_id
				FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads
				WHERE forum_id = ". (int) $this->forumId. "
				  AND unread_to = ". (int) $latestUpdated. "
			)
			ORDER BY forum_id, reader_id";
		ze\sql::update($sql, false, false);
	}
	
	
	function markThreadCheckUserIsInTable($userId) {
		//Check to see if the user is in the user_unread_threads table for this forum
		$sql = "
			SELECT 1
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads
			WHERE forum_id = ". (int) $this->forumId. "
			  AND reader_id = ". (int) $userId. "
			LIMIT 1";
		
		$result = ze\sql::select($sql);
		if (ze\sql::fetchRow($result)) {
			return;
		}
		
		//Was the reason for them not being in there because there are no forum posts? Do nothing if so.
		if (!($latestUpdated = $this->getLatestUpdated())) {
			return;
		}
		
		//If not, add a special row in for them so we know we've included them
		$sql = "
			INSERT INTO ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads SET
				unread_from = 0,
				unread_to = 0,
				forum_id = ". (int) $this->forumId. ",
				reader_id = ". (int) $userId;
		ze\sql::update($sql, false, false);
		
		//Then mark the forum as unread for them
		$sql = "
			INSERT INTO ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads SET
				unread_from = 1,
				unread_to = ". (int) $latestUpdated. ",
				forum_id = ". (int) $this->forumId. ",
				reader_id = ". (int) $userId;
		ze\sql::update($sql, false, false);
	}
	
	
	public static function markThreadCheckUserHasReadThread($latestUpdated, $forumId) {
		if (!($userId = $_SESSION['extranetUserID'] ?? false)) {
			return true;
		}
		
		$sql = "
			SELECT 1
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads
			WHERE forum_id = ". (int) $forumId. "
			  AND reader_id = ". (int) $userId. "
			  AND unread_from <= ". (int) $latestUpdated. "
			  AND unread_to >= ". (int) $latestUpdated. "
			LIMIT 1";
		
		$result = ze\sql::select($sql);
		return !ze\sql::fetchRow($result);
	}
	
	
	//Check to see if a user has read a forum
	public static function markThreadCheckUserHasReadForum($forumId) {
		//Not logged in? Always return true
		if (!$userId = $_SESSION['extranetUserID'] ?? false) {
			return true;
		}
		
		//Otherwise, check to see if there are any unread threads for the user in the forum
		$sql = "
			SELECT 1
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads
			WHERE forum_id = ". (int) $forumId. "
			  AND reader_id = ". (int) $userId. "
			  AND unread_from != 0
			  AND unread_to != 0
			LIMIT 1";
		
		$result = ze\sql::select($sql);
		if (ze\sql::fetchRow($result)) {
			return false;
		}
		
		//Otherwise, check to see if the user has ever been in the forum. (If not, then they've not read it!)
		$sql = "
			SELECT 1
			FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads
			WHERE forum_id = ". (int) $forumId. "
			  AND reader_id = ". (int) $userId. "
			  AND unread_from = 0
			  AND unread_to = 0
			LIMIT 1";
		
		$result = ze\sql::select($sql);
		if (ze\sql::fetchRow($result)) {
			return true;
		}
		
		return false;
	}
	
	
	//Move a Thread from another Forum into this Forum
	function moveThreadIntoForum($threadId) {
		require ze::funIncPath(__FILE__, __FUNCTION__);
	}
	
	
	function forumSelectMode() {
		if (ze::request('comm_confirm')) {
			$this->scrollToTopOfSlot(false);
			
			if (ze::request('comm_request') == 'subs_forum' && $this->canSubsForum() && !$this->hasSubsForum()) {
				$this->showConfirmBox($this->phrase(
					'Are you sure you wish to subscribe? A notification email will be sent to &quot;[[email]]&quot; when a new thread is created in this forum.',
					['email' => htmlspecialchars(ze\user::email())]), $this->phrase('Subscribe to this forum')
				);
				
			} elseif (ze::request('comm_request') == 'unsubs_forum' && $this->canSubsForum() && $this->hasSubsForum()) {
				$this->showConfirmBox($this->phrase('Are you sure you wish to unsubscribe, and no longer be notified of new threads created in this forum?'), $this->phrase('Unsubscribe from this forum'));
			}
			
		} else {
			$this->showForumActions();
		}
	}
	
	
	function showForumActions() {
		$loginCID = $loginCType = false;
		
		if ($this->canMakeThread()) {
			$this->sections['Add_New_Thread'] = true;
			$this->mergeFields['Add_New_Thread_Link'] = $this->linkToItemAnchor($this->forum['new_thread_content_id'], $this->forum['new_thread_content_type'], false, '&comm_request=add_thread');
		} elseif (ze::setting('user_use_screen_name') && !parent::getUserScreenNameConfirmed(ze\user::id())) {
			$this->sections['Forum_Profile_Link'] = true;
			
			$profileLink = '<a';
			if ($link = ze\link::toPluginPage('zenario_extranet_profile_edit')) {
				$profileLink .= ' href="'. htmlspecialchars($link). '"';
			}
			$profileLink .= '>'. $this->phrase('your profile'). '</a>';
			
			$this->mergeFields['Forum_Profile_Link'] = $this->phrase('You must confirm your screen name on [[profile_link]] in order to create a thread.', ['profile_link' => $profileLink]);
		} elseif ($this->lockedForum()) {
			$this->sections['Forum_Locked'] = true;
			
		} elseif (empty($_SESSION['extranetUserID']) && ze\content::langSpecialPage('zenario_login', $loginCID, $loginCType)) {
			$this->sections['Forum_Login_To_Post'] = true;
			$this->mergeFields['Login_To_Post_Link'] = $this->linkToItemAnchor($loginCID, $loginCType);
		}
		
		if (($_SESSION['extranetUserID'] ?? false) && $this->forum['post_count'] && !zenario_forum::markThreadCheckUserHasReadForum($this->forumId)) {
			$this->sections['Mark_Forum_Read'] = true;
			$this->mergeFields['Mark_Forum_Read_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_key='. $_SESSION['confirm_key']. '&comm_request=mark_forum_read', false);
		}
		
		if ($this->canSubsForum()) {
			if ($this->hasSubsForum()) {
				$this->sections['Unsubs_To_Forum'] = true;
				$this->sections['Subscribed_To_Forum'] = true;
				$this->mergeFields['Unsubs_To_Forum_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_key='. $_SESSION['confirm_key']. '&comm_request=unsubs_forum&comm_confirm=1', false);
			} else {
				$this->sections['Subs_To_Forum'] = true;
				$this->mergeFields['Subs_To_Forum_Link'] = $this->refreshPluginSlotAnchor('&comm_page='. $this->page. '&comm_key='. $_SESSION['confirm_key']. '&comm_request=subs_forum&comm_confirm=1', false);
			}
		
		} elseif ($this->couldSubsForum()) {
			$this->sections['Login_To_Subs_Forum'] = true;
			
			if (empty($this->mergeFields['Login_To_Post_Link']) && ze\content::langSpecialPage('zenario_login', $loginCID, $loginCType)) {
				$this->mergeFields['Login_To_Post_Link'] = $this->linkToItemAnchor($loginCID, $loginCType);
			}
		}
		
		$this->sections['Thread_Controls'] = true;
	}
	
	
	function showAddThread() {
		//Note: currently logic is identical to showPosts()
		$this->showPosts();
	}
	
	function showThreads() {
		
		
		if ($this->threads) {
			$this->sections['Thread'] = [];
			
			foreach($this->threads as &$thread) {
				
				$sections = [];
				$mergeFields = [];
				
				$mergeFields['Date_Posted'] = ze\date::formatDateTime($thread['updater_id'], $this->setting('date_format'));
				$mergeFields['Posted_By'] = $this->getUserScreenNameLink($thread['poster_id']);
				$mergeFields['Posted_By_On'] = $this->phrase('by [[by]] on [[on]]', ['by' => $mergeFields['Posted_By'], 'on' => $mergeFields['Date_Posted']]);
				$mergeFields['Title'] = htmlspecialchars($thread['title']);
				$mergeFields['Post_Count'] = $thread['post_count'];
				$mergeFields['Replies_Count'] = $thread['post_count']-1;
				$mergeFields['View_Count'] = $thread['view_count'];
	
				if ($mergeFields['Updated'] = (bool) $thread['updater_id']) {
					$mergeFields['Date_Updated'] = ze\date::formatDateTime($thread['date_updated'], $this->setting('date_format'));
					$mergeFields['Updated_By'] = $this->getUserScreenNameLink($thread['updater_id']);
					$mergeFields['Updated_By_On'] = $this->phrase('by [[by]] on [[on]]', ['by' => $mergeFields['Updated_By'], 'on' => $mergeFields['Date_Updated']]);
				}
				
				if (zenario_forum::markThreadCheckUserHasReadThread($thread['last_updated_order'], $this->forumId)) {
					$mergeFields['Status_Class'] = 'read';
				} else {
					$mergeFields['Status_Class'] = 'unread';
				}
				
				if ($thread['locked']) {
					$mergeFields['Locked'] = true;
					$mergeFields['Status_Class'] .= ' locked';
				}
				
				$mergeFields['Hyperlink'] = htmlspecialchars($this->linkToItem($this->forum['thread_content_id'], $this->forum['thread_content_type'], false, '&forum_thread='. $thread['id']));
				
				$this->sections['Thread'][] = $mergeFields;
			}
		}
		
		$this->framework('Threads', $this->mergeFields, $this->sections);
	}
	
	
	
	
	
	public static function deleteUserDataGetInfo($userIds) {
		$sql = "
			SELECT COUNT(id)
			FROM " . DB_PREFIX . ZENARIO_FORUM_PREFIX . "forums
			WHERE updater_id IN (" . ze\escape::in($userIds) . ")";
		$result = ze\sql::select($sql);
		$count = ze\sql::fetchValue($result);
		
		$forumsResults = ze\admin::phrase('Forums started by this user will have the updater ID removed ([[count]] found)', ['count' => $count]);

		$sql = "
			SELECT COUNT(id)
			FROM " . DB_PREFIX . ZENARIO_FORUM_PREFIX . "threads
			WHERE poster_id IN (" . ze\escape::in($userIds) . ")";
		$result = ze\sql::select($sql);
		$count = ze\sql::fetchValue($result);
		
		$threadsResults = ze\admin::phrase('Threads posted by this user will have the creator ID removed ([[count]] found)', ['count' => $count]);

		$sql = "
			SELECT COUNT(id)
			FROM " . DB_PREFIX . ZENARIO_FORUM_PREFIX . "user_posts
			WHERE poster_id IN (" . ze\escape::in($userIds) . ")";
		$result = ze\sql::select($sql);
		$count = ze\sql::fetchValue($result);
		
		$userPostsResults = ze\admin::phrase('This user\'s posts will have the creator ID removed ([[count]] found)', ['count' => $count]);
		
		return implode('<br />', [$forumsResults, $threadsResults, $userPostsResults]);
	}
	
	//Empty a user's history in the user_unread_threads table if they are deleted
	public static function eventUserDeleted($userId) {
		$sql = "
			DELETE FROM ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_unread_threads
			WHERE reader_id = ". (int) $userId;
		ze\sql::update($sql, false, false);

		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "forums SET
				updater_id = 0
			WHERE updater_id = ". (int) $userId;
		ze\sql::update($sql);

		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads SET
				poster_id = 0
			WHERE poster_id = ". (int) $userId;
		ze\sql::update($sql);

		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "threads SET
				updater_id = 0
			WHERE updater_id = ". (int) $userId;
		ze\sql::update($sql);

		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_posts SET
				poster_id = 0
			WHERE poster_id = ". (int) $userId;
		ze\sql::update($sql);

		$sql = "
			UPDATE ". DB_PREFIX. ZENARIO_FORUM_PREFIX. "user_posts SET
				updater_id = 0
			WHERE updater_id = ". (int) $userId;
		ze\sql::update($sql);
	}
}