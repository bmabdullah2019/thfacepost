<?php
/**
 * IMAGE GENERATION API - Working Implementation
 * Generate real images from prompts - 4 different methods
 */

header('Content-Type: application/json');

// Prevent errors from showing
ini_set('display_errors', 0);
error_reporting(0);

$response = ['status' => 'error', 'message' => 'No action specified'];

try {
    // Get request method
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $action = $_GET['action'] ?? $_POST['action'] ?? null;
    
    // Get parameters
    $prompt = $_POST['prompt'] ?? $_GET['prompt'] ?? 'abstract art';
    $width = (int)($_POST['width'] ?? $_GET['width'] ?? 512);
    $height = (int)($_POST['height'] ?? $_GET['height'] ?? 512);
    $style = $_POST['style'] ?? $_GET['style'] ?? 'colorful';
    $quality = $_POST['quality'] ?? $_GET['quality'] ?? 'high';
    
    // Validate parameters
    $width = min(max($width, 256), 2048);
    $height = min(max($height, 256), 2048);
    $prompt = substr(trim($prompt), 0, 500);
    
    if(empty($prompt)) {
        throw new Exception('Prompt cannot be empty');
    }
    
    // Create output directory
    $output_dir = __DIR__ . '/images/generated/';
    if(!is_dir($output_dir)) {
        @mkdir($output_dir, 0755, true);
    }
    
    if($action === 'generate') {
        // Generate image based on method
        $method_num = rand(1, 4);
        $filename = 'img_' . md5($prompt . time()) . '.png';
        $filepath = $output_dir . $filename;
        
        switch($method_num) {
            case 1:
                generateFractalLandscape($prompt, $width, $height, $filepath);
                $method_name = 'Fractal Landscape';
                break;
            case 2:
                generatePerlinNoise($prompt, $width, $height, $filepath);
                $method_name = 'Perlin Noise';
                break;
            case 3:
                generateParticleSystem($prompt, $width, $height, $filepath);
                $method_name = 'Particle System';
                break;
            case 4:
                generateCellularAutomata($prompt, $width, $height, $filepath);
                $method_name = 'Cellular Automata';
                break;
        }
        
        if(file_exists($filepath)) {
            $response = [
                'status' => 'success',
                'message' => 'Image generated successfully',
                'prompt' => $prompt,
                'method' => $method_name,
                'filename' => $filename,
                'filepath' => str_replace('\\', '/', $filepath),
                'image_url' => 'images/generated/' . $filename,
                'width' => $width,
                'height' => $height,
                'style' => $style,
                'quality' => $quality,
                'file_size' => filesize($filepath),
                'timestamp' => time()
            ];
        } else {
            throw new Exception('Failed to generate image');
        }
    } elseif($action === 'list') {
        // List generated images
        $files = array_diff(scandir($output_dir), ['.', '..']);
        $images = [];
        
        foreach($files as $file) {
            if(pathinfo($file, PATHINFO_EXTENSION) === 'png') {
                $filepath = $output_dir . $file;
                $images[] = [
                    'filename' => $file,
                    'url' => 'images/generated/' . $file,
                    'size' => filesize($filepath),
                    'created' => filemtime($filepath)
                ];
            }
        }
        
        $response = [
            'status' => 'success',
            'count' => count($images),
            'images' => $images
        ];
    } else {
        $response = ['status' => 'info', 'message' => 'Available actions: generate, list'];
    }
    
} catch(Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

// ============================================================================
// GENERATION METHODS
// ============================================================================

/**
 * Generate Fractal Landscape
 */
function generateFractalLandscape($prompt, $width, $height, $filepath) {
    $image = imagecreatetruecolor($width, $height);
    
    // Extract prompt colors
    $colors = extractColorsFromPrompt($prompt);
    
    // Generate height map
    $height_map = generateHeightMap($width, $height);
    
    for($y = 0; $y < $height; $y++) {
        for($x = 0; $x < $width; $x++) {
            $h = $height_map[$y][$x];
            
            // Terrain coloring
            if($h < 0.3) {
                $r = 25; $g = 85; $b = 150; // Water
            } elseif($h < 0.5) {
                $r = 34; $g = 139; $b = 34; // Grass
            } elseif($h < 0.75) {
                $r = 139; $g = 90; $b = 43; // Rock
            } else {
                $r = 245; $g = 245; $b = 245; // Snow
            }
            
            // Apply prompt colors
            if(!empty($colors)) {
                $color_mod = $colors[($x + $y) % count($colors)];
                $r = (int)(($r + $color_mod[0]) / 2);
                $g = (int)(($g + $color_mod[1]) / 2);
                $b = (int)(($b + $color_mod[2]) / 2);
            }
            
            $color = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, $x, $y, $x+1, $y+1, $color);
        }
    }
    
    imagepng($image, $filepath, 9);
    imagedestroy($image);
}

