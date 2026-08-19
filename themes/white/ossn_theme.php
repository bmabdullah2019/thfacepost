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
define('__THEMEDIR__', ossn_route()->themes . 'white/');

ossn_register_callback('ossn', 'init', 'ossn_goblue_theme_init');
ossn_register_callback('ossn', 'init', 'ossn_goblue_theme_css_rewrite', 500);

function ossn_goblue_theme_css_rewrite() {
		ossn_extend_view('css/ossn.default', 'white/components_override_css');
		ossn_extend_view('js/opensource.socialnetwork', 'white/components_override_js');
		ossn_add_hook('halt', 'view:components/OssnSitePages/pages/page', 'white_site_pages_override');
		if(ossn_isLoggedin()) {
				ossn_add_hook('newsfeed', 'sidebar:right', 'com_latest_members_widget_white');
		}
}
function com_latest_members_widget_white($hook, $type, $return) {
		$SiteSettings                   = new OssnSite();
		$com_white_theme_members_widget = $SiteSettings->getSettings('com_white_theme_members_widget');
		if(isset($com_white_theme_members_widget) && $com_white_theme_members_widget != 'disabled') {
				$widget_content = ossn_plugin_view('white/members_widget');
				$widget         = ossn_plugin_view('widget/view', array(
						'title'    => '<i class="fas fa-users"></i><span> ' . ossn_print('com:latestmembers:latest:members') . '</span>',
						'contents' => $widget_content,
				));
				$return[] = $widget;
		}
		return $return;
}

function ossn_goblue_theme_init() {
		ossn_unset_callback('ossn', 'init', 'com_RightColumnFocusizer_init');

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

		ossn_extend_view('ossn/admin/head', 'ossn_goblue_admin_head');
		ossn_extend_view('ossn/site/head', 'ossn_goblue_head');
		ossn_extend_view('js/opensource.socialnetwork', 'js/white');

		ossn_register_site_settings_page('white', 'settings/admin/white');

		if(ossn_isAdminLoggedin()) {
				ossn_register_menu_item('admin/sidemenu', array(
						'name'   => 'whitetheme',
						'text'   => ossn_print('admin:theme:white'),
						'href'   => ossn_site_url('administrator/settings/white'),
						'parent' => 'admin:sidemenu:themes',
				));
				ossn_register_action('goblue/settings', __THEMEDIR__ . 'actions/settings.php');
				ossn_register_action('theme/multipurpose/settings/logos_bgs_reset', __THEMEDIR__ . 'actions/logos_bgs_reset.php');
		}
		if(ossn_isLoggedin()) {
				ossn_register_action('white/darkmode/enable', __THEMEDIR__ . 'actions/darkmode/enable.php');
				ossn_register_action('white/darkmode/disable', __THEMEDIR__ . 'actions/darkmode/disable.php');

				$user = ossn_loggedin_user();
				if(!isset($user->theme_darkmode) || $user->theme_darkmode == false) {
						ossn_register_menu_item('topbar_dropdown', array(
								'name'      => 'darkmode',
								'id'        => 'theme-darkmode-btn',
								'text'      => ossn_print('darkmode:enable:darkmode'),
								'href'      => 'javascript:void(0);',
								'data-type' => 1,
								'priority'  => 1,
						));
				} else {
						ossn_register_menu_item('topbar_dropdown', array(
								'name'      => 'darkmode',
								'text'      => ossn_print('darkmode:enable:darkmode'),
								'id'        => 'theme-darkmode-btn',
								'href'      => 'javascript:void(0);',
								'data-type' => 0,
								'priority'  => 1,
						));
				}
		}
		if(!ossn_isLoggedin()) {
				ossn_add_hook('user', 'signup:fields', 'multipurpose_theme_mode_signup_field');
				ossn_add_hook('user', 'default:fields', 'multipurpose_theme_mode_signup_field');
		}
}
function multipurpose_theme_mode_signup_field($hook, $type, $fields) {
		$SiteSettings                      = new OssnSite();
		$com_white_theme_mode_field_signup = $SiteSettings->getSettings('whitetheme_mode_field_signup');
		if($com_white_theme_mode_field_signup && $com_white_theme_mode_field_signup == 'yes') {
				$component   = new OssnComponents();
				$label       = false;
				$placeholder = ossn_print('theme:white:thememode');
				$extrafield  = array(
						'name'                  => 'theme_darkmode',
						'label'                 => $label,
						'placeholder'           => $placeholder,
						'display_on_about_page' => false,
						'options'               => array(
								0 => ossn_print('theme:white:litemode'),
								1 => ossn_print('theme:white:darkmode'),
						),
				);
				$fields['required']['dropdown'][] = $extrafield;
		}
		return $fields;
}
function white_site_pages_override($hook, $type, $return, $params) {
		return ossn_plugin_view('white/site_pages', $params);
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
//right column
function white_RightColumnFocusizer_init() {
		ossn_extend_view('js/opensource.socialnetwork', 'white/right_column/js/RightColumnFocusizer');
		ossn_extend_view('css/ossn.default', 'white/right_column/RightColumnFocusizer');
		if(com_is_active('OssnAutoPagination')) {
				ossn_add_hook('newsfeed', 'sidebar:right', 'white_RightColumnFocusizer_sidebar_footer', 1000);
				ossn_add_hook('profile', 'modules', 'white_RightColumnFocusizer_sidebar_footer', 1000);
				ossn_add_hook('group', 'widgets', 'white_RightColumnFocusizer_sidebar_footer', 1000);
				ossn_add_hook('theme', 'sidebar:right', 'white_RightColumnFocusizer_sidebar_footer', 1000);
		}
}

function white_RightColumnFocusizer_sidebar_footer($hook, $tye, $return) {
		$return[] =
		"<footer class='sidebar-footer'><div class='sidebar-footer-content' style='display: none'><div class='ossn-footer-menu'>" .
		str_replace('menu-footer-powered', 'sidebar-footer-powered', ossn_plugin_view('white/right_column/menus/sidebar_footer')) .
				'</div></div></footer>';
		return $return;
}
function ossn_styler_override() {
		ossn_unregister_menu_item('styler', 'configure', 'topbar_admin');
		if(com_is_active('Styler') && function_exists('styler_colors')) {
				$colors = styler_colors();
				foreach ($colors as $color) {
						ossn_unload_css("styler.{$color}");
				}
		}
}
function multipurpose_theme_set_custom_logos_bgs_setting($key, $val) {
		$settings = multipurpose_theme_get_custom_logos_bgs_setting();
		if(!empty($key) && !empty($val)) {
				if(!$settings) {
						$settings = array();
				}
				$settings[$key] = $val;
				$json           = json_encode($settings);
				$config         = ossn_route()->themes . 'white/logos_backgrounds/config.json';
				return file_put_contents($config, $json);
		}
		return false;
}
function multipurpose_theme_get_custom_logos_bgs_setting() {
		$config = ossn_route()->themes . 'white/logos_backgrounds/config.json';
		if(file_exists($config)) {
				$json = file_get_contents($config);
				if(!empty($json)) {
						$json = json_decode($json, true);
						if(json_last_error() === JSON_ERROR_NONE) {
								return $json;
						}
				}
		}
		return false;
}
ossn_register_callback('ossn', 'init', 'white_RightColumnFocusizer_init');
ossn_register_callback('ossn', 'init', 'ossn_styler_override', 300);