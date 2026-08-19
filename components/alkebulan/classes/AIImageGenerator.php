<?php
/**
 * Alkebulan AI - Image Generation Engine v2.0
 * Generates images from text prompts with customizable styles and parameters
 * Supports: Text generation, style application, color schemes, and export
 */

class AIImageGenerator {
    
    private $db;
    private $max_width = 1024;
    private $max_height = 1024;
    private $supported_styles = ['abstract', 'minimalist', 'colorful', 'dark', 'gradient', 'geometric'];
    private $image_formats = ['png', 'jpg', 'webp'];
    
    public function __construct() {
        $this->db = ossn_get_database();
    }
    
    /**
     * Generate simple image from text prompt
     * @param string $prompt User's text prompt
     * @param array $options Style, size, colors, etc.
     * @return array Generated image data
     */
    public function generateImage($prompt, $options = []) {
        if(empty($prompt)) {
            return [
                'status' => 'error',
                'message' => 'Prompt cannot be empty'
            ];
        }
        
        // Validate prompt length
        if(strlen($prompt) > 500) {
            return [
                'status' => 'error',
                'message' => 'Prompt too long (max 500 characters)'
            ];
        }
        
        // Set defaults
        $width = min($options['width'] ?? 800, $this->max_width);
        $height = min($options['height'] ?? 600, $this->max_height);
        $style = $options['style'] ?? 'colorful';
        $format = $options['format'] ?? 'png';
        $colors = $options['colors'] ?? null;
        
        // Validate style
        if(!in_array($style, $this->supported_styles)) {
            $style = 'colorful';
        }
        
        // Validate format
        if(!in_array($format, $this->image_formats)) {
            $format = 'png';
        }
        
        // Create image
        $image_data = $this->createImageFromPrompt($prompt, $width, $height, $style, $colors);
        
        if($image_data['status'] !== 'success') {
            return $image_data;
        }
        
        // Save to database
        $image_id = $this->saveGeneratedImage($prompt, $image_data, $options);
        
        return [
            'status' => 'success',
            'image_id' => $image_id,
            'prompt' => $prompt,
            'width' => $width,
            'height' => $height,
            'style' => $style,
            'format' => $format,
            'image_path' => $image_data['path'],
            'preview_url' => ossn_site_url() . 'cache/alkebulan_images/' . basename($image_data['path']),
            'generation_time' => $image_data['generation_time'],
            'created' => time()
        ];
    }
    
