<?php
/**
 * Open Source Social Network
 *
 * @package   (softlab24.com).ossn
 * @author    OSSN Core Team <info@softlab24.com>
 * @copyright (C) SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
function dashboard_menu_priority() {
		if(com_is_active('Dashboard')){
			ossn_unregister_menu_item('dashboard', 'links', 'newsfeed');
			ossn_register_sections_menu('newsfeed', array(
					'name' => 'dashboard',
					'text' => ossn_print('dashboard'),
					'url' => ossn_site_url('dashboard/categories'),
					'section' => 'links',
					'icon' => true,
					'priority' => 1,
			));					
		}
}
ossn_register_callback('ossn', 'init', 'dashboard_menu_priority');