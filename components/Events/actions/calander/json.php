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

header('Content-type: application/json; charset=utf-8');
$is_group = input('is_group');
$is_businesspage = input('is_businesspage');

$events   = new Events();
$args     = array(
		'page_limit' => false,
);
if(!empty($is_group)) {
		$args['entities_pairs'] = array(
				array(
						'name'  => 'container_type',
						'value' => 'group',
				),
				array(
						'name'  => 'container_guid',
						'value' => $is_group,
				),
		);
} elseif(!empty($is_businesspage)){
		$args['entities_pairs'] = array(
				array(
						'name'  => 'container_type',
						'value' => 'businesspage',
				),
				array(
						'name'  => 'container_guid',
						'value' => $is_businesspage,
				),
		);	
} else {
		$args['entities_pairs'] = array(
				array(
						'name'  => 'container_type',
						'value' => 'user',
				),
		);
}
$list    = $events->getEvents($args);
$results = array();
if($list) {
		foreach ($list as $event) {
				$date  = explode('/', $event->date);
				$sdate = "{$date['2']}-{$date['0']}-{$date['1']}";
				$color = '#B29DD9';
				if($date['1'] == date('d')) {
						$color = '#FE6B64';
				}
				$results[] = array(
						'id'            => $event->guid,
						'start'         => $sdate . ' ' . date('H:i', strtotime($event->start_time)),
						'end'           => $sdate . ' ' . date('H:i', strtotime($event->end_time)),
						'title'         => html_entity_decode($event->title, ENT_QUOTES, 'UTF-8'),
						'color'         => $color,
						'extendedProps' => array(
								'url' => $event->profileURL(),
						),
				);
		}
}
echo json_encode($results, JSON_PRETTY_PRINT);
exit();