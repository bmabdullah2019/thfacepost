<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */
 $guid = input('user');
 $user = ossn_user_by_guid($guid);
 if($user){
		$user->data->is_verified_user = true;
		if($user->save()){
				ossn_trigger_message(ossn_print('userverified:success'));
				redirect(REF);
		}
 }
 ossn_trigger_message(ossn_print('userverified:failed'), 'error');
 redirect(REF);