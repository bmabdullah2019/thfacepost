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
class Cover extends \OssnFile {
		/**
		 * Add a cover for page
		 *
		 * @param integer $guid A page guid
		 *
		 * @return boolean
		 */
		public function addCover($guid) {
				if(empty($guid)) {
						return false;
				}
				$this->owner_guid = $guid;
				$this->type       = 'object';
				$this->subtype    = 'page:cover';
				$this->setFile('coverphoto');
				$this->setPath('page/cover/');
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
								$page->data->cover_entity_guid = $file_guid;
								$page->data->cover_top         = false;
								$page->data->cover_left        = false;
								$page->save();
						}
						$this->deleteOld($guid, $file_guid);
						return true;
				}
				return false;
		}
		/**
		 * Delete old covers for page
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
						'subtype'    => 'page:cover',
						'owner_guid' => $guid,
				));
				if($pics) {
						foreach($pics as $pic) {
								if($pic->guid == $pic_guid) {
										continue;
								}
								$pic->deleteFile();
						}
				}
		}
}