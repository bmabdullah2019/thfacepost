<?php
/**
 * Open Source Social Network
 *
 * @packageOpen Source Social Network
 * @author    Open Social Website Core Team <info@softlab24.com>
 * @copyright (C) SOFTLAB24 LIMITED
 * @license    OSSN License  https://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org/licence
 */

$current_lang = ossn_site_settings('language');
if(ossn_isLoggedin()) {
	$user = ossn_loggedin_user();
	if(isset($user->language)) {
		$current_lang = $user->language;
	}
}

if (!isset($params["announcement"]->type)) {
	$params["announcement"]->type = "danger";
}
?>

$(document).ready(function(){
	var  announcement= <?php echo json_encode('<div class="announcement" style="text-align: justify;"> <div class="alert alert-'. $params["announcement"]->type.'"> <strong>'.ossn_print('announcement:title').'</strong> <p>'.html_entity_decode($params["announcement"]->announcement).'</p> </div> </div>');?>;
	if($('.newsfeed-middle .ossn-wall-container').length){
		$(announcement).insertAfter('.newsfeed-middle .ossn-wall-container');
		$("[class*='textarea-language-']").hide();
		$('.textarea-language-<?php echo $current_lang; ?>').show();
	}
});