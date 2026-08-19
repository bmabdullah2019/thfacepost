<?php
/**
 * Alkebulan AI - Image Generator
 * Advanced image generation with multiple styles and capabilities
 * 
 * @author Alkebulan AI Team
 * @version 3.0
 */

class ImageGenerator {
    
    private $user_id;
    private $cache;
    private $db;
    private $supported_styles = [
        'abstract' => 'Abstract and artistic style',
        'photorealistic' => 'Photorealistic and detailed',
        'minimalist' => 'Minimalist with clean lines',
        'surreal' => 'Surreal and dreamlike',
        'cyberpunk' => 'Cyberpunk neon style',
        'watercolor' => 'Watercolor painting style',
        'oil_painting' => 'Oil painting style',
        'sketch' => 'Pencil sketch style',
        'cartoon' => 'Cartoon and comic style',
        'dark' => 'Dark and moody',
        'neon' => 'Neon and glowing effects',
        'gradient' => 'Gradient and colorful',
        'geometric' => 'Geometric patterns',
        'vintage' => 'Vintage retro style',
        'anime' => 'Anime and manga style',
        'steampunk' => 'Steampunk mechanical style',
        'fantasy' => 'Fantasy and magical',
        'scifi' => 'Science fiction style'
    ];
    
    private $supported_formats = ['png', 'jpg', 'webp', 'gif'];
    
    private $image_types = [
        'portrait' => 'Human portrait generation',
        'landscape' => 'Nature and landscape',
        'still_life' => 'Objects and compositions',
        'abstract' => 'Abstract compositions',
        'character' => 'Character design',
        'environment' => 'Environment design',
        'weapon' => 'Weapon design',
        'vehicle' => 'Vehicle design',
        'architecture' => 'Building and architecture',
        'creature' => 'Creature and monster design',
        'product' => 'Product design mockups',
        'concept' => 'Concept art'
    ];
    
    private $lighting_styles = [
        'natural' => 'Natural daylight',
        'golden_hour' => 'Golden hour sunset',
        'blue_hour' => 'Blue hour twilight',
        'studio' => 'Studio lighting',
        'dramatic' => 'Dramatic lighting',
        'cinematic' => 'Cinematic lighting',
        'neon' => 'Neon glow',
        'volumetric' => 'Volumetric light rays'
    ];
    
    private $camera_angles = [
        'front' => 'Front facing view',
        'side' => 'Side profile view',
        'top' => 'Top down view',
        'worm_eye' => 'Worm eye view (low angle)',
        'bird_eye' => 'Bird eye view (aerial)',
        'isometric' => 'Isometric perspective',
        '3_4' => '3/4 angle (most common)',
        'close_up' => 'Close-up shot'
    ];
    
    private $quality_presets = [
        'draft' => [
            'resolution' => '512x512',
            'quality' => 'low',
            'steps' => 20,
            'processing_time' => '5-10s',
            'cost' => 'low'
        ],
        'standard' => [
            'resolution' => '768x768',
            'quality' => 'medium',
            'steps' => 40,
            'processing_time' => '15-30s',
            'cost' => 'medium'
        ],
        'hd' => [
            'resolution' => '1024x1024',
            'quality' => 'high',
            'steps' => 60,
            'processing_time' => '30-60s',
            'cost' => 'high'
        ],
        'ultra' => [
            'resolution' => '1536x1536',
            'quality' => 'ultra',
            'steps' => 100,
            'processing_time' => '60-120s',
            'cost' => 'very_high'
        ]
    ];
    
    /**
     * Constructor
     */
    public function __construct($user_id = null) {
        $this->user_id = $user_id ?: (ossn_loggedin_user()->guid ?? 0);
        $this->cache = new CacheManager();
        $this->db = ossn_get_database();
    }
    
