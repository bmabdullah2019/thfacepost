<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
$sitename = ossn_site_settings('site_name');
$sitelanguage = ossn_site_settings('language');
if (isset($params['title'])) {
    $title = $params['title'] . ' : ' . $sitename;
} else {
    $title = $sitename;
}
if (isset($params['contents'])) {
    $contents = $params['contents'];
} else {
    $contents = '';
}
$darkmode = ossn_loggedin_user();
$darkmode_class = '';

$SiteSettings = new OssnSite;
$com_white_theme_mode = $SiteSettings->getSettings('com_white_theme_mode');
if(!empty($com_white_theme_mode) && $com_white_theme_mode == 'darkmode'){
	$darkmode_class = 'white-darkmode';	
}
if(ossn_isLoggedin() && isset($darkmode->theme_darkmode) && $darkmode->theme_darkmode == 1){	
	$darkmode_class = 'white-darkmode';
}
if(ossn_isLoggedin() && isset($darkmode->theme_darkmode) && $darkmode->theme_darkmode == 0){	
	$darkmode_class = '';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $sitelanguage; ?>" id="ossn-html" class="<?php echo $darkmode_class;?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="<?php echo ossn_add_cache_to_url(ossn_theme_url().'images/favicon.ico');?>" type="image/x-icon" />
	
    <?php echo ossn_fetch_extend_views('ossn/endpoint'); ?>
    <?php echo ossn_fetch_extend_views('ossn/site/head'); ?>

    <script>
        <?php echo ossn_fetch_extend_views('ossn/js/head'); ?>
    </script>
</head>

<body>
	<div class="ossn-page-loading-annimation">
    		<div class="ossn-page-loading-annimation-inner">
            	<div class="ossn-loading"></div>
            </div>
    </div>

	<div class="ossn-halt ossn-light"></div>
	<div class="ossn-message-box"></div>
	<div class="ossn-viewer" style="display:none"></div>
    <?php echo ossn_plugin_view('theme/page/elements/system_messages'); ?>
    
    <div class="opensource-socalnetwork">
    	<?php echo ossn_plugin_view('theme/page/elements/sidebar');?>
    	 <div class="ossn-page-container">
			  <?php echo ossn_plugin_view('theme/page/elements/topbar');?>
          <div class="ossn-inner-page">    
  	  		  <?php echo $contents; ?>
          </div>    
		</div>
    </div>
    <div id="ossn-theme-config" class="hidden" data-desktop-cover-height="300" data-minimum-cover-image-width="1200"></div>		
    <?php echo ossn_fetch_extend_views('ossn/page/footer'); ?>           
</body>
</html>
