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
 
$com = new OssnComponents;
$params = $com->getSettings('Videos');
echo ossn_view_form('videos/admin', array(
    'action' => ossn_site_url() . 'action/video/admin/settings',
	'params' => array('video' => $params),
    'class' => 'ossn-admin-form'	
));