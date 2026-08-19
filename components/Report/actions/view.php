<?php
$guid   = input('guid');
$object = ossn_get_object($guid);

switch($object->container_type){
	case 'post':
		$url = "post/view/{$object->container_guid}";
	break;
	case 'comment':
		$annotation = ossn_get_annotation($object->container_guid);
		switch($annotation->type){
			case 'comments:post':
				$url = "post/view/{$annotation->subject_guid}#comments-item-{$annotation->id}";
			break;
		}
	break;
	case 'video':
		$video = ossn_get_video($object->container_guid);
		$url  = "video/view/{$video->guid}";
		break;
	case 'user':
		$user = ossn_user_by_guid($object->container_guid);
		if($user){
			$url = "u/{$user->username}";	
		}
		break;
}

$object->data->is_read = true;
$object->save();

redirect($url);