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
 echo ossn_view_widget(array(
	'title' => ossn_print('groupinvite:widget:title'),
	'contents' => ossn_plugin_view('groupinvite/widget_inner', $params),
));		 
