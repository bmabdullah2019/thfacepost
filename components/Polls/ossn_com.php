<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE  https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */

define('__POLLS__', ossn_route()->com . 'Polls/');
ossn_register_class(array(
		'Softlab24\Ossn\Component\Polls' => __POLLS__ . 'classes/Polls.php',
));
/**
 * Polls Init
 *
 * @return void
 */
function polls_init() {
		ossn_register_page('polls', 'polls_page_handler');
		ossn_extend_view('css/ossn.default', 'polls/css');
		ossn_extend_view('js/opensource.socialnetwork', 'polls/js');

		if(ossn_isLoggedin()) {
				ossn_register_action('poll/add', __POLLS__ . 'actions/add.php');
				ossn_register_action('poll/delete', __POLLS__ . 'actions/delete.php');

				ossn_register_action('poll/end', __POLLS__ . 'actions/end.php');
				ossn_register_action('poll/vote', __POLLS__ . 'actions/vote.php');

				ossn_register_callback('page', 'load:profile', 'ossn_poll_profile_wall_menu');

				$user = ossn_loggedin_user();
				ossn_register_sections_menu('newsfeed', array(
						'name'    => 'polls_my',
						'text'    => ossn_print('polls:my'),
						'url'     => ossn_site_url("polls/list/user/{$user->guid}"),
						'section' => 'polls',
						'icon'    => true,
				));
				ossn_add_hook('notification:redirect:uri', 'like:annotation:comments:entity', 'ossn_notification_like_comment_poll_redirect_uri');
				ossn_add_hook('notification:redirect:uri', 'like:entity:poll_entity', 'ossn_notification_like_comment_poll_redirect_uri');
				ossn_add_hook('notification:redirect:uri', 'comments:entity:poll_entity', 'ossn_notification_like_comment_poll_redirect_uri');
		}

		ossn_add_hook('wall:template', 'poll:item', 'ossn_wall_poll');

		ossn_add_hook('notification:view', 'like:entity:poll_entity', 'ossn_notification_poll');
		ossn_add_hook('notification:view', 'comments:entity:poll_entity', 'ossn_notification_poll');
		ossn_add_hook('notification:view', 'like:annotation', 'ossn_notification_poll');
		ossn_add_hook('notification:view', 'like:annotation:comments:entity', 'ossn_notification_poll');

		ossn_add_hook('notification:participants', 'like:entity:poll_entity', 'ossn_polls_participants_notification');

		ossn_register_callback('user', 'delete', 'ossn_user_polls_delete');
		ossn_register_callback('page', 'load:group', 'polls_group_load_event');

		ossn_register_sections_menu('newsfeed', array(
				'name'    => 'polls_all',
				'text'    => ossn_print('polls:all'),
				'url'     => ossn_site_url('polls/list/all'),
				'section' => 'polls',
				'icon'    => true,
		));
		// wall conatiner

		$menupost = array(
				'name' => 'polls',
				'text' => '<i class="fa fa-th-list"></i><span>' . ossn_print('polls:poll') . '</span>',
				'href' => ossn_site_url(),
		);
		ossn_register_menu_item('wall/container/home', $menupost);

		$menupost['name'] = 'pollsgroup';
		ossn_register_menu_item('wall/container/group', $menupost);

		$menupost['name'] = 'pollsbusinesspage';
		ossn_register_menu_item('wall/container/businesspage', $menupost);

		if(com_is_active('OssnGroups')) {
				ossn_group_subpage('polls');
				ossn_add_hook('group', 'subpage', 'polls_group_page');
				ossn_register_callback('comment', 'entityextra:menu', 'polls_allcomments_wall');
		}
		ossn_register_callback('group', 'delete', function ($c, $t, $params) {
				if(!empty($params['entity']->guid)) {
						ossn_polls_generic_delete_by_container('group', $params['entity']->guid);
				}
		});
		ossn_register_callback('businesspage', 'delete', function ($c, $t, $params) {
				if(!empty($params['page']->guid)) {
						ossn_polls_generic_delete_by_container('businesspage', $params['page']->guid);
				}
		});
		ossn_register_callback('page', 'load:businesspage:profile', function ($callback, $type, $params) {
				ossn_register_menu_item('business_page_profile', array(
						'name'     => 'polls',
						'text'     => ossn_print('polls'),
						'href'     => ossn_site_url("polls/list/business/{$params['page']->guid}"),
				));
		});		
}
/**
 * Generic all polls delete based on container
 *
 * @return boolean
 */
