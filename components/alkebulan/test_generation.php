<?php
/**
 * Quick test to verify image generation is working
 */

header('Content-Type: text/html');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Image Generation Test</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .test-box { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        img { max-width: 300px; margin: 10px 0; border-radius: 5px; }
        .gallery { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    </style>
</head>
<body>

<h1>🎨 Image Generation Test</h1>

<div class='test-box'>
    <h2>System Checks</h2>";

// Check 1: GD Library
$gd_check = extension_loaded('gd');
echo $gd_check ? "<p class='success'>✓ GD Library is enabled</p>" : "<p class='error'>✗ GD Library is NOT enabled</p>";

// Check 2: Directory
$dir = __DIR__ . '/images/generated/';
$dir_ok = is_dir($dir) || @mkdir($dir, 0755, true);
echo $dir_ok ? "<p class='success'>✓ Output directory is ready</p>" : "<p class='error'>✗ Output directory error</p>";

// Check 3: File structure
$actions_ok = file_exists(__DIR__ . '/actions/generate_image.php');
echo $actions_ok ? "<p class='success'>✓ API endpoint exists</p>" : "<p class='error'>✗ API endpoint NOT found</p>";

echo "</div>";

if($gd_check && $dir_ok && $actions_ok) {
    echo "<div class='test-box'>
        <h2>Generating Test Images...</h2>";
    
    // Simulate the generation
    $test_prompts = ['car', 'sunset', 'ocean'];
    
    foreach($test_prompts as $prompt) {
        echo "<h3>Testing with prompt: <strong>$prompt</strong></h3>";
        echo "<div class='gallery'>";
        
        for($i = 1; $i <= 4; $i++) {
            $image = @file_get_contents("http://localhost/alkebulan/actions/generate_image.php?prompt=$prompt&width=256&height=256", false, stream_context_create(['http' => ['timeout' => 10]]));
            
            if($image) {
                $data = json_decode($image, true);
                if($data && $data['status'] === 'success' && isset($data['images'][$i-1])) {
                    $img = $data['images'][$i-1];
                    echo "<div>
                        <h4>{$img['method']}</h4>
                        <p>Size: " . round($img['size']/1024, 1) . " KB</p>
                        <img src='{$img['url']}' alt='{$img['method']}'>
                    </div>";
                }
            }
        }
        
        echo "</div>";
    }
    
    echo "</div>";
}

echo "
    <div class='test-box'>
        <h2>Quick Test</h2>
        <form method='POST' style='display:flex; gap:10px;'>
            <input type='text' name='prompt' placeholder='Enter prompt...' value='car' required>
            <button type='submit'>Generate</button>
        </form>
    </div>";

if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['prompt'])) {
    $prompt = htmlspecialchars($_POST['prompt']);
    echo "<div class='test-box'>
        <h2>Results for: <strong>$prompt</strong></h2>
        <div class='gallery'>";
    
    $json = file_get_contents("http://localhost/alkebulan/actions/generate_image.php?prompt=" . urlencode($_POST['prompt']) . "&width=256&height=256");
    $data = json_decode($json, true);
    
    if($data && $data['status'] === 'success') {
        foreach($data['images'] as $img) {
            echo "<div>
                <h4>{$img['method']}</h4>
                <p>Size: " . round($img['size']/1024, 1) . " KB</p>
                <img src='{$img['url']}?t=" . time() . "' alt='{$img['method']}' style='width:100%;'>
            </div>";
        }
    } else {
        echo "<p class='error'>Error: " . ($data['message'] ?? 'Unknown error') . "</p>";
    }
    
    echo "</div></div>";
}

echo "
</body>
</html>";

?>
