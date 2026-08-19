<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
?>
<div class="ossn-page-contents">
    	<?php
			echo ossn_plugin_view('widget/view', array(
						'title' => ossn_print('polls:add'),
						'contents' => ossn_view_form('polls/add', array(
								'action' => ossn_site_url('action/poll/add'),
								'params' => $params,
					 	 )),
			));
		?>
</div>