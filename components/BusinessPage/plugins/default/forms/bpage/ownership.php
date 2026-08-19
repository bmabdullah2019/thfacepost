<input type="text" placeholder="<?php echo ossn_print('bpage:select:friends'); ?>" name="friend" id="bpage-friend-input" />
<div class="mt-2">
	<input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-danger btn-sm"  />
    <input type="hidden" name="guid" value="<?php echo $params['page']->guid;?>" />
</div>
<script>
	$(document).ready(function() {
		if (typeof $.fn.tokenInput === 'function') {
			$("#bpage-friend-input").tokenInput(Ossn.site_url + "friendpicker", {
				placeholder: Ossn.Print('bpage:select:friends'),
				hintText: false,
				propertyToSearch: "first_name",
				tokenLimit: 1,
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