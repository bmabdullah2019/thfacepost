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
$event = new Events; 

$title = input('title');
$info  = input('info');
$vars  = array(
		'country' => input('country'),
		'location' => input('location'),
		'event_cost' => input('event_cost'),
		'date' => input('date'),
		'start_time' => input('start_time'),
		'end_time' => input('end_time'),
		'comments' => input('allowed_comments')
);
$container_type = input('container_type');
$container_guid = input("container_guid");

if($container_type == 'group') {
		$group = ossn_get_object($container_guid);
		if(!$group || $group->subtype !== 'ossngroup') {
			ossn_trigger_message(ossn_print("event:creation:failed"));
			redirect(REF);			
		}
}
if($container_type == 'businesspage') {
		$business_page = get_business_page($container_guid);
		if(!$business_page || ($business_page && $business_page->owner_guid != ossn_loggedin_user()->guid)){
			ossn_trigger_message(ossn_print("event:creation:failed"), 'error');
			redirect(REF);				
		}
}
if($container_type == 'user' && $container_guid != ossn_loggedin_user()->guid || $container_type != 'user' && $container_type != 'group' && $container_type != 'businesspage'){
			ossn_trigger_message(ossn_print("event:creation:failed") , 'error');
			redirect(REF);	
}
if(empty($title) || empty($info) || empty($vars['date']) && empty($_FILES['picture'])) {
		ossn_trigger_message(ossn_print("event:fields:required:title:info"), 'error');
		redirect(REF);
}
$event->data->container_type = $container_type;

if($guid = $event->addEvent($title, $info, $container_guid, $vars)) {
		ossn_trigger_message(ossn_print("event:creation:succes"));
		$title = OssnTranslit::urlize($title);
		redirect("event/view/{$guid}/{$title}");
} else {
		ossn_trigger_message(ossn_print("event:creation:failed"), 'error');
		redirect(REF);
}