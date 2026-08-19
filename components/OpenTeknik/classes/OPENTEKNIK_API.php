<?php
/**
 *  Open Source Social Network
 *
 * @package   (Informatikon.com).ossn
 * @author    OSSN Core Team <info@opensource-socialnetwork.org>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
class OPENTEKNIK_API {
		//End Points
		private $Endpoint = 'https://api.openteknik.com/api/';
		
		//Factory Version
		private $Version = 'v1';
		
		/**
		 * Initilize 
		 *
		 * @param array $options The options you want to broadcast
		 * @return void
		 */
		public function __construct(array $options = array()) {
				$this->options = $options;
		}
		/**
		 * Factory methods List 
		 *
		 * @return array
		 */		
		private static function Methods() {
				return array(
						'sentiment' => '/sentiment.py',
				);
		}
		/**
		 * Prepare Call
		 *
		 * @param array $vars The options you want to broadcast
		 * @return boolean|string
		 */		
		private function prepareCall(array $vars = array()) {
				if(empty($this->Endpoint) || empty($this->Version) || empty($vars['method'])){
					return false;
				}
				return $this->Endpoint . $this->Version . $vars['method'];
		}
		/**
		 * Call
		 *
		 * @param string $method The mehtod you want to call
		 * @return string
		 */			
		private function Call($method = '') {
				$methods = self::Methods();
				if(!isset($methods[$method]) || empty($methods[$method])) {
						return false;
				}
				$vars           = array();
				$vars['method'] = $methods[$method];
				$endpoint       = $this->prepareCall($vars);
				return $this->handShake($endpoint, $this->options);
		}
		/**
		 * Hand Shake
		 *
		 * @param string $endpoint The complete URL for endpoint
		 * @param array $options The options you want to broadcast
		 * 
		 * @return boolean|string
		 */					
		private function handShake($endpoint, array $options = array()) {
				if(empty($endpoint) || empty($options)) {
						return false;
				}
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $endpoint);
				curl_setopt($curl, CURLOPT_CAINFO, ossn_route()->www . 'vendors/cacert.pem');
				curl_setopt($curl, CURLOPT_POST, sizeof($options));
				curl_setopt($curl, CURLOPT_POSTFIELDS, $options);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				$result = curl_exec($curl);
				curl_close($curl);
				return $result;
		}
		/**
		 * Get
		 *
		 * Reading data from inaccessible properties.
		 *
		 * @param string $option A object
		 * 
		 * @return boolean|string
		 */				
		public function __get($option) {
				return $this->Call($option);
		}
}		