    /**
     * Create image from prompt text
     * @param string $prompt The text prompt
     * @param int $width Image width
     * @param int $height Image height
     * @param string $style Visual style
     * @param array $colors Custom colors
     * @return array Image data with path
     */
    private function createImageFromPrompt($prompt, $width, $height, $style, $colors = null) {
        $start_time = microtime(true);
        
        try {
            // Create image resource
            $image = imagecreatetruecolor($width, $height);
            
            // Get colors for style
            $color_palette = $this->getColorPalette($style, $colors);
            
            // Draw background
            $bg_color = imagecolorallocate($image, $color_palette['bg'][0], $color_palette['bg'][1], $color_palette['bg'][2]);
            imagefill($image, 0, 0, $bg_color);
            
            // Draw decorative elements based on style
            $this->drawStyleElements($image, $width, $height, $style, $color_palette);
            
            // Draw prompt text
            $this->drawPromptText($image, $prompt, $width, $height, $color_palette);
            
            // Generate filename
            $filename = 'alkebulan_' . md5($prompt . time()) . '.png';
            $cache_dir = ossn_site_root() . 'cache/alkebulan_images/';
            
            // Ensure directory exists
            if(!is_dir($cache_dir)) {
                @mkdir($cache_dir, 0755, true);
            }
            
            $filepath = $cache_dir . $filename;
            
            // Save image
            imagepng($image, $filepath, 9);
            imagedestroy($image);
            
            $generation_time = round((microtime(true) - $start_time) * 1000, 2);
            
            return [
                'status' => 'success',
                'path' => $filepath,
                'filename' => $filename,
                'generation_time' => $generation_time . ' ms'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to generate image: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get color palette for style
     * @param string $style Visual style
     * @param array $custom_colors Override colors
     * @return array Color palette
     */
    private function getColorPalette($style, $custom_colors = null) {
        $palettes = [
            'abstract' => [
                'bg' => [245, 240, 235],
                'primary' => [100, 150, 200],
                'secondary' => [200, 100, 150],
                'accent' => [255, 180, 100],
                'text' => [50, 50, 50]
            ],
            'minimalist' => [
                'bg' => [255, 255, 255],
                'primary' => [0, 0, 0],
                'secondary' => [100, 100, 100],
                'accent' => [200, 200, 200],
                'text' => [0, 0, 0]
            ],
            'colorful' => [
                'bg' => [30, 30, 50],
                'primary' => [255, 100, 100],
                'secondary' => [100, 255, 100],
                'accent' => [100, 100, 255],
                'text' => [255, 255, 255]
            ],
            'dark' => [
                'bg' => [20, 20, 25],
                'primary' => [100, 200, 255],
                'secondary' => [200, 100, 255],
                'accent' => [255, 200, 100],
                'text' => [220, 220, 220]
            ],
            'gradient' => [
                'bg' => [240, 200, 160],
                'primary' => [255, 100, 50],
                'secondary' => [100, 150, 255],
                'accent' => [255, 255, 100],
                'text' => [50, 50, 50]
            ],
            'geometric' => [
                'bg' => [40, 40, 50],
                'primary' => [0, 200, 200],
                'secondary' => [200, 0, 200],
                'accent' => [255, 255, 0],
                'text' => [200, 200, 200]
            ]
        ];
        
        $palette = $palettes[$style] ?? $palettes['colorful'];
        
        // Override with custom colors if provided
        if($custom_colors && is_array($custom_colors)) {
            foreach($custom_colors as $key => $color) {
                if(isset($palette[$key]) && is_array($color) && count($color) === 3) {
                    $palette[$key] = $color;
                }
            }
        }
        
        return $palette;
    }
    
    /**
     * Draw style-specific decorative elements
     * @param resource $image GD image resource
     * @param int $width Image width
     * @param int $height Image height
     * @param string $style Visual style
     * @param array $palette Color palette
     */
    private function drawStyleElements(&$image, $width, $height, $style, $palette) {
        $primary = imagecolorallocate($image, $palette['primary'][0], $palette['primary'][1], $palette['primary'][2]);
        $secondary = imagecolorallocate($image, $palette['secondary'][0], $palette['secondary'][1], $palette['secondary'][2]);
        $accent = imagecolorallocate($image, $palette['accent'][0], $palette['accent'][1], $palette['accent'][2]);
        
        switch($style) {
            case 'geometric':
                // Draw geometric shapes
                for($i = 0; $i < 5; $i++) {
                    $x = rand(0, $width);
                    $y = rand(0, $height);
                    $size = rand(50, 200);
                    imagefilledrectangle($image, $x, $y, $x + $size, $y + $size, $i % 2 ? $primary : $secondary);
                }
                break;
                
            case 'abstract':
                // Draw abstract shapes
                for($i = 0; $i < 8; $i++) {
                    $points = [];
                    for($j = 0; $j < 6; $j++) {
                        $points[] = rand(0, $width);
                        $points[] = rand(0, $height);
                    }
                    imagefilledpolygon($image, $points, 6, $i % 2 ? $primary : $secondary);
                }
                break;
                
            case 'gradient':
                // Draw gradient-like rectangles
                for($i = 0; $i < $height; $i += 20) {
                    $color = imagecolorallocate($image, 
                        $palette['primary'][0] + ($palette['secondary'][0] - $palette['primary'][0]) * $i / $height,
                        $palette['primary'][1] + ($palette['secondary'][1] - $palette['primary'][1]) * $i / $height,
                        $palette['primary'][2] + ($palette['secondary'][2] - $palette['primary'][2]) * $i / $height
                    );
                    imagefilledrectangle($image, 0, $i, $width, $i + 20, $color);
                }
                break;
                
            case 'colorful':
                // Draw colorful circles
                for($i = 0; $i < 10; $i++) {
                    $x = rand(0, $width);
                    $y = rand(0, $height);
                    $radius = rand(20, 100);
                    $color = [$primary, $secondary, $accent][rand(0, 2)];
                    imagefilledellipse($image, $x, $y, $radius, $radius, $color);
                }
                break;
                
            case 'minimalist':
                // Draw minimal lines
                for($i = 0; $i < 5; $i++) {
                    imageline($image, rand(0, $width), 0, rand(0, $width), $height, $primary);
                    imageline($image, 0, rand(0, $height), $width, rand(0, $height), $primary);
                }
                break;
                
            case 'dark':
                // Draw dark style elements
                for($i = 0; $i < 4; $i++) {
                    $x1 = rand(0, $width);
                    $y1 = rand(0, $height);
                    $x2 = $x1 + rand(50, 300);
                    $y2 = $y1 + rand(50, 300);
                    imagefilledrectangle($image, $x1, $y1, $x2, $y2, $accent);
                }
                break;
        }
    }
    
    /**
     * Draw prompt text on image
     * @param resource $image GD image resource
     * @param string $prompt The text to draw
     * @param int $width Image width
     * @param int $height Image height
     * @param array $palette Color palette
     */
    private function drawPromptText(&$image, $prompt, $width, $height, $palette) {
        $text_color = imagecolorallocate($image, $palette['text'][0], $palette['text'][1], $palette['text'][2]);
        
        // Wrap text for display
        $lines = wordwrap($prompt, 40, "\n", true);
        $lines = explode("\n", $lines);
        
        $font_size = 3;
        $line_height = 20;
        $total_height = count($lines) * $line_height;
        $start_y = max(50, ($height - $total_height) / 2);
        
        $y = $start_y;
        foreach($lines as $line) {
            $x = max(20, ($width - strlen($line) * imagefontwidth($font_size)) / 2);
            imagestring($image, $font_size, $x, $y, $line, $text_color);
            $y += $line_height;
        }
    }
    
    /**
     * Save generated image to database
     * @param string $prompt The original prompt
     * @param array $image_data Image data
     * @param array $options Generation options
     * @return int Image record ID
     */
    private function saveGeneratedImage($prompt, $image_data, $options) {
        $user_id = ossn_loggedin_user()->guid ?? 0;
        $timestamp = time();
        
        // Create alkebulan_images table if not exists
        if(!$this->db->table_exists('alkebulan_images')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `alkebulan_images` (
                    `id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
                    `user_id` BIGINT(20) NOT NULL,
                    `prompt` TEXT,
                    `image_path` VARCHAR(500),
                    `filename` VARCHAR(200),
                    `width` INT DEFAULT 800,
                    `height` INT DEFAULT 600,
                    `style` VARCHAR(50),
                    `format` VARCHAR(10),
                    `generation_time` VARCHAR(50),
                    `file_size` INT,
                    `is_public` TINYINT DEFAULT 0,
                    `downloads` INT DEFAULT 0,
                    `created` BIGINT(20) NOT NULL,
                    KEY `idx_user` (`user_id`),
                    KEY `idx_created` (`created`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }
        
        // Insert record
        $insert = $this->db->insert('alkebulan_images', [
            'user_id' => $user_id,
            'prompt' => $prompt,
            'image_path' => $image_data['path'],
            'filename' => $image_data['filename'],
            'width' => $options['width'] ?? 800,
            'height' => $options['height'] ?? 600,
            'style' => $options['style'] ?? 'colorful',
            'format' => $options['format'] ?? 'png',
            'generation_time' => $image_data['generation_time'],
            'file_size' => filesize($image_data['path']) ?? 0,
            'is_public' => $options['is_public'] ?? 1,
            'created' => $timestamp
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Get user's generated images
     * @param int $user_id User ID
     * @param int $limit Results limit
     * @param int $offset Pagination offset
     * @return array Generated images
     */
    public function getUserImages($user_id, $limit = 20, $offset = 0) {
        $query = "
            SELECT * FROM alkebulan_images
            WHERE user_id = :user_id
            ORDER BY created DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $result = $this->db->query($query, [
            ':user_id' => $user_id,
            ':limit' => (int)$limit,
            ':offset' => (int)$offset
        ]);
        
        return $result->fetchAll(PDO::FETCH_ASSOC) ?? [];
    }
    
    /**
     * Delete generated image
     * @param int $image_id Image record ID
     * @param int $user_id User ID (permission check)
     * @return bool Success status
     */
    public function deleteImage($image_id, $user_id) {
        // Verify ownership
        $image = $this->db->query(
            "SELECT * FROM alkebulan_images WHERE id = :id AND user_id = :user_id",
            [':id' => $image_id, ':user_id' => $user_id]
        )->fetch(PDO::FETCH_ASSOC);
        
        if(!$image) {
            return false;
        }
        
        // Delete file
        if(file_exists($image['image_path'])) {
            @unlink($image['image_path']);
        }
        
        // Delete record
        $this->db->query(
            "DELETE FROM alkebulan_images WHERE id = :id",
            [':id' => $image_id]
        );
        
        return true;
    }
    
    /**
     * Get image generation statistics
     * @param int $user_id User ID
     * @return array Statistics
     */
    public function getImageStats($user_id) {
        $result = $this->db->query("
            SELECT 
                COUNT(*) as total_generated,
                COUNT(DISTINCT style) as styles_used,
                AVG(CAST(generation_time AS DECIMAL(10,2))) as avg_gen_time,
                MAX(created) as last_generated
            FROM alkebulan_images
            WHERE user_id = :user_id
        ", [':user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
        
        return $result ?? [
            'total_generated' => 0,
            'styles_used' => 0,
            'avg_gen_time' => 0,
            'last_generated' => null
        ];
    }
    
    /**
     * Get trending prompts
     * @param int $limit Results limit
     * @return array Top prompts
     */
    public function getTrendingPrompts($limit = 10) {
        $result = $this->db->query("
            SELECT 
                prompt,
                COUNT(*) as usage_count,
                AVG(CAST(REPLACE(generation_time, ' ms', '') AS DECIMAL(10,2))) as avg_time
            FROM alkebulan_images
            WHERE created > (UNIX_TIMESTAMP() - 86400 * 7)
            GROUP BY prompt
            ORDER BY usage_count DESC
            LIMIT :limit
        ", [':limit' => $limit])->fetchAll(PDO::FETCH_ASSOC);
        
        return $result ?? [];
    }
}
?>
