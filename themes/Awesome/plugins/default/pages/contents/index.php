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
?>
<div class="os-page-contents-inner">
<div class="row ossn-awesome-landing-row">
		<div class="col-md-6 home-left-contents">
            <div class="description">
            		<?php 
					  $settings = oa_theme_object();
					  if($settings){
						  $settings = $settings[0];
						  echo html_entity_decode($settings->description, ENT_QUOTES, 'UTF-8');  
					  } else {
							echo "You can change text in administrator panel -> Themes -> Awesome Theme"; 
					  }
					  ?>
            </div>
            <div class='oa-before-login'>
				<?php echo ossn_fetch_extend_views('forms/login2/before/submit'); ?>
			</div>
 	   </div>   
       <div class="col-md-5 offset-md-1">
       	<div class="oa-user-circle"><i class="fa fa-user-circle-o"></i></div>
    	<?php 
			$contents = ossn_view_form('signup', array(
        					'id' => 'ossn-home-signup',
							'class' => 'oa-landing-form',
        				'action' => ossn_site_url('action/user/register')
	   	 	));
			echo $contents;
			?>	       			
       </div>     
</div>	
</div>
