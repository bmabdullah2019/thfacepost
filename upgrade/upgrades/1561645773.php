<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
$new_components = array(
		'Kernel',
		'BanUser',
		'Birthdays',
		'Categories',
		'Censorship',
		'MobileLogin',
		'PhoneNumbers',
		'Sentiment',
		'SiteOffline',
		'OpenTeknik',
		'Styler',
		'Videos',
		'SocialLogin',
		'EMembers',
		'SharePost',
		'AccessCode',
		'CustomFields',
		'Moderator',
		'Hangout',
		'UserVerified',
		'LinkPreview',
		'Report',
		'HashTag',
		'FamilyRelationships',						
		'Announcement',
		'BusinessPage',
		'Polls',
		'Feedback',
		'FirstLogin',
		'GDPR',
		'GroupInvite',
		'Hangout',
		'PasswordValidation',
		'MenuBuilder',
		'RememberLogin',
		'Events',
		'PrivateNetwork',
		'Stories',
);

$components = new OssnComponents;
$systemcoms = $components->getComponents();

foreach($new_components as $item) {
		if(!in_array($item, $systemcoms)) {
				$components->enable($item);
				if($item == 'Feedback'){
						$components->DISABLE($item); //disable Feedback by default
				}	
		}
}