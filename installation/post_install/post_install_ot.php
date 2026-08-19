<?php
$site = new OssnSite();
$site->setSetting('theme', 'white');

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
		'MultiUpload',
		'UserStatsWidget',
);

$components = new OssnComponents;
$systemcoms = $components->getComponents();

foreach($new_components as $item) {
		if(!in_array($item, $systemcoms)) {
				$components->enable($item);
				if($item == 'Feedback' || $item == 'AccessCode'){
						$components->DISABLE($item); //disable Feedback by default
				}	
		}
}

$options = array(
		array(
				'field_name' => 'birthdate',
				'field_placeholder' => 'Birthdate',
				'field_options' => false,
				'field_type' => 'text',
				'show_on_signup' => 'yes',
				'is_required' => 'yes',
				'show_label' => 'no',
				'show_on_about' => 'yes'
		),
		array(
				'field_name' => 'gender',
				'field_placeholder' => 'Gender',
				'field_options' => "male, female",
				'field_type' => 'radio',
				'show_on_signup' => 'yes',
				'is_required' => 'yes',
				'show_label' => 'no',
				'show_on_about' => 'yes'
		)
);
//call the file custom fields
require_once(dirname(dirname(dirname(__FILE__))) . '/components/CustomFields/classes/CustomFields.php');

foreach ($options as $option) {
		$fields = new CustomFields(array(
				$option
		));
		$fields->addField();
}
$init = new OssnComponents;
$ini  = array(
		'init' => true
);
$init->setSettings('CustomFields', $ini);