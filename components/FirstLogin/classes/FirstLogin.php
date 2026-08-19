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
class FirstLogin {
		/**
		 * Transalte tags
		 *
		 * @return string|boolean
		 * @access public
		 */
		public static function getData() {
				$user = ossn_loggedin_user();
				$url  = first_login_settings();
				if(isset($user)) {
						return str_replace(array(
								'[USERNAME]',
								'[GUID]'
						), array(
								$user->username,
								$user->guid
						), $url);
				}
				return false;
		}
}