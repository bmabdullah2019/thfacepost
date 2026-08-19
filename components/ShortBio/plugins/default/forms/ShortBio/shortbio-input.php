<?php
/**
 * Open Source Social Network
 * @link      https://www.opensource-socialnetwork.org/
 * @package   Short Bio
 * @author    Michael Zülsdorff <ossn@z-mans.net>
 * @copyright (C) Michael Zülsdorff
 * @license   GNU General Public License https://www.gnu.de/documents/gpl-2.0.en.html
 */

if (isset($params['user']->ShortBio) && !empty($params['user']->ShortBio)) {
	$shortbio_data = $params['user']->ShortBio;
} else {
	$shortbio_data = '';
}
if (!isset($params['user']->ShortBioViewOwn)) {
	// set then checkbox to 'checked' by default
	// when visiting this page for the first time and nothing has been saved yet
	$shortbio_view_own = 'checked';
} else {
	// otherwise set checkbox according to saved setting
	$shortbio_view_own = $params['user']->ShortBioViewOwn;
}
?>
<div>
	<label><?php echo ossn_print('com:shortbio:label'); ?></label>
	<textarea placeholder="<?php echo ossn_print('com:shortbio:placeholder'); ?>" name="shortbio"><?php echo $shortbio_data; ?></textarea>
</div>
<div>
	<br>
	<input type="checkbox" name="shortbioview" value="checked" <?php echo ' ' . $shortbio_view_own;?> /> 
	<?php echo ossn_print('com:shortbio:checkbox:label');?>
</div>
<br>
<input class="btn btn-primary btn-sm" type="submit" value="<?php echo ossn_print('save'); ?>" />

