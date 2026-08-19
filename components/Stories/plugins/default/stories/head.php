<script>
$(document).ready(function() {
	$('<div class="stories-container"><div class="ossn-widget "><div class="widget-heading">'+Ossn.Print('stories')+'</div><div class="widget-contents"><div class="main"></div></div></div></div>').insertAfter('.newsfeed-middle .ossn-wall-container');
	$page_limit = 4;
	if($(window).width() < 480){
		$page_limit = 2;
	}
	$('.stories-container .main').fbStories({
		delay: 3,
		page_limit:$page_limit,
		dataurl: '<?php echo ossn_site_url('stories/list');?>',
		onShow: function(guid, url, user, slide){
				console.log(guid + ' ' + url);
				$owner_guid = slide.getAttribute('data-owner');
				$loggedin   = $('.fbstories-items').attr('data-loggedin');
				if($owner_guid == $loggedin){
					$('.fbstorie-item-delete').show();
				} else {
					$('.fbstorie-item-delete').hide();	
				}			
		},
		showAdd: true,
		addText : '<i class="fa fa-plus"></i>',		
	});
	$('.fbstories-items').attr('data-loggedin', <?php echo ossn_loggedin_user()->guid;?>);
	$('body').on('click', '.fbstorie-item-delete', function(e){
			e.preventDefault();
			$guid = $(this).attr('data-guid');
			var msg = 'ossn:exception:make:sure';
			var del = confirm(Ossn.Print(msg));
			if(del == true){				
				$url = Ossn.AddTokenToUrl(Ossn.site_url + 'action/stories/delete/item?guid='+$guid);
				window.location = $url;
			}
	});
});
$(document).ready(function() {
        $('.fbstories-item-add').on('click', function() {
            Ossn.MessageBox('stories/add');
        });
});
</script>