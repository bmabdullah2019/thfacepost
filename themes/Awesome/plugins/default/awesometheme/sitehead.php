<?php
$settings = oa_theme_object();
if(!isset($settings[0])){
	return;
}
$settings = $settings[0];	
$var = 'primary';
if(isset($settings->title) && !empty($settings->title)){
		$var = $settings->title;
}
?>
<script>
	$(document).ready(function(){
				switch("<?php echo $var;?>"){
				case 'primary':
					$btn = 'btn-primary';
					break;
				case 'black':
							$btn = 'btn-black';
					break;
				case 'warning':
							$btn = 'btn-warning';
					break;
					case "danger":
							$btn = 'btn-danger';
					break;
				case 'success':
							$btn = 'btn-success';
					break;
				case 'info':
							$btn = 'btn-info';
					break;	
					
				}
				$element = $('body').find('.btn-primary');						  
				$element.removeClass('btn-primary');
				$element.addClass($btn);
	});
</script>