    /**
     * Generate image from prompt
     * 
     * @param string $prompt - Description of the image to generate
     * @param array $options - Generation options
     * @return array
     */
    public function generateImage($prompt, $options = []) {
        $start_time = microtime(true);
        
        try {
            // Validate input
            if(empty($prompt) || strlen($prompt) > 2000) {
                return ['status' => 'error', 'message' => 'Prompt must be 1-2000 characters'];
            }
            
            // Normalize options
            $options = $this->normalizeOptions($options);
            
            // Check cache
            $cache_key = 'image_' . md5($prompt . json_encode($options));
            $cached = $this->cache->get($cache_key);
            
            if($cached && !empty($options['use_cache'])) {
                $cached['from_cache'] = true;
                return $cached;
            }
            
            // Build enhanced prompt
            $enhanced_prompt = $this->enhancePrompt($prompt, $options);
            
            // Generate image
            $image_data = $this->performImageGeneration($enhanced_prompt, $options);
            
            if(!$image_data || empty($image_data['image_path'])) {
                return ['status' => 'error', 'message' => 'Image generation failed'];
            }
            
            // Save to database
            $image_id = $this->saveImageToDatabase($image_data, $prompt, $options);
            
            // Calculate processing time
            $processing_time = round((microtime(true) - $start_time) * 1000, 2);
            
            // Prepare response
            $result = [
                'status' => 'success',
                'image_id' => $image_id,
                'image_url' => $image_data['image_url'],
                'image_path' => $image_data['image_path'],
                'prompt' => $prompt,
                'enhanced_prompt' => $enhanced_prompt,
                'style' => $options['style'],
                'dimensions' => $options['width'] . 'x' . $options['height'],
                'format' => $options['format'],
                'quality' => $options['quality'],
                'processing_time_ms' => $processing_time,
                'file_size' => $image_data['file_size'],
                'generation_params' => [
                    'lighting' => $options['lighting'] ?? 'natural',
                    'angle' => $options['angle'] ?? 'front',
                    'color_palette' => $options['colors'] ?? []
                ]
            ];
            
            // Cache result
            $this->cache->set($cache_key, $result, 86400); // 24 hour cache
            
            // Log analytics
            $this->logImageGeneration([
                'prompt' => $prompt,
                'style' => $options['style'],
                'dimensions' => $options['width'] . 'x' . $options['height'],
                'processing_time' => $processing_time,
                'success' => true
            ]);
            
            return $result;
            
        } catch(Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Generation error: ' . $e->getMessage(),
                'error_code' => 'GEN_ERROR'
            ];
        }
    }
    
    /**
     * Generate image variations
     * 
     * @param int $image_id - Original image ID
     * @param int $count - Number of variations (1-10)
     * @param array $modifications - What to change
     * @return array
     */
    public function generateVariations($image_id, $count = 3, $modifications = []) {
        try {
            $count = min(max(1, (int)$count), 10);
            
            // Get original image
            $original = $this->getImageById($image_id);
            if(!$original) {
                return ['status' => 'error', 'message' => 'Original image not found'];
            }
            
            $variations = [];
            $base_prompt = $original['prompt'];
            
            // Generate variations
            for($i = 0; $i < $count; $i++) {
                $modified_prompt = $this->createVariation($base_prompt, $modifications, $i);
                
                $variation = $this->generateImage($modified_prompt, [
                    'style' => $modifications['style'] ?? $original['style'],
                    'width' => $modifications['width'] ?? $original['width'],
                    'height' => $modifications['height'] ?? $original['height'],
                    'format' => $modifications['format'] ?? $original['format'],
                    'parent_id' => $image_id
                ]);
                
                if($variation['status'] === 'success') {
                    $variations[] = $variation;
                }
            }
            
            return [
                'status' => 'success',
                'original_id' => $image_id,
                'variations_count' => count($variations),
                'variations' => $variations
            ];
            
        } catch(Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Generate style transfer
     * Transform an image in a different style
     * 
     * @param int $image_id - Image to transform
     * @param string $target_style - Target style
     * @return array
     */
    public function styleTransfer($image_id, $target_style) {
        try {
            if(!array_key_exists($target_style, $this->supported_styles)) {
                return ['status' => 'error', 'message' => 'Unsupported style'];
            }
            
            $image = $this->getImageById($image_id);
            if(!$image) {
                return ['status' => 'error', 'message' => 'Image not found'];
            }
            
            // Generate image with same prompt but different style
            $result = $this->generateImage($image['prompt'], [
                'style' => $target_style,
                'width' => $image['width'],
                'height' => $image['height'],
                'format' => $image['format']
            ]);
            
            if($result['status'] === 'success') {
                // Link as style transfer
                $this->db->query(
                    "UPDATE alkebulan_images SET style_transfer_of = :parent WHERE id = :id",
                    [':parent' => $image_id, ':id' => $result['image_id']]
                );
            }
            
            return $result;
            
        } catch(Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Upscale image to higher resolution
     * 
     * @param int $image_id - Image to upscale
     * @param string $scale - Scale factor (2x, 4x)
     * @return array
     */
    public function upscaleImage($image_id, $scale = '2x') {
        try {
            $image = $this->getImageById($image_id);
            if(!$image) {
                return ['status' => 'error', 'message' => 'Image not found'];
            }
            
            // Determine new dimensions
            $scale_factor = intval($scale);
            $new_width = $image['width'] * $scale_factor;
            $new_height = $image['height'] * $scale_factor;
            
            // Upscale using image processing
            $upscaled_path = $this->performImageUpscaling(
                $image['image_path'],
                $new_width,
                $new_height
            );
            
            if(!$upscaled_path) {
                return ['status' => 'error', 'message' => 'Upscaling failed'];
            }
            
            // Save upscaled version
            $upscaled_id = $this->saveImageToDatabase([
                'image_path' => $upscaled_path,
                'image_url' => str_replace(ABSPATH, '', $upscaled_path),
                'file_size' => filesize($upscaled_path)
            ], $image['prompt'], [
                'width' => $new_width,
                'height' => $new_height
            ]);
            
            return [
                'status' => 'success',
                'original_id' => $image_id,
                'upscaled_id' => $upscaled_id,
                'original_dimensions' => $image['width'] . 'x' . $image['height'],
                'new_dimensions' => $new_width . 'x' . $new_height,
                'scale' => $scale,
                'upscaled_image_url' => $this->getImageUrl($upscaled_id)
            ];
            
        } catch(Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Get image by ID
     * 
     * @param int $image_id
     * @return array|null
     */
    public function getImageById($image_id) {
        try {
            $image = $this->db->query(
                "SELECT * FROM alkebulan_images WHERE id = :id AND user_id = :user",
                [':id' => (int)$image_id, ':user' => $this->user_id]
            )->fetch(PDO::FETCH_ASSOC);
            
            return $image ?: null;
            
        } catch(Exception $e) {
            return null;
        }
    }
    
    /**
     * Get user's image gallery
     * 
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getGallery($limit = 20, $offset = 0) {
        try {
            $limit = min(max(1, $limit), 100);
            $offset = max(0, $offset);
            
            $images = $this->db->query(
                "SELECT * FROM alkebulan_images 
                 WHERE user_id = :user 
                 ORDER BY created DESC 
                 LIMIT :limit OFFSET :offset",
                [
                    ':user' => $this->user_id,
                    ':limit' => $limit,
                    ':offset' => $offset
                ]
            )->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate statistics
            $total = $this->db->query(
                "SELECT COUNT(*) as count FROM alkebulan_images WHERE user_id = :user",
                [':user' => $this->user_id]
            )->fetch(PDO::FETCH_ASSOC);
            
            return [
                'status' => 'success',
                'images' => $images ?: [],
                'total' => $total['count'],
                'limit' => $limit,
                'offset' => $offset
            ];
            
        } catch(Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Delete image
     * 
     * @param int $image_id
     * @return bool
     */
    public function deleteImage($image_id) {
        try {
            $image = $this->getImageById($image_id);
            if(!$image) {
                return false;
            }
            
            // Delete file
            if(file_exists($image['image_path'])) {
                unlink($image['image_path']);
            }
            
            // Delete from database
            $this->db->query(
                "DELETE FROM alkebulan_images WHERE id = :id AND user_id = :user",
                [':id' => $image_id, ':user' => $this->user_id]
            );
            
            return true;
            
        } catch(Exception $e) {
            return false;
        }
    }
    
    /**
     * Get generation statistics
     * 
     * @return array
     */
    public function getStatistics() {
        try {
            $stats = $this->db->query(
                "SELECT 
                    COUNT(*) as total_images,
                    SUM(file_size) as total_storage,
                    AVG(processing_time) as avg_processing_time,
                    MAX(processing_time) as max_processing_time,
                    COUNT(DISTINCT style) as unique_styles
                 FROM alkebulan_images 
                 WHERE user_id = :user",
                [':user' => $this->user_id]
            )->fetch(PDO::FETCH_ASSOC);
            
            // Style breakdown
            $style_breakdown = $this->db->query(
                "SELECT style, COUNT(*) as count FROM alkebulan_images 
                 WHERE user_id = :user 
                 GROUP BY style",
                [':user' => $this->user_id]
            )->fetchAll(PDO::FETCH_ASSOC);
            
            // Format breakdown
            $format_breakdown = $this->db->query(
                "SELECT format, COUNT(*) as count FROM alkebulan_images 
                 WHERE user_id = :user 
                 GROUP BY format",
                [':user' => $this->user_id]
            )->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'status' => 'success',
                'total_images' => (int)$stats['total_images'],
                'total_storage_bytes' => (int)$stats['total_storage'],
                'total_storage_mb' => round($stats['total_storage'] / 1048576, 2),
                'average_processing_time_ms' => round($stats['avg_processing_time'], 2),
                'max_processing_time_ms' => round($stats['max_processing_time'], 2),
                'unique_styles' => (int)$stats['unique_styles'],
                'style_breakdown' => $style_breakdown ?: [],
                'format_breakdown' => $format_breakdown ?: []
            ];
            
        } catch(Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Get trending prompts
     * 
     * @param int $limit
     * @return array
     */
    public function getTrendingPrompts($limit = 10) {
        try {
            $limit = min(max(1, $limit), 50);
            
            $trending = $this->db->query(
                "SELECT prompt, COUNT(*) as usage_count, 
                    AVG(rating) as avg_rating,
                    GROUP_CONCAT(DISTINCT style) as styles
                 FROM alkebulan_images 
                 WHERE created > DATE_SUB(NOW(), INTERVAL 7 DAY)
                 GROUP BY prompt 
                 ORDER BY usage_count DESC 
                 LIMIT :limit",
                [':limit' => $limit]
            )->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'status' => 'success',
                'trending' => $trending ?: [],
                'timeframe' => '7_days'
            ];
            
        } catch(Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Search images
     * 
     * @param string $query
     * @param array $filters
     * @return array
     */
    public function searchImages($query, $filters = []) {
        try {
            $sql = "SELECT * FROM alkebulan_images WHERE user_id = :user";
            $params = [':user' => $this->user_id];
            
            if(!empty($query)) {
                $sql .= " AND (prompt LIKE :query OR tags LIKE :query)";
                $params[':query'] = '%' . $query . '%';
            }
            
            if(!empty($filters['style'])) {
                $sql .= " AND style = :style";
                $params[':style'] = $filters['style'];
            }
            
            if(!empty($filters['format'])) {
                $sql .= " AND format = :format";
                $params[':format'] = $filters['format'];
            }
            
            if(!empty($filters['min_rating'])) {
                $sql .= " AND rating >= :min_rating";
                $params[':min_rating'] = (float)$filters['min_rating'];
            }
            
            $sql .= " ORDER BY created DESC";
            
            if(!empty($filters['limit'])) {
                $sql .= " LIMIT " . min((int)$filters['limit'], 100);
            }
            
            $results = $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'status' => 'success',
                'results' => $results ?: [],
                'count' => count($results)
            ];
            
        } catch(Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Rate image
     * 
     * @param int $image_id
     * @param int $rating - 1-5 stars
     * @return bool
     */
    public function rateImage($image_id, $rating) {
        try {
            $rating = min(max(1, (int)$rating), 5);
            
            $this->db->query(
                "UPDATE alkebulan_images SET rating = :rating WHERE id = :id AND user_id = :user",
                [
                    ':rating' => $rating,
                    ':id' => $image_id,
                    ':user' => $this->user_id
                ]
            );
            
            return true;
            
        } catch(Exception $e) {
            return false;
        }
    }
    
    /**
     * Get supported styles
     */
    public function getSupportedStyles() {
        return [
            'status' => 'success',
            'styles' => $this->supported_styles,
            'total' => count($this->supported_styles)
        ];
    }
    
    /**
     * Get supported formats
     */
    public function getSupportedFormats() {
        return [
            'status' => 'success',
            'formats' => $this->supported_formats,
            'total' => count($this->supported_formats)
        ];
    }
    
    /**
     * Get image types
     */
    public function getImageTypes() {
        return [
            'status' => 'success',
            'types' => $this->image_types,
            'total' => count($this->image_types)
        ];
    }
    
    /**
     * Get quality presets
     */
    public function getQualityPresets() {
        return [
            'status' => 'success',
            'presets' => $this->quality_presets,
            'total' => count($this->quality_presets)
        ];
    }
    
    /**
     * Get lighting styles
     */
    public function getLightingStyles() {
        return [
            'status' => 'success',
            'lighting' => $this->lighting_styles,
            'total' => count($this->lighting_styles)
        ];
    }
    
    /**
     * Get camera angles
     */
    public function getCameraAngles() {
        return [
            'status' => 'success',
            'angles' => $this->camera_angles,
            'total' => count($this->camera_angles)
        ];
    }
    
    /**
     * === PRIVATE HELPER METHODS ===
     */
    
    /**
     * Normalize options
     */
    private function normalizeOptions($options) {
        return [
            'width' => min(max((int)($options['width'] ?? 800), 256), 1536),
            'height' => min(max((int)($options['height'] ?? 800), 256), 1536),
            'style' => array_key_exists($options['style'] ?? 'abstract', $this->supported_styles) 
                ? $options['style'] : 'abstract',
            'format' => in_array($options['format'] ?? 'png', $this->supported_formats) 
                ? $options['format'] : 'png',
            'quality' => array_key_exists($options['quality'] ?? 'standard', $this->quality_presets)
                ? $options['quality'] : 'standard',
            'lighting' => array_key_exists($options['lighting'] ?? 'natural', $this->lighting_styles)
                ? $options['lighting'] : 'natural',
            'angle' => array_key_exists($options['angle'] ?? 'front', $this->camera_angles)
                ? $options['angle'] : 'front',
            'colors' => is_array($options['colors'] ?? null) ? $options['colors'] : [],
            'use_cache' => (bool)($options['use_cache'] ?? true),
            'tags' => $options['tags'] ?? '',
            'is_public' => (bool)($options['is_public'] ?? false)
        ];
    }
    
    /**
     * Enhance prompt with style and technical details
     */
    private function enhancePrompt($prompt, $options) {
        $enhancements = [];
        
        // Add style
        if($options['style'] !== 'abstract') {
            $enhancements[] = "in " . $options['style'] . " style";
        }
        
        // Add lighting
        if($options['lighting'] !== 'natural') {
            $enhancements[] = "with " . $options['lighting'] . " lighting";
        }
        
        // Add angle
        if($options['angle'] !== 'front') {
            $enhancements[] = "from " . $options['angle'] . " perspective";
        }
        
        // Add quality hints
        $quality = $this->quality_presets[$options['quality']];
        $enhancements[] = $quality['quality'] . " quality, detailed";
        
        $enhanced = $prompt;
        if(!empty($enhancements)) {
            $enhanced .= ", " . implode(", ", $enhancements);
        }
        
        return $enhanced;
    }
    
    /**
     * Perform actual image generation
     */
    private function performImageGeneration($prompt, $options) {
        try {
            // Simulate image generation (replace with actual API call)
            $temp_file = tempnam(sys_get_temp_dir(), 'alkebulan_img_');
            
            // Create placeholder image or call real API
            $image = imagecreatetruecolor($options['width'], $options['height']);
            
            // Apply gradient background
            $colors = $options['colors'];
            if(!empty($colors)) {
                $color = imagecolorallocate($image, 
                    $colors[0] ?? 100,
                    $colors[1] ?? 150,
                    $colors[2] ?? 200
                );
            } else {
                $color = imagecolorallocate($image, 100, 150, 200);
            }
            
            imagefill($image, 0, 0, $color);
            
            // Add text
            $text_color = imagecolorallocate($image, 255, 255, 255);
            $text = substr($prompt, 0, 50) . '...';
            imagestring($image, 3, 10, 10, $text, $text_color);
            
            // Save image
            $output_dir = ABSPATH . 'cache/images/' . date('Y/m/d/');
            if(!is_dir($output_dir)) {
                mkdir($output_dir, 0755, true);
            }
            
            $filename = uniqid('alkebulan_') . '.' . $options['format'];
            $filepath = $output_dir . $filename;
            
            switch($options['format']) {
                case 'jpg':
                    imagejpeg($image, $filepath, 90);
                    break;
                case 'png':
                    imagepng($image, $filepath, 9);
                    break;
                case 'webp':
                    imagewebp($image, $filepath, 90);
                    break;
                default:
                    imagepng($image, $filepath);
            }
            
            imagedestroy($image);
            
            return [
                'image_path' => $filepath,
                'image_url' => str_replace(ABSPATH, '/', $filepath),
                'file_size' => filesize($filepath),
                'filename' => $filename
            ];
            
        } catch(Exception $e) {
            return null;
        }
    }
    
    /**
     * Perform image upscaling
     */
    private function performImageUpscaling($image_path, $new_width, $new_height) {
        try {
            if(!file_exists($image_path)) {
                return null;
            }
            
            $image = imagecreatefromstring(file_get_contents($image_path));
            if(!$image) {
                return null;
            }
            
            $upscaled = imagescale($image, $new_width, $new_height);
            
            $output_dir = dirname($image_path) . '/upscaled/';
            if(!is_dir($output_dir)) {
                mkdir($output_dir, 0755, true);
            }
            
            $ext = pathinfo($image_path, PATHINFO_EXTENSION);
            $filepath = $output_dir . uniqid('upscaled_') . '.' . $ext;
            
            imagepng($upscaled, $filepath);
            imagedestroy($image);
            imagedestroy($upscaled);
            
            return $filepath;
            
        } catch(Exception $e) {
            return null;
        }
    }
    
    /**
     * Save image to database
     */
    private function saveImageToDatabase($image_data, $prompt, $options) {
        try {
            $this->db->query(
                "INSERT INTO alkebulan_images 
                 (user_id, prompt, style, width, height, format, quality, 
                  lighting, angle, image_path, filename, file_size, processing_time, created)
                 VALUES 
                 (:user, :prompt, :style, :width, :height, :format, :quality,
                  :lighting, :angle, :image_path, :filename, :file_size, :processing_time, :created)",
                [
                    ':user' => $this->user_id,
                    ':prompt' => $prompt,
                    ':style' => $options['style'] ?? 'abstract',
                    ':width' => $options['width'] ?? 800,
                    ':height' => $options['height'] ?? 800,
                    ':format' => $options['format'] ?? 'png',
                    ':quality' => $options['quality'] ?? 'standard',
                    ':lighting' => $options['lighting'] ?? 'natural',
                    ':angle' => $options['angle'] ?? 'front',
                    ':image_path' => $image_data['image_path'],
                    ':filename' => $image_data['filename'] ?? basename($image_data['image_path']),
                    ':file_size' => $image_data['file_size'] ?? 0,
                    ':processing_time' => $options['processing_time'] ?? 0,
                    ':created' => time()
                ]
            );
            
            return $this->db->lastInsertId();
            
        } catch(Exception $e) {
            return 0;
        }
    }
    
    /**
     * Create variation of prompt
     */
    private function createVariation($base_prompt, $modifications, $variation_index) {
        $variations = [
            " with different composition",
            " from a different angle",
            " with altered lighting",
            " with different colors",
            " at a different time of day",
            " with different mood",
            " with different focus",
            " with zoomed in perspective",
            " with wider perspective",
            " with more details"
        ];
        
        return $base_prompt . $variations[$variation_index % count($variations)];
    }
    
    /**
     * Get image URL
     */
    private function getImageUrl($image_id) {
        $image = $this->getImageById($image_id);
        return $image ? $image['image_path'] : '';
    }
    
    /**
     * Log image generation analytics
     */
    private function logImageGeneration($data) {
        try {
            $analytics = new AIAnalytics();
            $analytics->logUsage($this->user_id, 'image_generation', $data);
        } catch(Exception $e) {
            // Silent fail for logging
        }
    }
}
?>
