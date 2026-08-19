<?php
/**
 * Alkebulan AI Debug - View Loading Test
 */

echo '<div style="padding: 20px; background: #f0f0f0; border: 2px solid #667eea; margin: 20px; border-radius: 8px;">';
echo '<h2 style="color: #667eea; margin-top: 0;">✅ Alkebulan AI Component is Loading!</h2>';
echo '<p style="color: #333; font-size: 1.1em;"><strong>View Path:</strong> ' . __FILE__ . '</p>';
echo '<p style="color: #333; font-size: 1.1em;"><strong>Current Time:</strong> ' . date('Y-m-d H:i:s') . '</p>';

// Check if pages directory exists
$pages_dir = __DIR__ . '/../pages/';
if (is_dir($pages_dir)) {
    echo '<p style="color: #11998e;"><strong>✓ Pages Directory:</strong> Found at ' . $pages_dir . '</p>';
    $files = glob($pages_dir . '*.php');
    echo '<p style="color: #333;"><strong>Files in pages directory:</strong></p>';
    echo '<ul style="color: #666;">';
    foreach ($files as $file) {
        echo '<li>' . basename($file) . ' (' . filesize($file) . ' bytes)</li>';
    }
    echo '</ul>';
} else {
    echo '<p style="color: #ff6b6b;"><strong>✗ Pages Directory:</strong> Not found</p>';
}

// Check if dashboard.php exists in pages
$dashboard_file = $pages_dir . 'dashboard.php';
if (file_exists($dashboard_file)) {
    echo '<p style="color: #11998e;"><strong>✓ Dashboard File:</strong> Found (' . filesize($dashboard_file) . ' bytes)</p>';
} else {
    echo '<p style="color: #ff6b6b;"><strong>✗ Dashboard File:</strong> Not found at ' . $dashboard_file . '</p>';
}

echo '</div>';

// Now try to load the actual dashboard content
if (file_exists($dashboard_file)) {
    echo '<hr style="border: 1px solid #ddd; margin: 20px 0;">';
    echo '<h3 style="color: #667eea; padding: 0 20px;">Dashboard Content:</h3>';
    include $dashboard_file;
}
?>
