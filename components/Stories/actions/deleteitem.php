<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0 https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com
 */
 $guid = input('guid');
 $file = ossn_get_file($guid);
 if($file && $file->subtype == 'file:storyfile'){
	 $status = story_get_item($file->owner_guid);
	 if($status &&  $status->owner_guid == ossn_loggedin_user()->guid){
			$path =  $file->getPath();
			if($file->deleteEntity()){
					unset($path);
					
					$stores = new Stories;
					//check if its only one file then delete entire status
				 	$count  = $stores->getStoryItemPhotos($status->guid, array(
									'count' => true,	
					));	
					if(!$count){
						$status->deleteObject();	
					}
					ossn_trigger_message(ossn_print('stories:deleted:status'));
					redirect(REF);
			}
	 }
 }
ossn_trigger_message(ossn_print('stories:delete:failed'));
redirect(REF);