function ossn_polls_generic_delete_by_container($type, $guid) {
		if(empty($type) || empty($guid)) {
				return false;
		}
		$poll = new \Softlab24\Ossn\Component\Polls();
		$all  = $poll->getAll(array(
				'page_limit'     => false,
				'entities_pairs' => array(
						array(
								'name'  => 'container_type',
								'value' => $type,
						),
						array(
								'name'  => 'container_guid',
								'value' => $guid,
						),
				),
		));
		if($all) {
				foreach ($all as $item) {
						$item->removeData();
						$item->deleteObject();
				}
		}
		return true;
}
/**
 * Redirect URI for Poll comment like
 *
 * @reutrn boolean|string
 */
function ossn_notification_like_comment_poll_redirect_uri($hook, $type, $return, $params) {
		$notification = $params['notification'];
		$entity       = ossn_get_entity($notification->subject_guid);
		if($entity && $entity->subtype == 'poll_entity') {
				$poll = ossn_poll_get($entity->owner_guid);
				$uri  = $poll->getURL(false);
				if(preg_match('/comments:/', $notification->type)) {
						$uri = "{$uri}#comments-item-{$notification->item_guid}";
				}
				return $uri;
		}
}

/**
 * Don't create participants notification
 *
 * @return false
 * @access private
 */
function ossn_polls_participants_notification() {
		return false;
}
/**
 * Show view all comments menu on poll wall post
 *
 * @param string $callback Name of callback
 * @param string $type A callback type
 * @param array  $params A option values
 *
 * @return boolean|void
 */
function polls_allcomments_wall($callback, $type, $params) {
		if(!class_exists('OssnComments')) {
				return false;
		}
		$context  = ossn_get_context();
		$contexts = explode('/', $context);
		$ncontext = '';
		if(isset($contexts[0]) && isset($contexts[1])) {
				$ncontext = '' . $contexts[0] . '/' . $contexts[1];
		}
		if($params['entity']->subtype == 'poll_entity') {
				ossn_unregister_menu('commentall', 'entityextra');

				$poll = ossn_poll_get($params['entity']->owner_guid);
				if($poll && $ncontext != 'polls/view') {
						$comment = new OssnComments();
						if($comment->countComments($params['entity']->guid, 'entity') > 5) {
								ossn_register_menu_item('entityextra', array(
										'name' => 'commentall',
										'href' => $poll->getURL(),
										'text' => ossn_print('comment:view:all'),
								));
						}
				}
		}
}
/**
 * Group poll requests page
 *
 * Page:
 *      group/<guid>/polls
 *
 * @return mixdata;
 * @access private
 */
function polls_group_page($hook, $type, $return, $params) {
		$page  = $params['subpage'];
		$group = ossn_get_group_by_guid(ossn_get_page_owner_guid());
		if($page == 'polls') {
				$allow_see = false;
				if($group->membership == OSSN_PUBLIC) {
						$allow_see = true;
				}
				if($group->membership == OSSN_PRIVATE) {
						if(ossn_isLoggedin() && $group->isMember(null, ossn_loggedin_user()->guid)) {
								$allow_see = true;
						}
				}
				if($allow_see) {
						$mod_content = ossn_plugin_view('polls/pages/groups', array(
								'group' => $group,
						));
				} else {
						$mod_content = ossn_print('polls:join:group');
				}
				$mod = array(
						'title'   => ossn_print('polls'),
						'content' => $mod_content,
				);
				echo ossn_set_page_layout('module', $mod);
		}
}
/**
 * Call event on group load
 *
 * @return void;
 * @access private
 */
function polls_group_load_event($event, $type, $params) {
		$owner = ossn_get_page_owner_guid();
		$url   = ossn_site_url();
		ossn_register_menu_link('polls', 'polls', ossn_group_url($owner) . 'polls', 'groupheader');
}
function ossn_wall_poll($hook, $type, $return, $params) {
		return ossn_plugin_view('polls/wall', $params);
}
/**
 * Add a notfication for comments and likes of Poll post
 *
 * @param string $hook A hook name
 * @param string $type A hook type
 * @param string $return A mixed data
 * @param object $params A option values
 *
 * @return array
 */
