<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0 https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com
 */
 $form = ossn_view_form('stories/add', array(
		'id' => 'stories-add',
		'action' => ossn_site_url('action/stories/add'),
		'params' => $params,
 ));
 echo ossn_plugin_view('widget/view', array(
		'title' => ossn_print('stories:story:add'),
		'contents' => $form,
 ));
