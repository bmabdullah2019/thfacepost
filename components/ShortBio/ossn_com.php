<?php
/**
 * Open Source Social Network
 * @link      https://www.opensource-socialnetwork.org/
 * @package   Short Bio
 * @author    Michael Zülsdorff <ossn@z-mans.net>
 * @copyright (C) Michael Zülsdorff
 * @license   GNU General Public License https://www.gnu.de/documents/gpl-2.0.en.html
 */

define('__SHORT_BIO__', ossn_route()->com . 'ShortBio/');

function com_shortbio_init() {
	ossn_add_hook('profile', 'load:content', 'com_shortbio_bio_box');
	if (ossn_isLoggedin()) {
		ossn_register_action('shortbio/save', __SHORT_BIO__ . 'actions/ShortBio/save.php');

		// in order to enter short bios
		// release 2.x is making use of a new (Short Bio) tab appearing on the profile-settings page
		// replacing the textarea on the timeline page
        ossn_add_hook('profile', 'edit:section', 'com_shortbio_edit_tab');
        ossn_register_menu_item('profile/edit/tabs', array(
			// use unique identifiers to avoid colliding with other component tabs
            'name' => 'com_shortbio',
            'href' => '?section=com_shortbio',
            'text' => ossn_print('com:shortbio:label'),
		));
	}
}

function com_shortbio_bio_box($hook, $type, $return, $params) {
	$extra_html = ossn_plugin_view('ShortBio/shortbio-box', $params);
	$extended_html = str_replace('<div class="ossn-profile-wall">', '<div class="shortbio">' . $extra_html . '</div><div class="ossn-profile-wall">', $return);
	return $extended_html;
}

function com_shortbio_edit_tab($hook, $type, $return, $params) {
    if ($params['section'] == 'com_shortbio') {
        return ossn_plugin_view('ShortBio/profile-settings-tab', $params);
    }
}

ossn_register_callback('ossn', 'init', 'com_shortbio_init');
