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
 	$wall       = new OssnWall;
	$posts = $wall->searchObject(array(
				'type' => 'user',
				'distinct' => true,
				'subtype' => 'wall',
				'limit' => 3,
				'order_by' => 'o.guid DESC',
				'entities_pairs' => array(
							array(
								'name' => 'access',
								'value' => 2,
						   ),
				),

	));
	if(!ossn_isLoggedin()){
		echo "<style>.comments-likes {min-height:0px;}</style>"	;	
	}
?>
<div class="row">
	   <div class="col-md-3">
    	<?php 
			$contents = ossn_view_form('login2', array(
            		'id' => 'ossn-login',
           			'action' => ossn_site_url('action/user/login'),
        	));
			echo ossn_plugin_view('widget/view', array(
						'title' => ossn_print('site:login'),
						'contents' => $contents,
			));			
			$contents = ossn_plugin_view('social_theme/newusers');
			echo ossn_plugin_view('widget/view', array(
						'class' => 'new-users-widget',
						'title' => ossn_print('users'),
						'contents' => $contents,
			));	
			if(com_is_active('OssnGroups')){
				$contents = ossn_plugin_view('social_theme/groups');
				echo ossn_plugin_view('widget/view', array(
							'class' => 'new-groups-wiget',
							'title' => ossn_print('groups'),
							'contents' => $contents,
				));
			}
			?>	       			
       </div>   
		<div class="col-md-5 home-left-contents">
			<?php
				if($posts) {
					foreach($posts as $post) {
						$item = ossn_wallpost_to_item($post);
						echo ossn_wall_view_template($item);
					}
				}
			?>
 	   </div>   
       <div class="col-md-4">
    	<?php 
			$contents = ossn_view_form('signup', array(
        					'id' => 'ossn-home-signup',
        				'action' => ossn_site_url('action/user/register')
	   	 	));
			$heading = "<p>".ossn_print('its:free')."</p>";
			echo ossn_plugin_view('widget/view', array(
						'title' => ossn_print('create:account'),
						'contents' => $heading.$contents,
			));
			
			?>	       			
       </div>     
</div>