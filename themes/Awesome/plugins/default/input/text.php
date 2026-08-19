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

$class = 'ossn-text-input form-control ';
if(isset($params['class'])){
	$class = $class . $params['class'].' ';
}
$defaults = array(
	'value' => '',
	'disabled' => false,
	'class' => $class,
	'type' => 'text'
);

$params = array_merge($defaults, $params);
$params['class'] = $class;
$attributes = ossn_args($params);

echo "<input {$attributes} />";