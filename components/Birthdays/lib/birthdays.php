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
/**
 * Get user friend birthdays
 *
 * @param object  $user OssnUser object
 * @param integer $inmonth Birthday in x months
 * @param integer $limit Default limit is 5
 *
 * @return array
 */
function ossn_get_upcomming_birthdays($user = '', $inmonths = 4, $limit = 5) {
		if($user instanceof OssnUser) {
				$friends = $user->getFriends();
				if($friends) {
						foreach($friends as $item) {
								$guids[] = $item->guid;
						}
				}
				$months   = array();
				$months[] = date('m', time());
				if($inmonths <= 1) {
						$inmonths = $inmonths - 1;
				}
				foreach(range(1, $inmonths) as $item) {
						if(empty($dd)) {
								$dd = time();
						} else {
								$dd = strtotime("+1 MONTH", $dd);
						}
						$months[] = date('m', strtotime("+1 MONTH", $dd));
				}
				$months = implode(',', $months);
				if(!empty($guids)) {
						$guids = implode(',', $guids);
						$time  = time();
						return $user->searchUsers(array(
								'entities_pairs' => array(
										
										array(
												'name' => 'birthdate',
												'wheres' => "MONTH(STR_TO_DATE(emd0.value, '%d/%m/%Y')) IN ({$months})",
												'value' => true
										)
								),
								'limit' => $limit,
								'order_by' => "emd0.value DESC",
								'wheres' => "u.guid IN ({$guids})"
						));
				}
		}
		return false;
}