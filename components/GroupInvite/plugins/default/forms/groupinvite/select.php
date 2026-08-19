<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
?>
<p><?php echo ossn_print('groupinvite:widget:desc');?></p>
<div class="groupinvite">
	<input type="text" placeholder="<?php echo ossn_print('tag:friends'); ?>" name="friends-input" id="group-invite-input" />
</div>
<input type="hidden" name="group" value="<?php echo $params['group']->guid;?>" />
<input type="submit" class="btn btn-success btn-sm" value="<?php echo ossn_print('groupinvite:invite');?>" />
<script>
	$(document).ready(function() {
		if (typeof $.fn.tokenInput === 'function' && $('#group-invite-input').length > 0) {
			var picker_type = $('#group-invite-input').attr('data-type');
			var tag_type = "";
			var $placeholder = Ossn.Print('tag:friends');

			$("#group-invite-input").tokenInput(Ossn.site_url + "friendpicker" + tag_type, {
				placeholder: $placeholder,
				hintText: false,
				propertyToSearch: "first_name",
				resultsFormatter: function(item) {
					return "<li>" + "<img src='" + item.imageurl + "' title='" + item.first_name + " " + item.last_name + "' height='25px' width='25px' />" + "<div style='display: inline-block; padding-left: 10px;'><div class='full_name' style='font-weight:bold;color:#2B5470;'>" + item.first_name + " " + item.last_name + "</div></div></li>"
				},
				tokenFormatter: function(item) {
					return "<li><p>" + item.first_name + " " + item.last_name + "</p></li>"
				},
			});
		}
	});
</script>