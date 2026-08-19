<?php
/**
 * Open Source Social Network
 * @link      https://www.opensource-socialnetwork.org/
 * @package   UnloggedinMenuHomeButton
 * @author    Michael Zülsdorff <ossn@z-mans.net>
 * @copyright (C) Michael Zülsdorff
 * @license   GNU General Public License https://www.gnu.de/documents/gpl-2.0.en.html
 */

function com_UnloggedinMenuHomeButton_init()
{
	// prevent disabling the UnLoggedinMenu component
	// as long as the UnloggedinMenuHomeButton component is enabled
	ossn_add_hook('required', 'components', 'com_UnloggedinMenuHomeButton_asure_requirements');

	// if not logged in
	// add a new 'Home' entry to the UnLoggedinMenu
	if (!ossn_isLoggedin()) {
		ossn_extend_view('ossn/site/head', 'js/UnloggedinMenuHomeButton');
	}
}

function com_UnloggedinMenuHomeButton_asure_requirements($hook, $type, $return, $params)
{
	$return[] = 'UnLoggedinMenu';
	return $return;
}

ossn_register_callback('ossn', 'init', 'com_UnloggedinMenuHomeButton_init');
