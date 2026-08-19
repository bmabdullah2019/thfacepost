<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
$custom_settings = multipurpose_theme_get_custom_logos_bgs_setting();
if(isset($custom_settings) && isset($custom_settings['screenhome_dark'])){
      	$screenhome_dark =  ossn_add_cache_to_url(ossn_theme_url("logos_backgrounds/screenhome_dark_{$custom_settings['screenhome_dark']}"));
	 } else {
        $screenhome_dark = ossn_theme_url() . 'images/screen-dark.png';                        
}  
if(isset($custom_settings) && isset($custom_settings['screenhome_lite'])){
      	$screenhome_lite =  ossn_add_cache_to_url(ossn_theme_url("logos_backgrounds/screenhome_lite_{$custom_settings['screenhome_lite']}"));
	 } else {
        $screenhome_lite = ossn_theme_url() . 'images/screen.png';                        
}  
?>
<script>
	$(document).ready(function(){
		$('footer').find('.col-md-11').addClass('col-md-12').removeClass('col-md-11');			
		$('body').on('click', '.login-topbar, .register-topbar', function(e){
					e.preventDefault();
					$('.home-right-image').toggle();
					$('.home-right-login').toggle();
					$('.register-topbar').toggle();
					$('.login-topbar').toggle();
		});		
		$('body').on('click', '.login-topbar-xs, .register-topbar-xs', function(){
					$('.home-right-image').toggle();
					$('.home-left-contents').toggle();
					$('.home-right-login').toggle();	
					$('.register-topbar-xs').toggle();
					$('.login-topbar-xs').toggle();
		});
	});
</script>
<style>
.home-left-contents {
    margin-top: 20px;
}
</style>
<div class="row">
	<div class="col-md-12">
<div class="welcome-text">
	<div class="welcome"><?php echo ossn_print('home:top:heading', array(ossn_site_settings('site_name'))); ?></div>
    <p><?php echo ossn_print('home:top:sub:heading');?></p>
    <p class="d-block d-sm-none"><a href="javascript:void(0);" class="btn btn-primary login-topbar-xs btn-lg">Login</a><a href="javascript:void(0);" class="btn btn-primary register-topbar-xs btn-lg">Register</a></p>
</div>    
    </div>
</div>	
<div class="row ossn-page-contents">
		<div class="col-md-7 home-left-contents">
        <?php
				$SiteSettings = new OssnSite;
				$com_white_theme_mode = $SiteSettings->getSettings('com_white_theme_mode');
				if(!empty($com_white_theme_mode) && $com_white_theme_mode == 'darkmode'){ 
				?>
	                   <img src="<?php echo $screenhome_dark;?>" class="img-fluid" />           
               <?php 
				} else { 
				?>
	               <img src="<?php echo $screenhome_lite;?>" class="img-fluid" />           
          <?php } ?>
 	    </div>      
        <div class="col-md-5">
			<div class="home-right-contents">
            			<div class="home-right-image">
       					<?php 
							$contents = ossn_view_form('signup', array(
        						'id' => 'ossn-home-signup',
        						'action' => ossn_site_url('action/user/register')
	   	 					));
							echo ossn_plugin_view('widget/view', array(
								'title' => ossn_print('create:account'),
								'contents' => $contents,
							));
			
						?>	                      	
                        </div>
                        <div class="home-right-login">
                        	<?php
							$contents = ossn_view_form('login2', array(
            						'id' => 'ossn-login',
           							'action' => ossn_site_url('action/user/login'),
        					));	
							echo ossn_plugin_view('widget/view', array(
								'title' => ossn_print('site:login'),
								'contents' => $contents,
							));											
							?>
                        </div>
            </div>
        </div>
</div>	