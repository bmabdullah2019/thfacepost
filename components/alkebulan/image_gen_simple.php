<?php
/**
 * Simple Direct Image Generator API
 * Generates real images from prompts using 4 different algorithms
 */

header('Content-Type: application/json');

// Get parameters
$action = $_GET['action'] ?? 'generate';
$prompt = $_GET['prompt'] ?? 'default';
$width = (int)($_GET['width'] ?? 512);
$height = (int)($_GET['height'] ?? 512);
$method = $_GET['method'] ?? 'all';

// Ensure reasonable dimensions
$width = max(64, min(2048, $width));
$height = max(64, min(2048, $height));

// Output directory
$output_dir = __DIR__ . '/images/generated/';
if(!is_dir($output_dir)) {
    @mkdir($output_dir, 0755, true);
}

// Color palette from prompt
function getColorsFromPrompt($prompt) {
    $colors = [];
    $prompt_lower = strtolower($prompt);
    
    if(strpos($prompt_lower, 'car') !== false || strpos($prompt_lower, 'auto') !== false) {
        $colors = [[255, 0, 0], [0, 0, 0], [200, 200, 200], [100, 100, 100]];
    } elseif(strpos($prompt_lower, 'sunset') !== false || strpos($prompt_lower, 'orange') !== false) {
        $colors = [[255, 165, 0], [255, 69, 0], [255, 215, 0], [255, 100, 0]];
    } elseif(strpos($prompt_lower, 'ocean') !== false || strpos($prompt_lower, 'water') !== false || strpos($prompt_lower, 'blue') !== false) {
        $colors = [[0, 119, 182], [25, 137, 213], [72, 167, 226], [0, 100, 150]];
    } elseif(strpos($prompt_lower, 'forest') !== false || strpos($prompt_lower, 'tree') !== false || strpos($prompt_lower, 'green') !== false) {
        $colors = [[34, 139, 34], [50, 205, 50], [144, 238, 144], [0, 100, 0]];
    } elseif(strpos($prompt_lower, 'space') !== false || strpos($prompt_lower, 'purple') !== false) {
        $colors = [[75, 0, 130], [138, 43, 226], [147, 112, 219], [255, 0, 255]];
    } else {
        $colors = [[100, 150, 200], [200, 100, 150], [150, 200, 100], [200, 150, 100]];
    }
    
    return $colors;
}

// Algorithm 1: Fractal Landscape
function generateFractal($width, $height, $colors, $prompt) {
    $image = imagecreatetruecolor($width, $height);
    
    for($y = 0; $y < $height; $y++) {
        for($x = 0; $x < $width; $x++) {
            $nx = $x / $width;
            $ny = $y / $height;
            
            $value = sin($nx * 3.14159 * 4) * cos($ny * 3.14159 * 4) * sin(($nx + $ny) * 6.28318);
            $value = ($value + 1.5) / 3;
            $value = max(0, min(1, $value));
            
            if($value < 0.25) {
                $r = 25; $g = 85; $b = 150; // Water
            } elseif($value < 0.5) {
                $r = 34; $g = 139; $b = 34; // Grass
            } elseif($value < 0.75) {
                $r = 139; $g = 90; $b = 43; // Rock
            } else {
                $r = 245; $g = 245; $b = 245; // Snow
            }
            
            $color = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, $x, $y, $x+1, $y+1, $color);
        }
    }
    
    return $image;
}

// Algorithm 2: Perlin Noise Style
function generatePerlin($width, $height, $colors, $prompt) {
    $image = imagecreatetruecolor($width, $height);
    
    for($y = 0; $y < $height; $y++) {
        for($x = 0; $x < $width; $x++) {
            $nx = $x / $width;
            $ny = $y / $height;
            
            $value = 0;
            $amplitude = 1.0;
            $frequency = 1.0;
            $max_value = 0;
            
            for($i = 0; $i < 5; $i++) {
                $value += sin($nx * $frequency * 6.28318) * cos($ny * $frequency * 6.28318) * $amplitude;
                $max_value += $amplitude;
                $amplitude *= 0.5;
                $frequency *= 2;
            }
            
            $value = ($value / $max_value + 1) / 2;
            $value = max(0, min(1, $value));
            
            $color_idx = (int)($value * (count($colors) - 1));
            $base_color = $colors[$color_idx];
            
            $r = (int)($base_color[0] * $value + 50 * (1 - $value));
            $g = (int)($base_color[1] * $value + 100 * (1 - $value));
            $b = (int)($base_color[2] * $value + 150 * (1 - $value));
            
            $color = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, $x, $y, $x+1, $y+1, $color);
        }
    }
    
    return $image;
}

