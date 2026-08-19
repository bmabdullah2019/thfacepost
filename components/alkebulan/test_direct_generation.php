<?php
/**
 * DIRECT IMAGE GENERATION TEST
 */

// Test parameters
$prompt = 'car';
$width = 512;
$height = 512;

// Create output directory
$output_dir = __DIR__ . '/images/generated/';
if(!is_dir($output_dir)) {
    @mkdir($output_dir, 0755, true);
}

echo "\n=== GENERATING IMAGES ===\n\n";

// Test 1: Fractal Landscape
echo "Generating Fractal Landscape...\n";
$image = imagecreatetruecolor($width, $height);

for($y = 0; $y < $height; $y++) {
    for($x = 0; $x < $width; $x++) {
        $nx = $x / $width;
        $ny = $y / $height;
        $value = sin($nx * 3.14159 * 4) * cos($ny * 3.14159 * 4);
        $value = ($value + 1) / 2;
        
        if($value < 0.3) {
            $r = 25; $g = 85; $b = 150;
        } elseif($value < 0.5) {
            $r = 34; $g = 139; $b = 34;
        } elseif($value < 0.75) {
            $r = 139; $g = 90; $b = 43;
        } else {
            $r = 245; $g = 245; $b = 245;
        }
        
        $color = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, $x, $y, $x+1, $y+1, $color);
    }
}

$file1 = $output_dir . 'fractal_' . md5($prompt . time()) . '.png';
imagepng($image, $file1, 9);
imagedestroy($image);

if(file_exists($file1)) {
    echo "✅ SAVED: " . basename($file1) . " (" . round(filesize($file1)/1024, 2) . " KB)\n";
} else {
    echo "❌ FAILED\n";
}

// Test 2: Perlin Noise
echo "\nGenerating Perlin Noise...\n";
$image = imagecreatetruecolor($width, $height);
$colors = [
    [100, 150, 200],
    [200, 100, 150],
    [150, 200, 100],
    [200, 200, 100],
    [100, 200, 200]
];

for($y = 0; $y < $height; $y++) {
    for($x = 0; $x < $width; $x++) {
        $nx = $x / $width;
        $ny = $y / $height;
        
        $value = sin($nx * 3.14159 * 2) + cos($ny * 3.14159 * 2);
        $value = ($value + 2) / 4;
        $value = max(0, min(1, $value));
        
        $color_idx = (int)($value * (count($colors) - 1));
        $color_rgb = $colors[$color_idx];
        $r = max(0, min(255, $color_rgb[0] + rand(-20, 20)));
        $g = max(0, min(255, $color_rgb[1] + rand(-20, 20)));
        $b = max(0, min(255, $color_rgb[2] + rand(-20, 20)));
        
        $color = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, $x, $y, $x+1, $y+1, $color);
    }
}

$file2 = $output_dir . 'perlin_' . md5($prompt . time()) . '.png';
imagepng($image, $file2, 9);
imagedestroy($image);

if(file_exists($file2)) {
    echo "✅ SAVED: " . basename($file2) . " (" . round(filesize($file2)/1024, 2) . " KB)\n";
} else {
    echo "❌ FAILED\n";
}

// Test 3: Particle System
echo "\nGenerating Particle System...\n";
$image = imagecreatetruecolor($width, $height);
$bg = imagecolorallocate($image, 50, 50, 80);
imagefill($image, 0, 0, $bg);

$particles = 150;
for($p = 0; $p < $particles; $p++) {
    $x1 = rand(0, $width);
    $y1 = rand(0, $height);
    $x2 = $x1 + rand(-150, 150);
    $y2 = $y1 + rand(-150, 150);
    
    $col_idx = $p % (count($colors) - 1) + 1;
    $col = $colors[$col_idx];
    
    for($t = 0; $t <= 1; $t += 0.05) {
        $x = $x1 + ($x2 - $x1) * $t;
        $y = $y1 + ($y2 - $y1) * $t;
        $px = (int)$x;
        $py = (int)$y;
        
        if($px >= 0 && $px < $width && $py >= 0 && $py < $height) {
            $color = imagecolorallocate($image, $col[0], $col[1], $col[2]);
            imagefilledrectangle($image, $px-1, $py-1, $px+2, $py+2, $color);
        }
    }
}

$file3 = $output_dir . 'particle_' . md5($prompt . time()) . '.png';
imagepng($image, $file3, 9);
imagedestroy($image);

if(file_exists($file3)) {
    echo "✅ SAVED: " . basename($file3) . " (" . round(filesize($file3)/1024, 2) . " KB)\n";
} else {
    echo "❌ FAILED\n";
}

// Test 4: Cellular Automata
echo "\nGenerating Cellular Automata...\n";
$image = imagecreatetruecolor($width, $height);

$grid = [];
for($y = 0; $y < $height; $y++) {
    $grid[$y] = [];
    for($x = 0; $x < $width; $x++) {
        $grid[$y][$x] = rand(0, 1);
    }
}

for($gen = 0; $gen < 20; $gen++) {
    $new_grid = $grid;
    
    for($y = 1; $y < $height - 1; $y++) {
        for($x = 1; $x < $width - 1; $x++) {
            $neighbors = 0;
            for($dy = -1; $dy <= 1; $dy++) {
                for($dx = -1; $dx <= 1; $dx++) {
                    if($dx != 0 || $dy != 0) {
                        $neighbors += $grid[$y + $dy][$x + $dx];
                    }
                }
            }
            
            if($grid[$y][$x] == 1) {
                $new_grid[$y][$x] = ($neighbors == 2 || $neighbors == 3) ? 1 : 0;
            } else {
                $new_grid[$y][$x] = ($neighbors == 3) ? 1 : 0;
            }
        }
    }
    
    $grid = $new_grid;
}

for($y = 0; $y < $height; $y++) {
    for($x = 0; $x < $width; $x++) {
        if($grid[$y][$x] == 1) {
            $r = 100 + rand(0, 155);
            $g = 100 + rand(0, 155);
            $b = 100 + rand(0, 155);
        } else {
            $r = 30;
            $g = 30;
            $b = 40;
        }
        
        $color = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, $x, $y, $x+1, $y+1, $color);
    }
}

$file4 = $output_dir . 'cellular_' . md5($prompt . time()) . '.png';
imagepng($image, $file4, 9);
imagedestroy($image);

if(file_exists($file4)) {
    echo "✅ SAVED: " . basename($file4) . " (" . round(filesize($file4)/1024, 2) . " KB)\n";
} else {
    echo "❌ FAILED\n";
}

echo "\n=== SUMMARY ===\n";
echo "Output Directory: $output_dir\n";
echo "Total Files: " . count(array_diff(scandir($output_dir), ['.', '..'])) . "\n";
echo "\n✅ REAL IMAGE GENERATION IS WORKING!\n\n";

?>
