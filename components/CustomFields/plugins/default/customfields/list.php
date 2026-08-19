<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE  https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */
 $custom = new CustomFields;
 $all = $custom->getFields();
 $types = $custom->inputTypes();
?>
<script>
	$(document).ready(function(){
		$( "tbody" ).sortable({
    		axis: 'y',
  		    update: function (event, ui) {
      			  var fields_order = $(this).sortable('toArray').join(',');
				   Ossn.PostRequest({
        				url: Ossn.site_url + "action/customfields/sort?data=" + fields_order,
      					 callback: function(callback) {
            			
          				}
    			  });
    		}
});						   
	});
</script>
<table class="table table-striped">
  <tr>
    <th scope="col"><?php echo ossn_print('customfield:list:name');?></th>
    <th scope="col"><?php echo ossn_print('customfield:list:title');?></th>
    <th scope="col"><?php echo ossn_print('customfield:list:type');?></th>
    <th scope="col"><?php echo ossn_print('customfield:list:onsignup');?></th>
    <th scope="col"><?php echo ossn_print('customfield:list:required');?></th>
    <th scope="col"><?php echo ossn_print('customfield:list:label');?></th>
    <th scope="col"><?php echo ossn_print('customfield:list:about');?></th>
    <th scope="col"><?php echo ossn_print('customfield:list:manage');?></th>
  </tr>
  <tbody class="customfield-list custom-field-items">
  <?php
  	if($all){	
		foreach($all as $item){
					$default  = "<i class='fa fa-times'></i>";
					$onsignup = $default;
					$required = $default;
					$label 	  = $default;
					$about    = $default;
					if($item->show_on_signup == 'yes'){
							$onsignup  = "<i class='fa fa-check'></i>";
					}
					if($item->is_required ==  'yes'){
							$required  = "<i class='fa fa-check'></i>";
					}	
					if($item->show_label ==  'yes'){
							$label  = "<i class='fa fa-check'></i>";
					}		
					if($item->show_on_about ==  'yes'){
							$about  = "<i class='fa fa-check'></i>";
					}	
					$opacity = '';
			?>
  <tr id="customfield-item-<?php echo $item->guid;?>" class="<?php echo $opacity;?> customfield-item" data-guid='<?php echo $item->guid;?>'>
    <td><?php echo $item->field_name;?></td>
    <td><?php echo $item->placeholder;?></td>
    <td><?php echo $types[$item->field_type];?></td>
    <td><?php echo $onsignup;?></td>
    <td><?php echo $required;?></td>
    <td><?php echo $label;?></td>
    <td><?php echo $about;?></td>
    <td>
    <a href="<?php echo ossn_site_url("administrator/settings/customfields?cpage=edit&guid={$item->guid}");?>" class="btn btn-success btn-sm"><i class="fa fa-pencil-alt"></i></a>&nbsp;<a href="<?php echo ossn_site_url("action/customfield/delete?guid={$item->guid}", true);?>" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
    </td>
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
<?php echo ossn_plugin_view('customfields/menu');?>
<div style="margin-top:20px;">
	<?php echo ossn_print('customfields:note');?>
</div>
