<?php
$config = social_login_cred();
?>
<?php if($config->facebook->button != 'no'){ ?>
<a href="<?php echo ossn_site_url('action/social/login/facebook', true);?>" class="btn btn-block btn-social btn-facebook btn-sm">
  <span class="fab fa-facebook"></span>
  <?php echo ossn_print('social:login:with:facebook');?>
</a>
<?php } ?>
<?php if($config->twitter->button != 'no'){ ?>
<a href="<?php echo ossn_site_url('action/social/login/twitter', true);?>" class="btn btn-block btn-social btn-twitter btn-sm">
  <span class="fab fa-twitter"></span>
  <?php echo ossn_print('social:login:with:twitter');?>
</a>
<?php } ?>
<?php if($config->google->button != 'no'){ ?>
<a href="<?php echo ossn_site_url('action/social/login/google', true);?>" class="btn btn-block btn-social btn-google btn-sm">
  <span class="fab fa-google"></span>
  <?php echo ossn_print('social:login:with:google');?>
</a>
<?php } ?>
<?php if($config->apple->button != 'no'){ ?>
<a href="<?php echo ossn_site_url('action/social/login/apple', true);?>" class="btn btn-block btn-social btn-reddit btn-sm">
  <span class="fab fa-apple"></span>
  <?php echo ossn_print('social:login:with:apple');?>
</a>
<?php } ?>