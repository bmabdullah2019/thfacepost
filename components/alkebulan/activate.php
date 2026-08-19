<?php
/**
 * Alkebulan AI - Component Activation Helper
 * This file helps with proper component registration
 * Place in OSSN root if component doesn't activate
 */

// Add this hook to make component activatable
add_hook('ossn', 'init:before', function() {
    // Check if alkebulan component path exists
    $component_path = ossn_route()->basePath . 'components/alkebulan/';
    
    if(file_exists($component_path . 'ossn_com.php')) {
        // Component is in right place, allow it to load
        ossn_include('components/alkebulan/ossn_com.php');
    }
});

// Force component to be activatable
function alkebulan_make_activatable() {
    $activateable = ossn_get_settings('alkebulan', 'activatable');
    if(!$activateable) {
        ossn_set_settings('alkebulan', 'activatable', 1);
        ossn_set_settings('alkebulan', 'enabled', 1);
        ossn_set_settings('alkebulan', 'installed', 1);
    }
}

// Run on every page load
alkebulan_make_activatable();
?>
