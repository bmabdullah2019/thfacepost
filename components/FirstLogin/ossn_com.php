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
define('FIRST_LOGIN', ossn_route()->com . 'FirstLogin/');
require_once(FIRST_LOGIN . 'classes/FirstLogin.php');
/**
 * First login initialize
 *
 * @return voud
 * @access private
 */
function first_login_init() {
		ossn_register_com_panel('FirstLogin', 'settings');
		if(ossn_isAdminLoggedin()) {
				ossn_register_action('first/login/settings', FIRST_LOGIN . 'actions/settings.php');
		}
		ossn_register_callback('login', 'success', 'first_login');
}
/**
 * First login settings
 *
 * @return string|boolean
 * @access public
 */
function first_login_settings() {
		$com      = new OssnComponents;
		$settings = $com->getSettings('FirstLogin');
		if(isset($settings->pattern)) {
				return $settings->pattern;
		}
		return false;
}
/**
 * First Login
 *
 * @param string $callback Name of callback
 * @param string $type Callback type
 * @param array  $params Arrays or Objects
 *
 * @return void
 * @access private
 */
function first_login($callback, $type, $params) {
		if(isset($params['user']->guid) && empty($params['user']->last_activity)) {
				$url = FirstLogin::getData();
				if(!empty($url)){
					header("Location: {$url}");
					exit;
				}
		}
}
ossn_register_callback('ossn', 'init', 'first_login_init');