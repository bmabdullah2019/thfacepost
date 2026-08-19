<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    Open Social Website Core Team <info@softlab24.com>
 * @copyright SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
$apikey = input('apikey');
$setting = new OssnSite;
$args = array(
			   'apikey' => trim($apikey),
);
if($setting->setSetting('com:vidconverse:apikey', $args['apikey'])){	
				ossn_trigger_message(ossn_print('vidconverse:apikey:saved'));
				redirect(REF);
}
redirect(REF);
