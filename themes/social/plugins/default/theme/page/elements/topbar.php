<?php
	$center_name = 'text-center site-name';
	$hide_loggedin = '';
	if(ossn_isLoggedin()){		
		$hide_loggedin = "d-none d-lg-inline-block";
		$center_name = 'site-name left-side left';
	}
?>
<!-- ossn topbar -->
<div class="topbar">
	<div class="container">
			<li class="d-inline-block <?php echo $center_name;?> <?php echo $hide_loggedin;?>">
				<span><a href="<?php echo ossn_site_url();?>"><?php echo ossn_site_settings('site_name');?></a></span>
			</li>
			<div class="d-inline-block topbar-menu-right topbar-social-theme-notification">
				<li><a class="topbar-home-mobile d-block d-sm-none" href="<?php echo ossn_site_url();?>"><i class="fa fa-home"></i></a></li>
                <?php
						if(ossn_isLoggedin()){
							echo ossn_plugin_view('notifications/page/topbar');
						}
						?>
			</div>
			<div class="d-inline-block text-right right-side">
				<div class="topbar-menu-right">
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
                    <?php if(ossn_isLoggedin()){ ?>
                    	<div class="userdetails-topbar d-none d-md-inline-block">
	                    	<div class="topbar-username"><a href="<?php echo ossn_loggedin_user()->profileURL();?>"><?php echo ossn_loggedin_user()->fullname;?></a></div>
    	                    <div class="topbar-subtitle"><?php echo ossn_print('site:index');?>!</div>
                        </div>
                        <div class="userimage-topbar d-none d-md-inline-block">
                        	<a href="<?php echo ossn_loggedin_user()->profileURL();?>"><img src="<?php echo ossn_loggedin_user()->iconURL()->small;?>" /></a>
                        </div>
					<?php } ?>
				</div>
			</div>
	</div>
</div>
<!-- ./ ossn topbar -->