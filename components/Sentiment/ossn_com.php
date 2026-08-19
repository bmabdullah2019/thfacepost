<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
define('SENTIMENT', ossn_route()->com . 'Sentiment/');

require_once(SENTIMENT . 'classes/Sentiment.php');
/**
 * Sentiment Initilizze
 * 
 * @param null
 * @return void
 */
function sentiment_init() {
		ossn_register_callback('wall', 'post:created', 'sentiment_wallpost_add', 1);
		ossn_register_callback('wall', 'post:edited', 'sentiment_wallpost_add', 1);
		ossn_register_callback('comment', 'created', 'sentiment_comment_add', 1);
		ossn_register_callback('comment', 'edited', 'sentiment_comment_add', 1);
		
		ossn_extend_view('css/ossn.default', 'css/sentiment');
		ossn_extend_view("comments/templates/comment", 'sentiment/comment_show');
		ossn_register_com_panel('Sentiment', 'settings');
		if(ossn_isAdminLoggedin()) {
				ossn_register_action('sentiment/settings', SENTIMENT . 'actions/settings.php');
		}		
		
		ossn_extend_view('wall/templates/wall/user/item', 'sentiment/wall_show');
		ossn_extend_view('wall/templates/wall/group/item', 'sentiment/wall_show');
		ossn_extend_view('wall/templates/wall/businesspage/item', 'sentiment/wall_show');		
}
/**
 * Sentiment wallpost add
 * 
 * @param string $callback A name of callback
 * @param string $type A callback type
 * @param array	 $params A option values
 * 
 * @return void
 */
function sentiment_wallpost_add($callback, $type, $params) {
		if(isset($params['object_guid']) && !empty($params['object_guid'])) {
				$object = ossn_get_object($params['object_guid']);
		} elseif(isset($params['object']) && $params['object'] instanceof OssnObject) {
				$object = $params['object'];
		}
		if($object) {
				$sentiment = sentiment_process($object->description);
				if(!empty($object->description) && $object->description !== 'null:data') {
						$object->data->sentiment = $sentiment;
						$object->save();
				}
		}
}
/**
 * Sentiment comment add
 * 
 * @param string $callback A name of callback
 * @param string $type A callback type
 * @param array	 $params A option values
 * 
 * @return void
 */
function sentiment_comment_add($callback, $type, $params) {
		if(isset($params['id']) && !empty($params['id'])) {
				$comment = ossn_get_annotation($params['id']);
		} elseif($params['annotation']) {
				$comment = ossn_get_annotation($params['annotation']->id);
		}
		if($comment) {
				$comment->data = new stdClass;
				if($comment->type == 'comments:entity') {
						$text = $comment->getParam('comments:entity');
				} elseif($comment->type == 'comments:post') {
						$text = $comment->getParam('comments:post');
				}
				if(!empty($text)) {
						$sentiment = sentiment_process($text);
						if(!empty($sentiment)) {
								$comment->data->sentiment = $sentiment;
								$comment->save();
						}
				}
		}
}
/**
 * Sentiment process
 * 
 * @param string $text A text
 * 
 * @return string|boolean
 */
function sentiment_process($text = '') {
		$data = Sentiment::analyze($text);
		if(!empty($text) && !empty($data)) {
				return Sentiment::getType($data);
		}
		return false;
}
ossn_register_callback('ossn', 'init', 'sentiment_init');
