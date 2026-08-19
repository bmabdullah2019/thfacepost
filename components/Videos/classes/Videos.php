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
class Videos extends OssnObject {
		/**
		 * Intialize the attributes
		 *
		 * @return void
		 */
		public function initAttributes() {
				$this->file = new OssnFile();
				$this->wall = new OssnWall();
		}
		/**
		 * Add Video
		 *
		 * @param string $title A title of video
		 * @param string $description A description of video
		 *
		 * @return boolean
		 */
		public function addVideo($title = '', $description = '', $container_guid = 0, $container_type = 'user') {
				self::initAttributes();
				$user = ossn_loggedin_user();

				$converter = ossn_video_ffmpeg_dir();
				if(empty($converter)) {
						error_log('The path for FFMPEG is empty');
						return false;
				}
				if(empty($container_guid) || empty($container_type)) {
						return false;
				}
				if(!empty($title) && !empty($description) && !empty($user->guid)) {
						$this->title       = $title;
						$this->description = $description;
						$this->type        = 'user';
						$this->subtype     = 'video';

						$this->owner_guid = $user->guid;

						$this->data->container_guid = $container_guid;
						$this->data->container_type = $container_type;
						$this->data->is_pending     = 'yes';
						$this->data->cover_guid     = false;
						$this->data->file_guid      = false;
						//create video
						if($guid = $this->addObject()) {
								$this->file->type       = 'object';
								$this->file->owner_guid = $guid;
								$this->file->subtype    = 'video';
								$this->file->setFile('video');
								$this->file->setPath('video/file/');
								$this->file->setMimeTypes(array(
										'mp4' => array(
												'video/mp4',
										),
										'mov' => array(
												'video/mpeg',
												'video/quicktime',
										),
								));
								$this->file->setExtension(array(
										'mp4',
										'mov',
								));

								//create video file
								if($fileguid = $this->file->addFile()) {
										$object = ossn_get_object($guid);
										$object->data->file_guid = $fileguid;
										$object->save();
										return $guid;
								}
								$this->deleteObject($guid);
						}
				}
				return false;
		}
		/**
		 * Video conversion cron jobs
		 *
		 * @return boolean|array
		 */
		public function getPending() {
				return $this->getAll(array(
						'entities_pairs' => array(
								array(
										'name'  => 'is_pending',
										'value' => 'yes',
								),
						),
				));
		}
		/**
		 * Get all videos
		 *
		 * @param array $params A options
		 *
		 * @return array
		 */
		public function getAll(array $params = array()) {
				$default                   = array();
				$default['type']           = 'user';
				$default['subtype']        = 'video';
				$default['order_by']       = 'o.guid DESC';
				$default['entities_pairs'] = array(
						array(
								'name'  => 'container_type',
								'value' => 'user',
						),
				);
				$vars = array_merge($default, $params);
				return $this->searchObject($vars);
		}
		/**
		 * Set temperory image file
		 *
		 * @param string $path A path of image
		 * @param string $id A name of image
		 * @param string $type MimeType 
		 * @param string $extension jpg or mp4
		 *
		 * @return void
		 */
		public static function setTmpFile($path, $id = '', $type = 'image/jpeg', $extension = 'jpg') {
				if(!empty($path) && !empty($id)) {
						$_FILES[$id] = array(
								'tmp_name' => $path,
								'name'     => hash('sha1', time()) . '.'.$extension,
								'type'     => $type,
								'size'     => filesize($path),
								'error'    => UPLOAD_ERR_OK,
						);
				}
		}
		/**
		 * Add video to wall
		 *
		 * @param integer $itemguid A video guid
		 *
		 * @return boolean
		 */
		public function addWall($walltype = 'user', $owner_guid = '', $itemguid = '') {
				if(empty($itemguid) || !class_exists('OssnWall')) {
						return false;
				}
				$video                   = ossn_get_video($itemguid);
				$this->wall              = new \OssnWall();
				$this->wall->type        = $walltype;
				$this->wall->item_type   = 'video';
				$this->wall->owner_guid  = $owner_guid;
				$this->wall->poster_guid = $video->owner_guid;
				$this->wall->item_guid   = $itemguid;
				$access                  = OSSN_PUBLIC;
				if($walltype !== 'user') {
						$access = OSSN_PRIVATE;
				}
				if($this->wall->Post('null:data', '', '', $access)) {
						return true;
				}
				return false;
		}
		/**
		 * Get video cover url
		 *
		 * @return void|string
		 */
		public function getCoverURL() {
				if(!empty($this->guid)) {
						$hash = md5($this->guid);
						return ossn_site_url("video/cover/{$this->cover_guid}/{$hash}.jpg");
				}
		}
		/**
		 * Get video file url
		 *
		 * @return void|string
		 */
		public function getFileURL() {
				if(!empty($this->guid)) {
						$hash = md5($this->guid);
						return ossn_site_url("video/file/{$this->file_guid}/{$hash}.mp4");
				}
		}
		/**
		 * Get video url
		 *
		 * @return void|string
		 */
		public function getURL() {
				if(!empty($this->guid)) {
						$hash = OssnTranslit::urlize($this->title);
						return ossn_site_url("video/view/{$this->guid}/{$hash}");
				}
		}
		/**
		 * Get delete video url
		 *
		 * @return void|string
		 */
		public function getDeleteURL() {
				if(!empty($this->guid)) {
						return ossn_site_url("action/video/delete?guid={$this->guid}", true);
				}
		}
		/**
		 * Get video edit url
		 *
		 * @return void|string
		 */
		public function getEditURL() {
				if(!empty($this->guid)) {
						$translit = OssnTranslit::urlize($this->title);
						return ossn_site_url("video/edit/{$this->guid}/{$translit}", true);
				}
		}
		/**
		 * Video Edit
		 *
		 * @param string $title A title of video
		 * @param string description A description of video
		 *
		 * @return boolean
		 */
		public function videoEdit($title = '', $description = '') {
				if(!empty($this->guid) && !empty($title) && !empty($description)) {
						$fields = array(
								'title',
								'description',
						);
						$values = array(
								$title,
								$description,
						);
						if($this->updateObject($fields, $values, $this->guid)) {
								return true;
						}
				}
				return false;
		}
		/**
		 * Delete video wall post
		 *
		 * @param integer $fileguid Video file guid
		 * @return boolean
		 */
		public function deleteWallPost($fileguid) {
				if(empty($fileguid) || !class_exists('OssnWall')) {
						return false;
				}
				//prepare a query to get post guid
				$statement = "SELECT * FROM ossn_entities, ossn_entities_metadata WHERE(
				  	  ossn_entities_metadata.guid = ossn_entities.guid
				      AND  ossn_entities.subtype='item_guid'
				      AND  ossn_entities.type = 'object'
				      AND ossn_entities_metadata.value = '{$fileguid}'
				      );";

