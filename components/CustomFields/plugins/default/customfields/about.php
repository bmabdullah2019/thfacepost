<?php
 $user = $params['user'];
 if(!isset($user->guid)){
		return; 
 }
 $custom = new CustomFields;
 $all = $custom->getFields();
 $types = $custom->inputTypes();
 $langauge = ossn_site_settings('language');
?>
<table class="table table-striped">
  <tbody class="customfield-list custom-field-items">
  <?php
  	if($all){	
		foreach($all as $item){
				if(!isset($user->{$item->field_name}) || !$item->showOnAbout()){
					continue;	
				}
				$title = $item->placeholder;
				$value = $user->{$item->field_name};
				
				if($langauge != 'en'){
						if($item->field_name != 'gender' && $item->field_name != 'birthdate'){
							$title = ossn_print("customfields:translate:{$item->field_name}");
							$translate_key = strtolower(str_replace(' ', '', $value));
							if($item->field_type != 'text' && $item->field_type != 'textarea'){
								$value = ossn_print("customfield:val:translate:{$translate_key}");						
							}
						}
				}
				if($item->field_name == 'gender' || $item->field_name == 'birthdate'){
						$title = ossn_print($title);
						if($item->field_name == 'gender'){
								$value = ossn_print($value);	
						}
				}	
			?>
  <tr id="customfield-item-<?php echo $item->guid;?>" class="customfield-item" data-guid='<?php echo $item->guid;?>'>
    <th><?php echo $title;?></th>
    <td><?php echo $value;?></td>
  </tr>
  <?php } 
	}?>
    </tbody>
</table>
<script>
	$(document).ready(function(){
			$custom_fields = "<?php echo custom_fields_sort();?>";	
			if($custom_fields){
				$allfields_raw = $custom_fields.split(',');
				$.each($allfields_raw, function(key, val){
							$order = key+1;
							$('tr[data-guid="'+val.replace('customfield-item-', '')+'"]').attr('data-order', $order);
				});
				$list = $('.customfield-item').sort(function (a, b) {
     				 var contentA =parseInt( $(a).attr('data-order'));
      				 var contentB =parseInt( $(b).attr('data-order'));
      			    return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
   				});
				$('.custom-field-items').html($list);
			}
	});
</script>