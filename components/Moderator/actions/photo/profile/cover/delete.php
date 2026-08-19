<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */

$photoid         = input('id');
$delete          = ossn_photos();
$delete->photoid = $photoid;
$photo           = $delete->GetPhoto($delete->photoid);
if(($photo->owner_guid == ossn_loggedin_user()->guid) || ossn_isAdminLoggedin() || user_canModerate()) {
		if($delete->deleteProfileCoverPhoto()) {
				
				$user                  = ossn_user_by_guid($photo->owner_guid);
				$user->data->cover_time = time();
				$user->save();
				
				ossn_trigger_message(ossn_print('photo:deleted:success'), 'success');
				redirect("album/covers/profile/{$photo->owner_guid}");
		} else {
				ossn_trigger_message(ossn_print('photo:delete:error'), 'error');
				redirect(REF);
		}
} else {
		ossn_trigger_message(ossn_print('photo:delete:error'), 'error');
		redirect(REF);
}
