<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network (OSSN)
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$simple_curl_get = function($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}; 
?>
<header class="content-header">
    <h1><?php echo ossn_installation_print('ossn:license:title'); ?></h1>
    <p><?php echo ossn_installation_print('ossn:license:desc'); ?></p>
</header>
<?php 
echo '<div id="ossnlicense" style="background: #f9f9f9; padding: 20px; border-radius: 3px; border: 2px dashed #eee;">';
$url = 'https://www.openteknik.com/static/license.php';
$license_data = $simple_curl_get($url);

if (!$license_data) {
    echo '<div style="color: red; font-weight: bold;">Error: Failed to load license information. Please check your internet connection.</div>';
}
if(empty($license_data) && !str_contains($license_data, 'OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0')) {
   	 	echo '<div style="color: red; font-weight: bold;">Error: Failed to load license information. Please check your internet connection.</div>';
    } else {
   		echo nl2br($license_data);
}
echo '</div><br />';
echo '<a href="/installation/" class="installer-btn btn-cancel">Back</a>';
echo '<a href="' . ossn_installation_paths()->url . '?page=settings" class="ms-2 installer-btn btn-primary mt-2">'.ossn_installation_print('ossn:install:next').'</a>';
?>