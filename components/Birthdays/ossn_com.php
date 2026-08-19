<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
define('BIRTHDAYS', ossn_route()->com . 'Birthdays/');
require_once(BIRTHDAYS . 'lib/birthdays.php');
/**
 * Birthday component init
 *
 * @param null
 * @return void
 */
function birthdays_init() {
		ossn_add_hook('newsfeed', "sidebar:right", 'ossn_birthdays_newsfeed', 1);
		ossn_extend_view('css/ossn.default', 'css/birthdays');
}
/**
 * Birthday component display on newsfeed sidebar
 *
 * @param string $hook A name of hook
 * @param string $type A type of hook
 * @param array  $return A mixed data arrays
 *
 * @return array
 */
function ossn_birthdays_newsfeed($hook, $type, $return) {
		$return[] = ossn_plugin_view('widget/view', array(
				'title' => '<i class="fas fa-birthday-cake"></i><span> '.ossn_print('birthdays:upcoming').'</span>',
				'contents' => ossn_plugin_view('birthdays/newsfeed'),
				'class' => 'birthdays'
		));
		return $return;
}
ossn_register_callback('ossn', 'init', 'birthdays_init');
