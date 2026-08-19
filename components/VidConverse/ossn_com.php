<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    Open Social Website Core Team <info@informatikon.com>
 * @copyright 2014 iNFORMATIKON TECHNOLOGIES
 * @license   General Public Licence http://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org
 */
define('VidConverse', ossn_route()->com . 'VidConverse/');
ossn_register_class(array(
		'VidConverse' => 	VidConverse . 'classes/VidConverse.php',					  
));
function vid_converse_init() {
		ossn_register_page('vidconverse', 'vidconverse_site_details');

		ossn_extend_view('css/ossn.default', 'vidconverse/css');
		ossn_extend_view('js/opensource.socialnetwork', 'vidconverse/js');

		if(ossn_isLoggedin()) {
				ossn_register_action('vidconverse/setup/call', VidConverse . 'actions/setup.php');
				ossn_register_action('vidconverse/status', VidConverse . 'actions/status.php');
				ossn_add_hook('messages', 'message:smilify', 'vidconverse_message_template');
				ossn_add_hook('chat', 'message:smilify', 'vidconverse_message_template');

				ossn_extend_view('profile/pages/profile', 'vidconverse/widget.js');
		}
		if(ossn_isAdminLoggedin()) {
				ossn_register_com_panel('VidConverse', 'settings');
				ossn_register_action('vidconverse/admin/settings', VidConverse . 'actions/settings.php');
		}
		ossn_add_hook('private:network', 'allowed:pages', function($hook, $type, $return, $params){
					$return[0][] = 'avatar';
					$return[0][] = 'vidconverse';
					return $return;											   
		});
}
function vidconverse_apikey() {
		$component = new OssnSite();
		$settings  = $component->getSettings('com:vidconverse:apikey');
		if(isset($settings) && !empty($settings)) {
				return $settings;
		}
		return false;
}
function vidconverse_site_details($pages) {
		switch($pages[0]) {
		case 'details':
				header('Content-type: application/json; charset=utf-8');

				$component   = new OssnComponents();
				$vidconverse = $component->getCom('VidConverse');
				$results     = array(
						'site_name'    => ossn_site_settings('site_name'),
						'email'        => ossn_site_settings('owner_email'),
						'url'          => ossn_site_url(),
						'ossn_version' => ossn_site_settings('site_version'),
						'vidconverse'  => (string) $vidconverse->version,
				);
				echo json_encode($results, JSON_PRETTY_PRINT);
				break;
			default:
				ossn_error_page();
		}
}
function vidconverse_message_template($hook, $type, $text, $params) {
		if(isset($params['instance']->vid_converse_sent) && strlen($params['instance']->vid_converse_sent) > 0) {
				$hash = vidconverse_match_call_urls($params['instance']->vid_converse_sent);
				if((int) $params['instance']->message_from == ossn_loggedin_user()->guid) {
						$message = str_replace('[VIDCONVERSE:REQUEST]', '<span class="vidconverse-message-request">' . ossn_print('vidconverse:request:message') . '</span>', $text);
						return $message;
				} else {
						if(time() - $params['instance']->time > 3600) {
								$btn_container = ' vidconverse-accept-disabled';
								$btn           = '';
						} else {
								$btn_container = '';
								$btn           = 'vidconverse-accept-btn';
						}
						$message = str_replace('[VIDCONVERSE:REQUEST]', '', $text);
						if($params['view_type'] == 'messages/pages/view/recent' || $params['view_type'] == 'messages/templates/message-with-notifi') {
								return '<i>' . ossn_print('vidconverse:request:message:received:text') . '</i>';
						}
						return '<span class="vidconverse-call-request-message' .
						$btn_container .
						'">' .
						ossn_print('vidconverse:request:message:received:text') .
						' <span data-hash="' .
						$hash .
						'" class="vidconverse-message-accept ' .
						$btn .
						'"><i class="fa fa-video-camera"></i>' .
						ossn_print('vidconverse:accept') .
								'</a></span><span class="vidconverse-message-accept-mobile d-inline d-sm-none"></span>';
				}
		}
		return $text;
}
function vidconverse_match_call_urls($text) {
		if(!empty($text)) {
				if(preg_match('/(https?:\/\/vidconverse\.com\/start\/)([0-9a-z]{8})([\-])([0-9a-z]{8})([\-])([0-9a-z]{8})([\-])([0-9a-z]{8})([\-])([0-9a-z]{8})(\/)([0-9a-z]{8})/', $text, $matches)) {
						if(isset($matches[0])) {
								return $matches[0];
						}
				}
		}
		return false;
}
ossn_register_callback('ossn', 'init', 'vid_converse_init');