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


class zenario_extranet_password_reset extends zenario_extranet {
	
	public function init() {
		ze::requireJsLib('zenario/libs/yarn/zxcvbn/dist/zxcvbn.js');
		ze::requireJsLib('zenario/js/password_functions.min.js');

		$this->registerPluginPage();
		
		$this->allowCaching(
			$atAll = true, $ifUserLoggedIn = false, $ifGetSet = false, $ifPostSet = false, $ifSessionSet = false, $ifCookieSet = false);
		$this->clearCacheBy(
			$clearByContent = false, $clearByMenu = false, $clearByUser = false, $clearByFile = false, $clearByModuleData = false);
		
		$this->mode = 'modeResetPasswordStage1';
		
		ze::requireJsLib('zenario/modules/zenario_users/js/password_visitor_phrases.js.php?langId='. ze::$visLang);
		
		if (ze::post('extranet_send_reset_email')) {
			
			//Add a short delay to make it a tiny bit harder to repeatedly spam this plugin
			usleep(500000);
			
			$userIsContactOrSuspended = false;

			//If the user is a contact or suspended, no email will be sent,
			//and the function sendResetEmail() will set $userIsContactOrSuspended to true.
			//The "if" statement below will run.
			if ($this->sendResetEmail($userIsContactOrSuspended) || $userIsContactOrSuspended) {
				if ($this->setting('block_email_enumeration') || $userIsContactOrSuspended) {
					$this->message = $this->phrase('If the email address you provided matches your email on this site, you will have been sent an email containing a link to reset your password.<br /><br />Please ensure you check your spam/bulk mail folder in case it is mis-filed.');
				} else {
					$this->message = $this->phrase('You have been sent an email containing a link to reset your password.<br /><br />Please ensure you check your spam/bulk mail folder in case it is mis-filed.');
				}
				$this->mode = 'modeLogin';
			}
		} elseif (ze::request('extranet_reset_password') && ($userId = $this->getUserIdFromHashCode(ze::request('hash')))) {
			if (!$this->checkResetPasswordTime($userId)) {
				ze\row::update('users', ['reset_password_time' => null], ['id' => $userId]);
				$this->message = $this->phrase('This link has expired. To reset your password make a new request.');
				$this->mode = 'modeLogin';
			} else {
				$this->mode = 'modeResetPasswordStage2';
				if (ze::post('extranet_change_password')) {
					if ($this->changePassword($userId)) {
						$this->message = $this->phrase('Your password has been changed.');
						$this->mode = 'modeLogin';
						ze\row::update('users', ['reset_password_time' => null], ['id' => $userId]);
					}
				}
			}
		}
		return true;
	}
	
	// Display a form that lets the user enter the email address that they used to register,
	// and then have their password reset via email
	protected function modeResetPasswordStage1() {
		$this->addLoginLinks();
		
		if ($this->setting('stop_users_reseting_passwords')) {
			$this->subSections['Contact_For_Reset'] = true;
			$this->objects['Contact_For_Reset_Message'] = $this->phrase($this->setting('contact_for_reset_message'));
			$this->framework('Outer', $this->objects, $this->subSections);
			
		} else {
			echo $this->openForm($onSubmit = '', $extraAttributes = '', $action = false, $scrollToTopOfSlot = true, $fadeOutAndIn = true);
				$this->subSections['Main_Title'] = true;
				$this->subSections['Reset_Password_Form'] = true;
				$this->framework('Outer', $this->objects, $this->subSections);
			echo $this->closeForm();
		}
	}
	
	// Display a form that lets the user enter a new password and confirmation
	protected function modeResetPasswordStage2() {
		$this->objects['hash'] = $_REQUEST['hash'] ?? false;
		$this->objects['extranet_reset_password'] = $_REQUEST['extranet_reset_password'] ?? false;
		
		echo $this->openForm($onSubmit = '', $extraAttributes = '', $action = false, $scrollToTopOfSlot = true, $fadeOutAndIn = true);
			$this->subSections['Main_Title'] = true;
			$this->subSections['Reset_Password_Form_Passwords'] = true;
			$this->objects['Password_Requirements_Settings'] = [
				'min_extranet_user_password_length' => ze::setting('min_extranet_user_password_length'),
				'min_extranet_user_password_score' => ze::setting('min_extranet_user_password_score')
			];
			$this->framework('Outer', $this->objects, $this->subSections);
		echo $this->closeForm();
		
		$this->callScript('zenarioP', 'updatePasswordNotifier', '#extranet_new_password', $this->objects['Password_Requirements_Settings'], '#password_message', $adminFacing = false, $isInstaller = false);
	}
	
