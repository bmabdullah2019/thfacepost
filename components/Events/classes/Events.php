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
class Events extends OssnObject {
		/**
		 * Initialize The Attrs
		 *
		 * @return void
		 */
		public function initAttrs() {
				$this->file = new OssnFile();
				if(class_exists('OssnWall')) {
						$this->wall = new OssnWall();
				}
		}
		/**
		 * Add the event
		 *
		 * @param  string $title A event title
		 * @param  string $info A description for event
		 * @param  $container_guid If its a group event
		 * @param  $params A option values
		 *
		 * @return boolean
		 */
		public function addEvent($title, $info, $container_guid = false, array $params = array()) {
				self::initAttrs();
				$user = ossn_loggedin_user();
				if(!empty($user->guid) && !empty($title) && !empty($params['date']) && !empty($info)) {
						//setup basic settings
						$this->title       = $title;
						$this->description = $info;
						$this->type        = 'user';
						$this->subtype     = 'event';
						$this->owner_guid  = $user->guid;

						//set event metadata
						$this->data->event_cost             = $params['event_cost'];
						$this->data->country                = $params['country'];
						$this->data->location               = $params['location'];
						$this->data->date                   = $params['date'];
						$this->data->start_time             = $params['start_time'];
						$this->data->end_time               = $params['end_time'];
						$this->data->allowed_comments_likes = $params['comments'];
						$this->data->is_finished            = false;

						//is event is of any other object like group event?
						if($container_guid) {
								$this->data->container_guid = $container_guid;
						}
						$container_guid = $this->data->container_guid;
						$container_type = $this->data->container_type;
						if($object_guid = $this->addObject()) {
								$this->addWall($container_type, $container_guid, $this->eventEntityAdd($object_guid));
								//add event image
								if(isset($_FILES['picture'])) {
										$this->file->owner_guid = $object_guid;
										$this->file->type       = 'object';
										$this->file->subtype    = 'event:photo';
										$this->file->setFile('picture');
										if(ossn_file_is_cdn_storage_enabled()) {
												$this->file->setStore('cdn');
										}
										$this->file->setPath('event/photo/');
										$this->file->setExtension(array(
												'jpg',
												'png',
												'jpeg',
												'gif',
										));
										$fileGuid = $this->file->addFile();
										if(!$fileGuid) {
												//delete event if image creation failed.
												$this->deleteObject($this->file->owner_guid);
												return false;
										}
										//resize event image and create one small one of 150x150
										if(isset($this->file->file['tmp_name'])) {
												$file_name = $this->file->newfilename;

												$resized = ossn_resize_image($this->file->file['tmp_name'], 250, 250, true);
												if(!ossn_file_is_cdn_storage_enabled()) {
														file_put_contents(ossn_get_userdata("object/{$this->file->owner_guid}/event/photo/small_{$file_name}"), $resized);
												} else {
														$dirlocalpath = "object/{$this->file->owner_guid}/event/photo/";
														$filename     = "small_{$file_name}";

														$cdn           = new \CDNStorage\Controller($dirlocalpath, $fileGuid);
														$cdn->mimeType = mime_content_type($this->file->file['tmp_name']);
														$cdn->upload($resized, $filename, 'public-read', false);
												}
										}
										$object                  = ossn_get_object($object_guid);
										$object->data->file_guid = $fileGuid;
										$object->save();
								}
								//return the event guid
								return $object_guid;
						}
				}
				return false;
		}
		/**
		 * Save the image for event
		 *
		 * @param integer $guid A event guid
		 *
		 * @return boolean
		 */
		public function saveImage($guid) {
				if(isset($_FILES['picture']) && !empty($_FILES['picture']['name']) && !empty($guid)) {
						$file = new OssnFile();
						$file = $file->searchFiles(array(
								'type'       => 'object',
								'owner_guid' => $guid,
								'subtype'    => 'event:photo',
						));
						$cdn = ossn_file_is_cdn_storage_enabled();
						if(!$cdn) {
								OssnFile::DeleteDir(ossn_get_userdata("object/{$guid}/event/photo/"));
						}
						foreach($file as $pic) {
								$pic->deleteFile();
						}

						self::initAttrs();
						$this->file->owner_guid = $guid;
						$this->file->type       = 'object';
						$this->file->subtype    = 'event:photo';
						$this->file->setFile('picture');
						$this->file->setPath('event/photo/');
						if($cdn) {
								$this->file->setStore('cdn');
						}
						$this->file->setExtension(array(
								'jpg',
								'png',
								'jpeg',
								'gif',
						));
						$fileGuid = $this->file->addFile();
						if(!$fileGuid) {
								return false;
						}
						if(isset($this->file->file['tmp_name'])) {
								$file_name = $this->file->newfilename;

								$resized = ossn_resize_image($this->file->file['tmp_name'], 250, 250, true);
								if(!$cdn) {
										file_put_contents(ossn_get_userdata("object/{$this->file->owner_guid}/event/photo/small_{$file_name}"), $resized);
								} else {
										$dirlocalpath = "object/{$this->file->owner_guid}/event/photo/";
										$filename     = "small_{$file_name}";

										$cdn           = new \CDNStorage\Controller($dirlocalpath, $fileGuid);
										$cdn->mimeType = mime_content_type($this->file->file['tmp_name']);
										$cdn->upload($resized, $filename, 'public-read', false);
								}
						}
						$object                  = ossn_get_object($guid);
						$object->data->file_guid = $fileGuid;
						$object->save();
				}
				return true;
		}
		/**
		 * Event icon URL
		 *
		 * @param string $size A size of icon
		 *
		 * @return string
		 */
		public function iconURL($size = 'small') {
				if(!isset($this->last_update)) {
						$this->last_update = $this->time_created;
				}
				$hash = md5($this->guid . $this->last_update);
				return ossn_site_url("event/image/{$this->file_guid}/{$size}/{$hash}.jpg");
		}
		/**
		 * Event profile URL
		 *
		 * @param boolean $uri URI only default is false
		 * @return string
		 */
		public function profileURL($uri = false) {
				$title = OssnTranslit::urlize($this->title);
				if($uri === true){
						return "event/view/{$this->guid}/{$title}";
				}
				return ossn_site_url("event/view/{$this->guid}/{$title}");
		}
		/**
		 * Get events
		 *
		 * @param array $params A option values
		 *
		 * @return array
		 */
		public function getEvents(array $params = array()) {
				$default = array(
						'type'     => 'user',
						'subtype'  => 'event',
						'order_by' => 'o.guid DESC',
				);
				if(EVENTS_SORT_BY_DATE) {
						$pairs = false;
						if(isset($params['entities_pairs'])){
							$pairs = $params['entities_pairs'];
							unset($params['entities_pairs']);
						}
						$params['entities_pairs'][] = array(
								'name'   => 'date',
								'value'  => false,
								'wheres' => 'emd0.value IS NOT NULL',
						);
						if($pairs) {
								foreach($pairs as $pair) {
										$params['entities_pairs'][] = $pair;
								}
						}
						$default['order_by'] = "STR_TO_DATE(emd0.value, '%c/%d/%Y') ASC";
				}
				$vars = array_merge($default, $params);
				return $this->searchObject($vars);
		}
		/**
		 * Event add entity for wall post
		 *
		 * @param integer $guid A event GUID
		 *
		 * @return boolean
		 */
		private function eventEntityAdd($guid) {
				if(empty($guid)) {
						return false;
				}
				$entity             = new OssnEntities();
				$entity->type       = 'object';
				$entity->owner_guid = $guid;
				$entity->subtype    = 'event:wall';
				$entity->value      = true;
				if($entity->add()) {
						return $entity->AddedEntityGuid();
				}
				return false;
		}
		public function addWall($walltype = 'user', $owner_guid = '', $itemguid = '') {
				if(empty($itemguid) || !class_exists('OssnWall')) {
						return false;
				}
				$this->wall              = new \OssnWall();
				$this->wall->type        = $walltype;
				$this->wall->item_type   = 'event';
				$this->wall->owner_guid  = $owner_guid;
				$this->wall->poster_guid = ossn_loggedin_user()->guid;
				$this->wall->item_guid   = $itemguid;

				$access = OSSN_PUBLIC;
				if($walltype !== 'user') {
						$access = OSSN_PRIVATE;
				}
				if($this->wall->Post('null:data', '', '', $access)) {
						return true;
				}
				return false;
		}
}