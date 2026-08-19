<?php
/**
 * Open Source Social Network
 * @link      https://www.opensource-socialnetwork.org/
 * @package   Short Bio
 * @author    Michael Zülsdorff <ossn@z-mans.net>
 * @copyright (C) Michael Zülsdorff
 * @license   GNU General Public License https://www.gnu.de/documents/gpl-2.0.en.html
 */

// display the 'shortbio-input' form on the account settings 'Short Bio' tab
echo ossn_view_form('shortbio-input',
	array(
		'action' => ossn_site_url() . 'action/shortbio/save',
		'component' => 'ShortBio',
		'id' => 'shortbio-form',
		'params' => array(
			'user' => $params['user']
		),
	),
	false
);
?>
