//<script>
$(document).ready(function() {
	if ($('.ossn-wall-container-data').length) {
		$('.ossn-wall-container-data').append("<div class='link-preview-container'></div>");
	}
	$('body').on('click', '.ossn-wall-post', function() {
		$('.ossn-wall-container-data .link-preview-container').html(" ");
	});
	$('body').on('change', '.ossn-wall-container-data textarea', function() {
		//chaange above to 	$('.ossn-wall-container-data textarea').bind('input propertychange', function() { if you wanted to detect change while typing but that is not realiable way as incomplete URLS can be passed to fetcher.

		$('.ossn-wall-container-data .link-preview-container').html(" ");
		$text = $(this).val();
		var urls = $text.match(/https?:\/\/[^\s]+/g);
		if (urls) {
			var $urls = Array.prototype.slice.apply(urls);
			$urls = $urls[0].split(' ');
			$url = $urls.pop();
			if ($url != '') {
				Ossn.PostRequest({
					url: Ossn.site_url + 'link_preview',
					params: '&text='+$text,
					beforeSend: function(request) {
						$('.ossn-wall-container-data .link-preview-container').html('<div class="ossn-loading"></div>');
					},
					callback: function(callback) {
						if (callback['contents']) {
							$('.ossn-wall-container-data .link-preview-container').html(callback['contents']);
						} else {
							$('.ossn-wall-container-data .link-preview-container').html(" ");
						}

					}
				});
			}
		} else {
			$('.ossn-wall-container-data .link-preview-container').html(" ");
		}

	});
	var $wall_request_completed = false;
	$(document).ajaxSuccess(function(event, xhr, settings) {
		var aurl = Ossn.ParseUrl(settings.url).path;
		if (aurl == '/action/wall/post/a' || aurl == '/action/wall/post/u' || aurl == '/action/wall/post/g') {
			$('.ossn-wall-container-data .link-preview-container').html(" ");
			$wall_request_completed = true;
		}
	});
	$(document).ajaxSuccess(function(event, xhr, settings) {
		var aurl = Ossn.ParseUrl(settings.url).path;
		if (aurl == '/link_preview' && $wall_request_completed == true) {
			$('.ossn-wall-container-data .link-preview-container').html(" ");
			$wall_request_completed = false;
		}
	});
});