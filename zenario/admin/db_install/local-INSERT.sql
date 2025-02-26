
ALTER TABLE `[[DB_PREFIX]]menu_sections` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]menu_sections` VALUES
 (1,'Main'),
 (2,'Footer');
ALTER TABLE `[[DB_PREFIX]]menu_sections` ENABLE KEYS;


ALTER TABLE `[[DB_PREFIX]]modules` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]modules` VALUES
 (1,'zenario_common_features','zenario_common_features','Common Features','Community','core','','zenario_common_features',0,'',1,0,0,0,'module_is_abstract',0),
 (2,'zenario_banner','zenario_banner','Banner','Community','pluggable','image_then_title_then_text','zenario_banner',1,'',0,0,0,1,'module_running',0),
 (3,'zenario_breadcrumbs','zenario_breadcrumbs','Breadcrumbs','Community','pluggable','standard','zenario_breadcrumbs',1,'',0,0,0,1,'module_running',0),
 (4,'zenario_footer','zenario_footer','Footer Menu','Community','pluggable','standard','zenario_footer',1,'',0,0,0,1,'module_running',0),
 (5,'zenario_html_snippet','zenario_html_snippet','Raw HTML Snippet','Community','pluggable','standard','zenario_html_snippet',1,'',0,1,0,1,'module_running',0),
 (6,'zenario_menu','zenario_menu','Menu (Horizontal)','Community','pluggable','standard','zenario_menu',1,'',0,0,0,1,'module_running',0),
 (7,'zenario_content_list','zenario_content_list','Content Summary List','Community','pluggable','standard','zenario_content_list',1,'',0,0,0,1,'module_running',0),
 (8,'zenario_wysiwyg_editor','zenario_wysiwyg_editor','WYSIWYG Editor','Community','pluggable','standard','zenario_html_snippet',1,'',0,1,0,1,'module_running',0),
 (9,'zenario_email_template_manager','zenario_email_template_manager','Email Template Manager','Community','management','','zenario_email_template_manager',0,'',0,0,0,0,'module_running',0),
 (10,'zenario_menu_vertical','zenario_menu_vertical','Menu (Vertical)','Community','pluggable','standard','zenario_menu_vertical',1,'',0,0,0,1,'module_running',0),
 (11,'zenario_language_picker','zenario_language_picker','Language Picker','Community','pluggable','select_list','zenario_language_picker',1,'',0,0,0,1,'module_running',0),
 (12,'zenario_menu_responsive_push_pull','zenario_menu_responsive_push_pull','Menu for Mobile with Push-Pull','Community','pluggable','standard','zenario_menu_responsive_push_pull',1,'',0,0,0,1,'module_running',0),
 (13,'zenario_slideshow_simple','zenario_plugin_nest','Slideshow (simple)','Community','pluggable','standard','zenario_slideshow',1,'',0,0,0,0,'module_running',0),
 (14,'zenario_plugin_nest','zenario_plugin_nest','Nest','Community','pluggable','standard','zenario_plugin_nest',1,'',0,0,0,0,'module_running',0),
 (15,'zenario_copyright','zenario_copyright','Copyright','Community','pluggable','standard','zenario_copyright',1,'',0,0,0,1,'module_running',0),
 (16,'zenario_document_container','zenario_document_container','Document Container','Community','pluggable','standard','zenario_document_container',1,'',0,0,0,1,'module_running',0),
 (17,'zenario_user_forms','zenario_user_forms','Form Container','Pro','management','standard','zenario_user_forms',1,'',0,0,0,1,'module_running',0),
 (18,'zenario_slideshow','zenario_plugin_nest','Slideshow (advanced)','Community','pluggable','standard','zenario_plugin_nest',1,'',0,0,0,0,'module_running',0),
 (19,'zenario_ctype_news','zenario_ctype_news','Content Type News','Community','content_type','','zenario_ctype_news',0,'',0,0,0,0,'module_running',0);
ALTER TABLE `[[DB_PREFIX]]modules` ENABLE KEYS;


ALTER TABLE `[[DB_PREFIX]]plugin_instances` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]plugin_instances` VALUES 
 (1,'Logo',2,0,'',0,'','image_then_title_then_text','banner_logo',0,0),
 (2,'Top Menu',6,0,'',0,'','standard','',0,0),
 (3,'Responsive Multilevel Menu',12,0,'',0,'','standard','',0,0),
 (4,'Language picker',14,0,'',0,'','standard','nest_top_bar',1,0),
 (5,'Slideshow (simple) home page',13,0,'',0,'','standard','',0,1),
 (7,'Home, call-out banner',2,0,'',0,'','image_then_title_then_text','banner_animation_parent',0,0),
 (8,'Home, services',14,0,'',0,'','standard','container_12 nest_animation_parent nest_full_boxes',1,0),
 (9,'Home, services',14,0,'',0,'','standard','nest_portfolio nest_animation_parent',1,0),
 (12,'Home, full banner',2,0,'',0,'','image_then_title_then_text','banner_more_button banner_animation_parent banner_overlay_content light align_left',0,0),
 (14,'Contact form',17,0,'',0,'','standard','form_contact',0,0),
 (16,'Company address',2,0,'',0,'','image_then_title_then_text','zenario_banner__default_style',0,0),
 (17,'Social links',5,0,'',0,'','standard','',0,0),
 (18,'Footer Menu',4,0,'',0,'','standard','',0,0),
 (19,'Copyright notice',15,0,'',0,'','standard','',0,0),
 (20,'Contact popup',17,0,'',0,'','standard','form_popup',0,0),
 (21,'Breadcrumbs',3,0,'',0,'','standard','',0,0),
 (22,'Masthead, second page',2,0,'',0,'','image_then_title_then_text','banner_masthead',0,0),
 (29,'News list for home page',7,0,'',0,'','standard','',0,0);
ALTER TABLE `[[DB_PREFIX]]plugin_instances` ENABLE KEYS;


ALTER TABLE `[[DB_PREFIX]]nested_plugins` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]nested_plugins` VALUES 
 (1,4,1,0,0,'show',0,'','',0,1,0,0,0,0,0,0,60,'','','','','Slide 1','public','any',0,'','','','',1),
 (2,4,1,1,9,'show',2,'image_then_title_then_text','align_right',0,0,0,0,0,0,0,0,60,'','','','','Banner','public','any',0,'','','','',1),
 (3,4,1,2,3,'show',11,'select_list','align_right',0,0,0,0,0,0,0,0,60,'','','','','Language Picker','public','any',0,'','','','',1),
 (4,5,1,0,0,'show',0,'','',0,1,0,0,0,0,0,0,60,'','','','','Slide 1','public','any',0,'','','','',1),
 (5,5,1,1,0,'show',2,'image_then_title_then_text','',0,0,0,0,0,0,0,0,60,'','','','','slide-1.jpg','public','any',0,'','','','',1),
 (6,5,2,0,0,'show',0,'','',0,1,0,0,0,0,0,0,60,'','','','','Slide 2','public','any',0,'','','','',1),
 (7,5,2,1,0,'show',2,'image_then_title_then_text','',0,0,0,0,0,0,0,0,60,'','','','','slide-2.jpg','public','any',0,'','','','',1),
 (10,8,1,0,0,'show',0,'','',0,1,0,0,0,0,0,0,60,'','','','','Slide 1','public','any',0,'','','','',1),
 (11,8,1,1,4,'show',2,'image_then_title_then_text','animated_child_1',0,0,0,0,0,0,0,0,60,'','','','','Banner: Design Service','public','any',0,'','','','',1),
 (12,8,1,2,4,'show',2,'image_then_title_then_text','animated_child_2',0,0,0,0,0,0,0,0,60,'','','','','Banner: Development Service','public','any',0,'','','','',1),
 (13,8,1,3,4,'show',2,'image_then_title_then_text','animated_child_3',0,0,0,0,0,0,0,0,60,'','','','','Banner: Support Service','public','any',0,'','','','',1),
 (14,9,1,0,0,'show',0,'','',0,1,0,0,0,0,0,0,60,'','','','','Slide 1','public','any',0,'','','','',1),
 (15,9,1,1,0,'show',2,'image_then_title_then_text','',0,0,0,0,0,0,0,0,60,'','','','','Banner: a call to action...','public','any',0,'','','','',1),
 (16,9,1,2,4,'show',2,'image_then_title_then_text','animated_child_1',0,0,0,0,0,0,0,0,60,'','','','','Banner: Product 1','public','any',0,'','','','',1),
 (17,9,1,3,4,'show',2,'image_then_title_then_text','animated_child_1',0,0,0,0,0,0,0,0,60,'','','','','Banner: Product 2','public','any',0,'','','','',1),
 (18,9,1,4,4,'show',2,'image_then_title_then_text','animated_child_1',0,0,0,0,0,0,0,0,60,'','','','','Banner: Product 3','public','any',0,'','','','',1);
