<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
if(com_is_active('BusinessPage')) {
		$page = new \Ossn\Component\BusinessPage\Page();
		$list = $page->getPages(array(
				'page_limit' => false,
				'limit'      => false,
		));
		if($list) {
				foreach ($list as $item) {
						if(!isset($item->photo_entity_guid)) {
								continue;
						}
						$file = ossn_get_file($item->photo_entity_guid);
						if($file) {
								$path       = $file->getPath();
								$dir        = dirname($path);
								$filename   = basename($path);
								$large_file = $dir . '/larger_' . $filename;
								if(!file_exists($large_file)) {
										$larger = ossn_resize_image($path, 200, 200, true);
										file_put_contents($large_file, $larger);
								}
						}
				}
		}
}