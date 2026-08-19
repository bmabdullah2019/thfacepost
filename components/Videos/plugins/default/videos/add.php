<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
 echo "<div class='ossn-page-contents'>";
 $form = ossn_view_form('videos/add', array(
		'id' => 'video-add',
		'params' => $params,
 ));
 echo ossn_plugin_view('widget/view', array(
		'title' => ossn_print('video:com:add'),
		'contents' => $form,
 ));
 echo "</div>";