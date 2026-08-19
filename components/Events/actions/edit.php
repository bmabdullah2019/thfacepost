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
$title = input('title');
$info  = input('info');
$guid  = input('guid');
$vars  = array(
		'country'     => input('country'),
		'location'    => input('location'),
		'event_cost'  => input('event_cost'),
		'date'        => input('date'),
		'start_time'  => input('start_time'),
		'end_time'    => input('end_time'),
		'comments'    => input('allowed_comments'),
		'is_finished' => input('is_finished'),
);

$container_guid = input('container_guid');
if(!empty($container_guid)){
		$group = ossn_get_object($container_guid);
		if($group->subtype !== 'ossngroup'){
				$container_guid = false;
		}
}

if(empty($title) || empty($info) || empty($vars['date'])){
		ossn_trigger_message(ossn_print('event:fields:required:title:info'), 'error');
		redirect(REF);
}
$object = ossn_get_event($guid);
if(!$object || $object->owner_guid !== ossn_loggedin_user()->guid){
		ossn_trigger_message(ossn_print('event:save:failed'), 'error');
		redirect(REF);
}

$object->title       = $title;
$object->description = $info;

$object->data->event_cost             = $vars['event_cost'];
$object->data->country                = $vars['country'];
$object->data->location               = $vars['location'];
$object->data->date                   = $vars['date'];
$object->data->start_time             = $vars['start_time'];
$object->data->end_time               = $vars['end_time'];
$object->data->last_update            = time();
$object->data->allowed_comments_likes = $vars['comments'];
$object->data->is_finished            = $vars['is_finished'];

if($object->save()){
		$object->saveImage($object->guid);

		ossn_trigger_message(ossn_print('event:edited'));
		$title = OssnTranslit::urlize($title);
		redirect("event/view/{$guid}/{$title}");
} else {
		ossn_trigger_message(ossn_print('event:edit:failed'), 'error');
		redirect(REF);
}