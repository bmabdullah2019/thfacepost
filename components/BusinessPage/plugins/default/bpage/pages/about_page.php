<div class="col-lg-11">
<div class="ossn-page-contents">
	<a class="btn btn-success btn-sm mb-2" href="<?php echo $params['page']->getURL();?>"><?php echo ossn_print('back');?></a>
	<?php
		$params['full'] = true;
		echo ossn_plugin_view('bpage/pages/about', $params);
	?>
</div>
</div>