function ossn_notification_poll($hook, $type, $return, $params) {
		$notification = $params;
		$baseurl      = ossn_site_url();

		$user   = ossn_user_by_guid($notification->poster_guid);
		$entity = ossn_get_entity($notification->subject_guid);
		if($entity->subtype == 'poll_entity') {
				$subject = ossn_poll_get($entity->owner_guid);
				$url     = $subject->getURL();

				if($notification->type == 'like:annotation:comments:entity') {
						$notification->type = 'like:annotation';
				}
				if(preg_match('/like/i', $notification->type)) {
						$type = 'like';
				}
				if(preg_match('/comments/i', $notification->type)) {
						$type = 'comment';
				}
				$args = array(
						'iconURL'   => $user->iconURL()->small,
						'guid'      => $notification->guid,
						'type'      => $notification->type,
						'viewed'    => $notification->viewed,
						'icon_type' => $type,
						'instance'  => $notification,
						'fullname'  => $user->fullname,
				);
				if($subject->container_type == 'businesspage') {
						$bpage = get_business_page($subject->container_guid);
						if($bpage && $notification->poster_guid == $bpage->owner_guid) {
								$args['fullname'] = $bpage->title;
								$args['iconURL']  = $bpage->photoURL('small');
						}
				}
				return ossn_plugin_view('notifications/template/view', $args);
		}
}
/**
 * Get a poll
 *
 * @return object
 */
function ossn_poll_get($guid) {
		if(!empty($guid)) {
				$object = ossn_get_object($guid);
				if($object && $object->subtype == 'poll:item') {
						$object = (array) $object;
						return arrayObject($object, '\Softlab24\Ossn\Component\Polls');
				}
		}
		return false;
}
/**
 * Poll container types
 *
 * @return array
 */
function polls_container_types() {
		return ossn_call_hook('polls', 'container:types', false, array(
				'user',
				'group',
				'businesspage',
		));
}
/**
 * Page handler
 * Poll
 *
 * @return void
 */
