<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE  https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */
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
if ($init->setSettings('CustomFields', $ini)) {
		ossn_trigger_message(ossn_print('customfield:fields:initlized'));
		redirect(REF);
}
redirect(REF);