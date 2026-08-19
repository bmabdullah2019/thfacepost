<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
 ossn_load_external_js('jquery.tokeninput'); 
 $url_prefix = ossn_site_url("company/[your username]");
?>
<div class="col-lg-11">
	<?php
		echo ossn_plugin_view('widget/view', array(
					'title' => $params['page']->title,
					'contents' => ossn_view_form('bpage/edit', array(
							'action' => ossn_site_url('action/bpage/edit'),
							'params' => $params,
				 	 )),
		));
		?>
	<div class="ossn-widget">
		<div class="widget-heading"><?php echo ossn_print('bpage:delete');?></div>
		<div class="widget-contents">
			<div class="alert alert-danger"><?php echo ossn_print('bpage:delete:warning');?></div>
			<a href="<?php echo ossn_site_url("action/bpage/delete?guid={$params['page']->guid}", true);?>" class="btn-sm btn btn-danger ossn-make-sure"><?php echo ossn_print('bpage:delete');?></a>
		</div>
	</div>
	<!-- ownership -->
	<div class="ossn-widget">
		<div class="widget-heading"><?php echo ossn_print('bpage:ownership');?></div>
		<div class="widget-contents">
			<div class="alert alert-danger"><?php echo ossn_print('bpage:ownership:warning');?></div>
			<?php
				echo ossn_view_form('bpage/ownership', array(
								'action' => ossn_site_url('action/bpage/ownership'),
								'onsubmit' => "return confirm('".ossn_print('ossn:exception:make:sure')."');",
								'params' => $params,
				));
				?>
		</div>
	</div>
    
    <!-- username -->
	<div class="ossn-widget">
		<div class="widget-heading"><?php echo ossn_print('username');?></div>
		<div class="widget-contents">
        	<?php if(!isset($params['page']->username)){ ?>
			<p><?php echo ossn_print('bpage:username:note', [$url_prefix]);?></p>
			<?php
				echo ossn_view_form('bpage/username', array(
								'action' => ossn_site_url('action/bpage/username'),
								'onsubmit' => "return confirm('".ossn_print('ossn:exception:make:sure')."');",
								'params' => $params,
				));
				?>
              <?php } else { ?>
              <div class="ossn-form">
              	<input type="text" disabled="disabled" readonly="readonly" value="<?php echo ossn_site_url("company/{$params['page']->username}");?>" />
              </div>
              <?php } ?>                
		</div>
	</div>    
</div>