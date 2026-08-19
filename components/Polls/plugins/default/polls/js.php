//<script>
Ossn.RegisterStartupFunction(function() {
    $(document).ready(function() {
    		$('body').on('click', '.ossn-wall-container-menu-polls', function(){
					Ossn.redirect('polls/add');																  
			});
    		$('body').on('click', '.ossn-wall-container-menu-pollsgroup', function(){
					$url = window.location.href;
					$url = $url.replace(Ossn.site_url, '');
					$url = $url.replace('group/', '');
					Ossn.redirect('polls/add/group/'+$url);																  
			});		
    		$('body').on('click', '.ossn-wall-container-menu-pollsbusinesspage', function(){
					$url = window.location.href;
					$url = $url.replace(Ossn.site_url, '');
					$url = $url.replace('page/view/', '');
					$url = $url.replace('company/', '');
					Ossn.redirect('polls/add/businesspage/'+$url);																  
			});					
			var $counter = 1;
    		$('body').on('click', '#polls-add-option', function(){
					$counter++;
					$('.polls-form-options').append('<input type="text" name="poll_options_'+$counter+'" placeholder="'+Ossn.Print('polls:option:title')+'" />');				
					//due to #1474 we need to add different methods for these polls options as array breaks
					//i don't wanted to call input directly in action due to #1474
					$('#polls-form-counter').val($counter);
			});
        ///	$('.poll-embed').on('click', function(e) {
			////	$guid = $(this).attr('data-guid');
         //	   	Ossn.MessageBox('polls/embedcode?guid='+$guid);
       		//});
		$('body').on('change', '.ossn-polls-item input[type="checkbox"]', function(){
				var val = $(this).val();
				$(this).closest(".poll-container").find('input[name="option"]').val(val);
				$(this).closest(".poll-container").find('input[type="checkbox"]').not(this).prop('checked', false);
		});
		$('body').on('click', '.polls-show-voters', function(e){
				if(e.target.tagName != 'INPUT'){
					 var guid = $(this).data('guid');
					 var option = $(this).data('option');
					 if(guid && option){
						 Ossn.MessageBox('polls/voters/' + guid + '/'+ option);
					 }
				}
		});
    });
});
Ossn.poll = function($guid){
	var $form = '.ossn-polls-form-questions-'+$guid;
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