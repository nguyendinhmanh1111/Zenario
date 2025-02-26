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

header('Content-Type: text/javascript; charset=UTF-8');
require '../basicheader.inc.php';

ze\cache::useBrowserCache('zenario-inc-organizer-js-'. LATEST_REVISION_NO);
ze\cache::start();


//Run pre-load actions

if (ze::$canCache) require CMS_ROOT. 'zenario/includes/wrapper.pre_load.inc.php';


//Include all of the standard JavaScript Admin libraries for the CMS
ze\cache::incJS('zenario/libs/manually_maintained/mit/jquery/jquery.nestable');
ze\cache::incJS('zenario/js/admin_organizer');

//Include every panel-type
//N.b. these need to be included in dependency order
ze\cache::incJS('zenario/reference/panel_type_base_class');
ze\cache::incJS('zenario/js/panel_type_grid');
ze\cache::incJS('zenario/js/panel_type_list');
ze\cache::incJS('zenario/js/panel_type_list_or_grid');
ze\cache::incJS('zenario/js/panel_type_list_with_caching_disabled');
ze\cache::incJS('zenario/js/panel_type_list_with_master_switch');
ze\cache::incJS('zenario/js/panel_type_list_with_totals');
ze\cache::incJS('zenario/js/panel_type_list_with_totals_on_refiners');
ze\cache::incJS('zenario/js/panel_type_list_with_subheadings');
ze\cache::incJS('zenario/js/panel_type_multi_line_list');
ze\cache::incJS('zenario/js/panel_type_multi_line_list_or_grid');
ze\cache::incJS('zenario/js/panel_type_network_graph');
ze\cache::incJS('zenario/js/panel_type_hierarchy');
ze\cache::incJS('zenario/js/panel_type_hierarchy_with_lazy_load');
ze\cache::incJS('zenario/js/panel_type_hierarchy_documents');
ze\cache::incJS('zenario/js/panel_type_slot_reload_on_change');
ze\cache::incJS('zenario/js/panel_type_start_page');

ze\cache::incJS('zenario/js/panel_type_google_map');
ze\cache::incJS('zenario/js/panel_type_google_map_or_list');

ze\cache::incJS('zenario/js/panel_type_form_builder_base_class');
ze\cache::incJS('zenario/js/panel_type_form_builder');
ze\cache::incJS('zenario/js/panel_type_admin_box_builder');



//Run post-display actions
if (ze::$canCache) require CMS_ROOT. 'zenario/includes/wrapper.post_display.inc.php';
