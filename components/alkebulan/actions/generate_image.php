<?php
/**
 * Generate Image Action
 * Integrates with the component system to generate images from prompts
 */

$prompt = $_POST['prompt'] ?? $_GET['prompt'] ?? '';
$width = (int)($_POST['width'] ?? $_GET['width'] ?? 512);
$height = (int)($_POST['height'] ?? $_GET['height'] ?? 512);
$style = $_POST['style'] ?? $_GET['style'] ?? 'realistic';
$method = $_POST['method'] ?? $_GET['method'] ?? 'procedural';

// Validate input
if(empty($prompt)) {
    $response = ['status' => 'error', 'message' => 'Prompt is required'];
    echo json_encode($response);
    exit;
}

// Create output directory
$output_dir = dirname(__DIR__) . '/images/generated/';
if(!is_dir($output_dir)) {
    if(!@mkdir($output_dir, 0755, true)) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot create output directory']);
        exit;
    }
}

// Always use direct image generation (most reliable)
$width = max(64, min(2048, $width));
$height = max(64, min(2048, $height));

$images = [];

// Generate 4 images using different methods
for($i = 1; $i <= 4; $i++) {
    $image = generateImageDirect($prompt, $width, $height, $i);
    if($image) {
        $filename = 'img_' . $i . '_' . md5($prompt . time() . rand(1000, 9999)) . '.png';
        $filepath = $output_dir . $filename;
        
        $png_result = imagepng($image, $filepath, 9);
        imagedestroy($image);
        
        if($png_result && file_exists($filepath)) {
            $images[] = [
                'filename' => $filename,
                'url' => '/alkebulan/images/generated/' . $filename,
                'method' => getMethodName($i),
                'size' => filesize($filepath)
            ];
        }
    }
}

if(empty($images)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to generate any images',
        'prompt' => $prompt
    ]);
    exit;
}

echo json_encode([
    'status' => 'success',
    'prompt' => $prompt,
    'images' => $images,
    'width' => $width,
    'height' => $height,
    'timestamp' => date('Y-m-d H:i:s')
]);
exit;
    switch($method_id) {
        case 1:
            return generateFractal($prompt, $width, $height);
        case 2:
            return generatePerlin($prompt, $width, $height);
        case 3:
            return generateParticles($prompt, $width, $height);
        case 4:
            return generateCellular($prompt, $width, $height);
    }
    return null;
}

function getMethodName($id) {
    $names = [
        1 => 'Fractal Landscape',
        2 => 'Perlin Noise',
        3 => 'Particle System',
        4 => 'Cellular Automata'
    ];
    return $names[$id] ?? 'Unknown';
}

