<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team https://www.openteknik.com/contact
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
namespace Ossn\Component\BusinessPage;
class Page extends \OssnObject {
		/**
		 * Categories for page
		 *
		 * @return array
		 */
		public static function categories() {
				return array(
						'business',
						'brand',
						'product',
						'artist',
						'public:figure',
						'entertainment',
						'cause',
						'community',
						'org'
				);
		}
		/**
		 * Add business page
		 * 
		 * @param array $vars A option values
		 * 
		 * @return boolean
		 */				
		public function addPage(array $vars = array()) {
				if(!empty($vars['name']) && !empty($vars['category']) && !empty($vars['description'])) {
						$this->title       = $vars['name'];
						$this->description = $vars['description'];
						
						$this->type       = 'user';
						$this->owner_guid = ossn_loggedin_user()->guid;
						$this->subtype    = 'business:page';
						
						$this->data->category = $vars['category'];
						$this->data->website  = $vars['website'];
						$this->data->phone    = $vars['phone'];
						$this->data->address  = $vars['address'];
						$this->data->email	  = $vars['email'];
						return $this->addObject();
				}
				return false;
		}
		/**
		 * Edit business page
		 * 
		 * @param integer $guid A page guid
		 * 
		 * @return boolean
		 */				
		public function editPage($guid, $vars) {
				$page = ossn_get_object($guid);
				if(empty($guid) || !$page) {
						return false;
				}
				if($page->owner_guid == ossn_loggedin_user()->guid || ossn_loggedin_user()->canModerate()) {
						$page->title          = $vars['name'];
						$page->description    = $vars['description'];
						$page->data->category = $vars['category'];
						$page->data->website  = $vars['website'];
						$page->data->phone    = $vars['phone'];
						$page->data->address  = $vars['address'];
						$page->data->email	  = $vars['email'];
						return $page->save();
				}
				return false;
		}
		/**
		 * Delete businesspage
		 * 
		 * @param integer $guid A page guid
		 * 
		 * @return boolean
		 */				
		public function deletePage($guid) {
				$page = get_business_page($guid);
				if(empty($guid) || !$page) {
						return false;
				}
				return business_page_delete($page);
		}		
		/**
		 * Get user pages
		 *
		 * @param array $params A option values
		 * 
		 * @return object
		 */
		public function getUserPages($user_guid, $params = array()) {
				if(empty($user_guid)) {
						return false;
				}
				$vars = array(
						'type' => 'user',
						'subtype' => 'business:page',
						'owner_guid' => $user_guid
				);
				$args = array_merge($vars, $params);
				return $this->searchObject($args);
		}
		/**
		 * Get business page by username
		 * 
		 * @param string $username Username
		 * 
		 * @return boolean}object
		 */				
		public function getByUsername($username) {
				if(empty($username)){
						return false;	
				}
				$vars = array(
						'type' => 'user',
						'subtype' => 'business:page',
						'page_limit' => 1,
						'limit' => 1,
						'entities_pairs' => array(
										array(
												'name'   => 'username',
												'value'  => trim($username),
										),
						)
				);
				if($found = $this->searchObject($vars)){
						return $found[0];	
				}
				return false;
		}			
		/**
		 * Get business pages
		 * 
		 * @param array $params A option values
		 * 
		 * @return boolean
		 */				
		public function getPages($params = array()) {
				$vars = array(
						'type' => 'user',
						'subtype' => 'business:page',
				);
				$args = array_merge($vars, $params);
				return $this->searchObject($args);
		}	
		/**
		 * Get photo URL
		 * 
		 * @param string $type A photo type
		 * 
		 * @return string
		 */				
		public function photoURL($type = "larger") {
				if(isset($this->photo_entity_guid) && !empty($this->photo_entity_guid)) {
						$hash = sha1($this->guid);
						return ossn_site_url("page/photo/{$this->photo_entity_guid}/{$type}/{$hash}.jpg");
				} else {
						return ossn_site_url("page/photo/{$this->guid}/{$type}/default.jpg");
				}
		}
		/**
		 * Get page URL
		 * 
		 * @return string
		 */
		public function getURL(){
				if(isset($this->username)){
					return ossn_site_url("company/{$this->username}");	
				}
				return ossn_site_url("page/view/{$this->guid}");	
		}
		/**
		 * Get cover URL
		 * 
		 * @return string
		 */			
		public function coverURL() {
				if(isset($this->cover_entity_guid) && !empty($this->cover_entity_guid)) {
						$hash = sha1($this->guid);
						return ossn_site_url("page/cover/{$this->cover_entity_guid}/{$hash}.jpg");
				} else {
						return ossn_site_url("page/cover/{$this->guid}/default.jpg");
				}
		}
		/**
		 * Get pages that user liked
		 *
		 * @return boolean|array
		 */
		 public function getLikedPages($guid = '', $params = array()) {
			 	if(empty($guid)){
					return false;	
				}
				$vars = array(
						'type' => 'user',
						'subtype' => 'business:page',
						'wheres' => "(o.guid IN(SELECT DISTINCT subject_id FROM `ossn_likes` WHERE `guid` = {$guid}  AND `type` LIKE 'business:page'))",
						
				);
				$args = array_merge($vars, $params);
				return $this->searchObject($args);
		 }
		/**
		 * Delete current profile photo
		 *		 *
		 * @return boolean
		 */
		public function deletePhoto() {
				if(!isset($this->photo_entity_guid) || (isset($this->photo_entity_guid) && empty($this->photo_entity_guid))) {
						return false;
				}
				$file = ossn_get_file($this->photo_entity_guid);
				if($file && $file->subtype == 'file:page:photo' && $file->deleteFile()){
						$this->data->photo_entity_guid = false;
						$this->save();
						return true;	
				}
				return false;
		}	
		/**
		 * Delete current cover photo
		 *		 *
		 * @return boolean
		 */
		public function deleteCover() {
				if(!isset($this->cover_entity_guid) || (isset($this->cover_entity_guid) && empty($this->cover_entity_guid))) {
						return false;
				}
				$file = ossn_get_file($this->cover_entity_guid);
				if($file && $file->subtype == 'file:page:cover' && $file->deleteFile()){
						$this->data->cover_entity_guid = false;
						$this->data->cover_left        = false;
						$this->data->cover_top         = false;
						$this->save();
						return true;	
				}
				return false;
		}			
}