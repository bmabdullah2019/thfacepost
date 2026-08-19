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
class Photo extends \OssnFile {
		/**
		 * Add a photo for page
		 *
		 * @param integer $guid A page guid
		 *
		 * @return boolean
		 */
		public function addPhoto($guid) {
				if(empty($guid)) {
						return false;
				}
				$this->owner_guid = $guid;
				$this->type       = 'object';
				$this->subtype    = 'page:photo';
				$this->setFile('photo');
				$this->setPath('page/photo/');
				if(ossn_file_is_cdn_storage_enabled()) {
						$this->setStore('cdn');
				}
				$this->setExtension(array(
						'jpg',
						'png',
						'jpeg',
						'gif',
				));
				if($file_guid = $this->addFile()) {
						$page = ossn_get_object($guid);
						if($page) {
								$page->data->photo_entity_guid = $file_guid;
								$page->save();
								
								//$file
								$fileName = $this->newfilename;
								//resized photos
								$small = ossn_resize_image($this->file['tmp_name'], 50, 50, true);
								$smaller = ossn_resize_image($this->file['tmp_name'], 32, 32, true);
								$larger = ossn_resize_image($this->file['tmp_name'], 200, 200, true);
								if(!ossn_file_is_cdn_storage_enabled()) {
										file_put_contents(ossn_get_userdata("object/{$guid}/page/photo/small_{$fileName}"), $small);
										file_put_contents(ossn_get_userdata("object/{$guid}/page/photo/smaller_{$fileName}"), $smaller);
										file_put_contents(ossn_get_userdata("object/{$guid}/page/photo/larger_{$fileName}"), $larger);
								} else {
									$dirlocalpath  = "object/{$guid}/page/photo/";
									$cdn           = new \CDNStorage\Controller($dirlocalpath, $file_guid);
									$cdn->mimeType = mime_content_type($this->file['tmp_name']);
									
									$filename      = "small_{$fileName}";									
									$cdn->upload($small, $filename, 'public-read', false);
									
									$filename      = "smaller_{$fileName}";									
									$cdn->upload($smaller, $filename, 'public-read', false);
									
									$filename      = "larger_{$fileName}";									
									$cdn->upload($larger, $filename, 'public-read', false);									
								}
								
						}
						$this->deleteOld($guid, $file_guid);
						return true;
				}
				return false;
		}
		/**
		 * Delete old photos for page except new photo pic_guid
		 *
		 * @param integer $guid A page guid
		 *
		 * @return boolean
		 */
		public function deleteOld($guid, $pic_guid) {
				if(empty($guid)) {
						return false;
				}
				$file = new \OssnFile();
				$pics = $file->searchFiles(array(
						'type'       => 'object',
						'subtype'    => 'page:photo',
						'owner_guid' => $guid,
				));
				if($pics) {
						foreach($pics as $pic) {
								if($pic->guid == $pic_guid) {
										continue;
								}
								$pic->deleteFile();
						}
						return true;
				}
		}		
}