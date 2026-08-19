<p>
<?php
				echo ossn_view_form('search', array(
								'component' => 'OssnSearch',
								'class' => 'ossn-search',
								'tabindex' => 0,
								'autocomplete' => 'off',
								'method' => 'get',
								'security_tokens' => false,
								'action' => ossn_site_url("search"),
					), false);
?>
</p>