//<script>
Ossn.add_hook('ajax', 'request:settings', 'multiupload_check');
var multiple_photos_files = [];

function multiupload_check(hook, type, ret, params) {
	if (ret['url'].includes('action/wall/post/a') || ret['url'].includes('action/wall/post/g') || ret['url'].includes('action/wall/post/u') || ret['url'].includes('action/wall/post/bpage')) {
		$text = $('#ossn-wall-form').find('textarea').val();
		fileAmmountCheck = $('.multiple-upload-container').attr('data-count');
		if (fileAmmountCheck && fileAmmountCheck > 1) {
			if ($text == '') {
				Ossn.trigger_message(Ossn.Print('multiupload:text'), 'error');
				return {
					action: false,
				};
			}
		}
		//clear data
		$('.multiple-upload-container').attr('data-count', 0);
		$('.multiple-upload-container').html("");
		multiple_photos_files = [];
	}
	return ret;
}
$(document).ready(function() {
	var form = $('#ossn-wall-form');
	if (form.length > 0) {
		form.find('input[name="ossn_photo"]').attr('name', 'multiphotos[]').attr('multiple', 'multiple').attr('id', 'multipleupload-wall');
	}
	$('#ossn-wall-photo').append('<div class="multiple-upload-container" data-count="0"></div>');

	var _imagesPreview = function(input) {
		if (input) {
			var filesAmount = input.length;
			$('.multiple-upload-container').attr('data-count', filesAmount);

			for (var i = 0; i < filesAmount; i++) {
				var reader = new FileReader();

				reader.onload = (function(file) {
					return function(event) {
						var template = '<div class="multiple-upload-item"><span data-name="' + file.name + '" class="multiple-upload-remove-item"><span>x</span></span><img src="' + event.target.result + '" /></div>';
						$('.multiple-upload-container').append(template);
					};
				})(input[i]);
				reader.readAsDataURL(input[i]);
			}
		}

	};

	var imagesPreview = function(input) {
		if (input.files) {
			var filesAmount = input.files.length;
			$('.multiple-upload-container').attr('data-count', filesAmount);

			for (var i = 0; i < filesAmount; i++) {
				var reader = new FileReader();

				reader.onload = (function(file) {
					return function(event) {
						var template = '<div class="multiple-upload-item"><span data-name="' + file.name + '" class="multiple-upload-remove-item"><span>x</span></span><img src="' + event.target.result + '" /></div>';
						$('.multiple-upload-container').append(template);
					};
				})(input.files[i]);
				reader.readAsDataURL(input.files[i]);
			}
		}

	};
	$('body').on('click', '.multiple-upload-remove-item', function() {
		var name = $(this).attr('data-name');
		var buffer_second = new DataTransfer();
		var buffer = [];
		//remmove from array list
		for (let i = 0; i < multiple_photos_files.length; i++) {
			if (name !== multiple_photos_files[i].name) {
				buffer.push(multiple_photos_files[i]);
				buffer_second.items.add(multiple_photos_files[i]);
			}
		}
		multiple_photos_files = buffer;

		document.getElementById("multipleupload-wall").files = buffer_second.files;
		$(this).parent().fadeOut().remove();
	});

	$('#multipleupload-wall').on('change', function(e) {
		$('.multiple-upload-container').html("");

		for (var i = 0; i < this.files.length; i++) {
			var found_in_files = false;

			for (var j = 0; j < multiple_photos_files.length; j++) {
				//ignore duplicate file names
				if (multiple_photos_files[j].name == this.files[i].name) {
					found_in_files = true;
					break;
				}
			}

			if (found_in_files) {
				continue;
			}
			multiple_photos_files.push(this.files[i]);
		}

		_imagesPreview(multiple_photos_files);

		var buffer = new DataTransfer();
		for (let i = 0; i < multiple_photos_files.length; i++) {
			buffer.items.add(multiple_photos_files[i]);
		}
		document.getElementById("multipleupload-wall").files = buffer.files;
	});
});