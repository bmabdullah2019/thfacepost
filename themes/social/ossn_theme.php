<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
define('__THEMEDIR__', ossn_route()->themes . 'social/');

ossn_register_callback('ossn', 'init', 'ossn_goblue_theme_init');

function ossn_goblue_theme_init() {
		//add bootstrap
		ossn_new_css('bootstrap.min', 'css/bootstrap/bootstrap.min.css');
		//ossn_new_js('bootstrap.min', 'js/bootstrap/bootstrap.min.js');

		ossn_new_css('ossn.default', 'css/core/default');
		ossn_new_css('ossn.admin.default', 'css/core/administrator');

		//load bootstrap
		ossn_load_css('bootstrap.min', 'admin');
		ossn_load_css('bootstrap.min');

		ossn_load_css('ossn.default');
		ossn_load_css('ossn.admin.default', 'admin');
		ossn_extend_view('css/ossn.default', 'social/profile');
		
		ossn_extend_view('ossn/admin/head', 'ossn_goblue_admin_head');
		ossn_extend_view('ossn/site/head', 'ossn_goblue_head');
		ossn_extend_view('js/opensource.socialnetwork', 'js/goblue');
}
function ossn_goblue_head() {
		$head = array();

		$head[] = ossn_html_css(array(
				'href' => '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css',
		));
		$head[] = ossn_html_css(array(
				'href' => 'https://fonts.googleapis.com/css?family=PT+Sans:400italic,700,400',
		));
		$head[] = ossn_html_js(array(
				'src' => ossn_theme_url() . 'vendors/bootstrap/js/bootstrap.min.js?v5.3',
		));
		$head[] = ossn_html_css(array(
				'href' => '//code.jquery.com/ui/1.14.1/themes/smoothness/jquery-ui.css',
		));
		return implode('', $head);
}
function ossn_goblue_admin_head() {
		$head   = array();
		$head[] = ossn_html_css(array(
				'href' => '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css',
		));
		$head[] = ossn_html_css(array(
				'href' => '//fonts.googleapis.com/css?family=Roboto+Slab:300,700,400',
		));
		$head[] = ossn_html_js(array(
				'src' => ossn_theme_url() . 'vendors/bootstrap/js/bootstrap.min.js?v5.3',
		));
		$head[] = ossn_html_css(array(
				'href' => '//code.jquery.com/ui/1.14.1/themes/smoothness/jquery-ui.css',
		));
		return implode('', $head);
}