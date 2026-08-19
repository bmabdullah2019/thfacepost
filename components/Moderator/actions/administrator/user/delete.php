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

$guid = input('guid');
$user = ossn_user_by_guid($guid);
if($user && $user->guid !== ossn_loggedin_user()->guid && !$user->isAdmin()){
	if($user->deleteUser()){
		ossn_trigger_message(ossn_print('admin:user:deleted'), 'success');
	} else {
		ossn_trigger_message(ossn_print('admin:user:delete:error'), 'error');		
	}
}
redirect(REF);