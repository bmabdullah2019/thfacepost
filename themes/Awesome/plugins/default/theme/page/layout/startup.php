<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
?>
<div class="ossn-layout-startup">
	<div class="container">
		<div class="row">
			<div class="ossn-home-container">
				<div class="inner">
					<?php echo $params['content']; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<?php echo ossn_plugin_view('theme/page/elements/footer');?>
	</div>
</div>