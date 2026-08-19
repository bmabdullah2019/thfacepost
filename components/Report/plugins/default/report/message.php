<?php
$message_id = $params['instance']->id;
if($params['instance']->message_from != ossn_loggedin_user()->guid){
	?>
    <a class="message-report ossn-report-this" data-type='message' data-guid="<?php echo $message_id;?>" href="javascript:void(0);"><i class="fas fa-flag"></i><?php echo ossn_print('report:this');?></a>
    <?php
}