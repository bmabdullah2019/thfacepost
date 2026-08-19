<?php
$custom_settings = multipurpose_theme_get_custom_logos_bgs_setting();
$time = time();
?>
 <fieldset class="titleform">
 	<div class="alert alert-warning">
    	<?php echo ossn_print('theme:goblue:browercache');?>
    </div>	
 	<div>	
    	<label><?php echo ossn_print('theme:goblue:logo:site');?> (Height 60px - 500 KB PNG Max) </label>
        <input type="file" name="logo_site" />
        <div class="logo-container-goblue">
        		<?php
					if(isset($custom_settings) && isset($custom_settings['logo_site'])){
						$logo_url = ossn_add_cache_to_url(ossn_theme_url("logos_backgrounds/logo_site_{$custom_settings['logo_site']}"));
					} else {
						$logo_url = ossn_add_cache_to_url(ossn_theme_url("images/logo.png?v={$time}"));
					}
				?>
            	<img src="<?php echo $logo_url;?>" />
        </div>
    </div>
  	<div>	
    	<label><?php echo ossn_print('theme:goblue:logo:admin');?> (180x45 - 500 KB JPG Max)</label>
        <input type="file" name="logo_admin" />
        <div class="logo-container-goblue">
        		<?php
					if(isset($custom_settings) && isset($custom_settings['logo_admin'])){
						$logo_url = ossn_add_cache_to_url(ossn_theme_url("logos_backgrounds/logo_admin_{$custom_settings['logo_admin']}"));
					} else {					
						$logo_url = ossn_add_cache_to_url(ossn_theme_url("images/logo_admin.jpg?v={$time}"));
					}
				?>
            	<img src="<?php echo $logo_url;?>" />   
        </div>
    </div>  
  	<div>	
    	<label><?php echo ossn_print('theme:white:homepage:image');?> (PNG, JPG - 500KB Max)</label>
        <input type="file" name="screenhome_lite" />
        <div class="logo-container-goblue">
        		<?php
					if(isset($custom_settings) && isset($custom_settings['screenhome_lite'])){
						$screen = ossn_add_cache_to_url(ossn_theme_url("logos_backgrounds/screenhome_lite_{$custom_settings['screenhome_lite']}"));
					} else {					
						$screen = ossn_add_cache_to_url(ossn_theme_url("images/screen.png"));
					}
				?>
            	<img src="<?php echo $screen;?>" />                     
        </div>
    </div>      
  	<div>	
    	<label><?php echo ossn_print('theme:white:homepage:image:dark');?> (PNG, JPG - 500KB Max)</label>
        <input type="file" name="screenhome_dark" />
        <div class="logo-container-goblue">
        		<?php
					if(isset($custom_settings) && isset($custom_settings['screenhome_dark'])){
						$screendark = ossn_add_cache_to_url(ossn_theme_url("logos_backgrounds/screenhome_dark_{$custom_settings['screenhome_dark']}"));
					} else {					
						$screendark = ossn_add_cache_to_url(ossn_theme_url("images/screen-dark.png"));
					}
				?>
            	<img src="<?php echo $screendark;?>" />                     
        </div>
    </div>          
    <div>
        <label><?php echo ossn_print('theme:white:latestmember:widget');?></label>    
        <?php
			$SiteSettings = new OssnSite;
			$com_white_theme_members_widget = $SiteSettings->getSettings('com_white_theme_members_widget');
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'com_white_theme_members_widget',
					'value' => $com_white_theme_members_widget,
					'options' => array(
						'enabled' => ossn_print('admin:button:enabled'),
					 	'disabled' => ossn_print('admin:button:disabled'),
					),
			));
		?>  	
    </div>    
    <div>
    	<label><?php echo ossn_print('theme:white:default:mode');?></label>    	
        <?php
			$SiteSettings = new OssnSite;
			$com_white_theme_mode = $SiteSettings->getSettings('com_white_theme_mode');
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'com_white_theme_mode',
					'value' => $com_white_theme_mode,
					'options' => array(
						'litemode' => ossn_print('theme:white:litemode'),
					 	'darkmode' => ossn_print('theme:white:darkmode'),
					),
			));
		?>  	
    </div>  
    <div>
    	<label><?php echo ossn_print('theme:white:default:mode:field');?></label>    	
        <?php
			$SiteSettings = new OssnSite();
			$com_white_theme_mode_field_signup = $SiteSettings->getSettings('whitetheme_mode_field_signup');
			if(!$com_white_theme_mode_field_signup){
				$com_white_theme_mode_field_signup = 'no';	
			}
			echo ossn_plugin_view('input/dropdown', array(
					'name' => 'whitetheme_mode_field_signup',
					'value' => $com_white_theme_mode_field_signup,
					'options' => array(
						'yes' => ossn_print('theme:white:yes'),
					 	'no' => ossn_print('theme:white:no'),
					),
			));
		?>  	
    </div>            
	<input type="submit" class="btn btn-success btn-sm" value="<?php echo ossn_print('save');?>"/>
    <a href="<?php echo ossn_site_url("action/theme/multipurpose/settings/logos_bgs_reset", true);?>" class="btn btn-danger d-inline-block right btn-sm"><i class="fa-solid fa-rotate"></i> <?php echo ossn_print('theme:white:lbgs:reset');?></a>    
</fieldset>