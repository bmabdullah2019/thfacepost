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
class Sentiment {
		/**
		 * Analyze a text
		 *
		 * @param string $text A text
		 *
		 * @return array
		 */
		public static function analyze($text = '') {
				if(!empty($text)) {
						$component = new OssnComponents();
						$settings  = $component->getSettings('Sentiment');
						if($settings && isset($settings->email) && isset($settings->website) && isset($settings->apikey)) {
								$api = new OPENTEKNIK_API(array(
										'method'  => 'sentiment',
										'email'   => $settings->email,
										'website' => $settings->website,
										'apikey'  => $settings->apikey,
										'text'    => $text,
								));
								$data = $api->sentiment;
								if(!empty($data)) {
										return json_decode($data, true);
								}
						}
				}
		}
		/**
		 * Get type of text (neutral, positive, negatice)
		 *
		 * @param array $data A API data
		 *
		 * @return astring
		 */
		public static function getType(array $data = array()) {
				if(!empty($data)) {
						if(isset($data['data']) && !empty($data['data']['positive']) && !empty($data['data']['negative'])) {
								if($data['data']['positive'] > $data['data']['negative']) {
										return 'positive';
								} elseif($data['data']['negative'] > $data['data']['positive']) {
										return 'negative';
								} elseif($data['data']['positive'] == $data['data']['negative']) {
										return 'neutral';
								} else {
										return 'neutral';
								}
						}
				}
		}
}