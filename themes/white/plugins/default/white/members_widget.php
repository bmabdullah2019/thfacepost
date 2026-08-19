<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */

$users = new OssnUser;
$attr = array(
	'keyword' => false,
	'order_by' => 'guid DESC',
	'limit' => 12,
	'page_limit' => 12,
	'offset' => 1
);
$users = $users->searchUsers($attr);
echo "<div class='latest-members-widget'>";	 
foreach($users as $user) {
	$user_name = $user->first_name . ' ' . $user->last_name;
	if (com_is_active('DisplayUsername')) {
		$user_name = $user->username;
	} ?>
	<div class="com-latestmembers-widget-memberlist" data-balloon="<?php echo $user_name; ?>" data-balloon-pos="up">
	<a class="com-latestmembers-memberlist-item"
	href="<?php echo ossn_site_url() . 'u/' . $user->username; ?>">
	<img class="user-img" src="<?php echo $user->iconURL()->small;?>"></a>
	</div>
<?php
}
echo "</div>";