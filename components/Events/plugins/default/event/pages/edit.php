<div class="ossn-page-contents">
	<div class="events">
			<?php
					echo ossn_view_form('event/edit', array(
							'action' => ossn_site_url() . 'action/event/edit',
							'params' => $params,
					));
			?>
	</div>
</div>