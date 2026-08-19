<div class="ossn-page-contents">
	<div class="events">
			<?php
					echo ossn_view_form('event/add', array(
							'action' => ossn_site_url() . 'action/event/add',
							'params' => $params,
					));
			?>
	</div>
</div>