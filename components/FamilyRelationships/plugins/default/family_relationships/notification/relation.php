<?php
$baseurl        = ossn_site_url();
$user           = ossn_user_by_guid($params->poster_guid);
$user->fullname = "<strong>{$user->fullname}</strong>";
$img            = "<div class='notification-image'><img src='{$baseurl}avatar/{$user->username}/small' /></div>";
		$type           = "<div class='ossn-groups-notification-icon'></div>";
		if($params->viewed !== NULL) {
				$viewed = '';
		} elseif($params->viewed == NULL) {
				$viewed = 'class="ossn-notification-unviewed"';
		}
		// lead directly to groups request page
		$loggedin 		   = ossn_loggedin_user();
		$url               = "{$baseurl}familyrelationships/check";
		$notification_read = "{$baseurl}notification/read/{$params->guid}?notification=" . urlencode($url);
?>
<a href='<?php echo $notification_read;?>' class='ossn-group-notification-item'>
	    <li <?php echo $viewed;?>> 
           <?php echo $img;?>
		   <div class='notfi-meta'>
           <?php echo $type;?>
		   		<div class='data'>
					<?php echo ossn_print("ossn:notifications:{$params->type}", array(
							$user->fullname,
						  ));
					?>
       		 </div>
		   </div>
       </li>
</a>