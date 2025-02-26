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

class zenario_users__admin_boxes__user__delete extends zenario_users {
	
	public function fillAdminBox($path, $settingGroup, &$box, &$fields, &$values) {
		$userIds = explode(',', $box['key']['id']);
		if (count($userIds) > 1) {
			$box['tabs']['details']['notices']['are_you_sure']['message'] = ze\admin::phrase('Are you sure you wish to delete the selected accounts?');
		} else {
			$user = ze\user::details($userIds[0]);
			ze\lang::applyMergeFields($box['tabs']['details']['notices']['are_you_sure']['message'], $user);
		}
		
		//Get all the modules that will be deleting user data.
		$allDataExplained = '';
		$moduleDataResponses = ze\module::sendSignal('deleteUserDataGetInfo', [$userIds]);
		if (!empty($moduleDataResponses)) {
			$allDataExplained .= '<p>' . ze\admin::phrase('If deleting all data, the following will be removed:') . '<p>';
			$allDataExplained .= implode('<br />', $moduleDataResponses);
		}
		
		ze\lang::applyMergeFields($box['tabs']['details']['fields']['all_data_explained']['snippet']['html'], ['all_data_explained' => $allDataExplained]);
	}
	
	public function saveAdminBox($path, $settingGroup, &$box, &$fields, &$values, $changes) {
		foreach (explode(',', $box['key']['id']) as $userId) {
			ze\userAdm::delete($userId, $values['details/delete_options'] == 'delete_all_data');
		}
	}
}
