<?php
/**
 * Open Source Social Network
 *
 * @package   OSSN
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (c) OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0 https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com
 */
namespace Hashtag;
class Trending extends \OssnBase {
		private $table   = 'ossn_hashtags_trending';
		private $columns = array(
				'hid',
				'subject_guid',
				'hashtag',
				'subject_type',
		);

		public function save(array $data = array()) {
				if(!isset($data['subject_guid'])) {
						return false;
				}
				//unset ID autoincrement
				unset($this->columns[0]);
				$db = new \OssnDatabase();
				return $db->insert(array(
						'into'   => $this->table,
						'names'  => $this->columns,
						'values' => array(
								$data['subject_guid'],
								$data['hashtag'],
								'object:wall',
						),
				));
		}
		public function clearAll() {
				$db = new \OssnDatabase();
				$db->statement("TRUNCATE TABLE {$this->table};");
				return $db->execute();
		}
		public function getBySubjectGuid($subject_guid) {
				if(empty($subject_guid)) {
						return false;
				}
				return $this->search(array(
						'wheres'     => "subject_guid={$subject_guid}",
						'page_limit' => false,
				));
		}
		public function deleteBySubjectGuid($subject_guid) {
				if(empty($subject_guid)) {
						return false;
				}
				$database = new \OssnDatabase();
				return $database->delete(array(
						'from'   => $this->table,
						'wheres' => array(
								"subject_guid={$subject_guid}",
						),
				));
		}
		public function topTrends() {
				$database = new \OssnDatabase();
				$vars     = array(
						'from'       => $this->table,
						'params'     => array(
								'count(*) as total, hashtag',
						),
						'wheres'     => array(
								'hid IS NOT NULL',
						),
						'group_by'   => 'hashtag',
						'order_by'   => 'total DESC',
						'limit'      => HASHTAG_TRENDING_LIMIT,
						'page_limit' => HASHTAG_TRENDING_LIMIT,
				);
				return $database->select($vars, true);
		}
		public function search(array $params = array()) {
				$database = new \OssnDatabase();

				$default = array(
						'page_limit' => 10,
						'limit'      => false,
						'order_by'   => 'hid DESC',
						'offset'     => input('offset', '', 1),
						'wheres'     => 'hid IS NOT NULL',
				);
				$options = array_merge($default, $params);
				//validate offset values
				if(!empty($options['limit']) && !empty($options['page_limit'])) {
						$offset_vals = ceil($options['limit'] / $options['page_limit']);
						$offset_vals = abs($offset_vals);
						$offset_vals = range(1, $offset_vals);
						if(!in_array($options['offset'], $offset_vals)) {
								return false;
						}
				}
				//get only required result, don't bust your server memory
				$getlimit = $database->generateLimit($options['limit'], $options['page_limit'], $options['offset']);
				if($getlimit) {
						$vars['limit'] = $getlimit;
				} else {
						$vars['limit'] = $options['limit'];
				}
				if(isset($options['wheres']) && !empty($options['wheres'])) {
						if(!is_array($options['wheres'])) {
								$wheres[] = $options['wheres'];
						} else {
								foreach($options['wheres'] as $witem) {
										$wheres[] = $witem;
								}
						}
				}
				$vars['from']   = $this->table;
				$vars['wheres'] = array(
						$database->constructWheres($wheres),
				);
				if(isset($params['count']) && $params['count'] === true) {
						unset($vars['params']);
						unset($vars['limit']);
						$count['params'] = array(
								'count(*) as total',
						);
						$count = array_merge($vars, $count);
						return $database->select($count)->total;
				}
				$vars['order_by'] = $options['order_by'];
				$data             = $database->select($vars, true);
				if($data) {
						return $data;
				}
				return false;
		}
}