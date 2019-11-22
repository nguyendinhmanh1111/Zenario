<?php if (!defined('NOT_ACCESSED_DIRECTLY')) exit('This file may not be directly accessed');

	/*
	 * DO NOT EDIT THIS FILE!
	 *
	 * This file was created by the Zenario Gridmaker system.
	 * Any manual edits will be lost when the system next changes this file.
	 */
?>

<script type="text/javascript">
	zenarioL.init({"cols":12,"minWidth":769,"maxWidth":1366,"fluid":true,"responsive":true,"bp1":0,"bp2":0});
</script>

<?php if (file_exists(CMS_ROOT. ze::$templatePath. '/includes/header.inc.php')) {
	require CMS_ROOT. ze::$templatePath. '/includes/header.inc.php';
}?>

<div class="slot Slot_Top slot_top">
	<?php ze\plugin::slot('Slot_Top', 'outside_of_grid'); ?>
</div>
<div class="Grid_Header Fixed">
	<div class="container container_12">
		<div class="span span3 span1_4 slot_logo alpha slot small_slot Slot_Logo">
			<?php ze\plugin::slot('Slot_Logo', 'grid'); ?>
		</div>
		<div class="span span9 span3_4 slot_menu omega responsive slot small_slot Slot_Menu">
			<?php ze\plugin::slot('Slot_Menu', 'grid'); ?>
		</div>
		<div class="grid_clear"></div>
		<div class="span span12 span1_1 slot_responsive_menu alpha omega responsive_only slot small_slot Slot_Responsive_Menu">
			<?php ze\plugin::slot('Slot_Responsive_Menu', 'grid'); ?>
		</div>
	</div>
</div>
<div class="slot Slot_Slideshow slot_slideshow">
	<?php ze\plugin::slot('Slot_Slideshow', 'outside_of_grid'); ?>
</div>
<div class="Grid_Body">
	<div class="container container_12">
		<div class="span span2 span1_6 slot_search alpha slot small_slot Slot_Search">
			<?php ze\plugin::slot('Slot_Search', 'grid'); ?>
		</div>
		<div class="span span10 span5_6 omega grid_space">
			<span class="pad_slot">&nbsp;</span>
		</div>
		<div class="grid_clear"></div>
		<div class="span span12 span1_1 Grouping_Content section alpha omega">
			<div class="span span6 span1_2 slot_content alpha slot small_slot Slot_Content_1">
				<?php ze\plugin::slot('Slot_Content_1', 'grid'); ?>
			</div>
			<div class="span span6 span1_2 slot_content_image omega slot small_slot Slot_Content_Image_1">
				<?php ze\plugin::slot('Slot_Content_Image_1', 'grid'); ?>
			</div>
		</div>
	</div>
</div>
<div class="Grid_Full_Boxes section">
	<div class="container container_12">
		<div class="span span12 span1_1 slot_full_boxes alpha omega slot small_slot Slot_Full_Boxes">
			<?php ze\plugin::slot('Slot_Full_Boxes', 'grid'); ?>
		</div>
	</div>
</div>
<div class="Grid_Content">
	<div class="container container_12">
		<div class="span span12 span1_1 slot_portfolio section alpha omega slot small_slot Slot_Portfolio">
			<?php ze\plugin::slot('Slot_Portfolio', 'grid'); ?>
		</div>
		<div class="grid_clear"></div>
		<div class="span span12 span1_1 Grouping_Content section alpha omega">
			<div class="span span6 span1_2 slot_content alpha slot small_slot Slot_Content_2">
				<?php ze\plugin::slot('Slot_Content_2', 'grid'); ?>
			</div>
			<div class="span span6 span1_2 slot_content_image omega slot small_slot Slot_Content_Image_2">
				<?php ze\plugin::slot('Slot_Content_Image_2', 'grid'); ?>
			</div>
		</div>
		<div class="grid_clear"></div>
		<div class="span span12 span1_1 slot_news section alpha omega slot small_slot Slot_News">
			<?php ze\plugin::slot('Slot_News', 'grid'); ?>
		</div>
		<div class="grid_clear"></div>
		<div class="span span12 span1_1 slot_full_banner section alpha omega slot small_slot Slot_Full_Banner">
			<?php ze\plugin::slot('Slot_Full_Banner', 'grid'); ?>
		</div>
		<div class="grid_clear"></div>
		<div class="span span12 span1_1 Grouping_Contact section alpha omega">
			<div class="span span6 span1_2 slot_contact alpha slot small_slot Slot_Contact">
				<?php ze\plugin::slot('Slot_Contact', 'grid'); ?>
			</div>
			<div class="span span6 span1_2 slot_contact_form omega slot small_slot Slot_Contact_Form">
				<?php ze\plugin::slot('Slot_Contact_Form', 'grid'); ?>
			</div>
		</div>
	</div>
