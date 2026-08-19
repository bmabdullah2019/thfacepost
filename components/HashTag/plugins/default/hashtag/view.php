<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */

$tag = $params['hashtag'];
 
$wall       = new OssnWall;
$accesstype = ossn_get_homepage_wall_access();
$loggedinuser = ossn_loggedin_user();

// allow admin to watch ALL postings independant of Wall setting
if($loggedinuser->canModerate()) {
	$posts = $wall->getAllPosts(array(
				'type' => false,
				'distinct' => true,
				'wheres' => array("o.type IN ('businesspage', 'user') AND o.description LIKE '%{$tag}%'"),
	));			
	$count = $wall->getAllPosts(array(
			'count' => true,
			'type' => false,
			'wheres' => array("o.type IN ('businesspage', 'user') AND  o.description LIKE '%{$tag}%'"),
			'distinct' => true,
	));
// wall mode: all site posts
} elseif($accesstype == 'public') {
	$posts = $wall->getPublicPosts(array(
				'distinct' => true,
				'wheres' => array("o.type IN ('businesspage', 'user') AND  o.description LIKE '%{$tag}%'"),
	));
	$count = $wall->getPublicPosts(array(
			'count' => true,
			'wheres' => array("o.type IN ('businesspage', 'user') AND o.description LIKE '%{$tag}%'"),
			'distinct' => true,
	));
// wall mode: friends-only posts	
} elseif($accesstype == 'friends') {
	$posts = $wall->getFriendsPosts(array(
				'distinct' => true,
				'wheres' => array("o.type IN ('businesspage', 'user')  AND o.description LIKE '%{$tag}%'"),
	));
	$count = $wall->getFriendsPosts(array(
			'count' => true,
			'wheres' => array("o.type IN ('businesspage', 'user')  AND o.description LIKE '%{$tag}%'"),
			'distinct' => true,
	));
}

if($posts) {
		foreach($posts as $post) {
				$item = ossn_wallpost_to_item($post);
				echo ossn_wall_view_template($item);
		}
		
}
echo ossn_view_pagination($count);
