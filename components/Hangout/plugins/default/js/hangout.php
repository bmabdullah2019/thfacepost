<?php
if(ossn_isLoggedin()){ ?>
<script>
$(document).ready(function() {
		$code = '<div class="ossn-widget "> <div class="widget-heading">'+Ossn.Print("hangout")+'</div><div class="widget-contents"> <div class="ossn-profile-module-friends text-center"><a target="_blank" href="https://meet.google.com/" class="btn btn-success"><i class="fa fa-camera"></i>Start Google Meet</a></div></div>';
		$('.ossn-profile-sidebar .ossn-profile-modules').prepend($code);
});
</script>
<?Php } ?>