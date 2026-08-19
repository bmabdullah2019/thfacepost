<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OPENTEKNIK LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE  https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */
$options = array(
		'field_name'        => strtolower(input('field_name')),
		'field_placeholder' => input('field_placeholder'),
		'field_options'     => input('field_options', '', false),
		'field_type'        => input('field_type'),
		'show_on_signup'    => input('show_on_signup'),
		'is_required'       => input('is_required'),
		'show_label'        => input('show_label'),
		'show_on_about'     => input('show_on_about'),
);
if($options['show_on_signup'] == 'yes' && $options['is_required'] == 'no') {
		//$options['show_on_signup'] = 'no';
}
if(empty($options['field_name']) || empty($options['field_type'])) {
		ossn_trigger_message(ossn_print('customfield:fields:required:a'), 'error');
		redirect(REF);
}
$optioninput = array(
		'radio',
		'dropdown',
);
if(isset($options['field_type']) && in_array($options['field_type'], $optioninput) && empty($options['field_options'])) {
		ossn_trigger_message(ossn_print('customfield:fields:required:b'), 'error');
		redirect(REF);
}
if(!preg_match('/^[A-Za-z]+$/i', $options['field_name'])) {
		ossn_trigger_message(ossn_print('customfield:fields:required:c'), 'error');
		redirect(REF);
}
$fields = new CustomFields(array(
		$options,
));
if($fields->addField()) {
		ossn_trigger_message(ossn_print('customfield:fields:added'));
		redirect(REF);
} else {
		ossn_trigger_message(ossn_print('customfield:fields:add:failed'), 'error');
		redirect(REF);
}