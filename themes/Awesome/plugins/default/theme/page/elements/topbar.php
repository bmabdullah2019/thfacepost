<?php if(!ossn_isLoggedin()){ ?>
<nav class="navbar <?php echo oa_theme_get_settings('topbarparent_nav');?> visible-xs">
    <div class='site-name-small site-name-nl' onclick="window.location='<?php echo ossn_site_url();?>';">
			<?php echo ossn_site_settings('site_name');?>
    </div>	
</nav>
<?php } ?>
<?php if(ossn_isLoggedin()){ ?>
<nav class="topbar-parent navbar navbar-expand-lg navbar-light <?php echo oa_theme_get_settings('topbarparent_nav');?>"  role="navigation">
	<div class="container">
    <div class='site-name-small visible-xs'>
	    <div onclick="window.location='<?php echo ossn_site_url();?>';" class="site-name"><?php echo ossn_site_settings('site_name');?></div>
    </div>
   	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navigationbar" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
     			<i class="fa fa-bars fa-lg"></i>
    </button>    
	<div class="collapse navbar-collapse" id="navigationbar">
		<ul class="navbar-nav">
			<?php 
				if(ossn_isLoggedin()){
						echo  ossn_view_sections_menu('newsfeed');
				}
				?>
		</ul>
		<ul class="nav navbar-nav ms-auto">
			<li class="nav-item oa-topbar-user-metadata hidden-xs">
				<img src="<?php echo ossn_loggedin_user()->iconURL()->smaller; ?>" width="" height="" />
				<span class="username-full"><a href="<?php echo ossn_loggedin_user()->profileURL(); ?>"><?php echo ossn_loggedin_user()->first_name; ?></a></span>
			</li>
			<li class="nav-item dropdown ossn-topbar-dropdown-menu oa-topbar-user-metadata">
				<?php
					if(ossn_isLoggedin()){						
						echo ossn_plugin_view('output/url', array(
											'class' => 'nav-link dropdown-toggle',
											'data-bs-toggle' => 'dropdown',
											'data-bs-target' => '#',
											'text' => '',
											'aria-haspopup' => "true",
											'aria-expanded' => "false",
											'id' => 'topbar-user-menu-dropdown',
						));									
						echo ossn_view_menu('topbar_dropdown'); 
					}
					?>        
			</li>
		</ul>
	</div>
    </div>
</nav>
<?php } ?>
<!-- ossn topbar -->
<div class="topbar">
	<div class="container">
		<div class="topbar-inner">
			<div class="row">
				<div class="col-md-4 hidden-xs">
					<div class="site-logo" onclick="window.location='<?php echo ossn_site_url();?>';"><img src="<?php echo ossn_theme_url();?>images/logo.png" class="img-responsive" /></div>
				</div>
				<div class="col-md-8 text-right">
					<?php
						if(ossn_isLoggedin()){ 
							echo ossn_view_form('search', array(
								'component' => 'OssnSearch',
								'class' => 'ossn-search',
								'autocomplete' => 'off',
								'method' => 'get',
								'security_tokens' => false,
								'action' => ossn_site_url("search"),
							), false);
						}
						?>
					<?php
						if(!ossn_isLoggedIn()){ 
							echo ossn_view_form('login_topbar', array(
						    							'id' => 'ossn-login',
								'class' => 'ossn-login-form text-right',
								'format' => true,
						   								'action' => ossn_site_url('action/user/login'),
							));
						} else { ?>
					<div class="topbar-menu-right">             
						<?php
							if(ossn_isLoggedin()){
								echo ossn_plugin_view('notifications/page/topbar');
							}
							?>
					</div>
					<?php	}
						?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- ./ ossn topbar -->