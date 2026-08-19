<?php
function mySiteUrl() {
	global $Ossn;
	return $Ossn->url;
}
?>
<script>
$(document).ready(function() {
	// add Home button to UnloggedinMenu
	var extraHomeButton = '<a class="nav-link" href="' + Ossn.site_url + '">' + Ossn.Print("com:UnloggedinMenuHomeButton:home") + '</a>'	
	$('.navbar-nav').prepend(extraHomeButton);

	// remove the not working Login/Register toggle of the white theme topbar
	// in case we're not on the starting page
	var siteUrl = '<?php echo mySiteUrl(); ?>';
	var locUrl = window.location.href;
	if ($('.btn.btn-primary.login-topbar').length && locUrl != siteUrl) {
		$('.btn.btn-primary.login-topbar').remove();
		$('.btn.btn-success.register-topbar').remove();
	}
})
</script>