function polls_page_handler($pages) {
		switch ($pages[0]) {
		case 'add':
				if(!ossn_isLoggedin()) {
						ossn_error_page();
				}

				$container = array(
						'container_guid' => ossn_loggedin_user()->guid,
						'container_type' => 'user',
				);

				if(isset($pages[1]) && in_array($pages[1], polls_container_types()) && function_exists('ossn_get_group_by_guid')) {
						$group = ossn_get_group_by_guid($pages[2]);
						if($group && $group->isMember($group->guid, ossn_loggedin_user()->guid)) {
								$container = array(
										'container_guid' => $group->guid,
										'container_type' => 'group',
										'group'          => $group,
								);
						}
				}
				if(isset($pages[1]) && $pages[1] == 'businesspage' && isset($pages[2]) && function_exists('get_business_page')) {
						if(preg_match('/^[0-9]/', $pages[2])){
							$business_page = get_business_page($pages[2]);
						} else {
							$business_page = get_business_by_username($pages[2]);	
						}
						$container = array(
								'container_guid' => $business_page->guid,
								'container_type' => 'businesspage',
								'business'       => $business_page,
						);
				}
				$title               = ossn_print('polls:add');
				$contents['content'] = ossn_plugin_view('polls/pages/add', $container);
				$content             = ossn_set_page_layout('newsfeed', $contents);
				echo ossn_view_page($title, $content);
				break;
		case 'voters':
				$poll = ossn_poll_get($pages[1]);
				if(!$poll || !ossn_is_xhr()) {
						header('HTTP/1.0 404 Not Found');
						exit();
				}
				if((isset($poll->show_voters) && $poll->show_voters == 'yes') || (ossn_isLoggedin() && ossn_loggedin_user()->guid == $poll->owner_guid)) {
						$annotation = new \OssnAnnotation();
						$list       = $annotation->searchAnnotation(array(
								'type'           => 'poll:item',
								'subject_guid'   => $poll->guid,
								'limit'          => false,
								'page_limit'     => false,
								'entities_pairs' => array(
										array(
												'name'  => 'poll:item',
												'value' => $pages[2],
										),
								),
						));
						$content = '';
						if($list) {
								$guids = false;
								foreach ($list as $poll) {
										$guids[] = $poll->owner_guid;
								}
								if(!empty($guids)) {
										$implode = implode(',', $guids);
										$users   = new OssnUser();
										$search  = $users->searchUsers(array(
												'wheres'     => "(u.guid IN ({$implode}))",
												'page_limit' => false,
										));
										$content = ossn_plugin_view('output/users_list', array(
												'users'     => $search,
												'icon_size' => 'small',
										));
								}
						}
				} else {
						header('HTTP/1.0 404 Not Found');
						exit();
				}
				echo ossn_plugin_view('output/ossnbox', array(
						'title'    => ossn_print('polls:voters'),
						'contents' => $content,
						'callback' => false,
				));
				break;
		case 'view':
				$poll = ossn_poll_get($pages[1]);
				if($poll) {
						$title               = $poll->title;
						$contents['content'] = ossn_plugin_view('polls/pages/view', array(
								'poll' => $poll,
						));
						$content = ossn_set_page_layout('newsfeed', $contents);
						echo ossn_view_page($title, $content);
				} else {
						ossn_error_page();
				}
				break;
		case 'all':
				redirect('polls/list/all');
				break;
		case 'list':
				$poll = new \Softlab24\Ossn\Component\Polls();
				switch ($pages[1]) {
				case 'all':
						$all = $poll->getAll(array(
								'entities_pairs' => array(
										array(
												'name'  => 'container_type',
												'value' => 'user',
										),
								),
						));
						$count = $poll->getAll(array(
								'count'          => true,
								'entities_pairs' => array(
										array(
												'name'  => 'container_type',
												'value' => 'user',
										),
								),
						));
						break;
				case 'user':
						if(empty($pages[2])) {
								ossn_error_page();
						}
						$all = $poll->getAll(array(
								'entities_pairs' => array(
										array(
												'name'  => 'container_type',
												'value' => 'user',
										),
										array(
												'name'  => 'container_guid',
												'value' => $pages[2],
										),
								),
						));
						$count = $poll->getAll(array(
								'count'          => true,
								'entities_pairs' => array(
										array(
												'name'  => 'container_type',
												'value' => 'user',
										),
										array(
												'name'  => 'container_guid',
												'value' => $pages[2],
										),
								),
						));
						break;
				case 'business':
						if(empty($pages[2])) {
								ossn_error_page();
						}
						$all = $poll->getAll(array(
								'entities_pairs' => array(
										array(
												'name'  => 'container_type',
												'value' => 'businesspage',
										),
										array(
												'name'  => 'container_guid',
												'value' => $pages[2],
										),
								),
						));
						$count = $poll->getAll(array(
								'count'          => true,
								'entities_pairs' => array(
										array(
												'name'  => 'container_type',
												'value' => 'businesspage',
										),
										array(
												'name'  => 'container_guid',
												'value' => $pages[2],
										),
								),
						));
						break;
				}

				$title               = ossn_print('polls:list');
				$contents['content'] = ossn_plugin_view('polls/pages/all', array(
						'all'   => $all,
						'count' => $count,
				));
				$content = ossn_set_page_layout('newsfeed', $contents);
				echo ossn_view_page($title, $content);

				break;
		default:
				ossn_error_page();
		}
}
/**
 * Delete user polls  when user deleted
 *
 * @return void;
 * @access private
 */
function ossn_user_polls_delete($callback, $type, $params) {
		$guid  = $params['entity']->guid;
		$polls = new \Softlab24\Ossn\Component\Polls();
		$all   = $polls->getAll(array(
				'owner_guid' => $guid,
				'page_limit' => false,
		));
		if($all && !empty($guid)) {
				foreach ($all as $item) {
						$item->removeData();
						$item->deleteObject();
				}
		}
}
/**
 * Add poll link to user timeline
 *
 * @return void
 * @access private
 */
function ossn_poll_profile_wall_menu($callback, $type, $params) {
		$guid = ossn_get_page_owner_guid();
		if(ossn_isLoggedin() && ossn_loggedin_user()->guid == $guid) {
				ossn_register_menu_item('wall/container/user', array(
						'name' => 'polls',
						'text' => '<i class="fa fa-th-list"></i><span>' . ossn_print('polls:poll') . '</span>',
						'href' => ossn_site_url(),
				));
		}
}
ossn_register_callback('ossn', 'init', 'polls_init');