ALTER TABLE `[[DB_PREFIX]]nested_plugins` ENABLE KEYS;


ALTER TABLE `[[DB_PREFIX]]plugin_layout_link` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]plugin_layout_link` VALUES 
 (5,8,0,2,'Slot_Content_1'),
 (16,3,21,1,'Slot_Breadcrumbs'),
 (17,8,0,1,'Slot_Main_1'),
 (24,3,21,3,'Slot_Breadcrumbs'),
 (29,8,0,3,'Slot_Main_1');
ALTER TABLE `[[DB_PREFIX]]plugin_layout_link` ENABLE KEYS;


INSERT INTO `[[DB_PREFIX]]plugin_item_link` VALUES
 (1,13,5,1,'html',1,'Slot_Slideshow'),
 (2,2,7,1,'html',1,'Slot_Content_Image_1'),
 (3,14,8,1,'html',1,'Slot_Full_Boxes'),
 (4,14,9,1,'html',1,'Slot_Portfolio'),
 (7,2,12,1,'html',1,'Slot_Full_Banner'),
 (8,17,14,1,'html',1,'Slot_Contact_Form'),
 (10,2,22,2,'html',1,'Slot_Masthead'),
 (26,7,29,1,'html',1,'Slot_News');


ALTER TABLE `[[DB_PREFIX]]plugin_settings` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]plugin_settings` VALUES 
 (1,'canvas',0,'fixed_height','synchronized_setting','text',NULL,0,'','remove',NULL),
 (1,'height',0,'55','synchronized_setting','text',NULL,0,'','remove',NULL),
 (1,'hyperlink_target',0,'html_1','synchronized_setting','text','content',1,'html','remove',NULL),
 (1,'image',0,'1','synchronized_setting','text','file',1,'','remove',NULL),
 (1,'image_source',0,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (1,'link_type',0,'_CONTENT_ITEM','synchronized_setting','text',NULL,0,'','remove',NULL),
 (1,'mobile_behaviour',0,'mobile_same_image_different_size','synchronized_setting','text',NULL,0,'','remove',NULL),
 (1,'mobile_canvas',0,'fixed_height','synchronized_setting','text',NULL,0,'','remove',NULL),
 (1,'mobile_height',0,'30','synchronized_setting','text',NULL,0,'','remove',NULL),
 (4,'text',2,'<p class=\"phone\">[[Call us on +44 118 324 5555]]</p>','synchronized_setting','translatable_html',NULL,0,'','remove',NULL),
 (4,'title_tags',2,'p','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'advanced_behaviour',5,'none','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'advanced_behaviour',7,'none','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'alt_tag',5,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'alt_tag',7,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'animation_library',0,'cycle2','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'banner_canvas',0,'crop_and_zoom','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'banner_height',0,'775','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'banner_width',0,'2000','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'hyperlink_target',5,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'hyperlink_target',7,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'image',5,'8','synchronized_setting','text','file',8,'','remove',NULL),
 (5,'image',7,'6','synchronized_setting','text','file',6,'','remove',NULL),
 (5,'image_source',5,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'image_source',7,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'link_type',5,'_NO_LINK','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'link_type',7,'_NO_LINK','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'mobile_canvas',5,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'mobile_canvas',7,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'mobile_height',5,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'mobile_height',7,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'mobile_image',5,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'mobile_image',7,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'mobile_width',5,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'mobile_width',7,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'more_link_text',5,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'more_link_text',7,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'rollover_image',5,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'rollover_image',7,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'show_next_prev_buttons',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'target_blank',5,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'target_blank',7,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'text',5,'Its slide description goes here, which you can edit','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'text',7,'Its slide description goes here, which you can edit','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'title',5,'Slide title goes here','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'title',7,'And this is another slide, add more if you wish!','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'url',5,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (5,'url',7,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'advanced_behaviour',0,'none','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'alt_tag',0,'','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (7,'canvas',0,'crop_and_zoom','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'enlarge_canvas',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'floating_box_title_mode',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'height',0,'466','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'hide_private_item',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'image',0,'7','synchronized_setting','text','file',7,'','remove',NULL),
 (7,'image_source',0,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'link_type',0,'_NO_LINK','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'mobile_behaviour',0,'mobile_same_image','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'mobile_canvas',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'set_an_anchor',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (7,'text',0,'<p>Add calls-to-action on your page using Banner plugins.</p>','synchronized_setting','translatable_html',NULL,0,'','remove',NULL),
 (7,'title',0,'This is a Banner plugin with image','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (7,'title_tags',0,'h2','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'translate_text',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (7,'width',0,'588','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'banner_canvas',0,'crop_and_zoom','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'banner_height',0,'150','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'banner_width',0,'150','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'eggs_equal_height',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'heading_tag',0,'p','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'heading_text',0,'SERVICES','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'image',11,'9','synchronized_setting','text','file',9,'','remove',NULL),
 (8,'image',12,'9','synchronized_setting','text','file',9,'','remove',NULL),
 (8,'image',13,'9','synchronized_setting','text','file',9,'','remove',NULL),
 (8,'image_source',11,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'image_source',12,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'image_source',13,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'show_heading',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (8,'text',11,'<p>This is a nested banner</p>','synchronized_setting','translatable_html',NULL,0,'','remove',NULL),
 (8,'text',12,'<p>This is a nested banner</p>','synchronized_setting','translatable_html',NULL,0,'','remove',NULL),
 (8,'text',13,'<p>This is a nested banner</p>','synchronized_setting','translatable_html',NULL,0,'','remove',NULL),
 (8,'title',11,'Creative Design','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (8,'title',12,'Skillful Development','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (8,'title',13,'Brilliant Support','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (9,'banner_canvas',0,'crop_and_zoom','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'banner_height',0,'380','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'banner_width',0,'380','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'heading_tag',0,'p','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'heading_text',0,'PORTFOLIO','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'image',16,'4','synchronized_setting','text','file',4,'','remove',NULL),
 (9,'image',17,'9','synchronized_setting','text','file',9,'','remove',NULL),
 (9,'image',18,'3','synchronized_setting','text','file',3,'','remove',NULL),
 (9,'image_source',16,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'image_source',17,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'image_source',18,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'retina',16,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'retina',17,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'retina',18,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'show_heading',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (9,'text',15,' <p>This nested Banner has just words.</p>\n <p>The Banners below have images...</p>','synchronized_setting','translatable_html',NULL,0,'','remove',NULL),
 (9,'text',16,'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit lorem ipsum dolor sit amet, consectetur adipiscing.</p>','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (9,'text',17,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur euismod ullamcorper nibh sit amet congue. Nullam rutrum orci vitae lacus luctus, eu facilisis urna varius. Morbi nec orci sed lacus tincidunt tincidunt sit amet et.','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (9,'text',18,'Duis ut mi orci. Integer urna lacus, hendrerit nec arcu sit amet, bibendum pretium nulla.','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (9,'title',15,'This is another Nest containing Banner plugins','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (9,'title',16,'Software','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (9,'title',17,'Hardware','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (9,'title',18,'Tools','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (12,'advanced_behaviour',0,'none','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'alt_tag',0,'','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (12,'canvas',0,'crop_and_zoom','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'enlarge_canvas',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'floating_box_title_mode',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'height',0,'220','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'hide_private_item',0,'_ALWAYS_SHOW','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'hyperlink_target',0,'html_1','synchronized_setting','text','content',1,'html','remove',NULL),
 (12,'image',0,'2','synchronized_setting','text','file',2,'','remove',NULL),
 (12,'image_source',0,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'link_to_anchor',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (12,'link_type',0,'_CONTENT_ITEM','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'mobile_behaviour',0,'mobile_same_image','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'mobile_canvas',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'more_link_text',0,'','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (12,'set_an_anchor',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (12,'target_blank',0,'','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (12,'text',0,'<p>Set CSS for this Banner plugin: we\'ve predefined <em>align_center</em>, <em>align_left</em> and <em>align_right</em>; and <em>dark</em> and <em>light</em>.</p>\n<p>Edit CSS for Banners to add as many more styles as you like.</p>','synchronized_setting','translatable_html',NULL,0,'','remove',NULL),
 (12,'title',0,'Your text can overlay an image','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (12,'title_tags',0,'h2','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'translate_text',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (12,'width',0,'1338','synchronized_setting','text',NULL,0,'','remove',NULL),
 (14,'user_form',0,'1','synchronized_setting','text','user_form',1,'','remove',NULL),
 (16,'text',0,'<p>Put your company address here.</p>','synchronized_setting','translatable_html',NULL,0,'','remove',NULL),
 (16,'title',0,'Your company name','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (17,'html',0,'<div class=\"social_links\">\n 	<a><i class=\"fa fa-youtube\"></i></a>\n 	<a href=\"https://twitter.com/ZenarioCMS\" target=\"_blank\"><i class=\"fa fa-twitter\"></i></a>\n </div>','synchronized_setting','html',NULL,0,'','remove',NULL),
 (19,'company_name',0,'Your Company','synchronized_setting','text',NULL,0,'','remove',NULL),
 (19,'display_single_year',0,'current_year','synchronized_setting','text',NULL,0,'','remove',NULL),
 (19,'year_display',0,'display_single_year','synchronized_setting','text',NULL,0,'','remove',NULL),
 (20,'display_mode',0,'inline_popup','synchronized_setting','text',NULL,0,'','remove',NULL),
 (20,'user_form',0,'1','synchronized_setting','text','user_form',1,'','remove',NULL),
 (22,'advanced_behaviour',0,'background_image','synchronized_setting','text',NULL,0,'','remove',NULL),
 (22,'canvas',0,'crop_and_zoom','synchronized_setting','text',NULL,0,'','remove',NULL),
 (22,'height',0,'400','synchronized_setting','text',NULL,0,'','remove',NULL),
 (22,'image',0,'5','synchronized_setting','text','file',5,'','remove',NULL),
 (22,'image_source',0,'_CUSTOM_IMAGE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (22,'text',0,'<p>Design your own unique pages</p>','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (22,'title',0,'Second Page','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (22,'title_tags',0,'h1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (22,'width',0,'2000','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'canvas',0,'crop_and_zoom','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'category_filters_dropdown',0,'','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'child_item_levels',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'content_type',0,'news','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'data_field',0,'content_summary','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'date_format',0,'_RELATIVE','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'enable_omit_category',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'enable_rss',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'fall_back_to_default_image',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'heading_if_items',0,'Latest news','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (29,'heading_if_no_items',0,'There\'s no news just yet','synchronized_setting','translatable_text',NULL,0,'','remove',NULL),
 (29,'heading_tags',0,'h1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'height',0,'160','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'hide_private_items',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'language_selection',0,'visitor','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'make_content_items_equal_height',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'maximum_results_number',0,'5','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'more_link_text',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'offset',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'only_show',0,'public','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'only_show_child_items',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'order',0,'Most_Recent_First','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'page_limit',0,'9','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'page_size',0,'maximum_of','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'pagination_style',0,'zenario_common_features::pagCloseWithNPIfNeeded','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'phrase.framework.Download volume ',0,'Download volume','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'pinned_content_items',0,'prioritise_pinned','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'pinned_text',0,'Pinned','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'refine_type',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'relative_operator',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'relative_units',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'relative_value',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'release_date',0,'ignore','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'show_author',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'show_category_name',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'show_content_items_lowest_category',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'show_dates',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'show_headings',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'show_headings_if_no_items',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'show_language',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'show_more_link',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'show_pagination',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'show_permalink',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'show_pinned_icon',0,'to_admins_and_visitors','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'show_pinned_text',0,'to_admins_and_visitors','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'show_featured_image',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'show_text_preview',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'show_times',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'show_titles',0,'1','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'simple_access_cookie_required',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'target_blank',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'titles_tags',0,'h2','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'translate_text',0,'','synchronized_setting','text',NULL,0,'','remove',NULL),
 (29,'use_download_page',0,'0','synchronized_setting','empty',NULL,0,'','remove',NULL),
 (29,'width',0,'240','synchronized_setting','text',NULL,0,'','remove',NULL);
ALTER TABLE `[[DB_PREFIX]]plugin_settings` ENABLE KEYS;

ALTER TABLE `[[DB_PREFIX]]files` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]files` (
	`id`, `checksum`, `short_checksum`, `usage`, `privacy`, `archived`, `created_datetime`, `filename`, `mime_type`,
	`width`, `height`, `alt_tag`, `title`, `floating_box_title`, `size`, `location`, `data`, `path`,
	`thumbnail_180x130_width`, `thumbnail_180x130_height`, `thumbnail_180x130_data`,
	`custom_thumbnail_1_width`, `custom_thumbnail_1_height`, `custom_thumbnail_1_data`, `custom_thumbnail_2_width`, `custom_thumbnail_2_height`, `custom_thumbnail_2_data`
) VALUES
 (1,'w2T_e2Qk8xUupA7kv4jjQw','w2T_e','image','public',0,NOW(),'logo-zebra-designs.svg','image/svg+xml',176,44,'Zebra Designs logo',NULL,NULL,10636,'db','<?xml version="1.0" encoding="UTF-8" standalone="no"?>\n<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">\n<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:serif="http://www.serif.com/" width="176px" height="44px" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">\n    <g transform="matrix(1.67785,0,0,1.67785,-5.07807,-9.76701)">\n        <g transform="matrix(0.293333,0,0,0.293333,-6.36014,4.26647)">\n            <g>\n                <g>\n                    <g>\n                        <path d="M49.6,55.5L50,56.5L50.4,55.5L51.9,49.6L53.5,46.2L52.7,38.7L50.9,33.1L50,32.2L49.1,33.1L47.3,38.7L46.5,46.2L48.1,49.6L49.6,55.5Z" style="fill-rule:nonzero;"/>\n                        <path d="M42.3,54.2L46.1,59.4L46.5,62.9L43.2,58.2L39.2,56.7L36.1,54.57L36.4,59.7L38.3,58.9L41.4,58.9L43.1,62.1L43.6,65.1L43.6,66.9L41.4,63.7L39.4,63.1L37.1,63.8L36.9,63.8L40.7,78.3L41.9,76.6L44.7,67L48.2,68.3L50,70L51.8,68.3L55.3,67L58.1,76.6L59.3,78.3L63.1,63.8L62.9,63.8L60.6,63L58.6,63.6L56.4,66.8L56.4,65L58.1,63L59.8,62.1L60.8,61.1L58.6,60.5L57,62.1L58.7,58.9L61.8,58.9L63.7,59.7L64,54.57L60.9,56.7L56.9,58.2L53.6,62.9L54,59.4L57.8,54.2L59,53.5L63.963,50.299L64.9,48.9L64.9,39.031L59.9,47.8L60.8,45.7L61.5,42.7L60.2,40.7L60.9,36L59.3,34.5L51.7,26.7L50,26.1L48.5,26.6L40.9,34.4L39.3,35.9L40,40.6L38.7,42.6L39.4,45.6L40.3,47.7L35.3,39.203L35.3,48.8L35.563,50.199L41.2,53.4L42.3,54.2ZM59.8,49.8L62,47.5L63.7,47.5L63.677,47.552L63.69,47.531L59.8,51.4L59.7,51.4L58,51.7L57.7,51.7L59.8,49.8ZM57.7,34.9L58.2,38.8L59.8,44.6L56,53L51.5,59.6L50.4,65L50.4,57.8L55.1,51.6L57.7,43.4L56.4,38.1L55.6,35.1L51.6,29.4L57.7,34.9ZM50,30.3L55.1,39.5L55.6,47L52.3,53L50,57.1L47.8,53L44.5,47L45,39.5L50,30.3ZM41.8,38.7L42.3,34.8L48.5,29.4L44.5,35.1L43.7,38.1L42.4,43.4L45,51.6L49.7,57.8L49.7,65L48.6,59.6L44,53L40.2,44.5L41.8,38.7ZM40.2,51.5L36.298,47.605L36.312,47.627L36.3,47.6L38,47.6L40.2,49.9L42.3,51.8L42,51.8L40.3,51.6L40.2,51.5Z" style="fill-rule:nonzero;"/>\n                        <path d="M56,76.9L54.3,69L52.2,70.1L50,72L47.8,70.1L45.7,69L44,76.9L41.5,81.5L39.4,85.2L41.4,87.7L42.2,91.6L45.8,93.9L50,94.7L54.2,93.9L57.8,91.6L58.6,87.7L60.6,85.2L58.5,81.5L56,76.9Z" style="fill-rule:nonzero;"/>\n                        <path d="M61.8,7.3L54.7,14.7L54.7,25.6L52.3,23.8L53.5,10.5L51.6,6.1L50,5.3L48.3,6L46.4,10.4L47.6,23.7L45.2,25.5L45.2,14.7L38.1,7.3L32,12.1L33.7,24.8L39.8,30.4L35.1,38.1L35.1,38.9L40.1,33L43.4,30.5L47.2,25.8L49.9,25.3L52.6,25.8L56.4,30.5L59.7,33L64.7,38.9L64.7,38.1L60,30.4L66.1,24.8L67.8,12.1L61.8,7.3ZM41.8,27.9L36.3,24.5L34.1,13.8L38.7,10.7L42.6,14.6L43.5,26.7L41.8,27.9ZM63.7,24.5L58.2,27.9L56.5,26.7L57.4,14.6L61.3,10.7L65.9,13.8L63.7,24.5Z" style="fill-rule:nonzero;"/>\n                    </g>\n                </g>\n            </g>\n        </g>\n        <g transform="matrix(1,0,0,1,10.8456,0.71438)">\n            <path d="M18.984,5.338L14.928,17.386L19.008,17.386L18.312,22.138L8.328,22.138L12.384,10.114L8.328,10.09L8.928,5.338L18.984,5.338Z" style="fill-rule:nonzero;"/>\n            <path d="M36.192,5.338L36.768,10.138L33.288,10.09L35.424,13.666L33.24,17.434L36.768,17.434L36.192,22.138L24.768,22.138L29.88,13.762L29.88,13.738L24.768,5.338L36.192,5.338Z" style="fill-rule:nonzero;"/>\n            <path d="M51.336,5.338L51.768,5.386C51.912,5.402 52.08,5.442 52.272,5.506C52.464,5.57 52.648,5.634 52.824,5.698C52.936,5.746 53.044,5.794 53.148,5.842C53.252,5.89 53.352,5.946 53.448,6.01C53.576,6.09 53.696,6.17 53.808,6.25C53.92,6.33 54.032,6.418 54.144,6.514C55.184,7.394 55.72,8.562 55.752,10.018C55.752,10.754 55.596,11.446 55.284,12.094C54.972,12.742 54.536,13.29 53.976,13.738L53.976,13.786C54.152,13.914 54.324,14.074 54.492,14.266C54.66,14.458 54.808,14.65 54.936,14.842C55.304,15.37 55.54,15.978 55.644,16.666C55.748,17.354 55.712,18.018 55.536,18.658C55.504,18.818 55.456,18.966 55.392,19.102C55.328,19.238 55.264,19.386 55.2,19.546C55.12,19.642 55.072,19.722 55.056,19.786C54.88,20.09 54.668,20.366 54.42,20.614C54.172,20.862 53.912,21.098 53.64,21.322C53.32,21.53 52.992,21.698 52.656,21.826C52.64,21.826 52.624,21.83 52.608,21.838C52.592,21.846 52.576,21.85 52.56,21.85C52.32,21.962 52.016,22.042 51.648,22.09C51.536,22.106 51.428,22.118 51.324,22.126C51.22,22.134 51.112,22.138 51,22.138L42.528,22.138L42.528,5.338L51.336,5.338ZM51.072,10.042L47.328,10.042L47.304,13.762L47.304,17.338L50.88,17.338L47.304,13.762L51.072,10.042Z" style="fill-rule:nonzero;"/>\n            <path d="M69.624,5.362C69.72,5.362 69.784,5.37 69.816,5.386C69.896,5.354 69.976,5.354 70.056,5.386C70.136,5.418 70.216,5.426 70.296,5.41C70.488,5.426 70.676,5.458 70.86,5.506C71.044,5.554 71.224,5.61 71.4,5.674C71.608,5.754 71.824,5.85 72.048,5.962C72.704,6.298 73.256,6.778 73.704,7.402C74.104,7.962 74.36,8.586 74.472,9.274C74.584,9.962 74.552,10.642 74.376,11.314C74.344,11.394 74.312,11.478 74.28,11.566C74.248,11.654 74.216,11.738 74.184,11.818C74.088,12.09 73.952,12.354 73.776,12.61C73.6,12.866 73.424,13.106 73.248,13.33L72.072,14.986L75.216,22.138L69.648,22.138L66.432,14.866L69.912,10.09L66.288,10.09L66.288,22.138L61.68,22.138L61.68,5.362L69.624,5.362Z" style="fill-rule:nonzero;"/>\n            <path d="M96.84,22.138L91.56,22.138L88.968,16.642L86.28,22.138L81,22.138L88.968,5.338L96.84,22.138Z" style="fill-rule:nonzero;"/>\n        </g>\n        <g transform="matrix(1,0,0,1,-18.1476,-2.90003)">\n            <path d="M37.618,34.736L39.158,34.736C40.964,34.736 41.461,33.427 41.461,32.174C41.461,30.606 40.74,29.829 39.144,29.829L37.618,29.829L37.618,34.736ZM38.682,30.711L39.067,30.711C39.844,30.711 40.362,31.089 40.362,32.272C40.362,33.217 40.075,33.854 39.074,33.854L38.682,33.854L38.682,30.711Z" style="fill-rule:nonzero;"/>\n            <path d="M46.879,34.736L46.879,33.854L44.954,33.854L44.954,32.685L46.536,32.685L46.536,31.803L44.954,31.803L44.954,30.711L46.795,30.711L46.795,29.829L43.89,29.829L43.89,34.736L46.879,34.736Z" style="fill-rule:nonzero;"/>\n            <path d="M50.827,33.903C50.232,33.903 49.805,33.525 49.595,33.315L48.986,34.197C49.154,34.386 49.742,34.806 50.687,34.806C51.933,34.806 52.542,34.092 52.542,33.357C52.542,31.796 50.288,31.915 50.288,31.124C50.288,30.879 50.484,30.683 50.834,30.683C51.352,30.683 51.632,30.991 51.751,31.138L52.416,30.347C52.255,30.137 51.744,29.759 50.925,29.759C49.91,29.759 49.196,30.396 49.196,31.208C49.196,32.755 51.457,32.65 51.457,33.427C51.457,33.686 51.254,33.903 50.827,33.903Z" style="fill-rule:nonzero;"/>\n            <rect x="54.978" y="29.829" width="1.071" height="4.907"/>\n            <path d="M60.865,29.759C59.563,29.759 58.534,30.557 58.534,32.328C58.534,33.966 59.227,34.806 60.62,34.806C61.53,34.806 62.02,34.617 62.377,34.372L62.377,32.209L61.306,32.209L61.306,33.868C61.208,33.91 60.991,33.938 60.844,33.938C60.025,33.938 59.633,33.413 59.633,32.202C59.633,31.04 60.179,30.683 60.823,30.683C61.278,30.683 61.537,30.879 61.719,31.075L62.405,30.326C62.272,30.172 61.831,29.759 60.865,29.759Z" style="fill-rule:nonzero;"/>\n            <path d="M65.919,31.166C66.122,31.705 66.325,32.188 66.57,32.664L67.648,34.736L68.901,34.736L68.901,29.829L67.886,29.829L67.886,31.383C67.886,31.901 67.886,32.58 67.942,33.266L67.914,33.266C67.732,32.776 67.466,32.23 67.221,31.761L66.22,29.829L64.939,29.829L64.939,34.736L65.961,34.736L65.961,32.881C65.961,32.034 65.94,31.656 65.891,31.166L65.919,31.166Z" style="fill-rule:nonzero;"/>\n            <path d="M73.045,33.903C72.45,33.903 72.023,33.525 71.813,33.315L71.204,34.197C71.372,34.386 71.96,34.806 72.905,34.806C74.151,34.806 74.76,34.092 74.76,33.357C74.76,31.796 72.506,31.915 72.506,31.124C72.506,30.879 72.702,30.683 73.052,30.683C73.57,30.683 73.85,30.991 73.969,31.138L74.634,30.347C74.473,30.137 73.962,29.759 73.143,29.759C72.128,29.759 71.414,30.396 71.414,31.208C71.414,32.755 73.675,32.65 73.675,33.427C73.675,33.686 73.472,33.903 73.045,33.903Z" style="fill-rule:nonzero;"/>\n            <path d="M83.237,32.167L83.237,32.139C83.608,32.006 83.951,31.656 83.951,31.103C83.951,30.116 83.237,29.829 82.369,29.829L80.612,29.829L80.612,34.736L82.236,34.736C83.867,34.736 84.189,33.868 84.189,33.231C84.189,32.538 83.706,32.223 83.237,32.167ZM82.922,31.25C82.922,31.53 82.719,31.796 82.201,31.796L81.669,31.796L81.669,30.711L82.243,30.711C82.747,30.711 82.922,30.949 82.922,31.25ZM82.404,33.854L81.669,33.854L81.669,32.678L82.348,32.678C82.747,32.678 83.104,32.839 83.104,33.266C83.104,33.546 82.943,33.854 82.404,33.854Z" style="fill-rule:nonzero;"/>\n            <path d="M90.174,29.829L88.522,33.161L88.522,34.736L87.451,34.736L87.451,33.161L85.799,29.829L86.989,29.829L87.99,32.09L88.018,32.09L89.04,29.829L90.174,29.829Z" style="fill-rule:nonzero;"/>\n            <path d="M96.46,34.736L97.531,34.736L97.531,30.711L98.749,30.711L98.749,29.829L95.242,29.829L95.242,30.711L96.46,30.711L96.46,34.736Z" style="fill-rule:nonzero;"/>\n            <path d="M100.877,29.829L100.877,34.736L101.934,34.736L101.934,32.909L102.004,32.909C102.228,32.909 102.361,32.965 102.452,33.14L103.264,34.736L104.538,34.736L103.789,33.385C103.558,32.972 103.397,32.79 103.138,32.664L103.138,32.636C103.712,32.524 104.174,32.125 104.174,31.271C104.174,30.242 103.558,29.829 102.473,29.829L100.877,29.829ZM101.934,30.711L102.354,30.711C102.823,30.711 103.089,30.914 103.089,31.362C103.089,31.817 102.907,32.062 102.34,32.062L101.934,32.062L101.934,30.711Z" style="fill-rule:nonzero;"/>\n            <rect x="106.764" y="29.829" width="1.071" height="4.907"/>\n            <path d="M113.162,32.167L113.162,32.139C113.533,32.006 113.876,31.656 113.876,31.103C113.876,30.116 113.162,29.829 112.294,29.829L110.537,29.829L110.537,34.736L112.161,34.736C113.792,34.736 114.114,33.868 114.114,33.231C114.114,32.538 113.631,32.223 113.162,32.167ZM112.847,31.25C112.847,31.53 112.644,31.796 112.126,31.796L111.594,31.796L111.594,30.711L112.168,30.711C112.672,30.711 112.847,30.949 112.847,31.25ZM112.329,33.854L111.594,33.854L111.594,32.678L112.273,32.678C112.672,32.678 113.029,32.839 113.029,33.266C113.029,33.546 112.868,33.854 112.329,33.854Z" style="fill-rule:nonzero;"/>\n            <path d="M117.831,32.013C118.006,31.39 118.111,30.991 118.195,30.571L118.223,30.571C118.307,31.005 118.405,31.362 118.594,32.041L118.783,32.727L117.628,32.727L117.831,32.013ZM117.474,29.829L115.99,34.736L117.068,34.736L117.383,33.609L119.028,33.609L119.336,34.736L120.456,34.736L118.965,29.829L117.474,29.829Z" style="fill-rule:nonzero;"/>\n            <path d="M122.661,29.829L122.661,34.736L125.538,34.736L125.538,33.854L123.732,33.854L123.732,29.829L122.661,29.829Z" style="fill-rule:nonzero;"/>\n        </g>\n    </g>\n</svg>','',0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
ALTER TABLE `[[DB_PREFIX]]files` ENABLE KEYS;


ALTER TABLE `[[DB_PREFIX]]skins` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]skins` VALUES
 (1,'[[THEME]]','[[THEME]]','','zenario/styles/fea/fea_common.css\nzenario/styles/fea/fea_font_awesome.css','','body',1,0);
ALTER TABLE `[[DB_PREFIX]]skins` ENABLE KEYS;


ALTER TABLE `[[DB_PREFIX]]layout_head_and_foot` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]layout_head_and_foot` VALUES
 ('sitewide',12,769,1240,1,1,'{\"cols\": 12, \"cells\": [{\"width\": 12, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Top\"}, {\"name\": \"Slot_Header_Top\", \"slot\": true, \"width\": 12}, {\"width\": 12, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Header Fixed\"}, {\"name\": \"Slot_Header_Logo\", \"slot\": true, \"width\": 3}, {\"name\": \"Slot_Header_Menu\", \"slot\": true, \"small\": \"hide\", \"width\": 9}, {\"name\": \"Slot_Header_Mobile_Menu\", \"slot\": true, \"small\": \"only\", \"width\": 12}], \"fluid\": true, \"mirror\": false, \"maxWidth\": 1240, \"minWidth\": 769, \"gutterFlu\": 1, \"responsive\": true, \"gutterLeftEdgeFlu\": 1, \"gutterRightEdgeFlu\": 1}','{\"cells\": [{\"width\": 12, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Footer\"}, {\"name\": \"Slot_Footer_Address\", \"slot\": true, \"width\": 6}, {\"cells\": [{\"name\": \"Slot_Footer_Social\", \"slot\": true, \"width\": 6}, {\"name\": \"Slot_Footer_Menu\", \"slot\": true, \"width\": 6}, {\"name\": \"Slot_Footer_Copyright\", \"slot\": true, \"width\": 6}], \"width\": 6, \"css_class\": \"Grouping_Footer\"}, {\"name\": \"Slot_Footer_Contact\", \"slot\": true, \"width\": 12}]}');
ALTER TABLE `[[DB_PREFIX]]layout_head_and_foot` ENABLE KEYS;


ALTER TABLE `[[DB_PREFIX]]layouts` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]layouts` VALUES
 (1,'HTML Standard layout','html','active',12,769,1240,1,1,1,1,'ltr',0,'',NULL,NULL,'{\"cols\": 12, \"cells\": [{\"width\": 12, \"isHeader\": true, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Top\"}, {\"name\": \"Slot_Header_Top\", \"slot\": true, \"width\": 12, \"isHeader\": true}, {\"width\": 12, \"isHeader\": true, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Header Fixed\"}, {\"name\": \"Slot_Header_Logo\", \"slot\": true, \"width\": 3, \"isHeader\": true}, {\"name\": \"Slot_Header_Menu\", \"slot\": true, \"small\": \"hide\", \"width\": 9, \"isHeader\": true}, {\"name\": \"Slot_Header_Mobile_Menu\", \"slot\": true, \"small\": \"only\", \"width\": 12, \"isHeader\": true}, {\"name\": \"Slot_Masthead\", \"slot\": true, \"width\": 12, \"grid_break\": true}, {\"width\": 12, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Body\"}, {\"cells\": [{\"name\": \"Slot_Breadcrumbs\", \"slot\": true, \"width\": 12, \"height\": \"small\"}, {\"name\": \"Slot_Main_1\", \"slot\": true, \"width\": 12}, {\"name\": \"Slot_Main_2\", \"slot\": true, \"width\": 12}, {\"name\": \"Slot_Main_3\", \"slot\": true, \"width\": 12}], \"width\": 12, \"css_class\": \"Main_Area\"}, {\"width\": 12, \"isFooter\": true, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Footer\"}, {\"name\": \"Slot_Footer_Address\", \"slot\": true, \"width\": 6, \"isFooter\": true}, {\"cells\": [{\"name\": \"Slot_Footer_Social\", \"slot\": true, \"width\": 6, \"isFooter\": true}, {\"name\": \"Slot_Footer_Menu\", \"slot\": true, \"width\": 6, \"isFooter\": true}, {\"name\": \"Slot_Footer_Copyright\", \"slot\": true, \"width\": 6, \"isFooter\": true}], \"width\": 6, \"isFooter\": true, \"css_class\": \"Grouping_Footer\"}, {\"name\": \"Slot_Footer_Contact\", \"slot\": true, \"width\": 12, \"isFooter\": true}], \"fluid\": true, \"mirror\": false, \"maxWidth\": 1240, \"minWidth\": 769, \"gutterFlu\": 1, \"responsive\": true, \"headerAndFooter\": true, \"gutterLeftEdgeFlu\": 1, \"gutterRightEdgeFlu\": 1}','0L739A-i',NULL,'not_needed',NULL,0,NULL,'not_needed',NULL,0,0),
 (2,'Home Page Layout','html','active',12,769,1240,1,1,1,1,'ltr',0,'',NULL,NULL,'{\"cols\": 12, \"cells\": [{\"width\": 12, \"isHeader\": true, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Top\"}, {\"name\": \"Slot_Header_Top\", \"slot\": true, \"width\": 12, \"isHeader\": true}, {\"width\": 12, \"isHeader\": true, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Header Fixed\"}, {\"name\": \"Slot_Header_Logo\", \"slot\": true, \"width\": 3, \"isHeader\": true}, {\"name\": \"Slot_Header_Menu\", \"slot\": true, \"small\": \"hide\", \"width\": 9, \"isHeader\": true}, {\"name\": \"Slot_Header_Mobile_Menu\", \"slot\": true, \"small\": \"only\", \"width\": 12, \"isHeader\": true}, {\"name\": \"Slot_Slideshow\", \"slot\": true, \"width\": 12, \"grid_break\": true}, {\"width\": 12, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Body space_at_top\"}, {\"cells\": [{\"name\": \"Slot_Content_1\", \"slot\": true, \"width\": 6, \"height\": \"small\"}, {\"name\": \"Slot_Content_Image_1\", \"slot\": true, \"width\": 6, \"height\": \"small\"}], \"width\": 12, \"css_class\": \"Grouping_Content\"}, {\"width\": 12, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Full_Boxes space_at_top\"}, {\"name\": \"Slot_Full_Boxes\", \"slot\": true, \"width\": 12, \"height\": \"small\"}, {\"width\": 12, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Content space_at_top\"}, {\"name\": \"Slot_Portfolio\", \"slot\": true, \"width\": 12, \"height\": \"small\"}, {\"name\": \"Slot_Full_Banner\", \"slot\": true, \"width\": 12, \"height\": \"small\", \"css_class\": \"space_at_top\"}, {\"cells\": [{\"name\": \"Slot_News\", \"slot\": true, \"width\": 6, \"height\": \"small\"}, {\"name\": \"Slot_Contact_Form\", \"slot\": true, \"width\": 6, \"height\": \"small\"}], \"width\": 12, \"css_class\": \"Grouping_Contact space_at_top\"}, {\"name\": \"Slot_Map\", \"slot\": true, \"width\": 12, \"css_class\": \"space_at_top\", \"grid_break\": true}, {\"width\": 12, \"isFooter\": true, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Footer\"}, {\"name\": \"Slot_Footer_Address\", \"slot\": true, \"width\": 6, \"isFooter\": true}, {\"cells\": [{\"name\": \"Slot_Footer_Social\", \"slot\": true, \"width\": 6, \"isFooter\": true}, {\"name\": \"Slot_Footer_Menu\", \"slot\": true, \"width\": 6, \"isFooter\": true}, {\"name\": \"Slot_Footer_Copyright\", \"slot\": true, \"width\": 6, \"isFooter\": true}], \"width\": 6, \"isFooter\": true, \"css_class\": \"Grouping_Footer\"}, {\"name\": \"Slot_Footer_Contact\", \"slot\": true, \"width\": 12, \"isFooter\": true}], \"fluid\": true, \"mirror\": false, \"maxWidth\": 1240, \"minWidth\": 769, \"gutterFlu\": 1, \"responsive\": true, \"headerAndFooter\": true, \"gutterLeftEdgeFlu\": 1, \"gutterRightEdgeFlu\": 1}','W-Qq8jNG','<link rel=\"stylesheet\" href=\"zenario/libs/yarn/animate.css/animate.min.css\"/>','not_needed',NULL,0,'<script type=\"text/javascript\" src=\"zenario/libs/yarn/wow.js/dist/wow.min.js\"></script>\n<script type=\"text/javascript\" src=\"zenario_custom/skins/zebra_designs/js/animation_load.js\"></script>','not_needed',NULL,0,0),
 (3,'News Layout','news','active',12,769,1240,1,1,1,1,'ltr',0,'',NULL,NULL,'{\"cols\": 12, \"cells\": [{\"width\": 12, \"isHeader\": true, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Top\"}, {\"name\": \"Slot_Header_Top\", \"slot\": true, \"width\": 12, \"isHeader\": true}, {\"width\": 12, \"isHeader\": true, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Header Fixed\"}, {\"name\": \"Slot_Header_Logo\", \"slot\": true, \"width\": 3, \"isHeader\": true}, {\"name\": \"Slot_Header_Menu\", \"slot\": true, \"small\": \"hide\", \"width\": 9, \"isHeader\": true}, {\"name\": \"Slot_Header_Mobile_Menu\", \"slot\": true, \"small\": \"only\", \"width\": 12, \"isHeader\": true}, {\"name\": \"Slot_Masthead\", \"slot\": true, \"width\": 12, \"grid_break\": true}, {\"width\": 12, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Body\"}, {\"cells\": [{\"name\": \"Slot_Breadcrumbs\", \"slot\": true, \"width\": 12, \"height\": \"small\"}, {\"name\": \"Slot_Main_1\", \"slot\": true, \"width\": 12}], \"width\": 12, \"css_class\": \"Main_Area\"}, {\"width\": 12, \"isFooter\": true, \"grid_break\": true, \"grid_css_class\": \"Gridbreak_Footer\"}, {\"name\": \"Slot_Footer_Address\", \"slot\": true, \"width\": 6, \"isFooter\": true}, {\"cells\": [{\"name\": \"Slot_Footer_Social\", \"slot\": true, \"width\": 6, \"isFooter\": true}, {\"name\": \"Slot_Footer_Menu\", \"slot\": true, \"width\": 6, \"isFooter\": true}, {\"name\": \"Slot_Footer_Copyright\", \"slot\": true, \"width\": 6, \"isFooter\": true}], \"width\": 6, \"isFooter\": true, \"css_class\": \"Grouping_Footer\"}, {\"name\": \"Slot_Footer_Contact\", \"slot\": true, \"width\": 12, \"isFooter\": true}], \"fluid\": true, \"mirror\": false, \"maxWidth\": 1240, \"minWidth\": 769, \"gutterFlu\": 1, \"responsive\": true, \"headerAndFooter\": true, \"gutterLeftEdgeFlu\": 1, \"gutterRightEdgeFlu\": 1}','NCUwX1cK',NULL,'not_needed',NULL,0,NULL,'not_needed',NULL,0,0);
ALTER TABLE `[[DB_PREFIX]]layouts` ENABLE KEYS;


ALTER TABLE `[[DB_PREFIX]]layout_slot_link` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]layout_slot_link` VALUES
 (1,'Slot_Breadcrumbs',6,12,'show',0,0),
 (1,'Slot_Footer_Address',10,6,'show',0,1),
 (1,'Slot_Footer_Contact',14,12,'show',0,1),
 (1,'Slot_Footer_Copyright',13,6,'show',0,1),
 (1,'Slot_Footer_Menu',12,6,'show',0,1),
 (1,'Slot_Footer_Social',11,6,'show',0,1),
 (1,'Slot_Header_Logo',2,3,'show',1,0),
 (1,'Slot_Header_Menu',3,9,'hide',1,0),
 (1,'Slot_Header_Mobile_Menu',4,12,'only',1,0),
 (1,'Slot_Header_Top',1,12,'show',1,0),
 (1,'Slot_Main_1',7,12,'show',0,0),
 (1,'Slot_Main_2',8,12,'show',0,0),
 (1,'Slot_Main_3',9,12,'show',0,0),
 (1,'Slot_Masthead',5,12,'show',0,0),
 (2,'Slot_Contact_Form',12,6,'show',0,0),
 (2,'Slot_Content_1',6,6,'show',0,0),
 (2,'Slot_Content_Image_1',7,6,'show',0,0),
 (2,'Slot_Footer_Address',14,6,'show',0,1),
 (2,'Slot_Footer_Contact',18,12,'show',0,1),
 (2,'Slot_Footer_Copyright',17,6,'show',0,1),
 (2,'Slot_Footer_Menu',16,6,'show',0,1),
 (2,'Slot_Footer_Social',15,6,'show',0,1),
 (2,'Slot_Full_Banner',10,12,'show',0,0),
 (2,'Slot_Full_Boxes',8,12,'show',0,0),
 (2,'Slot_Header_Logo',2,3,'show',1,0),
 (2,'Slot_Header_Menu',3,9,'hide',1,0),
 (2,'Slot_Header_Mobile_Menu',4,12,'only',1,0),
 (2,'Slot_Header_Top',1,12,'show',1,0),
 (2,'Slot_Map',13,12,'show',0,0),
 (2,'Slot_News',11,6,'show',0,0),
 (2,'Slot_Portfolio',9,12,'show',0,0),
 (2,'Slot_Slideshow',5,12,'show',0,0),
 (3,'Slot_Breadcrumbs',6,12,'show',0,0),
 (3,'Slot_Footer_Address',8,6,'show',0,1),
 (3,'Slot_Footer_Contact',12,12,'show',0,1),
 (3,'Slot_Footer_Copyright',11,6,'show',0,1),
 (3,'Slot_Footer_Menu',10,6,'show',0,1),
 (3,'Slot_Footer_Social',9,6,'show',0,1),
 (3,'Slot_Header_Logo',2,3,'show',1,0),
 (3,'Slot_Header_Menu',3,9,'hide',1,0),
 (3,'Slot_Header_Mobile_Menu',4,12,'only',1,0),
 (3,'Slot_Header_Top',1,12,'show',1,0),
 (3,'Slot_Main_1',7,12,'show',0,0),
 (3,'Slot_Masthead',5,12,'show',0,0);
ALTER TABLE `[[DB_PREFIX]]layout_slot_link` ENABLE KEYS;


ALTER TABLE `[[DB_PREFIX]]plugin_sitewide_link` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]plugin_sitewide_link` VALUES (2,16,'Slot_Footer_Address'),
 (17,20,'Slot_Footer_Contact'),
 (15,19,'Slot_Footer_Copyright'),
 (4,18,'Slot_Footer_Menu'),
 (5,17,'Slot_Footer_Social'),
 (2,1,'Slot_Header_Logo'),
 (6,2,'Slot_Header_Menu'),
 (12,3,'Slot_Header_Mobile_Menu'),
 (14,4,'Slot_Header_Top');
ALTER TABLE `[[DB_PREFIX]]plugin_sitewide_link` ENABLE KEYS;


ALTER TABLE `[[DB_PREFIX]]site_settings` DISABLE KEYS;
INSERT INTO `[[DB_PREFIX]]site_settings` VALUES
 ('admin_domain_is_public','1','1',0,0,0),
 ('email_address_admin','[[EMAIL_ADDRESS_GLOBAL_SUPPORT]]','',0,0,0),
 ('email_address_from','[[EMAIL_ADDRESS_FROM]]','',0,0,0),
 ('email_name_from','[[admin_first_name]] [[admin_last_name]]','',0,0,0),
 ('short_checksum_length','5','',0,0,0),
 ('site_enabled','1','',0,0,0),
 ('site_disabled_message','<p>A site is being built at this location.</p><p><span class="x-small">If you are a site administrator please <a href="[[admin_link]]">click here</a> to manage your site.</span></p>','',0,0,0),
 ('site_disabled_title','Welcome','',0,0,0),
 ('sitewide_head',
 '<meta name="viewport" content="width=device-width, initial-scale=1" />\n\n<style>\n@font-face {\n\tfont-family: \'robotoregular\';\n\tsrc: url(\'zenario_custom/skins/zebra_designs/fonts/roboto-regular-webfont.woff2\') format(\'woff2\'),\n\t\t url(\'zenario_custom/skins/zebra_designs/fonts/roboto-regular-webfont.woff\') format(\'woff\');\n\tfont-weight: normal;\n\tfont-style: normal;\n}\n\n@font-face {\n\tfont-family: \'robotoitalic\';\n\tsrc: url(\'zenario_custom/skins/zebra_designs/fonts/roboto-italic-webfont.woff2\') format(\'woff2\'),\n\t\t url(\'zenario_custom/skins/zebra_designs/fonts/roboto-italic-webfont.woff\') format(\'woff\');\n\tfont-weight: normal;\n\tfont-style: normal;\n}\n\n@font-face {\n\tfont-family: \'robotolight\';\n\tsrc: url(\'zenario_custom/skins/zebra_designs/fonts/roboto-light-webfont.woff2\') format(\'woff2\'),\n\t\t url(\'zenario_custom/skins/zebra_designs/fonts/roboto-light-webfont.woff\') format(\'woff\');\n\tfont-weight: normal;\n\tfont-style: normal;\n}\n\n@font-face {\n\tfont-family: \'cardoregular\';\n\tsrc: url(\'zenario_custom/skins/zebra_designs/fonts/cardo-regular-webfont.woff2\') format(\'woff2\'),\n\t\t url(\'zenario_custom/skins/zebra_designs/fonts/cardo-regular-webfont.woff\') format(\'woff\');\n\tfont-weight: normal;\n\tfont-style: normal;\n}\n</style>',
 '',0,0,0),
 ('sitewide_foot',
 '<script type=\'text/javascript\' src=\'zenario_custom/skins/zebra_designs/js/sticky_header.js\' defer></script>\n\n\n<script>\n/* Contact popup form shows after 5 seconds */\nzOnLoad(function () {\n\t$(".zenario_user_forms.form_popup").delay(5000).fadeIn(500);\n});\n</script>',
 '',0,0,0),
 ('translations_different_aliases','','',0,0,0),
 ('translations_hide_language_code','','',0,0,0),
 ('period_to_delete_the_form_response_log_headers',30,'never_delete',0,0,0),
 ('period_to_delete_the_email_template_sending_log_headers',30,'never_delete',0,0,0),
 ('user_use_screen_name', '', 0, 0,0,0),
 ('min_extranet_user_password_length', 10, 10, 0,0,0),
 ('min_extranet_user_password_score', 3, 3, 0,0,0);
ALTER TABLE `[[DB_PREFIX]]site_settings` ENABLE KEYS;


INSERT INTO `[[DB_PREFIX]]lov_salutations` (name)
VALUES ('Dr'), ('Miss'), ('Mr'), ('Mrs'), ('Ms'), ('Mx'), ('Prof');

