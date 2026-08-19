<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    Open Source Social Network Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
$OssnComment = new OssnComments();
$image       = input('comment-attachment');
//comment image check if is attached or not
if(!empty($image)) {
		$OssnComment->comment_image = $image;
}
//entity on which comment is going to be posted
$entity = input('entity');

//comment text
$comment = input('comment');
if($OssnComment->PostComment($entity, ossn_loggedin_user()->guid, $comment, 'entity')) {
		$vars            = array();
		$vars['comment'] = (array) ossn_get_comment($OssnComment->getCommentId());

		$entity = ossn_get_entity($entity);
		if($entity->subtype == 'poll_entity') {
				$subject = ossn_poll_get($entity->owner_guid);
		}

		if($entity->subtype == 'event:wall') {
				$subject = ossn_get_event($entity->owner_guid);
		}
		if(isset($subject) && $subject->container_type == 'businesspage') {
				$bpage = get_business_page($subject->container_guid);
				if($bpage && $vars['comment']['owner_guid'] == $business->owner_guid) {
						$vars['businesspage'] = $bpage;
				}
		}
		$data = ossn_comment_view($vars);
		if(!ossn_is_xhr()) {
				redirect(REF);
		} else {
				header('Content-Type: application/json');
				echo json_encode(array(
						'comment' => $data,
						'process' => 1,
				));
				exit();
		}
} else {
		if(!ossn_is_xhr()) {
				redirect(REF);
		} else {
				header('Content-Type: application/json');
				echo json_encode(array(
						'process' => 0,
				));
				exit();
		}
}