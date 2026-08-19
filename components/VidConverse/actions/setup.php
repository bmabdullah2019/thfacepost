<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    Open Social Website Core Team <info@softlab24.com>
 * @copyright (C) SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
 header("Content-Type: application/json; charset=UTF-8");

 $guid = input('guid');
 $receipt_user = ossn_user_by_guid($guid);
 $user = ossn_loggedin_user();
 
 $VidConverse = new VidConverse;
 $Setup		  = $VidConverse->setCall($user->guid, $receipt_user->guid);
 
 if($Setup && isset($Setup['payload']['receipt_url'])){ 
 	$url = $Setup['payload']['receipt_url'];
 	if(class_exists('OssnMessages')){
		 $OssnMessages = new OssnMessages;
 		 $OssnMessages->data->vid_converse_sent = $url;
 	 	 $OssnMessages->send($user->guid, $receipt_user->guid, '[VIDCONVERSE:REQUEST]');
	 }

 	$args = array($receipt_user->fullname, $user->fullname,  $Setup['payload']['receipt_url'], ossn_site_settings('site_name'), ossn_site_url());
	 $receipt_subject = ossn_print('vidconverse:recipient:subject', array($user->fullname));
 	$receipt_mesaage = ossn_print('vidconverse:recipient:message', $args);


 	$args = array($user->fullname, $receipt_user->fullname, $Setup['payload']['caller_url'], ossn_site_settings('site_name'), ossn_site_url());
 	$owner_subject = ossn_print('vidconverse:owner:subject', array($receipt_user->fullname));
 	$owner_message = ossn_print('vidconverse:owner:message', $args);
 
	 
	 $Mail = new OssnMail;
	 $Mail->NotifiyUser($receipt_user->email, $receipt_subject, $receipt_mesaage);

	 $Mail = new OssnMail;
	 $Mail->NotifiyUser($user->email, $owner_subject, $owner_message);


	 echo json_encode(array(
	     'status' => true,
		 'caller_url' => $Setup['payload']['caller_url'],
	 ));
	 exit;
 } else {
	 echo json_encode(array(
	     'status' => false,
	 ));
	 exit;
 }
 