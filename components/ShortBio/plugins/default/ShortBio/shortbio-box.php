<?php
// check if there's a shortbio at all?
if (isset($params['user']->ShortBio) && !empty($params['user']->ShortBio)) {
	// yes!
	$pencil_html = '';
	if (ossn_loggedin_user() && ossn_loggedin_user()->guid == $params['user']->guid) {
		// if it is our own short bio ...
		if ($params['user']->ShortBioViewOwn == 'checked') {
			// prepare some extra html displaying a pencil with a link forwarding to the account settings page
			// as suggested by Dominik L.
			$pencil_html = '&nbsp;&nbsp;<a href="' . current_url(true) . '/edit?section=com_shortbio"><i class="fa fa-pencil-alt" title="' . ossn_print('edit') . '"></i></a>';
		} else {
			// no view - no pencil 
			// simply do nothing and
			return;
		}
	}
	// in any other case display the shortbio
	// (plus pencil on own page)
	?>
	<div class="ossn-wall-container">
		<div class="tabs-input">
			<div class="wall-tabs">
				<li class="item">
					<i class="fa fa-info-circle"><span><?php echo ossn_print('com:shortbio:label') . $pencil_html; ?></span></i>
				</li>
			</div>
		</div>
		<div class="ossn-wall-container-data">
			<?php 
			// using nl2br() for output here
			// because saved textarea newlines need to be converted to html <br>
			echo nl2br($params['user']->ShortBio); ?>
		</div>
	</div>
<?php
}
