<?php
/**
 *    OpenSource-SocialNetwork
 *
 * @package   Google
 * @author    AT3META <at3meta@3ncircle.com>
 * @copyright 2021 3NCIRCLE Inc.
 * @license   General Public Licence V3
 * @link      https://www.gnu.org/licenses/gpl-3.0.en.html
 */
define('__GOOGLE__', ossn_route()->com . 'Google/');

function google_init() {
	ossn_register_page('google', 'google_pages');
	  if (ossn_isLoggedin()) {       
		
		ossn_extend_view('css/ossn.default', 'css/google');
		
		
				ossn_register_sections_menu('newsfeed', array(

						'name' => 'search',

						'text' => ossn_print('com:ossn:search'),

						'url' => ossn_site_url('google/search'),

						'section' => 'google',

				));
				
				ossn_register_sections_menu('newsfeed', array(

						'name' => 'maps',

						'text' => ossn_print('com:ossn:maps'),

						'url' => ossn_site_url('google/maps'),

						'section' => 'google',

				));
				
				ossn_register_sections_menu('newsfeed', array(

						'name' => 'translate',

						'text' => ossn_print('com:ossn:translate'),

						'url' => ossn_site_url('google/translate'),

						'section' => 'google',

				));
					
    }
}
function google_pages($pages) {

if(!ossn_isLoggedin()) {

				ossn_error_page();
		}


		switch($pages[0]) {

				case 'search':

						$guid                = $pages[1];

						$title               = ossn_print('com:ossn:search');
						
						$contents['content'] = ossn_plugin_view('pages/search', array(

								'search' => $guid

						));

						$content             = ossn_set_page_layout('newsfeed', $contents);

						echo ossn_view_page($title, $content);

						break;
						
				case 'maps':

						$guid                = $pages[2];

						$title               = ossn_print('com:ossn:maps');
						
						$contents['content'] = ossn_plugin_view('pages/maps', array(

								'maps' => $guid

						));

						$content             = ossn_set_page_layout('newsfeed', $contents);

						echo ossn_view_page($title, $content);

						break;
						
				case 'translate':

						$guid                = $pages[3];

						$title               = ossn_print('com:ossn:translate');

						$contents['content'] = ossn_plugin_view('pages/translate', array(

								'translate' => $guid

						));

						$content             = ossn_set_page_layout('newsfeed', $contents);

						echo ossn_view_page($title, $content);

	}
}
ossn_register_callback('ossn', 'init', 'google_init');
