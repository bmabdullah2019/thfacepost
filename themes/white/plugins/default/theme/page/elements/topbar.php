<?php 
$custom_settings = multipurpose_theme_get_custom_logos_bgs_setting();
if(isset($custom_settings) && isset($custom_settings['logo_site'])){
      	$logourl =  ossn_add_cache_to_url(ossn_theme_url("logos_backgrounds/logo_site_{$custom_settings['logo_site']}"));
	 } else {
        $logourl = ossn_theme_url() . 'images/logo.png';                        
} 
?> 
<?php if(!ossn_isLoggedin()){ ?>
<div class="topbar site-name-sm">
	<div class="container-fluid">
    	<div class="site-name-container">
        	<div class="">
                <span onclick="Ossn.redirect('');"><img class="site-logo" src="<?php echo $logourl;?>" /></span>
            </div>
            <div class="right d-none d-sm-inline-block">
                    <a href="<?php echo ossn_site_url();?>login" class="btn btn-primary login-topbar"><?php echo ossn_print('site:login');?></a>
                    <a href="<?php echo ossn_site_url();?>login" class="btn btn-success register-topbar"><?php echo ossn_print('site:register');?></a>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php
	$hide_loggedin = '';
	if(!ossn_isLoggedin()){		
		$hide_loggedin = "d-xs-none d-sm-none";
		return;
	}
?>
<!-- ossn topbar -->
<div class="topbar site-name-sm">
	<div class="container-fluid">
    	<div class="site-name-container">
        	<div class="site-name text-center">
                <span onclick="Ossn.redirect('');"><img class="site-logo" src="<?php echo $logourl;?>" /></span>
            </div>
        </div>
        <div class="search-topbar-sm">
                <?php
				echo ossn_view_form('search', array(
								'component' => 'OssnSearch',
								'class' => 'ossn-search',
								'tabindex' => 0,
								'autocomplete' => 'off',
								'method' => 'get',
								'security_tokens' => false,
								'action' => ossn_site_url("search"),
					), false);
				?>             
        </div>
   </div>
</div>
<div class="topbar">
	<div class="container-fluid">
			<div class="left-side d-inline-block">
				<?php if(ossn_isLoggedin()){ ?>
				<div class="topbar-menu-left">
					<li id="sidebar-toggle" data-toggle='0'>
						<a role="button" data-target="#"> <i class="fa fa-th-list"></i></a>
					</li>
					<li id="topbar-search" class="d-inline-block d-sm-none" data-toggle='0'>
						<a role="button" data-target="#"> <i class="fa fa-search"></i></a>
					</li>                    
                <?php
				echo ossn_view_form('search', array(
								'component' => 'OssnSearch',
								'class' => 'ossn-search d-none d-md-inline-block',
								'tabindex' => 0,
								'autocomplete' => 'off',
								'method' => 'get',
								'security_tokens' => false,
								'action' => ossn_site_url("search"),
					), false);
				?>                   
				</div>
				<?php } ?>
			</div>
			<div class="d-none d-lg-inline-block site-name<?php echo $hide_loggedin;?>">
                <span onclick="Ossn.redirect('');"><img class="site-logo-logged" src="<?php echo $logourl;?>" /></span>
			</div>
			<div class="text-right right-side d-inline-block">
				<div class="topbar-menu-right">
					<ul>
                	<li class="user-image-name d-none d-md-inline-block">
                    	<img src="<?php echo ossn_loggedin_user()->iconURL()->smaller;?>" />
                        <span onclick="Ossn.redirect('u/<?php echo ossn_loggedin_user()->username;?>');"><?php echo ossn_loggedin_user()->first_name;?></span></a>
                    </li>                    
					<li class="ossn-topbar-dropdown-menu">
						<div class="dropdown">
						<?php
							if(ossn_isLoggedin()){						
								echo ossn_plugin_view('output/url', array(
									'role' => 'button',
									'data-bs-toggle' => 'dropdown',
									'data-bs-target' => '#',
									'text' => '<i class="fa fa-sort-down"></i>',
								));									
								echo ossn_view_menu('topbar_dropdown'); 
							}
							?>
						</div>
					</li>                
					<?php
						if(ossn_isLoggedin()){
							echo ossn_plugin_view('notifications/page/topbar');
						}
						?>
					</ul>
				</div>
			</div>
       </div>     
</div>
<!-- ./ ossn topbar -->
