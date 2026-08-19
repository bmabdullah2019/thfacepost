<?php
/**
 * Open Source Social Network
 *
 * @package   Marketplace
 * @author    Engr. Syed Arsalan Hussain Shah <arsalan@openteknik.com>
 * @copyright (C) OPENTEKNIK LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE
 * @link      https://www.openteknik.com/
 */
 
 $guid = input('guid');
 $privacy = input('privacy');
 $request = family_relation_get_guid($guid);
 if($request){
	 $deleted = false;
	 if($request->member_guid == false){
	 	$deleted = true;
	 } elseif($request->member_guid == ossn_loggedin_user()->guid){
	   $deleted = true;
	 } elseif($request->owner_guid == ossn_loggedin_user()->guid){
	   $deleted = true;
	 } else {
		$deleted = false;
	 }
	 if($deleted){
		  if($request->deleteObject()){
			  	ossn_trigger_callback('family', 'relation:removed', array(
						'request' => $request,													   
				));
				ossn_trigger_message(ossn_print('family:relationship:deleted'));
				redirect(REF);
	   	}
	 }
 } 
redirect(REF);
