<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
define('OA_THEME', ossn_route()->themes . 'Awesome/');
global $Ossn;
$settings           = oa_theme_object();
$Ossn->AwesomeTheme = false;
if(isset($settings[0])) {
		$settings           = $settings[0];
		$Ossn->AwesomeTheme = $settings;
}
function oa_theme_settings() {
		$settings = 0;
		$vals     = array(
				'primary',
				'warning',
				'info',
				'success',
				'danger',
				'black',
		);
		global $Ossn;
		if(isset($Ossn->AwesomeTheme->title)) {
				$settings = $Ossn->AwesomeTheme->title;
		}

		//default
		$btnoutline       = 'btn-outline-primary';
		$linkcolor        = '#0275d8';
		$linkcolor_hover  = '#0275d8';
		$topbarparent_nav = 'bg-primary';

		switch ($settings) {
		case 'primary':
				$btnoutline       = 'btn-outline-primary';
				$linkcolor        = '#0275d8';
				$linkcolor_hover  = '#0275d8';
				$topbarparent_nav = 'bg-primary';
				break;
		case 'warning':
				$btnoutline       = 'btn-outline-warning';
				$linkcolor        = '#f0ad4e';
				$linkcolor_hover  = '#f0ad4e';
				$topbarparent_nav = 'bg-warning';
				break;
		case 'danger':
				$btnoutline       = 'btn-outline-danger';
				$linkcolor        = '#d9534f';
				$linkcolor_hover  = '#d9534f';
				$topbarparent_nav = 'bg-danger';
				break;
		case 'success':
				$btnoutline       = 'btn-outline-success';
				$linkcolor        = '#5cb85c';
				$linkcolor_hover  = '#5cb85c';
				$topbarparent_nav = 'bg-success';
				break;
		case 'info':
				$btnoutline       = 'btn-outline-info';
				$linkcolor        = '#5bc0de';
				$linkcolor_hover  = '#5bc0de';
				$topbarparent_nav = 'bg-info';
				break;
		case 'black':
				$btnoutline       = 'btn-outline-black';
				$linkcolor        = '#333';
				$linkcolor_hover  = '#333';
				$topbarparent_nav = 'bg-black';
				break;
		}
		return array(
				'btnoutline'       => $btnoutline,
				'linkcolor'        => $linkcolor,
				'linkcolor_hover'  => $linkcolor_hover,
				'topbarparent_nav' => $topbarparent_nav,
		);
}
function oa_theme_get_settings($settings) {
		$data = oa_theme_settings();
		return $data[$settings];
}
function oa_theme_object() {
		$object = new OssnObject();
		return $object->searchObject(array(
				'type'    => 'site',
				'subtype' => 'awesome:theme',
				'offset'  => 1,
		));
}
function ossn_goblue_theme_init() {
		ossn_register_menu_item('admin/sidemenu', array(
				'name'   => 'awesometheme',
				'text'   => ossn_print('admin:theme:awesome'),
				'href'   => ossn_site_url('administrator/settings/awesometheme'),
				'parent' => 'admin:sidemenu:themes',
		));
		ossn_register_site_settings_page('awesometheme', 'settings/admin/awesometheme');

		ossn_new_external_css('summernote.css', '//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css', false);
		ossn_new_external_js('summernote.js', '//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js', false);

		if(ossn_isAdminLoggedin()) {
				ossn_register_action('awesome/settings', OA_THEME . 'actions/settings.php');
		}
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
		ossn_extend_view('js/opensource.socialnetwork', 'js/goblue');
		ossn_extend_view('css/ossn.default', 'awesometheme/profile');

		ossn_extend_view('ossn/site/head', 'awesometheme/sitehead');
		ossn_add_hook('halt', 'view:components/OssnSitePages/pages/page', 'ossn_site_pages_page');

		ossn_register_menu_item('admin/sidemenu', array(
				'name'   => 'awesometheme_logoimg',
				'text'   => ossn_print('admin:theme:logoimg'),
				'href'   => ossn_site_url('administrator/settings/logo_images'),
				'parent' => 'admin:sidemenu:themes',
		));
		ossn_register_site_settings_page('logo_images', 'settings/admin/images');

		if(ossn_isAdminLoggedin()) {
				ossn_register_action('awesome/settings/images', OA_THEME . 'actions/images.php');
		}
}
function ossn_site_pages_page($hook, $type, $return, $params) {
		return ossn_plugin_view('sitepages/page', $params);
}
function ossn_styler_override() {
		if(com_is_active('Styler') && function_exists('styler_colors')) {
				$colors = styler_colors();
				foreach ($colors as $color) {
						ossn_unload_css("styler.{$color}");
				}
		}
}
function ossn_goblue_head() {
		$head   = array();
		$head[] = ossn_html_css(array(
				'href' => '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css',
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

ossn_register_callback('ossn', 'init', 'ossn_goblue_theme_init');
ossn_register_callback('ossn', 'init', 'ossn_styler_override', 300);