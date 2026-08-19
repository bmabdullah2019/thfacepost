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
namespace Softlab24\Ossn\Component;
class Polls extends \OssnObject {
		/** 
		 * Poll Add
		 * 
		 * @param string  $title A title for poll
		 * @param integer $container_guid  A guid who own the poll
		 * @param integer $container_type  A type of container
		 * @param array   #options     Poll options
		 * 
		 * @return boolean
		 */
		public function addPoll($title = '', $container_guid = 0, $container_type = 0, array $options = array()) {
				if (empty($container_guid) || empty($container_type) || empty($title) || empty($options)) {
						return false;
				}
				if(!isset($this->show_voters)){
					$this->show_voters = true;	
				}
				$options_hashed = array();
				foreach ($options as $option) {
						//we need these to make use in later for finding out the option votes
						$options_hashed[md5($option)] = trim($option);
				}
				$this->title       = $title;
				$this->description = json_encode($options_hashed, JSON_UNESCAPED_UNICODE);
				$this->type        = 'user';
				$this->subtype     = 'poll:item';
				$this->owner_guid  = ossn_loggedin_user()->guid; //because poll is always created by the user.
				
				$this->data->container_guid = $container_guid;
				$this->data->container_type = $container_type;
				$this->data->is_ended       = false;
				$this->data->show_voters    = $this->show_voters;
				if ($guid = $this->addObject()) {
						$object     = ossn_get_object($guid);
						//entity is required for comments,  wall etc.
						$entity_add = ossn_add_entity(array(
								'type' => 'object',
								'subtype' => 'poll_entity',
								'value' => true,
								'owner_guid' => $guid
						));
						if ($entity_add) {
								//here we are passing object guid as item_guid because when we get item, it will have entity guid already
								$this->addWall($container_type, $container_guid, $guid);
								
								$entities = ossn_get_entities(array(
										'type' => 'object',
										'subtype' => 'poll_entity',
										'owner_guid' => $guid
								));
								if (isset($entities[0])) {
										$object->data->poll_entity = $entities[0]->guid;
										$object->save();
								}
						}
						ossn_trigger_callback('poll', 'created', array(
								'guid' => $guid
						));
						return $guid;
				}
				return false;
		}
		/** 
		 * Get options
		 *
		 * @return array
		 */
		public function getOptions() {
				return json_decode($this->description, true);
		}
		/** 
		 * Add poll to the wall
		 * 
		 * @param string  $walltype    User/group/page
		 * @param integer $owner_guid  Owner GUID
		 * @param integer $item_guid   Poll wall item guid
		 * 
		 * @return boolean
		 */
		public function addWall($walltype = "user", $owner_guid = "", $itemguid = '') {
				if (empty($itemguid) || !class_exists("OssnWall")) {
						return false;
				}
				$this->wall              = new \OssnWall;
				$this->wall->type        = $walltype;
				$this->wall->item_type   = 'poll:item';
				$this->wall->owner_guid  = $owner_guid;
				$this->wall->poster_guid = ossn_loggedin_user()->guid;
				$this->wall->item_guid   = $itemguid;
				if($walltype !== 'user'){
						$access = OSSN_PRIVATE;
				}	
				if ($this->wall->Post('null:data', '', '', $access)) {
						return true;
				}
				return false;
		}
		/** 
		 * Check if user has voted for poll or not
		 * 
		 * @return boolean
		 */
		public function hasVoted() {
				if (!isset($this->guid)) {
						return false;
				}
				$annotation = new \OssnAnnotation;
				$list       = $annotation->searchAnnotation(array(
						'type' => 'poll:item',
						'owner_guid' => ossn_loggedin_user()->guid,
						'subject_guid' => $this->guid,
						'offset' => 1,
				));
				if ($list) {
						return $list[0]->getParam('poll:item');
				}
				return false;
		}
		/** 
		 * Get all the votes for the poll
		 * 
		 * @return array
		 */
		public function getVotes() {
				if (!isset($this->guid)) {
						return false;
				}
				$annotation = new \OssnAnnotation;
				$list       = $annotation->searchAnnotation(array(
						'type' => 'poll:item',
						'subject_guid' => $this->guid,
						'page_limit' => false
				));
				$types      = array();
				if ($list) {
						foreach ($list as $item) {
								$types[$item->getParam('poll:item')][] = $item->id;
						}
						foreach ($types as $k => $type) {
								$results[$k] = count($type);
						}
						$total_votes = array_sum($results);
						foreach ($results as $r => $result) {
								$total_percentages[$r] = ceil(($result / $total_votes) * 100);
						}
						return $total_percentages;
				}
				return false;
		}
		/** 
		 * Add vote
		 * 
		 * @param string  $option    A option value
		 * 
		 * @return boolean
		 */
		public function addVote($option = '') {
				if (empty($option) || !isset($this->guid)) {
						return false;
				}
				if ($voted = $this->hasVoted()) {
						return $voted;
				}
				$annotation               = new \OssnAnnotation;
				$annotation->owner_guid   = ossn_loggedin_user()->guid;
				$annotation->subject_guid = $this->guid;
				$annotation->value        = $option;
				$annotation->type         = 'poll:item';
				if ($annotation->addAnnotation()) {
						return $option;
				}
				return false;
		}
		/**
		 * Count votes
		 *
		 * @return integer
		 */
		public function countVotes() {
				if (!isset($this->guid)) {
						return false;
				}
				$annotation = new \OssnAnnotation;
				return  $annotation->searchAnnotation(array(
						'type' => 'poll:item',
						'subject_guid' => $this->guid,
						'page_limit' => false,
						'count' => true,
				));
		}		 
		/**
		 * Get all Polls
		 *
		 * @param array $params A options
		 *
		 * @return array
		 */
		public function getAll(array $params = array()) {
				$default             = array();
				$default['type']     = 'user';
				$default['subtype']  = 'poll:item';
				$default['order_by'] = 'o.guid DESC';
				
				$vars = array_merge($default, $params);
				return $this->searchObject($vars);
		}
		/** 
		 * Get poll URL
		 * 
		 * @param boolean  $base   TRUE to include base URL
		 * 
		 * @return string
		 */
		public function getURL($base = true) {
				$title = \OssnTranslit::urlize($this->title);
				$URL   = "polls/view/{$this->guid}/{$title}";
				if (!$base) {
						return $URL;
				}
				return ossn_site_url($URL);
		}
		/**
		 * Remove file data
		 *
		 * @return void
		 */
		public function removeData() {
				$guid   = $this->guid;
				$entity = ossn_get_entities(array(
						'type' => 'object',
						'owner_guid' => $guid,
						'subtype' => 'poll_entity'
				));
				if (class_exists('OssnNotifications') && !empty($entity[0]->guid)) {
						$ndelete = new \OssnNotifications;
						$ndelete->deleteNotification(array(
								'subject_guid' => $entity[0]->guid,
								'type' => array(
										'comments:entity:poll_entity',
										'like:entity:poll_entity'
								)
						));
				}
				if (class_exists('OssnComments') && !empty($entity[0]->guid)) {
						$comments     = new \OssnComments;
						$comments_all = $comments->GetComments($entity[0]->guid, 'entity');
						foreach ($comments_all as $comment) {
								$comment->deleteComment($comment->id);
						}
				}
				if (class_exists('OssnLikes') && !empty($entity[0]->guid)) {
						$likes     = new \OssnLikes;
						$likes_all = $likes->deleteLikes($entity[0]->guid, 'entity');
				}
				if (class_exists('OssnWall') && !empty($guid)) {
						$wall       = new \OssnWall;
						$wall_posts = $wall->searchObject(array(
								'subtype' => 'wall',
								'entities_pairs' => array(
										array(
												'name' => 'item_type',
												'value' => 'poll:item'
										),
										array(
												'name' => 'item_guid',
												'value' => $guid
										)
								)
						));
						if ($wall_posts) {
								foreach ($wall_posts as $item) {
										$item->deletePost($item->guid);
								}
						}
				}
				$annotation = new \OssnAnnotation;
				$list       = $annotation->searchAnnotation(array(
						'type' => 'poll:item',
						'subject_guid' => $this->guid
				));
				if ($list) {
						foreach ($list as $item) {
								$item->deleteAnnotation($item->id);
						}
				}
		}
}