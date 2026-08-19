<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
$sitename = ossn_site_settings('site_name');
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
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	
	<?php echo ossn_fetch_extend_views('ossn/endpoint'); ?>
    <?php echo ossn_fetch_extend_views('ossn/site/head'); ?>

    <script>
        <?php echo ossn_fetch_extend_views('ossn/js/head'); ?>
    </script> 
</head>

<body>

	<div class="ossn-halt ossn-light"></div>
	<div class="ossn-message-box"></div>
	<div class="ossn-viewer" style="display:none"></div>
    <?php echo ossn_plugin_view('theme/page/elements/system_messages'); ?>    
  
    <div class="opensource-socalnetwork">
    	
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
