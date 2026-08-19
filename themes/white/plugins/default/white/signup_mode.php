<?php
$SiteSettings                      = new OssnSite();
$com_white_theme_mode_field_signup = $SiteSettings->getSettings('whitetheme_mode_field_signup');
if($com_white_theme_mode_field_signup && $com_white_theme_mode_field_signup == 'yes') {
?>
<script>
$(document).ready(function(){
		$field = "<div class='dropdown-block'><label><?php echo ossn_print('theme:white:thememode');?></label><select class=\"ossn-dropdown-input\" name=\"theme_darkmode\"><option value=\"litemode\"><?php echo ossn_print('theme:white:litemode');?><\/option><option value=\"darkmode\"><?php echo ossn_print('theme:white:darkmode');?><\/option><\/select></div>";			   
		$($field).insertBefore('#ossn-home-signup #ossn-signup-errors');
});
</script>
<?php
}