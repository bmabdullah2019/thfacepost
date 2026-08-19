<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
/** 
 * Auto login access check
 * 
 * @return void
 */
function remember_login_access_check(){
		if(!ossn_isLoggedin()) {
				ossn_add_hook('page', 'load', 'remember_login_member_validation', 1);
		}
}
/**
 * Remember Login Init
 *
 * @return void
 */
function remember_login_init() {
		if(!ossn_isLoggedin()) {
				ossn_extend_view('forms/login2/before/submit', 'rememberlogin/checkbox');
				ossn_register_callback('login', 'success', 'remember_login_check_member');

				ossn_add_hook('page', 'load', 'remember_login_member_validation');
		} else {
				ossn_register_action('user/logout', REMEMBER_LOGIN . 'actions/user/logout.php');
		}
}
/** 
 * Set cookie 
 * 
 * @return boolean
 */
function remember_login_set_cookie(){
		$data = rembember_me_data();	
		$token = remember_me_data_hash($data);
		$time = time() + ( 365 * 24 * 60 * 60); //1 year
		$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

		//ht= hashed token
		if(setcookie('rl_t', $data, $time, '/', '', $isSecure, true) && setcookie('rl_ht', $token, $time, '/', '', $isSecure, true)){
				return true;	
		}
		return false;
}
/**
 * Set autologin cookie
 *
 * @return void
 */
function remember_login_check_member($callback, $type, $params) {
		if(isset($params['user']) && $params['user'] instanceof OssnUser) {
				if(isset($_POST['rememberlogin'])) {
						if(REMEMBER_LOGIN_ADMIN_ALLOWED || (!REMEMBER_LOGIN_ADMIN_ALLOWED && !ossn_loggedin_user()->isAdmin())){
							remember_login_set_cookie();
						}
				}
		}
}
/**
 * Remember Login Member Validation
 *
 * @return void
 */
function remember_login_member_validation() {
		if(!ossn_isLoggedin() && isset($_COOKIE['rl_t']) && isset($_COOKIE['rl_ht'])) {
				
				$rl_t  = $_COOKIE['rl_t'];
				$rl_ht = $_COOKIE['rl_ht'];
				
				$token = base64_decode(trim($rl_t));
				$token = ossn_string_decrypt($token);
				$token = json_decode($token, true);

				if((isset($token['ua']) && !empty($token['ua']) && $token['ua'] !==  hash('sha512', $_SERVER['HTTP_USER_AGENT'])) || (hash('sha512', $rl_t) !== $rl_ht)) {

						unset($_COOKIE['rl_t']);
						unset($_COOKIE['rl_ht']);
						
						setcookie('rl_t', '', time() - 3600, '/');
						setcookie('rl_ht', '', time() - 3600, '/');
						ossn_logout();
				}
				if(filter_var($token['email'], FILTER_VALIDATE_EMAIL)) {
						$ossnuser = ossn_user_by_email($token['email']);
						if(($ossnuser && !$ossnuser->isAdmin()) || (REMEMBER_LOGIN_ADMIN_ALLOWED && $ossnuser)) {
								OssnSession::assign('OSSN_USER', $ossnuser);
								redirect('home');
						}
				}
		}
}
/**
 * Rembember me data
 *
 * return string
 */
function rembember_me_data() {
		$token = array(
				'email' => ossn_loggedin_user()->email,
				'ua'    =>  hash('sha512', $_SERVER['HTTP_USER_AGENT']),
		);
		$token = ossn_string_encrypt(json_encode($token));
		return base64_encode($token);
}
/**
 * Remember me login data hash
 *
 * @param string $data current encoded data
 * 
 * @return string
 */
function remember_me_data_hash($data){
		return hash('sha512', $data);
}
ossn_register_callback('ossn', 'init', 'remember_login_init');
ossn_register_callback('ossn', 'init', 'remember_login_access_check', 1);