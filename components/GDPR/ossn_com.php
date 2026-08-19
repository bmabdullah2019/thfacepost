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
define('GDPR', ossn_route()->com . 'GDPR/');
//require_once(GDPR . 'classes/GDPR.php');
function gdpr_init() {
		ossn_extend_view('js/opensource.socialnetwork', 'gdpr/js');
		ossn_extend_view('css/ossn.default', 'gdpr/css');
		
		if(ossn_isLoggedin() && !ossn_isAdminLoggedin()){
				ossn_register_action('gdpr/delete/account', GDPR . 'actions/delete.php');
		} 
		if(ossn_isLoggedin()){
				ossn_add_hook('profile', 'edit:section', 'gdpr_deleteaccount_page');				
				ossn_register_menu_item('profile/edit/tabs', array(
							'name' => 'deleteacc',
							'href' => '?section=deleteacc',
							'text' => ossn_print('gdpr:deleteaccount'),
				));					
		}
		
		ossn_register_callback('action', 'load', 'gdpr_signup_check');
}
/**
 * Register a page that shows a button to delete account
 *
 * @return string|void
 */
function gdpr_deleteaccount_page($hook, $type, $return, $params){
		if($params['section'] == 'deleteacc'){
			return ossn_plugin_view('gdpr/button');
		}
}
function gdpr_signup_check($callback, $type, $params){
			$gdpr_agree = input('gdpr_agree');
			if(!$gdpr_agree && isset($params['action']) && $params['action'] == 'user/register'){
						header('Content-Type: application/json');
						echo json_encode(array(
								'dataerr' => ossn_print('gdpr:signup:error')
						));
						exit;		
			}
}
ossn_register_callback('ossn', 'init', 'gdpr_init');