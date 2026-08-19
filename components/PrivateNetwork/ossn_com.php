<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */

define('__PRIVATE_NETWORK__', ossn_route()->com . 'PrivateNetwork/');

require_once(__PRIVATE_NETWORK__ . 'classes/PrivateNetwork.php');

/**
 * Private network initialize
 *
 * @return void
 */
function com_private_network_init() {
		ossn_add_hook('page', 'override:view', 'com_private_network');
}
/**
 * Private Network
 *
 * @param string $hook   Name of the hook
 * @param string $type 	 The type of hook
 * @param string $return The mixed data
 * @param array  $params The option values
 *
 * @return void
 */
function com_private_network($hook, $type, $return, $params) {
		if(!ossn_isLoggedin()) {
				$privatenetwork = new PrivateNetwork;
				$privatenetwork->start($params);
		} else {
				// now that we're logged in
				// check for formerly visit of a restricted page
				if(isset($_COOKIE['ossn_restricted_url_visited'])) {
					// if so, delelete cookie and do a 1-time redirection
					$visited_url = $_COOKIE['ossn_restricted_url_visited'];
					setcookie('ossn_restricted_url_visited', '', time() - 3600, "/");
					redirect($visited_url);
				}
		}
}
ossn_register_callback('ossn', 'init', 'com_private_network_init');