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
class Feedback extends OssnObject {
		public function addFeedback($description, $stars) {
				if(!empty($description) && !empty($stars)) {
						$this->owner_guid = ossn_loggedin_user()->guid;
						$this->type = 'user';
						$this->subtype = 'feedback';
						$this->description = $description;
						$this->data->stars = $stars;
						return $this->addObject();
				}
		}
		public function getFeedbacks(array $params = array()) {
				return $this->searchObject(array_merge(array(
						'type' => 'user',
						'subtype' => 'feedback'
				), $params));
		}
		public function feedbackExists() {
				$user = ossn_loggedin_user();
				if(!$user){
					return false;	
				}
				return $this->searchObject(array(
						'type' => 'user',
						'subtype' => 'feedback',
						'owner_guid' => $user->guid
				));
		}
}