	private function sendResetEmail(&$userIsContactOrSuspended) {
		if (!$this->validateFormFields('Reset_Password_Form')) {
			// Function displays error message so no action here
		} elseif (!$userDetails = $this->getDetailsFromEmail(ze::post('extranet_email'))) {
			if ($this->setting('block_email_enumeration')) {
				//If the block_email_enumeration option is enabled, don't tell the user that the email address doesn't exist!
				return true;
			} else {
				$this->errors[] = ['Error' => $this->phrase("Sorry, we couldn't find an account associated with that email address.")];
			}
		} else {
			if (ze\row::exists('users', ['email' => ze::post('extranet_email'), 'status' => 'pending', 'email_verified' => false  ])) {
				$this->errors[] = ['Error' => $this->phrase('You have not yet verified your email address. Please click on the link in your verification email.')];
			} else {
				if ($userDetails['status'] == 'active') {
					ze\userAdm::updateHash($userDetails['id']);
					ze\row::update('users', ['reset_password_time' => ze\date::now()], ['id' => $userDetails['id']]);
					$userDetails = ze\user::details($userDetails['id']);
					$userDetails['cms_url'] = ze\link::absolute();
					$userDetails['reset_url'] = static::getExtranetPasswordResetLink($userDetails['id'], $this->cID, $this->cType);
					
					if (ze\module::inc('zenario_email_template_manager')){
						if (zenario_email_template_manager::sendEmailsUsingTemplate($userDetails['email'],$this->setting('password_reset_email_template'),$userDetails,[])){
							return true;
						} else {
							$this->errors[] = ['Error' => $this->phrase('There appears to be a problem with our email system. Please try to retrieve your password again later.')];
						}
					} else {
						$this->errors[] = ['Error' => $this->phrase('There appears to be a problem with our email system. Please try to retrieve your password again later.')];
					}
				} else {
					$userIsContactOrSuspended = true;
					return false;
				}
			}
		}
		return false;
	}
	
	public static function getExtranetPasswordResetLink($userId, $cID = false, $cType = false) {
		
		$hash = ze\row::get('users', 'hash', $userId);
		$request = '&extranet_reset_password=1&hash='. urlencode($hash);
		
		if ($cID && $cType) {
			return ze\link::toItem($cID, $cType, $fullPath = true, $request);
		} else {
			return ze\link::toPluginPage('zenario_extranet_password_reset', '', false, $fullPath = true, $request);
		}
	}
	
	private function getUserIdFromHashCode($hash){
		if ($hash && ($userId = (int) ze\row::get("users","id",['hash'=>$hash]))){
			return $userId;
		} else {
			return 0;
		}
	}
	
	// Attempt to change a user's password
	private function changePassword($userId) {
		$errors = $this->validatePassword($_POST['extranet_new_password'] ?? false, ze::post('extranet_new_password_confirm'), ze::post('extranet_password'), $this->moduleClassNameForPhrases, $userId);
		
		if (count($errors)) {
			$this->errors = array_merge($this->errors, $errors);
			return false;
		} else {
			ze\userAdm::setPassword($userId, ze::post('extranet_new_password'), false);
			//Set email verified flag
			ze\row::update('users', ['email_verified' => 1], ['id' => $userId]);
			return true;
		}
	}
	
	private function checkResetPasswordTime($userId) {
		$userPasswordResetTimeStr = ze\row::get('users', 'reset_password_time', $userId);
		if (!$userPasswordResetTimeStr) {
			return false;
		} else {
			$userPasswordResetTimePlusOneDay = strtotime($userPasswordResetTimeStr. '+ 1 day');
			$now = strtotime(ze\date::now());
			return ($userPasswordResetTimePlusOneDay > $now);
		}
	}
}