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
class VideoThumb {
		private $pathffmpeg;
		private $binfile;
		private $filePath;
		private $thumb;
		
		/**
		 * Init
		 * 
		 * @param string $path A path to ffmpeg, its not full path just directory
		 * @param string $filepath A path to video file
		 * @param string $thumb A path where thumb will be created
		 *
		 * @return void
		 */
		public function __construct($path, $filepath, $thumb) {
				$this->pathffmpeg = $path;
				$this->binfile    = "ffmpeg";
				if(strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
						$this->binfile = "ffmpeg.exe";
				}
				$this->filePath = $filepath;
				$this->thumb    = $thumb;
		}
		/**
		 * Generate the thumb
		 * 
		 * @return boolean
		 */		
		public function generate() {
				$output = array();
				
				$cmd = sprintf("%s{$this->binfile} -i %s -an -ss 00:00:05 -r 1 -vframes 1 -y %s", $this->pathffmpeg, $this->filePath, $this->thumb);
				
				if(strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
						$cmd = str_replace('/', DIRECTORY_SEPARATOR, $cmd);
				} else {
						$cmd = str_replace('\\', DIRECTORY_SEPARATOR, $cmd);
				}
				exec($cmd, $output, $retval);
				
				if($retval) {
						return false;
				}
				return $this->thumb;
		}
}