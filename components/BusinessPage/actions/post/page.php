<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */

$OssnWall = new OssnWall();


$owner = input('wallowner');
if(isset($owner) && !empty($owner)) {
		$OssnWall->owner_guid = $owner;
}
$OssnWall->type = 'businesspage';

$post          = input('post');
$friends       = false;
$location      = input('location');
$business_page = get_business_page($owner);

$OssnWall->poster_guid = $business_page->owner_guid;

if($business_page && ($business_page->owner_guid == ossn_loggedin_user()->guid || ossn_loggedin_user()->canModerate()) && $OssnWall->Post($post, $friends, $location, OSSN_PUBLIC)) {
		if(ossn_is_xhr()) {
				$guid = $OssnWall->getObjectId();
				$get  = $OssnWall->GetPost($guid);
				if($get) {
						$get = ossn_wallpost_to_item($get);
						ossn_set_ajax_data(array(
								'post' => ossn_wall_view_template($get),
						));
				}
		}
		//no need to show message on success.
		//3.x why not? $arsalanshah
		ossn_trigger_message(ossn_print('post:created'));
		redirect(REF);
} else {
		ossn_trigger_message(ossn_print('post:create:error'), 'error');
		redirect(REF);
}