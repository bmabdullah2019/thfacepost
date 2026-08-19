<?php
// Direct image generation test - no includes, no dependencies

$_GET['prompt'] = 'car';
$_GET['action'] = 'generate';
$_GET['width'] = 256;
$_GET['height'] = 256;

// Simulate the API
include __DIR__ . '/image_gen_simple.php';
?>
