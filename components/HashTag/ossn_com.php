<?php
/**
 * Open Source Social Network
 *
 * @package   OSSN
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (c) OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0 https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com
 */
define('__HASHTAG__', ossn_route()->com . 'HashTag/');
define('HASHTAG_TRENDING_LIMIT', 6);

ossn_register_class(array(
		'Hashtag\Trending' => __HASHTAG__ . 'classes/Trending.php',
));
function hash_tag_trending() {
		if(ossn_isLoggedin()) {
				ossn_add_hook('newsfeed', 'sidebar:right', 'hashtags_sidebar_trending', 2);
				ossn_register_callback('wall', 'post:created', function ($callback, $type, $params) {
						$guid = $params['guid'];
						$wall = new OssnWall();
						$post = $wall->GetPost($guid);
						
						//restrict to public posts only (non group)
						if($post && (($post->type == 'user' && $post->access == OSSN_PUBLIC) || $post->type == 'businesspage')) {
								$wall_item = ossn_wallpost_to_item($post);
								if($wall_item && isset($wall_item['text'])) {
										$text = $wall_item['text'];
										preg_match_all('/(?<=\s|^)#([^\d_\s\W][\p{L}\p{Nd}\p{M}\d]{1,})/u', $text, $matches);
										if(isset($matches[1]) && !empty($matches[1])) {
												$unique = array_unique($matches[1]);
												foreach($unique as $hashtag) {
														if(empty($hashtag)) {
																continue;
														}
														$Trending = new Hashtag\Trending();
														$Trending->save(array(
																'hashtag'      => trim($hashtag),
																'subject_guid' => $post->guid,
																'subject_type' => 'object:wall',
														));
												}
										}
								}
						}
				});

				ossn_register_callback('wall', 'post:edited', function ($callback, $type, $params) {
						$guid = $params['object']->guid;

						$wall = new OssnWall();
						$post = $wall->GetPost($guid);

						if($post) {
								//delete existing tags for the post
								$trending = new Hashtag\Trending();
								$trending->deleteBySubjectGuid($guid);

								//check again and add the tags again for the edited post
								$wall_item = ossn_wallpost_to_item($post);
								if($wall_item && isset($wall_item['text'])) {
										$text = $wall_item['text'];
										preg_match_all('/(?<=\s|^)#([^\d_\s\W][\p{L}\p{Nd}\p{M}\d]{1,})/u', $text, $matches);
										if(isset($matches[1]) && !empty($matches[1])) {
												$unique = array_unique($matches[1]);
												foreach($unique as $hashtag) {
														if(empty($hashtag)) {
																continue;
														}
														$Trending = new Hashtag\Trending();
														$Trending->save(array(
																'hashtag'      => trim($hashtag),
																'subject_guid' => $post->guid,
																'subject_type' => 'object:wall',
														));
												}
										}
								}
						}
				});
				ossn_register_callback('post', 'delete', function ($c, $t, $post_guid) {
						//delete existing tags for the post
						$trending = new Hashtag\Trending();
						$trending->deleteBySubjectGuid($post_guid);
				});
		}
}
function hash_tag_init() {
		ossn_add_hook('wall', 'templates:item', 'ossn_hashtag', 150);
		ossn_add_hook('comment:view', 'template:params', 'ossn_chashtag', 150);
		ossn_register_page('hashtag', 'hashtag_page_handler');
		ossn_extend_view('css/ossn.default', 'hashtag/css');
}
/**
 * Trending Hashtag display on newsfeed sidebar
 *
 * @param string $hook A name of hook
 * @param string $type A type of hook
 * @param array  $return A mixed data arrays
 *
 * @return array
 */
function hashtags_sidebar_trending($hook, $type, $return) {
		$trending = new Hashtag\Trending();
		$search   = $trending->topTrends();
		if($search) {
				$return[] = ossn_plugin_view('widget/view', array(
						'title'    => '<i class="fas fa-bolt"></i> ' . ossn_print('hashtag:trending') . '</span>',
						'contents' => ossn_plugin_view('hashtag/newsfeed', array(
								'trending' => $search,
						)),
						'class'    => 'hashtag-trending',
				));
		}
		return $return;
}
/**
 * Hashtag page handler
 *
 * @param string $text A hashtag
 *
 * @return string
 */
function hashtag_page_handler($pages) {
		if(!ossn_isLoggedin()) {
				redirect('login');
		}
		$pages[0] = ossn_input_escape($pages[0]);
		$title    = '#' . $pages[0];
		if(com_is_active('OssnWall') && !empty($pages[0])) {
				$contents['content'] = ossn_plugin_view('hashtag/view', array(
						'hashtag' => trim('#' . $pages[0]),
				));
		}
		$content = ossn_set_page_layout('newsfeed', $contents);
		echo ossn_view_page($title, $content);
}
/**
 * Make the hashtag into to clickable events
 *
 * @param string $text A hashtag
 *
 * @return string
 */
function hashtag($text) {
		$url = ossn_site_url('hashtag/');
		//fixes for tamil and for chinese we changed {2,} to {1,} as minim 2 characters (0-1) means 2
		//changed to minimum 2 charactesr required instead of three
		return preg_replace('/(?<=\s|^)#([^\d_\s\W][\p{L}\p{Nd}\p{M}\d]{1,})/u', '<a class="ossn-hashtag-item" href="' . $url . '$1">#$1</a>', $text);
}
/**
 * Init hashtags in wall posts
 *
 * @note Please don't call this function directly in your code.
 *
 * @param string $hook Name of hook
 * @param string $type Hook type
 * @param array|object $return Array or Object
 * @params array $params Array contatins params
 *
 * @return array
 * @access private
 */
function ossn_hashtag($hook, $type, $return, $params) {
		$params['text'] = hashtag($return['text']);
		return $params;
}
/**
 * Init hashtags in wall posts
 *
 * @note Please don't call this function directly in your code.
 *
 * @param string $hook Name of hook
 * @param string $type Hook type
 * @param array|object $return Array or Object
 * @params array $params Array contatins params
 *
 * @return array
 * @access private
 */
function ossn_chashtag($hook, $type, $return, $params) {
		if(isset($return['comment']['comments:entity'])) {
				$return['comment']['comments:entity'] = hashtag($return['comment']['comments:entity']);
		}
		if(isset($return['comment']['comments:post'])) {
				$return['comment']['comments:post'] = hashtag($return['comment']['comments:post']);
		}
		if(isset($return['comment']['comments:object'])) {
				$return['comment']['comments:object'] = hashtag($return['comment']['comments:object']);
		}
		return $return;
}
ossn_register_callback('ossn', 'init', 'hash_tag_init');
ossn_register_callback('ossn', 'init', 'hash_tag_trending');