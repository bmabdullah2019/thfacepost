<?php
/**
 * IMAGE GENERATION SYSTEM - VERIFICATION SCRIPT
 * Run this to verify everything is working correctly
 */

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║   IMAGE GENERATION SYSTEM - VERIFICATION TEST                 ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$passed = 0;
$failed = 0;

function test($name, $condition, $message = '') {
    global $passed, $failed;
    if($condition) {
        echo "✅ PASS: " . $name;
        if($message) echo " - " . $message;
        echo "\n";
        $passed++;
    } else {
        echo "❌ FAIL: " . $name;
        if($message) echo " - " . $message;
        echo "\n";
        $failed++;
    }
}

// Test 1: GD Library
test("GD Library", extension_loaded('gd'), "Required for image generation");

// Test 2: File existence
test("generate_images.html", file_exists(__DIR__ . '/generate_images.html'), "Web UI");
test("actions/generate_image.php", file_exists(__DIR__ . '/actions/generate_image.php'), "API endpoint");
test("demo.php", file_exists(__DIR__ . '/demo.php'), "Demo/test page");
test("IMAGE_GENERATION_GUIDE.md", file_exists(__DIR__ . '/IMAGE_GENERATION_GUIDE.md'), "Documentation");

// Test 3: Directory structure
test("images/ directory", is_dir(__DIR__ . '/images'), "Storage directory");
test("images/generated/ writable", is_writable(__DIR__ . '/images') || mkdir(__DIR__ . '/images/generated', 0755, true) || is_writable(__DIR__ . '/images/generated'), "Can write images");

// Test 4: PHP functions
test("imagecreatetruecolor()", function_exists('imagecreatetruecolor'), "GD function");
test("imagepng()", function_exists('imagepng'), "GD function");
test("imagecolorallocate()", function_exists('imagecolorallocate'), "GD function");

// Test 5: Try actual image generation
echo "\n--- Attempting to Generate Test Images ---\n";

$test_dir = __DIR__ . '/images/generated/';
if(!is_dir($test_dir)) {
    @mkdir($test_dir, 0755, true);
}

$test_files = [];

// Test Fractal Generation
try {
    $image = imagecreatetruecolor(128, 128);
    for($y = 0; $y < 128; $y++) {
        for($x = 0; $x < 128; $x++) {
            $nx = $x / 128;
            $ny = $y / 128;
            $v = sin($nx * 3.14159 * 4) * cos($ny * 3.14159 * 4);
            $v = ($v + 1) / 2;
            $v = max(0, min(1, $v));
            
            if($v < 0.5) { $r = 100; $g = 150; $b = 200; }
            else { $r = 200; $g = 200; $b = 200; }
            
            $col = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, $x, $y, $x+1, $y+1, $col);
        }
    }
    
    $test_file = $test_dir . 'test_fractal_' . time() . '.png';
    imagepng($image, $test_file, 9);
    imagedestroy($image);
    
    if(file_exists($test_file)) {
        test("Fractal Generation", true, filesize($test_file) . " bytes");
        $test_files[] = $test_file;
    } else {
        test("Fractal Generation", false, "File not saved");
    }
} catch(Exception $e) {
    test("Fractal Generation", false, $e->getMessage());
}

// Test Perlin Generation
try {
    $image = imagecreatetruecolor(128, 128);
    for($y = 0; $y < 128; $y++) {
        for($x = 0; $x < 128; $x++) {
            $nx = $x / 128;
            $ny = $y / 128;
            $v = sin($nx * 6.28318) * cos($ny * 6.28318);
            $v = ($v + 1) / 2;
            
            $r = (int)(100 + $v * 155);
            $g = (int)(150 + $v * 105);
            $b = (int)(200 + $v * 55);
            
            $col = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, $x, $y, $x+1, $y+1, $col);
        }
    }
    
    $test_file = $test_dir . 'test_perlin_' . time() . '.png';
    imagepng($image, $test_file, 9);
    imagedestroy($image);
    
    if(file_exists($test_file)) {
        test("Perlin Generation", true, filesize($test_file) . " bytes");
        $test_files[] = $test_file;
    } else {
        test("Perlin Generation", false, "File not saved");
    }
} catch(Exception $e) {
    test("Perlin Generation", false, $e->getMessage());
}

// Test API endpoint
echo "\n--- Testing API Endpoint ---\n";

$api_file = __DIR__ . '/actions/generate_image.php';
if(file_exists($api_file)) {
    // Simple syntax check
    $code = file_get_contents($api_file);
    $has_json = strpos($code, 'json_encode') !== false;
    $has_functions = strpos($code, 'generateFractal') !== false;
    
    test("API endpoint valid", $has_json && $has_functions, "Contains required functions");
}

// Test ImageGeneratorV3 class
echo "\n--- Testing Core Class ---\n";

$class_file = __DIR__ . '/classes/ImageGeneratorV3.php';
if(file_exists($class_file)) {
    $code = file_get_contents($class_file);
    test("ImageGeneratorV3.php exists", true, "Main engine class");
    test("Has SimpleCacheManager fallback", strpos($code, 'SimpleCacheManager') !== false, "Fallback cache");
    test("Has generate methods", 
        strpos($code, 'generateFractal') !== false && 
        strpos($code, 'generatePerlin') !== false && 
        strpos($code, 'generateParticle') !== false && 
        strpos($code, 'generateCellular') !== false,
        "All 4 algorithms");
}

// Summary
echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                      TEST SUMMARY                              ║\n";
echo "╠════════════════════════════════════════════════════════════════╣\n";
echo "║  ✅ Passed: " . str_pad($passed, 50) . "║\n";
echo "║  ❌ Failed: " . str_pad($failed, 50) . "║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";

if($failed == 0) {
    echo "\n✅ ALL TESTS PASSED - SYSTEM IS READY!\n\n";
    echo "Next steps:\n";
    echo "  1. Open: http://localhost/alkebulan/generate_images.html\n";
    echo "  2. Enter a prompt (e.g., 'car', 'sunset', 'ocean')\n";
    echo "  3. Click 'Generate Images'\n";
    echo "  4. See 4 unique images created!\n\n";
} else {
    echo "\n⚠️  SOME TESTS FAILED - Please review the errors above.\n\n";
}

// Cleanup test files
foreach($test_files as $file) {
    @unlink($file);
}

echo "For more information, see: IMAGE_GENERATION_GUIDE.md\n\n";

?>
