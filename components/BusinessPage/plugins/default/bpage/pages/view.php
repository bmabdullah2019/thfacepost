<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
$cover_top = '';
if(!empty($params['page']->cover_top)){
	$cover_top = "top:{$params['page']->cover_top};";
}
$cover_left = '';
if(!empty($params['page']->cover_left)){
	$cover_left = "left:{$params['page']->cover_left};";
}
$like = new \Ossn\Component\BusinessPage\Likes;
$total_likes = $like->CountLikes($params['page']->guid);
if(empty($total_likes)){
	$total_likes = 0;	
}
$cats = business_page_categories();
$cover_rh = '';
if(isset($params['page']->cover_entity_guid) && empty($params['page']->cover_entity_guid) || !isset($params['page']->cover_entity_guid)){
	$cover_rh = ' d-none';	
}
?>
<div class="container business-page">
	<div class="top-container">
		<div id="container" class="business-page-cover">
			<div class="business-page-cover-controls">                          
				<?php		
					if(ossn_isLoggedin() && ($params['page']->owner_guid == ossn_loggedin_user()->guid || ossn_loggedin_user()->canModerate())){     
					?>
				<a href="javascript:void(0);" onclick="Ossn.Clk('.coverfile');" class="btn-action change-cover" id="change-bpcover-btn">
				<?php echo ossn_print('bpage:change:cover');?>
				</a>
				<a href="javascript:void(0);" id="reposition-cover-page" class="btn-action reposition-cover <?php echo $cover_rh;?>">
				<?php echo ossn_print('bpage:reposition');?>
				</a>
				<a href="javascript:void(0);" id="save-position-cover-page" class="btn-action reposition-cover">
				<?php echo ossn_print('bpage:save:position');?>
				</a>              
				<?php } ?>
			</div>
			<?php
				echo ossn_view_form('bpage/cover', array(
						'action' => ossn_site_url('action/bpage/cover/add'),
						'params' => $params,
						'id' => 'upload-cover-page',
					 ))
				?>
			<img id="draggable" class="business-page-cover-img" src="<?php echo $params['page']->coverURL();?>" data-guid="<?php echo $params['page']->guid;?>" style='<?php echo $cover_top; ?><?php echo $cover_left; ?>' />
		</div>
		<div class="business-page-metadata d-md-flex">
			<div class="business-page-photo d-flex justify-content-center align-items-center">
				<img data-fancybox href="<?php echo $params['page']->photoURL('master');?>" class="logo-page" src="<?php echo $params['page']->photoURL();?>" />
				<?php if(ossn_isLoggedin() && ($params['page']->owner_guid == ossn_loggedin_user()->guid || ossn_loggedin_user()->canModerate())){ ?>
				<div class="upload-photo" style="cursor:pointer;">
					<span onclick="Ossn.Clk('#bpage-upload-photo-btn');" title="Change Photo"><i class="fa-solid fa-camera"></i></span>
					<?php 
						echo ossn_view_form('bpage/photo', array(
							'action' => ossn_site_url('action/bpage/photo/add'),
							'params' => $params,
							'id' => 'upload-photo-page',
							));
						?>
				</div>
				<?php } ?>                                 
			</div>
			<div class="subdata">
				<div class="name"><?php echo $params['page']->title;?></div>
				<div class="page-likes"><?php echo ossn_print('bpage:likes', [business_page_like_formatter($total_likes)]);?></div>
			</div>
			<div class="buttons ms-auto d-flex justify-content-center align-items-center">
            	<?php
				if(ossn_isLoggedin() && ossn_loggedin_user()->guid  == $params['page']->owner_guid){ ?>
				<a href="<?php echo ossn_site_url("page/edit/{$params['page']->guid}");?>"  class="btn btn-secondary me-2"><i class="fas fa-pencil"></i><?php echo ossn_print('bpage:edit');?></a>
                <?php } ?>
				<?php
					if(ossn_isLoggedin()){ ?>
				<?php if(!$like->isLiked($params['page']->guid, ossn_loggedin_user()->guid)){ ?>
				<a href="javascript:void(0);" class="btn btn-primary change-cover like-page" data-guid="<?php echo $params['page']->guid;?>">
				<i class="fa fa-thumbs-up"></i><?php echo ossn_print('ossn:like');?>
				</a>             
				<?php } else { ?>
				<a href="javascript:void(0);" class="btn btn-warning change-cover unlike-page" data-guid="<?php echo $params['page']->guid;?>">
				<i class="fa fa-thumbs-down"></i><?php echo ossn_print('ossn:unlike');?>
				</a>                            
				<?php } 
					}?> 
			</div>
		</div>
	</div>

	<div class="bpage-menu-hr-container">
					<div id='bpage-hr-menu' class="bpage-hr-menu d-none d-lg-block">
						<?php echo ossn_plugin_view('menus/business_page_profile', array('menu_width' => 60)); ?>
					</div>
					<div id='bpage-hr-menu' class="bpage-hr-menu d-none d-md-block d-lg-none">
						<?php echo ossn_plugin_view('menus/business_page_profile', array('menu_width' => 40)); ?>
					</div>
					<div id='bpage-hr-menu' class="bpage-hr-menu d-none d-sm-block d-md-none">
						<?php echo ossn_plugin_view('menus/business_page_profile', array('menu_width' => 25)); ?>
					</div>
					<div id='bpage-hr-menu' class="bpage-hr-menu d-block d-sm-none">
						<?php echo ossn_plugin_view('menus/business_page_profile', array('menu_width' => 1)); ?>
					</div>
     </div>
                 
	<?php echo ossn_plugin_view('bpage/view/bottom', $params); ?>
</div>
<script>$('.ossn-system-messages .col-lg-11').removeClass('col-lg-11').addClass('col-lg-12');</script>