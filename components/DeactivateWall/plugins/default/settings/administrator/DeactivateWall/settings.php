<?php
echo ossn_view_form('deactivatewall/settings_form', array(
    'action' => ossn_site_url() . 'action/deactivatewall/admin/settings',
    'class' => 'ossn-admin-form',
));
