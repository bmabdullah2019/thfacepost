<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0 https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com
 */
class Stories extends OssnObject {
		public function addStory($title = ''){
				$this->title      = $title;
				$this->owner_guid = ossn_loggedin_user()->guid;
				$this->type       = 'user';
				$this->subtype    = 'story';
				if($guid = $this->addObject()){
						$object = ossn_get_object($guid);
						$images = ossn_input_images('images');
						if (isset($images) && !empty($images[0]) && !empty($images[0]['size'])){
								$count = count($images);
								$total = 0;
								foreach ($images as $key => $image){
										$_FILES["image{$key}"] = $image;
										$file                  = new OssnFile;
										$file->type            = 'object';
										$file->owner_guid      = $guid;
										$file->subtype         = 'storyfile';
										$file->setFile("image{$key}");
										$file->setPath('story/contents/');
										$file->setExtension(array(
												'jpg',
												'png',
												'jpeg',
												'gif'
										));
										if($file->addFile()){
											$count++;
										}
								}
								if($count > 0){
										return true;	
								}
						}
						$object->deleteObject($guid);
						return false;
				}
		}
		public function getStoryItemPhotos($guid, $params = array()){
				if(!empty($guid)){
						$default = array(
									'type' => 'object',
									'subtype' => 'file:storyfile',
									'wheres' => array("(e.owner_guid={$guid})"),
						);
						$args  = array_merge($default, $params);
						$files = $this->searchEntities($args);	
						if(isset($args['count'])){
							return $files;	
						}
						if($files){
							foreach($files as $file){
								$fitem[] = arrayObject((array)$file, 'OssnFile');	
							}
							return $fitem;
						}
				}
				return false;
		}
		public function getAll($params = array()){
				$default             = array();
				$default['type']     = 'user';
				$default['subtype']  = 'story';
				$default['order_by'] = 'o.guid DESC';
				
				$vars = array_merge($default, $params);
				return $this->searchObject($vars);			
		}
		public function getStoriesExpired(){
				$list = $this->getAll(array(
						'page_limit' => false,
						'wheres' => array("( (FROM_UNIXTIME(o.time_created) < DATE_SUB(NOW(), INTERVAL 24 HOUR)) )"),							
				));
				return $list;
		}		
		public function getStoriesActive(){
				$user = ossn_loggedin_user();
				if(isset($user->guid) && !empty($user->guid)) {
						$friends      = $user->getFriends($user->guid, array(
										'page_limit' => false,													 
						));
						$friend_guids = array();
						if($friends) {
								foreach($friends as $friend) {
										$friend_guids[] = $friend->guid;
								}
						}
						$friend_guids[] = $user->guid;				
						$friend_guids   = implode(',', $friend_guids);
				}
				if(empty($friend_guids)){
						return false;
				}	
				$list = $this->getAll(array(
						'page_limit' => false,
						'wheres' => array("( (FROM_UNIXTIME(o.time_created) >= DATE_SUB(NOW(), INTERVAL 24 HOUR)) AND o.owner_guid IN({$friend_guids}) )"),							
				));
				$owners = array();
				$images = array();
				
				if($list){
					foreach($list as $item){
							$files = $this->getStoryItemPhotos($item->guid, array(
									'page_limit' => false,	
							));	
							if(!$files){
								continue;
							}	
							if(!isset($owners[$item->owner_guid])){
									$owners[$item->owner_guid] = ossn_user_by_guid($item->owner_guid);
							}
							if($files){
								if(!isset($images[$item->owner_guid])){
									$images[$item->owner_guid]['owner']['fullname'] = $owners[$item->owner_guid]->fullname;
									$images[$item->owner_guid]['owner']['guid'] = $owners[$item->owner_guid]->guid;
									$images[$item->owner_guid]['owner']['item_guid'] = $item->guid;
									$images[$item->owner_guid]['owner']['icon'] = $owners[$item->owner_guid]->iconURL()->small;

									/**$images[10]['owner']['icon'] = $owners[$item->owner_guid]->iconURL()->small;
									$images[11]['owner']['icon'] = $owners[$item->owner_guid]->iconURL()->small;
									$images[12]['owner']['icon'] = $owners[$item->owner_guid]->iconURL()->small;
									$images[8]['owner']['icon'] = $owners[$item->owner_guid]->iconURL()->small;
									$images[7]['owner']['icon'] = $owners[$item->owner_guid]->iconURL()->small; */

								}
								//$datai['owner']['icon'] = './src/images/user/icon.jpg';
								$lists = array();
								foreach($files as $file){
										$data = new stdClass;
										$data->title = $item->title;
										$data->guid	= $file->guid;
										$data->time = ossn_user_friendly_time($file->time_created);
										//$data->viewed	=  true;
										
										$explode		   = explode('/', $file->value);
										$explode		   = array_reverse($explode);
										
										$data->url         = ossn_site_url("stories/file/{$file->guid}/{$explode[0]}");
										$images[$item->owner_guid]['files'][] = $data;	
										/*$images[10]['files'][] = $data;	
										$images[11]['files'][] = $data;	
										$images[12]['files'][] = $data;	
										$images[8]['files'][] = $data;	
										$images[7]['files'][] = $data;*/	
								}							
							}
					}
					if(!empty($images)){
						$images = array_values($images);
						return $images;	
					}
				}
				return false;
		}
}