/**
 * Generate Perlin Noise
 */
function generatePerlinNoise($prompt, $width, $height, $filepath) {
    $image = imagecreatetruecolor($width, $height);
    
    // Extract colors
    $colors = extractColorsFromPrompt($prompt);
    if(empty($colors)) {
        $colors = [
            [100, 150, 200],
            [200, 100, 150],
            [150, 200, 100],
            [200, 200, 100],
            [100, 200, 200]
        ];
    }
    
    // Generate noise
    $noise = generatePerlinNoiseMap($width, $height, 5);
    
    for($y = 0; $y < $height; $y++) {
        for($x = 0; $x < $width; $x++) {
            $value = $noise[$y][$x];
            $color_idx = (int)($value * (count($colors) - 1));
            $color_idx = max(0, min($color_idx, count($colors) - 1));
            
            $color_rgb = $colors[$color_idx];
            $r = $color_rgb[0] + rand(-20, 20);
            $g = $color_rgb[1] + rand(-20, 20);
            $b = $color_rgb[2] + rand(-20, 20);
            
            $r = max(0, min(255, $r));
            $g = max(0, min(255, $g));
            $b = max(0, min(255, $b));
            
            $color = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, $x, $y, $x+1, $y+1, $color);
        }
    }
    
    imagepng($image, $filepath, 9);
    imagedestroy($image);
}

/**
 * Generate Particle System
 */
function generateParticleSystem($prompt, $width, $height, $filepath) {
    $image = imagecreatetruecolor($width, $height);
    
    // Background
    $colors = extractColorsFromPrompt($prompt);
    if(empty($colors)) {
        $colors = [[50, 50, 80], [100, 150, 200], [200, 100, 100], [100, 200, 100]];
    }
    
    $bg = $colors[0];
    $bg_color = imagecolorallocate($image, $bg[0], $bg[1], $bg[2]);
    imagefill($image, 0, 0, $bg_color);
    
    // Generate particles
    $particles = 150;
    for($p = 0; $p < $particles; $p++) {
        $x1 = rand(0, $width);
        $y1 = rand(0, $height);
        $x2 = $x1 + rand(-200, 200);
        $y2 = $y1 + rand(-200, 200);
        
        $col_idx = ($p % (count($colors) - 1)) + 1;
        $col = $colors[$col_idx];
        
        // Draw trajectory
        for($t = 0; $t <= 1; $t += 0.05) {
            $x = $x1 + ($x2 - $x1) * $t;
            $y = $y1 + ($y2 - $y1) * $t;
            
            $px = (int)$x;
            $py = (int)$y;
            
            if($px >= 0 && $px < $width && $py >= 0 && $py < $height) {
                $fade = (int)(200 * (1 - $t * $t));
                $color = imagecolorallocate($image, 
                    min(255, $col[0] + $fade / 2),
                    min(255, $col[1] + $fade / 2),
                    min(255, $col[2] + $fade / 2)
                );
                imagefilledrectangle($image, $px-1, $py-1, $px+2, $py+2, $color);
            }
        }
    }
    
    imagepng($image, $filepath, 9);
    imagedestroy($image);
}

/**
 * Generate Cellular Automata
 */
