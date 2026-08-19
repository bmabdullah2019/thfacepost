<?php
set_time_limit(0);

define('__VIDEOS__', ossn_route()->com . 'Videos/');
require_once(__VIDEOS__ . 'classes/Videos.php');

$videos = new Videos();
$list   = $videos->getAll(array(
		'page_limit' => false,
));
if($list) {
		foreach($list as $video) {
				if(!isset($video->file_guid) || !isset($video->cover_guid)) {
						$file  = new OssnFile();
						$files = $file->searchFiles(array(
								'type'       => 'object',
								'subtype'    => 'video',
								'owner_guid' => $video->guid,
						));
						
						$file  = new OssnFile();
						$covers = $file->searchFiles(array(
								'type'       => 'object',
								'subtype'    => 'cover',
								'owner_guid' => $video->guid,
						));		
						if($covers){
								$video->data->cover_guid = $covers[0]->guid;
						}	
						if($files) {
								$video->data->file_guid = $files[0]->guid;
						}
						$video->save();
				}
		}
}