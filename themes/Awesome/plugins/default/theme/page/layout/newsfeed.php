<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
 
//unused pagebar skeleton when ads are disabled #628 
if(ossn_is_hook('newsfeed', "sidebar:right")) {
	$newsfeed_right = ossn_call_hook('newsfeed', "sidebar:right", NULL, array());
	$sidebar = implode('', $newsfeed_right);
	$isempty = trim($sidebar);
}  
?>
<div class="container">
	<div class="row">
			<div class="col-md-8">
				<div class="newsfeed-middle">
					<?php echo $params['content']; ?>
				</div>
			</div>
			<div class="col-md-4">
				<?php if(!empty($isempty)){ ?>
				<div class="newsfeed-right">
					<?php
						echo $sidebar;
						?>
				</div>
				<?php } ?>
			</div>
		</div>
</div>
<?php echo ossn_plugin_view('theme/page/elements/footer');?>