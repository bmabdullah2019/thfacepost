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
$guid  = input('guid');
$video = ossn_get_video($guid);
if(!$video) {
		ossn_trigger_message(ossn_print('video:com:invalid'), 'error');
		redirect(REF);
}
$user = ossn_loggedin_user();

if(!$video || ($video->owner_guid !== ossn_loggedin_user()->guid && $video->container_type == 'user') && !ossn_isAdminLoggedin()) {
		ossn_trigger_message(ossn_print('video:com:delete:fail'), 'error');
		redirect($video->getURL(false));
}
if($video->container_type == 'group') {
		if(function_exists('ossn_get_group_by_guid')) {
				$group = ossn_get_group_by_guid($video->container_guid);
				if($group && ($group->owner_guid !== ossn_loggedin_user()->guid && $video->owner_guid !== ossn_loggedin_user()->guid)) {
						if(!$user->canModerate()) {
								ossn_trigger_message(ossn_print('video:com:delete:fail'), 'error');
								redirect(REF);
						}
				}
		}
}
$entity = ossn_get_entities(array(
		'type' => 'object',
		'owner_guid' => $video->guid,
		'subtype' => 'file:video'
));

if(class_exists("OssnLikes")) {
		$likes = new OssnLikes;
		$likes->deleteLikes($entity[0]->guid, 'entity');
}
if(class_exists("OssnComments")) {
		$comments = new OssnComments;
		$comments->commentsDeleteAll($entity[0]->guid, 'comments:entity');
}

$video->deleteWallPost($video->guid);
if($video->deleteObject()) {
		ossn_trigger_message(ossn_print('video:com:deleted'));
		if($video->container_type == 'user') {
				redirect('video/all');
		}
		if($video->container_type == 'group') {
				redirect(REF);
		}
}
ossn_trigger_message(ossn_print('video:com:delete:fail'), 'error');
redirect(REF);