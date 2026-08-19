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

$poll = ossn_poll_get($params['post']->item_guid);
$entity = ossn_get_entity($poll->poll_entity);

$params['entity'] = $entity;
$params['poll']   = $poll;

if($poll->container_type == 'user'){
	echo ossn_plugin_view('polls/wall/user', $params);
} 
if($poll->container_type == 'group'){
	echo ossn_plugin_view('polls/wall/group', $params);
}
if($poll->container_type == 'businesspage'){
	echo ossn_plugin_view('polls/wall/businesspage', $params);
}