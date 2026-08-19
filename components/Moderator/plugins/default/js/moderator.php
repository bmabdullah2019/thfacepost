/** 
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
Ossn.RegisterStartupFunction(function() {
	$(document).ready(function() {
		$('.profile-menu-extra-delete_user').on('click', function(e) {
			e.preventDefault();
			var del = confirm(Ossn.Print('ossn:exception:make:sure'));
			if (del == true) {
				var actionurl = $(this).attr('href');
				window.location = actionurl;
			}
		});
		if ($('select#can_moderate').length) {
			var html = '<div id="can_moderate" class="dropdown-block" style="display:none;">' + $('select#can_moderate').closest('div').html() + '</div>';
			$('select#can_moderate').closest('div').remove();
			$('select[name=type]').closest('div').after(html);
			can_moderate();
		}
		$('select[name=type]').on('change', function() {
			can_moderate();
		});
		$('#can_moderate').prepend("<label>" + Ossn.Print('moderator') + "</label>");
	});

	function can_moderate() {
		var value = $('select[name=type]').val();
		if (value == 'normal') {
			$('div#can_moderate').show();
		} else {
			$('select#can_moderate').val('no');
			$('div#can_moderate').hide();
		}
	}
});