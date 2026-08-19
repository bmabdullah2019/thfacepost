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
set_time_limit(0);
session_write_close();	

$container_guid = input('container_guid');
$container_type = input('container_type'); 
 
$title = input('title');
$description = input('description');

$error_cnt = 0;
 
/* error simulation  if(($container_type == 'user' && $container_guid + 1 !== ossn_loggedin_user()->guid) || !in_array($container_type, videos_container_types())){ */
if(($container_type == 'user' && $container_guid != ossn_loggedin_user()->guid) || !in_array($container_type, videos_container_types())){
		$error_cnt++;
	 	ossn_trigger_message(ossn_print('video:com:upload:failed'), 'error');
		redirect(REF);
}
if($container_type == 'group' && function_exists('ossn_get_group_by_guid')){
		$group =  ossn_get_group_by_guid($conatiner_guid);
		if($group && !$group->isMember($group->guid, ossn_loggedin_user()->guid)){
				$error_cnt++;
	 			ossn_trigger_message(ossn_print('video:com:upload:failed'), 'error');
				redirect(REF);				
		}
} 
if($container_type == 'businesspage') {
		$business_page = get_business_page($container_guid);
		if(!$business_page || ($business_page && $business_page->owner_guid != ossn_loggedin_user()->guid)){
			$error_cnt++;
			ossn_trigger_message(ossn_print("event:creation:failed"), 'error');
			redirect(REF);				
		}
}
// as you'll see here, the XHR redirects won't really help here 
// so better rely on error count! 
//error_log('continue 39');

if(!$error_cnt) {
		$file = new OssnFile;
		$extension = strtolower($file->getFileExtension($_FILES['video']['name']));
		$tmp_path = $_FILES['video']['tmp_name'];
 
		$video = new Videos;
		$extensions = array('3gp', 'mov', 'avi', 'wmv', 'flv', 'mp4');
		if(!in_array($extension, $extensions)){
				$error_cnt++;
				ossn_trigger_message(ossn_print('video:com:upload:failed'), 'error');
				redirect(REF);
		}
} 
//error_log('continue 72');
if(!$error_cnt) {
		if($guid = $video->addVideo($title, $description,  $container_guid, $container_type)){
				$title = OssnTranslit::urlize($title);
				$url = "video/view/{$guid}/{$title}";
	 
				ossn_set_ajax_data(array(
						'url' => $url
				));	 
	 
				if(isset($newfile)){
						unlink($newfile);
						unlink($progress_file);		 
				}
				ossn_trigger_message(ossn_print('video:com:uploaded'));	
				redirect($url);
		} else {
				if(isset($newfile)){
						unlink($newfile);
						unlink($progress_file);
				}
				ossn_trigger_message(ossn_print('video:com:upload:failed'), 'error');
				redirect(REF);
		}
}