<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network (OSSN)
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */

define('__PROFILE_PHOTO_AND_COVER_SELECTOR__', ossn_route()->com . 'ProfilePhotoAndCoverSelector/');

/**
 * Initialize Component
 *
 * @return void;
 * @access private;
 */
function com_profile_photo_and_cover_selector_init() {
	//hooks
	ossn_add_hook('photo:view', 'profile:controls', 'com_profile_photo_and_cover_selector_profile_photo_menu');
	ossn_add_hook('cover:view', 'profile:controls', 'com_profile_photo_and_cover_selector_album_cover_photo_menu');
	//actions
	if(ossn_isLoggedin()) {
		ossn_register_action('profile/photo/select', __PROFILE_PHOTO_AND_COVER_SELECTOR__ . 'actions/photo/profile/select.php');
		ossn_register_action('profile/cover/photo/select', __PROFILE_PHOTO_AND_COVER_SELECTOR__ . 'actions/photo/profile/cover/select.php');
	}
}

/**
 * Add to buttons on profile photo view
 *
 * @return mix data
 * @access private;
 */
function com_profile_photo_and_cover_selector_profile_photo_menu($hook, $type, $return, $params) {
	if (isset(ossn_loggedin_user()->guid) && ($params->owner_guid == ossn_loggedin_user()->guid || ossn_isAdminLoggedin())) {
		if (isset (ossn_loggedin_user()->icon_guid) && ossn_loggedin_user()->icon_guid != $params->guid) {
			return ossn_plugin_view('PPACSHooks/photos/views/profilephoto/menu', $params) . $return;
		}
	}
}

/**
 * Add to buttons on profile cover photo view
 *
 * @return mix data
 * @access private;
 */
function com_profile_photo_and_cover_selector_album_cover_photo_menu($hook, $type, $return, $params) {
	if (isset(ossn_loggedin_user()->guid) && ($params->owner_guid == ossn_loggedin_user()->guid || ossn_isAdminLoggedin())) {
		if (isset (ossn_loggedin_user()->cover_guid) && ossn_loggedin_user()->cover_guid != $params->guid) {
			return ossn_plugin_view('PPACSHooks/photos/views/coverphoto/menu', $params) . $return;
		}
	}
}

ossn_register_callback('ossn', 'init', 'com_profile_photo_and_cover_selector_init');
