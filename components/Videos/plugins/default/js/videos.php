//<script>
Ossn.videoProgress = function($token) {
	Ossn.PostRequest({
		url: Ossn.site_url + 'video/progress/' + $token,
		callback: function(callback) {
			// console.log('progress: ' , callback);
			// don't display that strange '1% convert' while uploading is still unfinished
			if(callback['progress'] > 1) {
				$('.progress-bar-conversion').css('width', callback['progress'] + '%');
				$('.progress-bar-conversion').find('span').html(callback['progress'] + '%');
			}
			if(callback['status'] == 'done'){
				$('.progress-bar-conversion').css('width', '100%');
				$('.progress-bar-conversion').find('span').html('100%');
				window.location.reload();
			}
			if(callback['progress'] >= 100 || callback['status'] == 'failed'){
				window.location.reload();	
			}
		},
	});
}
Ossn.videoRound = function (value, precision, mode) {

  var m, f, isHalf, sgn; // helper variables
  precision |= 0; // making sure precision is integer
  m = Math.pow(10, precision);
  value *= m;
  sgn = (value > 0) | -(value < 0); // sign of the number
  isHalf = value % 1 === 0.5 * sgn;
  f = Math.floor(value);

  if (isHalf) {
    switch (mode) {
      case 'PHP_ROUND_HALF_DOWN':
        value = f + (sgn < 0); // rounds .5 toward zero
        break;
      case 'PHP_ROUND_HALF_EVEN':
        value = f + (f % 2 * sgn); // rouds .5 towards the next even integer
        break;
      case 'PHP_ROUND_HALF_ODD':
        value = f + !(f % 2); // rounds .5 towards the next odd integer
        break;
      default:
        value = f + (sgn > 0); // rounds .5 away from zero
    }
  }

  return (isHalf ? value : Math.round(value)) / m;
};

function video_error_message($message){
		$('#video-errors').removeClass('hidden');
		$('#video-errors').html($message);	
}
$(document).ready(function() {		
	if($.isFunction($.fn.mediaelementplayer)){
		$('video').mediaelementplayer({
			success: function(player, node) {
				$('#' + node.id + '-mode').html('mode: ' + player.pluginType);
			}	
		});
	}
	var	$url = Ossn.site_url + 'action/video/add';
	$('body').on('click', '#video-add-button', function(){
		$('#video-errors').html("");
		$('#video-errors').addClass('hidden');														
		
		//check if title, description and other fields are set
		if(!$('#video_title').val().length || !$('#video_description').val().length || !$('#container_type').val().length || !$('#container_guid').val().length) {
			video_error_message(Ossn.Print('video:com:fields:requred'));
			return false;
		} 
		$('#video-add').trigger('submit');
	});

	Ossn.ajaxRequest({
			url: $url,
			action: true,
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("loadstart", function(evt) {
					console.log('upload started');
					$('.video-upload').show();
					$('.video-upload').find('.progress').show();
				}, false);

				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						$percentage = Ossn.videoRound((percentComplete*100));
						$('.video-upload').find('.progress-bar').css('width', $percentage + '%');
						$('.video-upload').find('.progress-bar span').html($percentage + '%');
					}
				}, false);
				
				xhr.upload.addEventListener("load", function(evt) {
					// upload has finished successfully - display conversion state bar now
					console.log('upload finished');
					// mp4s will not  be converted - no bar necessary
					//mp4 must be converted to lower format $arsalan 9/13/2021
					//if($("#video_file")[0].files[0].type != 'video/mp4') {
						$('#video-add').find('.conversion').show();
						$('#video-add').find('.conversion').find('.progress').show();

						$token = $('#video-add').find('input[name="vtk"]').val();
						// increased update interval to make sure we see the last chunk of 100% before proceeding to view page
					//}
				}, false);
				
				return xhr;
			},
			containMedia: true,
			form: '#video-add',

			beforeSend: function(request) {
				$('.video-submit').hide();
			},
			callback: function(callback) {
				if (callback['error']) {
					//console.log('cbE');
					video_error_message(callback['error']);
					$('.video-submit').show();
					$('.video-upload').hide();
					$('#video-add').find('.conversion').hide();
					$('#video-add').find('.conversion').find('.progress').hide();
					return false;
				}
				if (callback['success']) {
					//console.log('cbS');
					Ossn.trigger_message(callback['success']);
					if (callback['data']['url']) {
						//console.log('cbs -redir', callback['data']['url']);
						Ossn.redirect(callback['data']['url']);
					}
				}
			}
	});

	// Check allowed video types in advance (before starting upload)
	$('body').on('change', "#video_file", function(){
		var allowedTypes = ['video/mp4', 'video/mpeg', 'video/3gp', 'video/x-ms-wmv', 'video/x-msvideo', 'video/quicktime', 'video/avi'];
		var file = this.files[0];
		var fileType = file.type;
		if(!allowedTypes.includes(fileType)) {
			$("#video_file").val('');
			video_error_message(Ossn.Print('video:com:mp4:files'));
			return false;
		}
	});
});