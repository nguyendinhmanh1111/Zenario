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

define('LATEST_REVISION_NO', 57715);	//N.b. 9.5 starts at revision #57770
define('LATEST_BIG_CHANGE_REVISION_NO', 57550);
define('INSTALLER_REVISION_NO', 56900);
define('INSTALLER_DEFAULT_THEME', 'zebra_designs');
define('EARLIEST_SUPPORTED_MIGRATION', 45400);

define('ZENARIO_IS_HEAD', false);
define('ZENARIO_VERSION', '9.4');
define('ZENARIO_MAJOR_VERSION', '9');
define('ZENARIO_MINOR_VERSION', '4');
define('ZENARIO_CHANGELOG_URL', 'https://zenar.io/zenario-94');
define('ZENARIO_IS_BUILD', true);
define('ZENARIO_REVISION', '58699');

define('TINYMCE_DIR', 'zenario/libs/manually_maintained/lgpl/tinymce_4_7_3/');
