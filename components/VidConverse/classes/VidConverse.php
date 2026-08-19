<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    Open Social Website Core Team <info@softlab24.com>
 * @copyright (C) SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
class VidConverse {
		private $API_ENDPOINT = 'https://api.openteknik.com/';
		private $API_VERSION  = 'v10';
		public function setCall($from, $to) {
				$key = vidconverse_apikey();
				if(!$key || empty($key)) {
						return false;
				}
				$userfrom = ossn_user_by_guid($from);
				$userto   = ossn_user_by_guid($to);
				if($userfrom && $userto) {
						$data = array(
								'from' => array(
										'name' => $userfrom->fullname,
										'guid' => $userfrom->guid,
										'icon' => $userfrom->iconURL()->small,
								),
								'to'   => array(
										'name' => $userto->fullname,
										'guid' => $userto->guid,
										'icon' => $userto->iconURL()->small,
								),
						);
						$result = $this->handShake($this->API_ENDPOINT . $this->API_VERSION . '/setup_call', array(
								'data' => base64_encode(json_encode($data)),
								'key'  => $key,
						));
						$result = json_decode($result, true);
						return $result;
				}
		}
		public function handShake($endpoint, array $options = array()) {
				if(empty($endpoint) || empty($options)) {
						return false;
				}
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $endpoint);
				curl_setopt($curl, CURLOPT_CAINFO, ossn_route()->www . 'vendors/cacert.pem');
				curl_setopt($curl, CURLOPT_POST, sizeof($options));
				curl_setopt($curl, CURLOPT_POSTFIELDS, $options);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($curl);
				curl_close($curl);
				return $result;
		}
}