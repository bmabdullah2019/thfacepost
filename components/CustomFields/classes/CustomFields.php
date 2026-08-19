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
class CustomFields extends OssnObject {
		public function __construct(array $options = array()) {
				$this->cfVars = array();
				if (!empty($options)) {
						foreach ($options as $key => $item) {
								if (is_array($item)) {
										if (!in_array($item->field_name, $this->invalidNames())) {
												$object = (object)$item;
												if (isset($object->field_options)) {
														$object->field_options = explode(',', $object->field_options);
														$object->field_options = array_map('trim', $object->field_options);
												}
												$this->cfVars[$key] = $object;
										}
								}
						}
				}
		}
		public function addField() {
				$this->type       = 'site';
				$this->subtype    = 'custom:fields';
				$this->owner_guid = 1;
				if (isset($this->cfVars[0]->field_name) && !empty($this->cfVars[0]->field_type) && !empty($this->cfVars[0]->field_placeholder)) {
						if ($exists = $this->isExists($this->cfVars[0]->field_name)) {
								return $exists;
						}
						$this->data					= new stdClass();
						$this->data->field_name     = $this->cfVars[0]->field_name;
						$this->data->field_type     = $this->cfVars[0]->field_type;
						$this->data->placeholder    = $this->cfVars[0]->field_placeholder;
						$this->data->show_on_signup = $this->cfVars[0]->show_on_signup;
						$this->data->is_required    = $this->cfVars[0]->is_required;
						$this->data->show_on_about  = $this->cfVars[0]->show_on_about;
						$this->data->show_label     = $this->cfVars[0]->show_label;
						
						if ($this->cfVars[0]->field_type == 'radio' || $this->cfVars[0]->field_type == 'dropdown') {
								if (!isset($this->cfVars[0]->field_options) || empty($this->cfVars[0]->field_options)) {
										return false;
								}
								$this->data->field_options = json_encode($this->cfVars[0]->field_options);
						}
						return $this->addObject();
				}
				return false;
		}
		public function editField($guid = '') {
				$object = ossn_get_object($guid);
				if (!$object) {
						return false;
				}
				if (!empty($this->cfVars[0]->field_placeholder)) {
						$object->data->placeholder    = $this->cfVars[0]->field_placeholder;
						$object->data->show_on_signup = $this->cfVars[0]->show_on_signup;
						$object->data->is_required    = $this->cfVars[0]->is_required;
						$object->data->show_on_about  = $this->cfVars[0]->show_on_about;
						$object->data->show_label     = $this->cfVars[0]->show_label;
						$object->data->field_type	  = $this->cfVars[0]->field_type;
						if ($this->cfVars[0]->field_type == 'radio' || $this->cfVars[0]->field_type == 'dropdown') {
								if (!isset($this->cfVars[0]->field_options) || empty($this->cfVars[0]->field_options)) {
										return false;
								}
								$object->data->field_options = json_encode($this->cfVars[0]->field_options);
						}
						return $object->save();
				}
				return false;
		}
		private function isExists($name = '') {
				if (empty($name)) {
						return false;
				}
				$field = $this->searchObject(array(
						'type' => 'site',
						'subtype' => 'custom:fields',
						'owner_guid' => 1,
						'entities_pairs' => array(
								array(
										'name' => 'field_name',
										'value' => $name
								)
						)
				));
				if ($field) {
						return $field[0];
				}
				return false;
		}
		public function getFields() {
				return $this->searchObject(array(
						'type' => 'site',
						'subtype' => 'custom:fields',
						'owner_guid' => 1,
						'page_limit' => false
				));
		}
		public function showOnSignup() {
				if (isset($this->show_on_signup) && $this->show_on_signup == 'yes') {
						return true;
				}
				return false;
		}
		public function showOnAbout() {
				if (isset($this->show_on_about) && $this->show_on_about == 'yes') {
						return true;
				}
				return false;
		}
		public function isRequired() {
				if (isset($this->is_required) && $this->is_required == 'yes') {
						return true;
				}
				return false;
		}
		public function showLabel() {
				if (isset($this->show_label) && $this->show_label == 'yes') {
						return true;
				}
				return false;
		}
		public function getFiledType() {
				return $this->field_type;
				
		}
		public function getArgs() {
				if (isset($this->field_name)) {
						$options = array(
								'name' => $this->field_name,
								'label' => $this->placeholder,
								'placeholder' => $this->placeholder,
								'data-guid' => $this->guid
						);
						$langauge = ossn_site_settings('language');
						$label_translation = $this->placeholder;
						
						if($langauge != 'en'){
							$label_translation = ossn_print("customfields:translate:{$this->field_name}");
							$options['label'] = $label_translation;	
							$options['placeholder'] = $label_translation;	
						}
						
						if ($this->field_name == 'birthdate') {
								$options['placeholder'] = ossn_print('birthdate');
								$options['label'] = ossn_print('birthdate');
						}
						if ($this->showLabel()) {
								$options['label'] = $label_translation;
						} else {
							    unset($options['label']);
						}						
						if (isset($this->field_options) && !empty($this->field_options)) {
								$field_options = json_decode($this->field_options);
								if ($field_options) {
										$options['options'] = array();
										foreach ($field_options as $option) {
												if ($this->field_name == 'gender') {
														$options['options'][$option] = ossn_print($option);
														continue;
												}
												$options['options'][$option] = $option;
												//translate if it is not in english
												if($langauge != 'en'){
														$translate_key = strtolower(str_replace(' ', '', $option));
														$options['options'][$option] = ossn_print("customfield:val:translate:{$translate_key}");
												}
										}
								}
						}
						return $options;
				}
				return false;
		}
		public function inputTypes() {
				return array(
						'text' => ossn_print('customfield:textbox'),
						'dropdown' => ossn_print('customfield:dropdownbox'),
						'radio' => ossn_print('customfield:radio'),
						'textarea' => ossn_print('customfield:textarea')
				);
		}
		public function yesNoInput() {
				return array(
						'yes' => ossn_print('customfield:yes'),
						'no' => ossn_print('customfield:no')
				);
		}
		public function invalidNames() {
				return array(
						'first_name',
						'last_name',
						'email',
						'username',
						'type',
						'password',
						'salt',
						'activation',
						'last_login',
						'last_activity',
						'time_created',
						'can_moderate',
				);
		}
}