</div>
<div class="slot Slot_Map slot_map section slot_animation_parent">
	<?php ze\plugin::slot('Slot_Map', 'outside_of_grid'); ?>
</div>
<div class="Grid_Footer">
	<div class="container container_12">
		<div class="span span6 span1_2 slot_address alpha slot small_slot Slot_Address">
			<?php ze\plugin::slot('Slot_Address', 'grid'); ?>
		</div>
		<div class="span span6 span1_2 Grouping_Footer omega">
			<div class="span span6 span1_1 slot_social alpha omega slot small_slot Slot_Social">
				<?php ze\plugin::slot('Slot_Social', 'grid'); ?>
			</div>
			<div class="grid_clear"></div>
			<div class="span span6 span1_1 slot_footer alpha omega slot small_slot Slot_Footer">
				<?php ze\plugin::slot('Slot_Footer', 'grid'); ?>
			</div>
			<div class="grid_clear"></div>
			<div class="span span6 span1_1 slot_copyright alpha omega slot small_slot Slot_Copyright">
				<?php ze\plugin::slot('Slot_Copyright', 'grid'); ?>
			</div>
		</div>
		<div class="grid_clear"></div>
		<div class="span span12 span1_1 slot_contact_popup alpha omega slot small_slot Slot_Contact_Popup">
			<?php ze\plugin::slot('Slot_Contact_Popup', 'grid'); ?>
		</div>
	</div>
</div>

<?php if (file_exists(CMS_ROOT. ze::$templatePath. '/includes/footer.inc.php')) {
	require CMS_ROOT. ze::$templatePath. '/includes/footer.inc.php';
}?>

<?php //data:eJy1Vk2P2yAQ_SsR5xzqrJSqvrVRs1tpW602lXqoVhaxsY2KGQS4SbTKfy_4K04CXsdtb_CGeTPMPD5eUUwYUyj8-Yp2NNE5CoPFHGWSJtFWEvwLhVqWZI4UA92OK2usVBQzrIwvurfAA8EJkWiOOC6IATfGI_oOwiD9tZYo0gY-zt8IORRmtqZ7kvQ57s5z7CfxCBm4smAW71F88FN8Jbx0URQ1rgrMmMFympDLjfkon4kSwBX9TVr2lgU4O7iCyZNHFffNCvpCb5jJU-Wwc0VRPaO7BZ8gOUxt36XvQIE2BMs4d6ZYWy5SOI8FpaA8i1bANeF6pkisKXDLdin4pT-FxjsKXFnEtbGfxgiqLwXOyCBhRO0SdHyZWOJ1yZip856obtcjJXnydKWXWuu2sk7MbHVdsaF0nkDqFBh1Hl7RGn2b_I9yWPxrOQwR-uTgY_5Gds72cYNPEwTmvLrYPYqozDd3Acd_0wXj7SuZNY3vgVkdrUEWA2xRau0jjqP38cDOR7DAoq3ArAIwN62200hgaaXkPeEA2nRk6v1w5T1Qo49JYp4dp6BwYzoncja8CXlbnzcQU8yc939tGbeDU-wr-d5SiBWIg6RZ7tFdaxx9TFvxPYEonfJo1SeqBccXswRs7SxpQfmPOsT7pfm1FHjfTIO7pdlDykqatEELKiVIFKaYKTM9fSI6law6XjtsmN6Z2X2pbYHs36oZP5JUf07MdRQGHfZsN96BWxFUzluxqEmqNWtW1jRnLBUatGjHU8PHPxmNrDI,//v2// ?>
<?php //checksum:Fb1MnPqAWhBbhstSA--57wobSb0,// ?>