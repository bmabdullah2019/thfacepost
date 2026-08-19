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

$type = input('type');
if(empty($type)){
	$type = 'list';
}
switch($type){
	case 'add':
		echo ossn_view_form('categories/add', array(
    		'action' => ossn_site_url() . 'action/category/add',
    		'class' => 'ossn-admin-form'	
		));	
	break;
	case 'list':
		echo ossn_plugin_view('categories/admin/list');
	break;
}