				$this->statement($statement);
				$this->execute();
				$entity = $this->fetch();

				//check if post exists or not
				if($entity) {
						//get object
						$object = ossn_get_object($entity->owner_guid);
						if($object && $object->subtype == 'wall') {
								$wall = new OssnWall();
								//delete wall post
								if($wall->deletePost($object->guid)) {
										return true;
								}
						}
				}
				return false;
		}
		/**
		 * Find ffmpeg tools version for verificaiton to see if it works?
		 * 
		 * @return string|boolean
		 */
		public static function version(){
			$binfile = 'ffmpeg';
				if(strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
						$binfile = 'ffmpeg.exe';
				}
				$converter = ossn_video_ffmpeg_dir();
				if(empty($converter)) {
						error_log('The path for FFMPEG is empty');
						return false;
				}
				exec("{$converter}{$binfile} -version", $output, $return);
				if(isset($output) && isset($output[0]) && !empty($output[0])){
						return $output[0];
				}	
				return false;
		}
		/**
		 * Convert given video into H.260 MP4
		 *
		 * @param string $source A complete path to source file
		 * @param string $newfile A complete path to new file
		 * @param string $progress A path to file where a progress stores
		 *
		 * @return boolean
		 */
		public static function convert($source, $newfile, $progress) {
				if(!is_file($source) || empty($source) || empty($newfile) || empty($progress)) {
						return false;
				}
				$binfile = 'ffmpeg';
				if(strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
						$binfile = 'ffmpeg.exe';
				}
				$converter = ossn_video_ffmpeg_dir();
				if(empty($converter)) {
						error_log('The path for FFMPEG is empty');
						return false;
				}
				$com         = new OssnComponents();
				$comsettings = $com->getSettings('Videos');
				if(!$comsettings) {
						return false;
				}
				if(!isset($comsettings->ffmpeg_compression) || (isset($comsettings->ffmpeg_compression) && empty($comsettings->ffmpeg_compression))) {
						error_log('Compression size not set');
						return false;
				}
				if(!isset($comsettings->ffmpeg_maxtime) || (isset($comsettings->ffmpeg_maxtime) && empty($comsettings->ffmpeg_maxtime))) {
						error_log('Max time limit not set');
						return false;
				}
				$compression = intval($comsettings->ffmpeg_compression);
				$timelimit   = intval($comsettings->ffmpeg_maxtime);

				$command = "{$converter}{$binfile} -t {$timelimit} -i {$source} -vcodec libx264 -crf {$compression} {$newfile} 1> {$progress} 2>&1";
				//$command = "{$converter}{$binfile} -i {$source} -vcodec libx264 -profile:v  {$newfile} 1> {$progress} 2>&1";
				exec($command, $opt, $return);
				if($return === 0) {
						return true;
				}
				return false;
		}
		/**
		 * Find a progress of video %
		 *
		 * @param string $file A complete path to progress file
		 *
		 * @return integer
		 */
		public static function progress($file) {
				if(!is_file($file)) {
						return false;
				}
				$content = file_get_contents($file);
				preg_match('/Duration: (.*?), start:/', $content, $matches);
				if(!isset($matches[1])) {
						return false;
				}
				$rawDuration = $matches[1];
				// # rawDuration is in 00:00:00.00 format. This converts it to seconds.
				$ar       = array_reverse(explode(':', $rawDuration));
				$duration = floatval($ar[0]);

				if(!empty($ar[1])) {
						$duration += intval($ar[1]) * 60;
				}
				if(!empty($ar[2])) {
						$duration += intval($ar[2]) * 60 * 60;
				}

				// # get the current time
				preg_match_all('/time=(.*?) bitrate/', $content, $matches);

				$matches = array_reverse($matches);
				$last    = array_pop($matches);
				// # this is needed if there is more than one match
				if(is_array($last)) {
						$last = array_pop($last);
				}
				$last = str_replace(array(
						" bitrate",
						"time="
				), '', $last);
				$last = array_reverse(explode(':', $last));

				$last_duration = floatval($last[0]);

				if(!empty($last[1])) {
						$last_duration += intval($last[1]) * 60;
				}
				if(!empty($last[2])) {
						$last_duration += intval($last[2]) * 60 * 60;
				}
				return percentage($last_duration, $duration, 1);
		}
		/**
		 * Video conversion cron job
		 *
		 * @return void
		 */
		public function cron() {
				$pending   = $this->getPending();
				$converter = ossn_video_ffmpeg_dir();
				if($pending) {
						foreach($pending as $video) {
								//change the state of video to conversion to avoid loop
								$video->data->is_pending = 'conversion';
								$video->save();

								$file                   = ossn_get_userdata("object/{$video->guid}/" . $video->{'file:video'});
								$conversion_file        = ossn_get_userdata("object/{$video->guid}/" . $video->{'file:video'}) . '.toconvert';
								$conversion_status_file = ossn_get_userdata("object/{$video->guid}/video/file/progress.txt");
								$thumbfile              = ossn_get_userdata("object/{$video->guid}/video/file/thumb.jpg");

								if(rename($file, $conversion_file)) {
										if($video->convert($conversion_file, $file, $conversion_status_file)) {
												$cdn = ossn_file_is_cdn_storage_enabled();
												//video loaded now to check if cdn is enabled then upload on CDN
												$cdnVid = false;
												if($cdn) {
														//get old file details
														$search = new OssnFile();
														$search = $search->searchFiles(array(
																'type'       => 'object',
																'subtype'    => 'video',
																'owner_guid' => $video->guid,
														));
														$videoFile = $search[0];
														
														//adding file Again
														self::setTmpFile($videoFile->getPath(), 'video', 'video/mp4', 'mp4'); 
														$addFile             = new OssnFile();
														$addFile->type       = 'object';
														$addFile->owner_guid = $video->guid;
														$addFile->subtype    = 'video';
														$addFile->setFile('video');
														$addFile->setPath('video/file/');
														$addFile->setStore('cdn');
														$addFile->setMimeTypes(array(
																'mp4' => array(
																		'video/mp4',
																),
														));
														$addFile->setExtension(array(
																'mp4',
														));
														$cdnVid = $addFile->addFile();
												}
												$thumb = new VideoThumb($converter, $file, $thumbfile);
												if($thumb->generate()) {
														self::setTmpFile($thumbfile, 'cover');

														$OssnFile             = new OssnFile();
														$OssnFile->type       = 'object';
														$OssnFile->owner_guid = $video->guid;
														$OssnFile->subtype    = 'cover';
														$OssnFile->setFile('cover');
														$OssnFile->setPath('video/cover/');
														if($cdn) {
																$OssnFile->setStore('cdn');
														}
														$OssnFile->setExtension(array(
																'jpg',
																'png',
																'jpeg',
																'gif',
														));

														//save video cover
														if($coverGuid = $OssnFile->addFile()) {
																//remove old thumb file and conversion file for video
																unlink($thumbfile);
																unlink($conversion_file);
																unlink($conversion_status_file);
																
																$video->data->cover_guid = $coverGuid;
																if($cdnVid){
																		$video->data->file_guid = $cdnVid;
																		$videoFile->deleteFile();
																}
																$video->data->is_pending = false;
																$this->addWall($video->container_type, $video->container_guid, $video->guid);
														}
												} else {
														//failed also for video thumb generate
														$video->data->is_pending = 'failed';
												}
										} else {
												//failed also for video conversion
												$video->data->is_pending = 'failed';
										}
								}
								$video->save();
						}
				}
		}
} //class