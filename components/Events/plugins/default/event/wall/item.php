<?php
/**
 * Open Source Social Network
 *
 * @package   (Informatikon.com).ossn
 * @author    OSSN Core Team <info@opensource-socialnetwork.org>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
$entity = ossn_get_entity($params['post']->item_guid);
if(!$entity){
	return;	
}
$event = ossn_get_event($entity->owner_guid);
if($event->container_type == 'user'){
	echo ossn_plugin_view('event/wall/user', $params);
}
if($event->container_type == 'group'){
	echo ossn_plugin_view('event/wall/group', $params);
}
if($event->container_type == 'businesspage'){
	echo ossn_plugin_view('event/wall/businesspage', $params);
}