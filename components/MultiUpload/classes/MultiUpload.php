<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.openteknik.com/
 */
class MultiUpload {
		public static function getPhotos($guids) {
				if(empty($guids)) {
						return false;
				}
				$files  = new OssnFile();
				$images = $files->searchFiles(array(
						'type'       => 'object',
						'page_limit' => false,
						'subtype'    => 'wallmultiupload',
						'wheres'     => "(e.guid IN ($guids))",
				));
				return $images;
		}
}