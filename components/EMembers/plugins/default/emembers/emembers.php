<div class="row">
	<div class="col-md-11 ossn-page-contents">
    	<?php
			if(!empty($params['count'])){
				echo ossn_plugin_view('output/users', array(
						'users' => $params['entities'],											
				));
				echo ossn_view_pagination($params['count']);
			}
		?>
    </div>
</div>