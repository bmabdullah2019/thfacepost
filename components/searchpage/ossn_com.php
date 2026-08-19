<?php
/**
 *    OpenSource-SocialNetwork
 *
 * @package   SearchPage
 * @author    AT3META <at3meta@3ncircle.com>
 * @copyright 2022 3NCIRCLE Inc.
 * @license   General Public License v3
 * @link      https://www.gnu.org/licenses/gpl-3.0.en.html
 */
define('__SEARCHPAGE__', ossn_route()->com . 'searchpage/');

function searchpage_init() {
	ossn_register_page('searchpage','searchpage_pagehandler');
	  if (ossn_isLoggedin()) {       
		ossn_extend_view('css/ossn.default', 'css/searchpage');
		
				ossn_register_sections_menu('newsfeed', array(

						'name' => 'searchpage',

						'text' => ossn_print('com:ossn:searchpage'),

						'url' => ossn_site_url('searchpage'),

						'parent' => 'links',

				));
					
    }
}
function searchpage_pagehandler(){
   if (!ossn_isLoggedin()) {
            ossn_error_page();
   }
   $title = ossn_print('com:ossn:searchpage');
   $contents['content'] = ossn_plugin_view('pages/searchpage');
   $content = ossn_set_page_layout('newsfeed', $contents);
   echo ossn_view_page($title, $content);	
}
ossn_register_callback('ossn', 'init', 'searchpage_init');