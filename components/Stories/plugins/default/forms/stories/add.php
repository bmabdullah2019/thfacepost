<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0 https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
 ?>
 <div>
 	<label><?php echo ossn_print('stories:title');?></label>
 	<input type="text" name="title" multiple />
 </div>
<div class="ossn-stories-add-button">
	<input type="file" name="images[]" multiple class="hidden"/>

	<button type="button" id="ossn-stories-add-button-inner" class="btn btn-default btn-lg"><i class="fa fa-copy"></i> <?php echo ossn_print('photo:select'); ?></button>
    <div class="images"><i class="fa fa-image"></i> <span class="count">0</span></div>
</div> 

<?php if(!ossn_is_xhr()){ ?>
 <div>
 	<input type="submit" value="<?php echo ossn_print('save');?>" class="btn btn-success" />
 </div>
<?php }  else {?>
 	<input type="submit" id="stories-add-submit" value="<?php echo ossn_print('save');?>" class="btn btn-success hidden" />

<?php } ?> 
 <script>
         $('body').delegate('#ossn-stories-add-button-inner', 'click', function(e){
        	e.preventDefault();
			$('.ossn-stories-add-button').find('input').click();
        });
	$('body').delegate('.ossn-stories-add-button input', 'change', function(e){
		$length = $(this)[0].files.length;
		$('.ossn-stories-add-button').find('.images').show();
		$('.ossn-stories-add-button').find('.images .count').html($length);
		$('#ossn-stories-add-button-inner').blur();
	});
 </script>
<style>
.ossn-stories-add-button{
    padding: 20px;
    text-align: center;
    border: 3px dashed #eee;
    margin-top: 10px;
    margin-bottom: 10px;
}
</style>