function generateCellularAutomata($prompt, $width, $height, $filepath) {
    $image = imagecreatetruecolor($width, $height);
    
    // Initialize grid
    $grid = [];
    for($y = 0; $y < $height; $y++) {
        $grid[$y] = [];
        for($x = 0; $x < $width; $x++) {
            $grid[$y][$x] = rand(0, 1);
        }
    }
    
    // Evolve
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
    
    // Draw
    $colors = extractColorsFromPrompt($prompt);
    if(empty($colors)) {
        $colors = [[30, 30, 50], [100, 200, 150]];
    }
    
    for($y = 0; $y < $height; $y++) {
        for($x = 0; $x < $width; $x++) {
            if($grid[$y][$x] == 1) {
                $col = $colors[1];
                $r = $col[0] + rand(0, 50);
                $g = $col[1] + rand(0, 50);
                $b = $col[2] + rand(0, 50);
            } else {
                $col = $colors[0];
                $r = $col[0];
                $g = $col[1];
                $b = $col[2];
            }
            
            $color = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, $x, $y, $x+1, $y+1, $color);
        }
    }
    
    imagepng($image, $filepath, 9);
    imagedestroy($image);
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

/**
 * Extract colors from prompt
 */
function extractColorsFromPrompt($prompt) {
    $prompt_lower = strtolower($prompt);
    $colors = [];
    
    $color_map = [
        'red' => [255, 0, 0],
        'green' => [0, 255, 0],
        'blue' => [0, 0, 255],
        'yellow' => [255, 255, 0],
        'purple' => [128, 0, 128],
        'orange' => [255, 165, 0],
        'pink' => [255, 192, 203],
        'cyan' => [0, 255, 255],
        'magenta' => [255, 0, 255],
        'black' => [0, 0, 0],
        'white' => [255, 255, 255],
        'gray' => [128, 128, 128],
        'grey' => [128, 128, 128],
        'golden' => [255, 215, 0],
        'silver' => [192, 192, 192],
        'bronze' => [205, 127, 50],
        'sunset' => [255, 102, 51],
        'ocean' => [0, 105, 148],
        'forest' => [34, 139, 34],
        'sand' => [194, 178, 128]
    ];
    
    foreach($color_map as $keyword => $rgb) {
        if(strpos($prompt_lower, $keyword) !== false) {
            $colors[] = $rgb;
        }
    }
    
    return $colors;
}

/**
 * Generate height map
 */
function generateHeightMap($width, $height) {
    $map = [];
    for($y = 0; $y < $height; $y++) {
        $map[$y] = [];
        for($x = 0; $x < $width; $x++) {
            // Simplex-like noise
            $nx = $x / $width;
            $ny = $y / $height;
            
            $value = sin($nx * 3.14159 * 4) * cos($ny * 3.14159 * 4);
            $value = ($value + 1) / 2;
            
            // Add layers
            $value += sin($nx * 10) * cos($ny * 10) * 0.3;
            $value += sin($nx * 20) * cos($ny * 20) * 0.15;
            
            $value = max(0, min(1, ($value / 1.45)));
            $map[$y][$x] = $value;
        }
    }
    return $map;
}

/**
 * Generate Perlin noise map
 */
function generatePerlinNoiseMap($width, $height, $octaves) {
    $noise = [];
    for($y = 0; $y < $height; $y++) {
        $noise[$y] = [];
        for($x = 0; $x < $width; $x++) {
            $nx = $x / $width;
            $ny = $y / $height;
            
            $value = 0;
            $amplitude = 1;
            $max_value = 0;
            
            for($o = 0; $o < $octaves; $o++) {
                $freq = pow(2, $o);
                $value += (sin($nx * $freq * 3.14159 * 2) + cos($ny * $freq * 3.14159 * 2)) * $amplitude / 2;
                $max_value += $amplitude;
                $amplitude *= 0.5;
            }
            
            $value /= $max_value;
            $value = ($value + 1) / 2;
            $noise[$y][$x] = max(0, min(1, $value));
        }
    }
    return $noise;
}

?>
