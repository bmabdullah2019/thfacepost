<script>
$(document).ready(function() {
    $custom_fields = "<?php echo custom_fields_sort();?>";
    if ($custom_fields) {
        //remove custom fields non-custom fields created fields
        $i = 0;
        $('.customfield-item').each(function() {
            if ($(this).attr('data-guid') == '') {
                $(this).attr('data-guid', $i);
                $(this).attr('data-order', $i);
                $i--;
            }
        });
		$('<div class="custom-field-items"></div>').insertBefore('.customfield-item:first');
        $allfields_raw = $custom_fields.split(',');
        $.each($allfields_raw, function(key, val) {
            $order = key + 1;
            $field = $('div[data-guid="' + val.replace('customfield-item-', '') + '"]').attr('data-order', $order);
        });
        $list = $('.customfield-item').sort(function(a, b) {
            var contentA = parseInt($(a).attr('data-order'));
            var contentB = parseInt($(b).attr('data-order'));
            return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
        });
        $('.custom-field-items').html($list);
        $("input[name='birthdate']").removeClass('hasDatepicker');
		
		if(Ossn.is_callable('ossn_profile_birthdate_picker', true)){
				ossn_profile_birthdate_picker();
		}
    }
});
</script>