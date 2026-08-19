<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 * @codeby	Homelancer
 */
$group = ossn_get_group_by_guid(input('guid'));
if ($group->owner_guid !== ossn_loggedin_user()->guid && !ossn_isAdminLoggedin() && !user_canModerate()) {
    ossn_trigger_message(ossn_print('group:delete:cover:error'), 'error');
    redirect(REF);
}
$files = $group->groupCovers();
if($files) {
	foreach($files as $file) {
		if($file->isFile()){
			unlink($file->getPath());	
		}
		$file->deleteEntity();
	}
	$group->ResetCoverPostition($group->guid);
}
ossn_trigger_message(ossn_print('group:delete:cover:success'));
redirect(REF);