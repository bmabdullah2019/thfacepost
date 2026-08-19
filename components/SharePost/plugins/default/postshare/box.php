<?php
echo ossn_view_form('sharepost/form', array(
		'action' => ossn_site_url('action/post/share'),
		'id' => 'share-post-form',
		'params' => $params,
));