// Algorithm 3: Particle System
function generateParticles($width, $height, $colors, $prompt) {
    $image = imagecreatetruecolor($width, $height);
    $bg = imagecolorallocate($image, 30, 30, 40);
    imagefill($image, 0, 0, $bg);
    
    $num_particles = 200;
    
    for($p = 0; $p < $num_particles; $p++) {
        $x1 = rand(0, $width);
        $y1 = rand(0, $height);
        $angle = (rand(0, 360) / 360) * 6.28318;
        $length = rand(50, 200);
        $x2 = $x1 + $length * cos($angle);
        $y2 = $y1 + $length * sin($angle);
        
        $color_idx = $p % count($colors);
        $color_rgb = $colors[$color_idx];
        
        for($t = 0; $t <= 1; $t += 0.1) {
            $x = (int)($x1 + ($x2 - $x1) * $t);
            $y = (int)($y1 + ($y2 - $y1) * $t);
            
            if($x >= 0 && $x < $width && $y >= 0 && $y < $height) {
                $alpha = 1 - ($t * $t); // Fade effect
                $color = imagecolorallocate($image, 
                    (int)($color_rgb[0] * $alpha),
                    (int)($color_rgb[1] * $alpha),
                    (int)($color_rgb[2] * $alpha)
                );
                imagefilledrectangle($image, $x-1, $y-1, $x+2, $y+2, $color);
            }
        }
    }
    
    return $image;
}

// Algorithm 4: Cellular Automata
function generateCellular($width, $height, $colors, $prompt) {
    $image = imagecreatetruecolor($width, $height);
    
    // Initialize grid
    $grid = [];
    for($y = 0; $y < $height; $y++) {
        $grid[$y] = [];
        for($x = 0; $x < $width; $x++) {
            $grid[$y][$x] = rand(0, 1);
        }
    }
    
    // Run generations
    for($gen = 0; $gen < 15; $gen++) {
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
    
    // Render to image
    for($y = 0; $y < $height; $y++) {
        for($x = 0; $x < $width; $x++) {
            if($grid[$y][$x] == 1) {
                $color_idx = ($x + $y) % count($colors);
                $col = $colors[$color_idx];
                $r = max(0, min(255, $col[0] + rand(-30, 30)));
                $g = max(0, min(255, $col[1] + rand(-30, 30)));
                $b = max(0, min(255, $col[2] + rand(-30, 30)));
            } else {
                $r = 20; $g = 20; $b = 25;
            }
            
            $color = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, $x, $y, $x+1, $y+1, $color);
        }
    }
    
    return $image;
}

// Handle actions
if($action === 'generate') {
    $methods = ['fractal', 'perlin', 'particles', 'cellular'];
    $colors = getColorsFromPrompt($prompt);
    
    $generated = [];
    
    foreach($methods as $m) {
        if($method !== 'all' && $method !== $m) continue;
        
        switch($m) {
            case 'fractal':
                $image = generateFractal($width, $height, $colors, $prompt);
                $title = 'Fractal Landscape';
                break;
            case 'perlin':
                $image = generatePerlin($width, $height, $colors, $prompt);
                $title = 'Perlin Noise';
                break;
            case 'particles':
                $image = generateParticles($width, $height, $colors, $prompt);
                $title = 'Particle System';
                break;
            case 'cellular':
                $image = generateCellular($width, $height, $colors, $prompt);
                $title = 'Cellular Automata';
                break;
            default:
                continue 2;
        }
        
        $filename = $m . '_' . md5($prompt . time() . $m) . '.png';
        $filepath = $output_dir . $filename;
        
        imagepng($image, $filepath, 9);
        imagedestroy($image);
        
        $generated[] = [
            'method' => $title,
            'filename' => $filename,
            'url' => 'images/generated/' . $filename,
            'size' => filesize($filepath),
            'success' => file_exists($filepath)
        ];
    }
    
    echo json_encode([
        'status' => 'success',
        'prompt' => $prompt,
        'width' => $width,
        'height' => $height,
        'images' => $generated,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} elseif($action === 'list') {
    $files = array_diff(scandir($output_dir), ['.', '..']);
    $images = [];
    
    foreach($files as $file) {
        if(strpos($file, '.png') !== false) {
            $images[] = [
                'filename' => $file,
                'url' => 'images/generated/' . $file,
                'size' => filesize($output_dir . $file)
            ];
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'total' => count($images),
        'images' => $images
    ]);
    
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unknown action: ' . $action
    ]);
}

?>
