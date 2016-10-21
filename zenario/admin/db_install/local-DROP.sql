
/*  Note that this file is used by the function lookupExistingCMSTables()
    to work out which tables are part of the CMS!  */

DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]admin_setting_defaults`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]categories`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]category_item_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]centralised_lists`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]characteristic_user_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]content`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]content_cache`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]content_items`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]content_item_versions`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]content_types`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]custom_dataset_field_values`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]custom_dataset_fields`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]custom_dataset_files_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]custom_dataset_tabs`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]custom_dataset_values_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]custom_datasets`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]disused_custom_phrases`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]document_rules`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]document_tag_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]document_tags`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]document_types`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]documents`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]documents_custom_data`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]email_templates`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]files`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]group_content_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]group_user_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]groups`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]image_tag_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]image_tags`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]inline_file_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]inline_images`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]job_logs`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]jobs`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]languages`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]last_sent_warning_emails`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]layouts`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]local_revision_numbers`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]menu_hierarchy`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]menu_nodes`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]menu_positions`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]menu_sections`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]menu_text`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]module_dependencies`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]modules`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]nested_paths`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]nested_plugins`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]page_preview_sizes`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]plugin_instance_cache`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]plugin_instances`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]plugin_item_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]plugin_layout_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]plugin_setting_defs`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]plugin_settings`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]signals`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]site_settings`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]skins`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]smart_group_opt_outs`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]smart_group_rules`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]smart_groups`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]spare_aliases`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]spare_domain_names`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]special_pages`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]template_families`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]template_files`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]template_slot_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]translation_chains`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]tuix_file_contents`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]user_admin_box_tabs`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]user_characteristic_values`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]user_characteristic_values_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]user_characteristics`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]user_content_accesslog`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]user_content_link`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]user_form_fields`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]user_forms`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]user_signin_log`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]user_sync_log`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]users`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]users_custom_data`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]versions`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]visitor_phrases`;
DROP TABLE IF EXISTS `[[DB_NAME_PREFIX]]xml_file_tuix_contents`;