<?php if (!defined('NOT_ACCESSED_DIRECTLY')) exit('This file may not be directly accessed'); ?>

<script type="text/javascript">
	zenarioGrid = {"cols":12,"minWidth":769,"maxWidth":1140,"fluid":true,"responsive":true};
</script>

<?php if (file_exists(CMS_ROOT. cms_core::$templatePath. '/includes/header.inc.php')) {
	require CMS_ROOT. cms_core::$templatePath. '/includes/header.inc.php';
}?>
<div class="Grid_Header">
	<div class="container container_12">
		<div class="span span12 span1_1 alpha omega responsive_only slot small_slot Top_Mobile_Menu">
			<?php slot('Top_Mobile_Menu', 'grid'); ?>
		</div>
		<div class="clear"></div>
		<div class="span span12 span1_1 Top_Area alpha omega">
			<div class="span span3 span1_4 alpha slot small_slot Top_1">
				<?php slot('Top_1', 'grid'); ?>
			</div>
			<div class="span span7 span7_12 responsive slot small_slot Top_2">
				<?php slot('Top_2', 'grid'); ?>
			</div>
			<div class="span span2 span1_6 omega slot small_slot Top_3">
				<?php slot('Top_3', 'grid'); ?>
			</div>
		</div>
	</div>
</div>
<div class="Grid_Body">
	<div class="container container_12">
		<div class="span span12 span1_1 alpha omega slot small_slot Full_Width">
			<?php slot('Full_Width', 'grid'); ?>
		</div>
		<div class="clear"></div>
		<div class="span span12 span1_1 Main_Area alpha omega">
			<div class="span span8 span2_3 alpha slot small_slot Main_1">
				<?php slot('Main_1', 'grid'); ?>
			</div>
			<div class="span span4 span1_3 omega slot small_slot Side_1">
				<?php slot('Side_1', 'grid'); ?>
			</div>
			<div class="clear"></div>
			<div class="span span8 span2_3 Main_Slots alpha">
				<div class="span span8 span1_1 alpha omega slot small_slot Main_2">
					<?php slot('Main_2', 'grid'); ?>
				</div>
				<div class="clear"></div>
				<div class="span span8 span1_1 alpha omega slot small_slot Main_3">
					<?php slot('Main_3', 'grid'); ?>
				</div>
				<div class="clear"></div>
				<div class="span span8 span1_1 alpha omega slot small_slot Main_4">
					<?php slot('Main_4', 'grid'); ?>
				</div>
			</div>
			<div class="span span4 span1_3 omega slot small_slot Side_2">
				<?php slot('Side_2', 'grid'); ?>
			</div>
		</div>
	</div>
</div>
<div class="Grid_Footer">
	<div class="container container_12">
		<div class="span span12 span1_1 Footer_Area alpha omega">
			<div class="span span2 span1_6 Space_In_Footer alpha responsive space">
				<span class="pad_slot">&nbsp;</span>
			</div>
			<div class="span span8 span2_3 slot small_slot Footer">
				<?php slot('Footer', 'grid'); ?>
			</div>
			<div class="span span2 span1_6 omega slot small_slot Built_On">
				<?php slot('Built_On', 'grid'); ?>
			</div>
		</div>
	</div>
</div>

<?php if (file_exists(CMS_ROOT. cms_core::$templatePath. '/includes/footer.inc.php')) {
	require CMS_ROOT. cms_core::$templatePath. '/includes/footer.inc.php';
}?>


<?php //data:eJytVF1PgzAU_S99JkY2opM3Z9w0cTFxJnswhnTQQWNpST_UZdl_t-Vr3QbCpk9wD_ece3vPpRsQIkIE8N824AtHMgG-O3BAzHEULDmCH8CXXKESCYUIQgKFzgdTAzwgGCEOHCDXGdKgxds6e4qCMFlpUZia5FeWBTO2xAQFM0TVTiXPdUCCcJxIE6eQEA0UTx8wStaH-nZrRvhWN6EpR6cbtrbidjVgVbxuVRn0P0aCI2SLtk9p2Nnau2UBUxmm8eGA-lo6ZtH6TEMnipBgkaf0H-WheTOIaat7o8a6OeUU-7xGmbn24ySZUUPnc00SJ7beuTP7Ndtkurekl4z3x2X7ZbjdB_2_PZ4wJvtdTTa1YLXun9n7DIaoKm1T5-ZD8EiPKheM9v--2YsjlS5Lm__JscJEBs_0rMlrLGRmAmZMKaaL8va7utEh_C5D1_UuHbAiCkdV8RRzzjjwV5AIHXIkMkYF_qwHF9_Vuua1VNIy8VRJc27fuxjU0RNayfsoRnbGi2l_B-bYhKiKuMfL8TqrZhbw9gflb02-//v2// ?>
<?php //checksum:9UYDiTpl2IsUhAsKj9eylwbGBo0,// ?>