<?php
/**
 * Open Source Social Network
 * @link      https://www.opensource-socialnetwork.org/
 * @package   Short Bio
 * @author    Michael Zülsdorff <ossn@z-mans.net>
 * @copyright (C) Michael Zülsdorff
 * @license   GNU General Public License https://www.gnu.de/documents/gpl-2.0.en.html
 */

$user = ossn_loggedin_user();
if ($user) {
	// retrieve input from the submitted form's textarea field named 'shortbio' and the checkbox
	// and pass it to the extended user object using ->data->YOUR_ATTRIBUTE_NAME
	$user->data->ShortBio = input('shortbio');
	$user->data->ShortBioViewOwn = input('shortbioview');
	// if ->data->YOUR_ATTRIBUTE_NAME doesn't exist yet it will be created automatically
	// otherwise it will be updated on save
	$user->save();
	ossn_trigger_message(ossn_print('com:shortbio:saved:message'), 'success');
}
// reload visited page
redirect(REF);
