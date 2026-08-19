<?php
	if(!ossn_isLoggedin()){
		return;
	}
?>
        <div class="sidebar">
            <div class="sidebar-contents">

           		 <?php
					echo ossn_view_form('search', array(
								'component' => 'OssnSearch',
								'class' => 'ossn-search',
								'autocomplete' => 'off',
								'method' => 'get',
								'security_tokens' => false,
								'action' => ossn_site_url("search"),
					), false);				 
          			  if (ossn_is_hook('newsfeed', "sidebar:left")) {
                			$newsfeed_left = ossn_call_hook('newsfeed', "sidebar:left", NULL, array());
               				 echo implode('', $newsfeed_left);
            		}
           		 ?>                
            </div>
        </div>