<?php
if(ossn_isLoggedin() && ossn_loggedin_user()->guid != $params['user']->guid){ ?>
<script>
$(document).ready(function() {
	 	var mobilebutton = '<a href="javascript:void(0);"  data-guid="<?php echo $params['user']->guid;?>" data-name="<?php echo $params['user']->first_name;?>" class="vidconverse-mobile-btn vidconverse-button-widget d-inline d-sm-none" style="margin-right: 5px;"><i class="fas fa-video"></i><?php echo ossn_print('vidconverse:callnow');?></a>';
		$('.ossn-profile #profile-menu').prepend(mobilebutton);
		setInterval(function(){
  				Ossn.PostRequest({
                		url: Ossn.site_url + 'action/vidconverse/status?guid=<?php echo $params['user']->guid;?>',
                		beforeSend: function(){},
                		callback: function(callback) {
							if($('.vidconverse-button-widget').length > 0){
									if(callback == 1){
										$('.vidconverse-button-widget').removeClass('vidconverse-button-widget-disabled');	
										$('.vidconverse-widget').find('.offline').hide();										
									} else {
										$('.vidconverse-button-widget').addClass('vidconverse-button-widget-disabled');
										$('.vidconverse-widget').find('.offline').show();										
									}
					   	 }
					}
            	});								 
		}, 5000);
		$code = <?php echo json_encode(ossn_plugin_view('vidconverse/widget', $params));?>;
		$('.ossn-profile-sidebar .ossn-profile-modules').prepend($code);
});
</script>
<?Php } ?>