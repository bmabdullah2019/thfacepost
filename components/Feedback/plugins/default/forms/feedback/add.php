<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
?>
<div>
	<label><?php echo ossn_print('feeback:desc');?></label>
    <textarea name="feedback"></textarea>
</div>
<div>
	<label><?php echo ossn_print('feeback:rate');?></label>
    <div class="feedback-rate"></div>
</div>
<div class="maring-top-10">
	<input class="btn btn-primary btn-sm" type="submit" value="<?php echo ossn_print('feedback:submit');?>" />
</div>
<input type="hidden" name="rate" class="rate" />
<script>
	$(document).ready(function(){
			$(".feedback-rate").rateYo({
				ratedFill: '#DE9D43',
				fullStar: true,
				readOnly: false,
				onSet: function(rating, p){
						$('.rate').val(rating);
				},
		  	});						   
	});
</script>