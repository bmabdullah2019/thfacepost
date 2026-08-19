//<script>
Ossn.register_callback('ossn', 'init', 'share_post_box');
function share_post_box(){
	$(document).ready(function(){
			$('body').on('click', '.share-object-select', function(e){
				$guid = $(this).attr('data-guid');
				$type = $(this).attr('data-type');
				Ossn.MessageBox('sharepost/'+$type+'/?guid='+$guid);
			});
			$('body').on('change', '#sharepost-select-type', function(){
						$type = $(this).val();
						switch($type){
							 case 'newsfeed':
							 	$('#sharepost-friends').hide();
								$('#sharepost-groups').hide();
							 	break;
							  case 'group':
							  	$('#sharepost-friends').hide();
								$('#sharepost-groups').show();
							  	break;
							  case 'friend':
							  	$('#sharepost-friends').show();
								$('#sharepost-groups').hide();							  							 		
							  	break;
							default:
								$('#sharepost-friends').hide();
								$('#sharepost-groups').hide();
						}
			});
	});
}
Ossn.pollShared = function($guid){
	var $form = '.ossn-poll-item-main-shared-'+$guid+' > form[data-guid="'+$guid+'"]';
	Ossn.ajaxRequest({
            url: Ossn.site_url + "action/poll/vote",
            form: $form,
            beforeSend: function() {
               	$($form).find('.ossn-poll-loading-submit').show();
                $($form).find('input[type="submit"]').hide();
            },
            callback: function(callback) {
                if (callback['data']['status'] == true) {
               		 $('.ossn-poll-item-main-' + $guid).each(function() {
                 		  $(this).html(callback['data']['option']);
              	 	 });
                    Ossn.trigger_message(callback['success']);
                }
                if (['data']['status']  == false) {
                    Ossn.trigger_message(callback['error'], 'error');
                }
            }
        });				
};