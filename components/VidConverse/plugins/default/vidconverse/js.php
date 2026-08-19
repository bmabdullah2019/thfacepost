//<script>
Ossn.register_callback('ossn', 'init', 'vid_converse_setup_call_btn');

function vid_converse_setup_call_btn() {
	$(document).ready(function() {
		$('body').on('click', '.vidconverse-button-widget:not(.vidconverse-button-widget-disabled)', function() {
			$ismobile = false;
			if ($('.vidconverse-mobile-btn').is(':visible')) {
				$ismobile = true;
				$('.vidconverse-mobile-btn').attr("class", "vidconverse-mobile-btn btn btn-success d-inline d-sm-none vidconverse-button-widget-disabled");
				$('.vidconverse-mobile-btn').html(Ossn.Print('vidconverse:call:startingmobiletext'));
			}
			var guid = $(this).attr('data-guid');
			var name = $(this).attr('data-name');
			Ossn.PostRequest({
				url: Ossn.site_url + 'action/vidconverse/setup/call?guid=' + guid,
				beforeSend: function() {
					$('.vidconverse-widget .button a').hide();
					$('.vidconverse-widget .button .ossn-loading').show();
				},
				callback: function(callback) {
					$('.vidconverse-widget .button').html("<div class='result'></div>");
					if (callback['status'] == true) {
						$('.vidconverse-widget .button .result').append("<li>" + Ossn.Print('vidconverse:call:initiated') + "</li>");
						$('.vidconverse-widget .button .result').append('<li>' + Ossn.Print('vidconverse:user:notified', [name]) + '</li>');
						$('.vidconverse-widget .button .result').append("<li>" + Ossn.Print('vidconverse:you:notified') + "</li>");

						if ($ismobile == false) {
							window.open(callback['caller_url'], 'popUpWindow', 'height=720,width=1280,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
						}
						if ($ismobile) {
							window.location = callback['caller_url'];
						}
					} else {
						if ($ismobile) {
							alert(Ossn.Print('vidconverse:call:error'));
						}
						$('.vidconverse-widget .button .result').append("<li class='text-danger'>" + Ossn.Print('vidconverse:call:error') + "</li>");
					}
				}
			});

		});
		$('body').on('click', '.vidconverse-accept-btn', function() {
			$ismobile = false;
			if ($('.vidconverse-message-accept-mobile').is(':visible')) {
				$ismobile = true;
			}
			$hash = $(this).attr('data-hash');
			if($ismobile == false){
				window.open($hash, 'popUpWindow', 'height=720,width=1280,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
			} else {
				window.location = $hash;
			}
		});
	});
}