function generateFractal($prompt, $width, $height) {
    $image = imagecreatetruecolor($width, $height);
    for($y = 0; $y < $height; $y++) {
        for($x = 0; $x < $width; $x++) {
    $colors = getColorsFromPrompt($prompt);
    
    for($y = 0; $y < $height; $y++) {
        for($x = 0; $x < $width; $x++) {
            $nx = $x / $width;
            $ny = $y / $height;
            
            // Multi-layer sine/cosine for complexity
            $v = sin($nx * 3.14159 * 4) * cos($ny * 3.14159 * 4) * sin(($nx + $ny) * 6.28318);
            $v = sin($v * 3.14159) * cos($nx * $ny * 6.28318);
            $v = ($v + 1.5) / 3;
            $v = max(0, min(1, $v));
            
            // Map to 4 distinct levels
            if($v < 0.25) { 
                $r = (int)($colors[0]['r'] * 0.7); 
                $g = (int)($colors[0]['g'] * 0.7); 
                $b = (int)($colors[0]['b'] * 0.7); 
            }
            elseif($v < 0.5) { 
                $r = $colors[0]['r']; 
                $g = $colors[0]['g']; 
                $b = $colors[0]['b']; 
            }
            elseif($v < 0.75) { 
                $r = $colors[1]['r']; 
                $g = $colors[1]['g']; 
                $b = $colors[1]['b']; 
            }
            else { 
                $r = $colors[2]['r']; 
                $g = $colors[2]['g']; 
                $b = $colors[2]['b']; 
           ge, $r, $g, $b);
            imagefilledrectangle($image, $x, $y, $x+1, $y+1, $col);
        }
    }
    return $image;
}

function generatePerlin($prompt, $width, $height) {
    $image = imagecreatetruecolor($width, $height);
    $colors = getColorsFromPrompt($prompt);
    
    for($y = 0; $y < $height; $y++) {
        for($x = 0; $x < $width; $x++) {
            $nx = $x / $width;
            $ny = $y / $height;
            
            // Multi-octave Perlin-like noise
            $v = 0;
            $amp = 1.0;
            $freq = 1.0;
            $max_v = 0;
            
            for($i = 0; $i < 6; $i++) {
                $v += sin($nx * $freq * 6.28318) * cos($ny * $freq * 6.28318) * $amp;
                $max_v += $amp;
                $amp *= 0.5;
                $freq *= 2;
            }
            
            $v = ($v / $max_v + 1) / 2;
            $v = max(0, min(1, $v));
            
            // Blend colors based on noise value
            $color_idx = (int)($v * (count($colors) - 1));
            $base_color = $colors[$color_idx];
            
            $r = (int)(100 + $v * ($base_color['r'] - 100));
            $g = (int)(150 + $v * ($base_color['g'] - 150));
            $b = (int)(200 + $v * ($base_color['b'] - 200));
            
            $r = max(0, min(255, $r));
            $g = max(0, min(255, $g));
            $b = max(0, min(255, $b));
            
            $col = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, $x, $y, $x+1, $y+1, $col);
        }
    }
    return $image;
}

function generateParticles($prompt, $width, $height) {
    $image = imagecreatetruecolor($width, $height);
    $colors = getColorsFromPrompt($prompt);
    
    // Create background
    $bg = imagecolorallocate($image, 10, 10, 15);
    imagefill($image, 0, 0, $bg);
    
    // Generate particles with trails
    $num_particles = 200;
    for($p = 0; $p < $num_particles; $p++) {
        // Random particle position and direction
        $x1 = rand(0, $width);
        $y1 = rand(0, $height);
        $ang = (rand(0, 360) / 360) * 6.28318;
        $len = rand(50, 200);
        $x2 = $x1 + $len * cos($ang);
        $y2 = $y1 + $len * sin($ang);
        
        // Use colors from palette
        $color_idx = $p % count($colors);
        $col_rgb = $colors[$color_idx];
        $pr = $col_rgb['r'];
        $pg = $col_rgb['g'];
        $pb = $col_rgb['b'];
        
        // Draw trail with fade
        for($t = 0; $t <= 1; $t += 0.05) {
            $px = (int)($x1 + ($x2 - $x1) * $t);
            $py = (int)($y1 + ($y2 - $y1) * $t);
            
            if($px >= 0 && $px < $width && $py >= 0 && $py < $height) {
                // Fade effect
                $fade = 1 - ($t * $t);
                $r = (int)($pr * $fade);
                $g = (int)($pg * $fade);
                $b = (int)($pb * $fade);
                
                $col = imagecolorallocate($image, $r, $g, $b);
                imagefilledrectangle($image, $px-1, $py-1, $px+2, $py+2, $col);
            }
        }
    }
    return $image;
}

function generateCellular($prompt, $width, $height) {
    $image = imagecreatetruecolor($width, $height);
    $colors = getColorsFromPrompt($prompt);
    
    // Create smaller grid for faster computation
    $grid_size = 4;
    $grid_width = (int)($width / $grid_size);
    $grid_height = (int)($height / $grid_size);
    
    // Initialize grid
    $grid = [];
    for($y = 0; $y < $grid_height; $y++) {
        $grid[$y] = [];
        for($x = 0; $x < $grid_width; $x++) {
            $grid[$y][$x] = rand(0, 1);
        }
    }
    
    // Run cellular automata (Conway's Game of Life)
    for($gen = 0; $gen < 25; $gen++) {
        $new_grid = $grid;
        for($y = 1; $y < $grid_height - 1; $y++) {
            for($x = 1; $x < $grid_width - 1; $x++) {
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
    for($gy = 0; $gy < $grid_height; $gy++) {
        for($gx = 0; $gx < $grid_width; $gx++) {
            if($grid[$gy][$gx] == 1) {
                $color_idx = ($gx + $gy) % count($colors);
                $col = $colors[$color_idx];
                $r = $col['r'];
                $g = $col['g'];
                $b = $col['b'];
            } else {
                $r = 10; $g = 10; $b = 15;
            }
            
            $color = imagecolorallocate($image, $r, $g, $b);
            // Fill cell area
            for($dy = 0; $dy < $grid_size; $dy++) {
                for($dx = 0; $dx < $grid_size; $dx++) {
                    $py = $gy * $grid_size + $dy;
                    $px = $gx * $grid_size + $dx;
                    if($px < $width && $py < $height) {
                        imagefilledrectangle($image, $px, $py, $px+1, $py+1, $color);
                    }
                }
            }
        }
    }
    return $image;
}

function getColorsFromPrompt($prompt) {
    $prompt_lower = strtolower($prompt);
    
    // Default color palette
    $default = [
        ['r' => 100, 'g' => 150, 'b' => 200],
        ['r' => 200, 'g' => 100, 'b' => 150],
        ['r' => 150, 'g' => 200, 'b' => 100],
        ['r' => 200, 'g' => 150, 'b' => 100]
    ];
    
    // Check for color keywords
    if(strpos($prompt_lower, 'sunset') !== false || strpos($prompt_lower, 'orange') !== false) {
        return [
            ['r' => 255, 'g' => 140, 'b' => 0],
            ['r' => 255, 'g' => 165, 'b' => 0],
            ['r' => 255, 'g' => 100, 'b' => 50],
            ['r' => 255, 'g' => 200, 'b' => 100]
        ];
    }
    if(strpos($prompt_lower, 'ocean') !== false || strpos($prompt_lower, 'water') !== false || strpos($prompt_lower, 'blue') !== false) {
        return [
            ['r' => 0, 'g' => 100, 'b' => 200],
            ['r' => 50, 'g' => 150, 'b' => 220],
            ['r' => 100, 'g' => 180, 'b' => 240],
            ['r' => 150, 'g' => 200, 'b' => 255]
        ];
    }
    if(strpos($prompt_lower, 'forest') !== false || strpos($prompt_lower, 'tree') !== false || strpos($prompt_lower, 'green') !== false) {
        return [
            ['r' => 34, 'g' => 139, 'b' => 34],
            ['r' => 50, 'g' => 205, 'b' => 50],
            ['r' => 100, 'g' => 180, 'b' => 80],
            ['r' => 150, 'g' => 220, 'b' => 100]
        ];
    }
    if(strpos($prompt_lower, 'space') !== false || strpos($prompt_lower, 'purple') !== false || strpos($prompt_lower, 'galaxy') !== false) {
        return [
            ['r' => 75, 'g' => 0, 'b' => 130],
            ['r' => 138, 'g' => 43, 'b' => 226],
            ['r' => 147, 'g' => 112, 'b' => 219],
            ['r' => 186, 'g' => 85, 'b' => 211]
        ];
    }
    if(strpos($prompt_lower, 'fire') !== false || strpos($prompt_lower, 'flame') !== false || strpos($prompt_lower, 'lava') !== false) {
        return [
            ['r' => 255, 'g' => 0, 'b' => 0],
            ['r' => 255, 'g' => 69, 'b' => 0],
            ['r' => 255, 'g' => 140, 'b' => 0],
            ['r' => 255, 'g' => 200, 'b' => 50]
        ];
    }
    if(strpos($prompt_lower, 'ice') !== false || strpos($prompt_lower, 'snow') !== false || strpos($prompt_lower, 'winter') !== false || strpos($prompt_lower, 'cold') !== false) {
        return [
            ['r' => 100, 'g' => 200, 'b' => 255],
            ['r' => 150, 'g' => 220, 'b' => 255],
            ['r' => 200, 'g' => 240, 'b' => 255],
            ['r' => 230, 'g' => 245, 'b' => 255]
        ];
    }
    if(strpos($prompt_lower, 'golden') !== false || strpos($prompt_lower, 'gold') !== false || strpos($prompt_lower, 'yellow') !== false) {
        return [
            ['r' => 255, 'g' => 215, 'b' => 0],
            ['r' => 255, 'g' => 200, 'b' => 50],
            ['r' => 200, 'g' => 150, 'b' => 50],
            ['r' => 255, 'g' => 240, 'b' => 100]
        ];
    }
    if(strpos($prompt_lower, 'desert') !== false || strpos($prompt_lower, 'sand') !== false) {
        return [
            ['r' => 210, 'g' => 180, 'b' => 100],
            ['r' => 220, 'g' => 190, 'b' => 110],
            ['r' => 230, 'g' => 200, 'b' => 120],
            ['r' => 240, 'g' => 210, 'b' => 130]
        ];
    }
    if(strpos($prompt_lower, 'car') !== false || strpos($prompt_lower, 'auto') !== false || strpos($prompt_lower, 'red') !== false) {
        return [
            ['r' => 220, 'g' => 20, 'b' => 20],
            ['r' => 255, 'g' => 50, 'b' => 50],
            ['r' => 100, 'g' => 100, 'b' => 100],
            ['r' => 200, 'g' => 200, 'b' => 200]
        ];
    }
    
    return $default;
}

?>

