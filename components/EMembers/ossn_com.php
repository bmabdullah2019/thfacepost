<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (c) iNFORMATIKON TECHNOLOGIES
 * @license   https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */

/**
 * Extended Members INIT
 *
 * @return void
 */
function extended_members() {
		ossn_register_page('site_members', 'ossn_site_members');
		ossn_extend_view('css/ossn.default', 'css/emembers');
		
		if(!OssnSession::isSession('com_emembers_genders')) {
				$addMore = new OssnUser;
				$Genders = $addMore->getGenders();
				
				OssnSession::assign('com_emembers_genders', $Genders);
		}
		if($Genders = OssnSession::getSession('com_emembers_genders')) {
				foreach($Genders as $gender) {
						ossn_register_sections_menu('newsfeed', array(
								'name' => "emembers_{$gender}",
								'text' => ossn_print($gender),
								'url' => ossn_site_url("site_members/{$gender}"),
								'section' => 'emembers',
								'icon' => true
						));
				}
		}
}
/**
 * Extended Members Pages
 *
 * @return mixed contents
 */

function ossn_site_members($pages) {
		$page = $pages[0];
		if(!OssnSession::isSession('com_emembers_genders')) {
				$addMore = new OssnUser;
				$Genders = $addMore->getGenders();
				
				OssnSession::assign('com_emembers_genders', $Genders);
		}
		$Genders = OssnSession::getSession('com_emembers_genders');
		if(!$Genders || $Genders && !in_array($page, $Genders)) {
				ossn_error_page();
		}
		
		$user  = new OssnUser;
		$vars  = array(
				'entities_pairs' => array(
						array(
								'name' => 'gender',
								'value' => $page
						)
				)
		);
		$list  = $user->searchUsers($vars);
		$count = $user->searchUsers(array_merge(array(
				'count' => true
		), $vars));
		
		
		$title    = ossn_print($page);
		$contents = array(
				'content' => ossn_plugin_view('emembers/emembers', array(
						'entities' => $list,
						'count' => $count
				))
		);
		$content  = ossn_set_page_layout('contents', $contents);
		echo ossn_view_page($title, $content);
}
ossn_register_callback('ossn', 'init', 'extended_members');