<?php
/**
 *    OpenSource-SocialNetwork
 *
 * @package   (webbehinds.com).ossn
 * @author    OSSN Core Team <info@opensource-socialnetwork.com>
 * @copyright 2014 Webbehinds
 * @license   General Public Licence http://opensource-socialnetwork.com/licence
 * @link      http://www.opensource-socialnetwork.com/licence
 */




function ossn_games_init() {
	ossn_register_page('games', 'ossn_games_pages');
	  if (ossn_isLoggedin()) {       
		
    	$icon = ossn_site_url('components/OssnPhotos/images/photos-ossn.png');
    	ossn_register_sections_menu('newsfeed', array(
        	'text' => ossn_print('com:ossn:games'),
        	'url' => ossn_site_url('games'),
        	 'icon' => $icon,
		 'section' => 'links'
	    	));		
    }
}


function ossn_games_pages($pages) {

 if (!ossn_isLoggedin()) {
            ossn_error_page();
   }
$title = ossn_print('com:ossn:games');
   $contents['content'] = ossn_plugin_view('pages/games');
   $content = ossn_set_page_layout('contents', $contents);
   echo ossn_view_page($title, $content);
}

ossn_register_callback('ossn', 'init', 'ossn_games_init');
