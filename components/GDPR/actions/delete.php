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
$user = ossn_loggedin_user();
if($user && !$user->isAdmin()){
	if($user->deleteUser()){
		$user->Logout();
		ossn_trigger_message(ossn_print('gdpr:account:deleted'), 'success');
		redirect();
	} else {
		ossn_trigger_message(ossn_print('gdpr:account:delete:error'), 'error');		
	}	
}
redirect(REF);