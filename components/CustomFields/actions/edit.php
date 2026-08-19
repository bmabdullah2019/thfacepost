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
 				'field_placeholder' => input('field_placeholder'),
				'field_options' => input('field_options', "", false),
 				'show_on_signup' => input('show_on_signup'),
 				'is_required' => input('is_required'),
				'show_label' => input('show_label'),
				'show_on_about' => input('show_on_about'),
				'field_type' => input('field_type'),
				'field_name' => strtolower(input('field_name')),				

  );
 $guid = input('guid');

 $optioninput = array('radio', 'dropdown');
 if(isset($options['field_type']) && in_array($options['field_type'], $optioninput) && empty($options['field_options'])){
			ossn_trigger_message(ossn_print('customfield:fields:required:b'), 'error');
			redirect(REF);
 }

 $fields = new CustomFields(array(
			$options 								 
 ));
 if($fields->editField($guid)){
			ossn_trigger_message(ossn_print('customfield:fields:edited'));
			redirect(REF);			 
 } else {
			ossn_trigger_message(ossn_print('customfield:fields:edit:failed'), 'error');
			redirect(REF);
}
