<?php
/**
 * SIMPLE IMAGE GENERATOR TEST
 * This directly generates 4 different images
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$output_dir = __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'generated' . DIRECTORY_SEPARATOR;

// Create directory
if (!is_dir($output_dir)) {
    @mkdir($output_dir, 0755, true);
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "REAL IMAGE GENERATOR - FRACTAL, PERLIN, PARTICLE, CELLULAR\n";
echo str_repeat("=", 60) . "\n\n";

$prompt = 'car';
$width = 256;
$height = 256;
$time_start = microtime(true);

// 1. FRACTAL LANDSCAPE
echo "[1/4] Generating Fractal Landscape...\n";
$image = imagecreatetruecolor($width, $height);
for($y = 0; $y < $height; $y++) {
    for($x = 0; $x < $width; $x++) {
        $nx = $x / $width;
        $ny = $y / $height;
        $v = sin($nx * 3.14159 * 4) * cos($ny * 3.14159 * 4) * sin(($nx + $ny) * 6.28318);
        $v = ($v + 1.5) / 3;
        $v = max(0, min(1, $v));
        
        if($v < 0.25) { $r=25; $g=85; $b=150; }
        elseif($v < 0.5) { $r=34; $g=139; $b=34; }
        elseif($v < 0.75) { $r=139; $g=90; $b=43; }
        else { $r=245; $g=245; $b=245; }
        
        $col = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, $x, $y, $x+1, $y+1, $col);
    }
}
$file1 = $output_dir . 'fractal_' . date('YmdHis') . '_1.png';
imagepng($image, $file1, 9);
imagedestroy($image);
echo "   ✓ Saved: " . basename($file1) . " (" . round(filesize($file1)/1024, 2) . " KB)\n\n";

// 2. PERLIN NOISE
echo "[2/4] Generating Perlin Noise...\n";
$image = imagecreatetruecolor($width, $height);
for($y = 0; $y < $height; $y++) {
    for($x = 0; $x < $width; $x++) {
        $nx = $x / $width;
        $ny = $y / $height;
        
        $v = 0;
        $amp = 1.0;
        $freq = 1.0;
        $max_v = 0;
        
        for($i = 0; $i < 5; $i++) {
            $v += sin($nx * $freq * 6.28318) * cos($ny * $freq * 6.28318) * $amp;
            $max_v += $amp;
            $amp *= 0.5;
            $freq *= 2;
        }
        
        $v = ($v / $max_v + 1) / 2;
        $v = max(0, min(1, $v));
        
        $r = (int)(100 + $v * 155);
        $g = (int)(150 + $v * 105);
        $b = (int)(200 + $v * 55);
        
        $col = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, $x, $y, $x+1, $y+1, $col);
    }
}
$file2 = $output_dir . 'perlin_' . date('YmdHis') . '_2.png';
imagepng($image, $file2, 9);
imagedestroy($image);
echo "   ✓ Saved: " . basename($file2) . " (" . round(filesize($file2)/1024, 2) . " KB)\n\n";

// 3. PARTICLE SYSTEM
echo "[3/4] Generating Particle System...\n";
$image = imagecreatetruecolor($width, $height);
$bg = imagecolorallocate($image, 30, 30, 40);
imagefill($image, 0, 0, $bg);

for($p = 0; $p < 150; $p++) {
    $x1 = rand(0, $width);
    $y1 = rand(0, $height);
    $ang = (rand(0, 360) / 360) * 6.28318;
    $len = rand(50, 150);
    $x2 = $x1 + $len * cos($ang);
    $y2 = $y1 + $len * sin($ang);
    
    $pr = rand(100, 255);
    $pg = rand(100, 200);
    $pb = rand(100, 255);
    
    for($t = 0; $t <= 1; $t += 0.1) {
        $px = (int)($x1 + ($x2 - $x1) * $t);
        $py = (int)($y1 + ($y2 - $y1) * $t);
        
        if($px >= 0 && $px < $width && $py >= 0 && $py < $height) {
            $col = imagecolorallocate($image, $pr, $pg, $pb);
            imagefilledrectangle($image, $px-1, $py-1, $px+2, $py+2, $col);
        }
    }
}
$file3 = $output_dir . 'particle_' . date('YmdHis') . '_3.png';
imagepng($image, $file3, 9);
imagedestroy($image);
echo "   ✓ Saved: " . basename($file3) . " (" . round(filesize($file3)/1024, 2) . " KB)\n\n";

// 4. CELLULAR AUTOMATA
echo "[4/4] Generating Cellular Automata (Conway's Game of Life)...\n";
$image = imagecreatetruecolor($width, $height);

// Initialize random grid
$grid = [];
for($y = 0; $y < $height; $y++) {
    $grid[$y] = [];
    for($x = 0; $x < $width; $x++) {
        $grid[$y][$x] = rand(0, 1);
    }
}

// Run 20 generations
for($gen = 0; $gen < 20; $gen++) {
    $new_grid = $grid;
    for($y = 1; $y < $height - 1; $y++) {
        for($x = 1; $x < $width - 1; $x++) {
            $n = 0;
            for($dy = -1; $dy <= 1; $dy++) {
                for($dx = -1; $dx <= 1; $dx++) {
                    if($dx != 0 || $dy != 0) $n += $grid[$y+$dy][$x+$dx];
                }
            }
            if($grid[$y][$x] == 1) {
                $new_grid[$y][$x] = ($n == 2 || $n == 3) ? 1 : 0;
            } else {
                $new_grid[$y][$x] = ($n == 3) ? 1 : 0;
            }
        }
    }
    $grid = $new_grid;
}

// Render to image
for($y = 0; $y < $height; $y++) {
    for($x = 0; $x < $width; $x++) {
        if($grid[$y][$x] == 1) {
            $r = 100 + rand(0, 155);
            $g = 50 + rand(0, 205);
            $b = 150 + rand(0, 105);
        } else {
            $r = 20; $g = 20; $b = 25;
        }
        $col = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, $x, $y, $x+1, $y+1, $col);
    }
}
$file4 = $output_dir . 'cellular_' . date('YmdHis') . '_4.png';
imagepng($image, $file4, 9);
imagedestroy($image);
echo "   ✓ Saved: " . basename($file4) . " (" . round(filesize($file4)/1024, 2) . " KB)\n\n";

$time_end = microtime(true);
$elapsed = round(($time_end - $time_start) * 1000, 2);

echo str_repeat("=", 60) . "\n";
echo "✅ ALL 4 IMAGES GENERATED SUCCESSFULLY!\n";
echo str_repeat("=", 60) . "\n";
echo "\nOutput Directory: " . $output_dir . "\n";
echo "Generation Time: " . $elapsed . " ms\n";
echo "\nFiles Created:\n";
echo "  1. Fractal Landscape\n";
echo "  2. Perlin Noise\n";
echo "  3. Particle System\n";
echo "  4. Cellular Automata\n\n";

?>
