<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */

$notif = $params['notification'];

$user = ossn_user_by_guid($notif->poster_guid);
$group = ossn_get_group_by_guid($notif->subject_guid);

$text = ossn_print("ossn:notifications:{$notif->type}", array($user->fullname, $group->title));
echo ossn_plugin_view('notifications/template/view', array(
				'iconURL'   => $user->iconURL()->small,
				'customprint' => $text,
				'guid'      => $notif->guid,
				'type'      => $notif->type,
				'viewed'    => $notif->viewed,
				'icon_type' => 'groupinvite',
				'instance'  => $notif,
				'fullname'  => $user->fullname,
));