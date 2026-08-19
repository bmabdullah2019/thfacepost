//<script>
Ossn.RegisterStartupFunction(function() {
    $(document).ready(function() { 
    		$signup = <?php echo json_encode(ossn_plugin_view('gdpr/signup')); ?>;
			$($signup).insertBefore('#ossn-home-signup .ossn-loading');
			$('#ossn-signup-errors').next('p').remove();
    });
});    