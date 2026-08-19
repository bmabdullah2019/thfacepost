<?php
/**
 * Alkebulan AI - Image Generation API Handler
 * Complete REST API for image generation, management, and analytics
 * 
 * @version 3.0
 * @author Alkebulan AI Team
 */

// Validate user is logged in
if(!ossn_isLoggedin()) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    return;
}

$user = ossn_loggedin_user();
$generator = new ImageGenerator($user->guid);

// Get action from URL
$action = array_shift(__OSSN_REQUESTED_ACTION__) ?: 'generate';

// Main API router
switch($action) {
    
    /**
     * CORE GENERATION ENDPOINTS
     */
    
    case 'generate':
        /**
         * Generate image from prompt
         * POST /alkebulan/action/image/generate
         * 
         * Required: prompt
         * Optional: style, width, height, format, quality, lighting, angle, colors, tags, is_public
         */
        handleImageGeneration($generator, $user);
        break;
    
    case 'variations':
        /**
         * Generate variations of an image
         * POST /alkebulan/action/image/variations
         * 
         * Required: image_id, count
         * Optional: style, angle, lighting
         */
        handleGenerateVariations($generator, $user);
        break;
    
    case 'style_transfer':
        /**
         * Apply different style to existing image
         * POST /alkebulan/action/image/style_transfer
         * 
         * Required: image_id, target_style
         */
        handleStyleTransfer($generator, $user);
        break;
    
    case 'upscale':
        /**
         * Upscale image to higher resolution
         * POST /alkebulan/action/image/upscale
         * 
         * Required: image_id, scale (2x, 4x)
         */
        handleImageUpscale($generator, $user);
        break;
    
    /**
     * GALLERY & MANAGEMENT ENDPOINTS
     */
    
    case 'gallery':
        /**
         * Get user's image gallery
         * GET /alkebulan/action/image/gallery
         * 
         * Optional: limit (1-100), offset (0+)
         */
        handleGetGallery($generator, $user);
        break;
    
    case 'search':
        /**
         * Search user's images
         * GET /alkebulan/action/image/search
         * 
         * Optional: query, style, format, min_rating, limit
         */
        handleSearchImages($generator, $user);
        break;
    
    case 'get':
        /**
         * Get single image details
         * GET /alkebulan/action/image/get
         * 
         * Required: image_id
         */
        handleGetImage($generator, $user);
        break;
    
    case 'delete':
        /**
         * Delete image
         * POST /alkebulan/action/image/delete
         * 
         * Required: image_id
         */
        handleDeleteImage($generator, $user);
        break;
    
    case 'rate':
        /**
         * Rate image (1-5 stars)
         * POST /alkebulan/action/image/rate
         * 
         * Required: image_id, rating (1-5)
         */
        handleRateImage($generator, $user);
        break;
    
    /**
     * ANALYTICS & INFO ENDPOINTS
     */
    
    case 'stats':
        /**
         * Get generation statistics
         * GET /alkebulan/action/image/stats
         */
        handleGetStatistics($generator, $user);
        break;
    
    case 'trending':
        /**
         * Get trending prompts
         * GET /alkebulan/action/image/trending
         * 
         * Optional: limit (1-50)
         */
        handleGetTrending($generator, $user);
        break;
    
    /**
     * REFERENCE DATA ENDPOINTS
     */
    
    case 'styles':
        /**
         * Get supported styles
         * GET /alkebulan/action/image/styles
         */
        echo json_encode($generator->getSupportedStyles());
        break;
    
    case 'formats':
        /**
         * Get supported formats
         * GET /alkebulan/action/image/formats
         */
        echo json_encode($generator->getSupportedFormats());
        break;
    
    case 'types':
        /**
         * Get image types
         * GET /alkebulan/action/image/types
         */
        echo json_encode($generator->getImageTypes());
        break;
    
    case 'presets':
        /**
         * Get quality presets
         * GET /alkebulan/action/image/presets
         */
        echo json_encode($generator->getQualityPresets());
        break;
    
    case 'lighting':
        /**
         * Get lighting styles
         * GET /alkebulan/action/image/lighting
         */
        echo json_encode($generator->getLightingStyles());
        break;
    
    case 'angles':
        /**
         * Get camera angles
         * GET /alkebulan/action/image/angles
         */
        echo json_encode($generator->getCameraAngles());
        break;
    
    /**
     * UTILITIES & HELP
     */
    
    case 'help':
        /**
         * Get API documentation
         * GET /alkebulan/action/image/help
         */
        handleGetHelp();
        break;
    
    default:
        /**
         * Invalid action
         */
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid image action: ' . htmlspecialchars($action),
            'available_actions' => [
                'generation' => [
                    'generate' => 'Generate image from prompt',
                    'variations' => 'Generate variations',
                    'style_transfer' => 'Apply style to image',
                    'upscale' => 'Upscale image resolution'
                ],
                'gallery' => [
                    'gallery' => 'Get image gallery',
                    'search' => 'Search images',
                    'get' => 'Get image details',
                    'delete' => 'Delete image',
                    'rate' => 'Rate image'
                ],
                'analytics' => [
                    'stats' => 'Get statistics',
                    'trending' => 'Get trending prompts'
                ],
                'reference' => [
                    'styles' => 'Supported styles',
                    'formats' => 'Supported formats',
                    'types' => 'Image types',
                    'presets' => 'Quality presets',
                    'lighting' => 'Lighting styles',
                    'angles' => 'Camera angles'
                ]
            ]
        ]);
        break;
}

/**
 * HANDLER FUNCTIONS
 */

/**
 * Handle image generation
 */
function handleImageGeneration($generator, $user) {
    $prompt = sanitize_user_input($_REQUEST['prompt'] ?? '');
    
    if(empty($prompt) || strlen($prompt) > 2000) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Prompt is required and must be 1-2000 characters',
            'field' => 'prompt'
        ]);
        return;
    }
    
    $options = [
        'style' => sanitize_user_input($_REQUEST['style'] ?? 'abstract'),
        'width' => (int)($_REQUEST['width'] ?? 800),
        'height' => (int)($_REQUEST['height'] ?? 800),
        'format' => sanitize_user_input($_REQUEST['format'] ?? 'png'),
        'quality' => sanitize_user_input($_REQUEST['quality'] ?? 'standard'),
        'lighting' => sanitize_user_input($_REQUEST['lighting'] ?? 'natural'),
        'angle' => sanitize_user_input($_REQUEST['angle'] ?? 'front'),
        'tags' => sanitize_user_input($_REQUEST['tags'] ?? ''),
        'is_public' => (int)($_REQUEST['is_public'] ?? 0)
    ];
    
    // Handle custom colors
    if(!empty($_REQUEST['colors'])) {
        $colors = json_decode($_REQUEST['colors'], true);
        if(is_array($colors) && count($colors) <= 3) {
            $options['colors'] = array_map('intval', $colors);
        }
    }
    
    $result = $generator->generateImage($prompt, $options);
    echo json_encode($result);
}

/**
 * Handle generating variations
 */
function handleGenerateVariations($generator, $user) {
    $image_id = (int)($_REQUEST['image_id'] ?? 0);
    $count = min(max(1, (int)($_REQUEST['count'] ?? 3)), 10);
    
    if(empty($image_id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Image ID is required',
            'field' => 'image_id'
        ]);
        return;
    }
    
    $modifications = [];
    if(!empty($_REQUEST['style'])) {
        $modifications['style'] = sanitize_user_input($_REQUEST['style']);
    }
    if(!empty($_REQUEST['angle'])) {
        $modifications['angle'] = sanitize_user_input($_REQUEST['angle']);
    }
    if(!empty($_REQUEST['lighting'])) {
        $modifications['lighting'] = sanitize_user_input($_REQUEST['lighting']);
    }
    
    $result = $generator->generateVariations($image_id, $count, $modifications);
    echo json_encode($result);
}

/**
 * Handle style transfer
 */
function handleStyleTransfer($generator, $user) {
    $image_id = (int)($_REQUEST['image_id'] ?? 0);
    $target_style = sanitize_user_input($_REQUEST['target_style'] ?? '');
    
    if(empty($image_id) || empty($target_style)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Image ID and target style are required'
        ]);
        return;
    }
    
    $result = $generator->styleTransfer($image_id, $target_style);
    echo json_encode($result);
}

/**
 * Handle image upscaling
 */
function handleImageUpscale($generator, $user) {
    $image_id = (int)($_REQUEST['image_id'] ?? 0);
    $scale = sanitize_user_input($_REQUEST['scale'] ?? '2x');
    
    if(empty($image_id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Image ID is required'
        ]);
        return;
    }
    
    if(!in_array($scale, ['2x', '4x'])) {
        $scale = '2x';
    }
    
    $result = $generator->upscaleImage($image_id, $scale);
    echo json_encode($result);
}

/**
 * Handle get gallery
 */
function handleGetGallery($generator, $user) {
    $limit = min(max(1, (int)($_REQUEST['limit'] ?? 20)), 100);
    $offset = max(0, (int)($_REQUEST['offset'] ?? 0));
    
    $result = $generator->getGallery($limit, $offset);
    echo json_encode($result);
}

/**
 * Handle search images
 */
function handleSearchImages($generator, $user) {
    $query = sanitize_user_input($_REQUEST['query'] ?? '');
    
    $filters = [
        'style' => sanitize_user_input($_REQUEST['style'] ?? ''),
        'format' => sanitize_user_input($_REQUEST['format'] ?? ''),
        'min_rating' => (float)($_REQUEST['min_rating'] ?? 0),
        'limit' => min(max(1, (int)($_REQUEST['limit'] ?? 20)), 100)
    ];
    
    $result = $generator->searchImages($query, $filters);
    echo json_encode($result);
}

/**
 * Handle get image
 */
function handleGetImage($generator, $user) {
    $image_id = (int)($_REQUEST['image_id'] ?? 0);
    
    if(empty($image_id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Image ID is required'
        ]);
        return;
    }
    
    $image = $generator->getImageById($image_id);
    
    if(!$image) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Image not found'
        ]);
        return;
    }
    
    echo json_encode([
        'status' => 'success',
        'image' => $image
    ]);
}

/**
 * Handle delete image
 */
function handleDeleteImage($generator, $user) {
    $image_id = (int)($_REQUEST['image_id'] ?? 0);
    
    if(empty($image_id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Image ID is required'
        ]);
        return;
    }
    
    $success = $generator->deleteImage($image_id);
    
    echo json_encode([
        'status' => $success ? 'success' : 'error',
        'message' => $success ? 'Image deleted successfully' : 'Failed to delete image'
    ]);
}

/**
 * Handle rate image
 */
function handleRateImage($generator, $user) {
    $image_id = (int)($_REQUEST['image_id'] ?? 0);
    $rating = (int)($_REQUEST['rating'] ?? 0);
    
    if(empty($image_id) || $rating < 1 || $rating > 5) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Image ID and rating (1-5) are required'
        ]);
        return;
    }
    
    $success = $generator->rateImage($image_id, $rating);
    
    echo json_encode([
        'status' => $success ? 'success' : 'error',
        'message' => $success ? 'Rating saved' : 'Failed to save rating'
    ]);
}

/**
 * Handle get statistics
 */
function handleGetStatistics($generator, $user) {
    $result = $generator->getStatistics();
    echo json_encode($result);
}

/**
 * Handle get trending
 */
function handleGetTrending($generator, $user) {
    $limit = min(max(1, (int)($_REQUEST['limit'] ?? 10)), 50);
    
    $result = $generator->getTrendingPrompts($limit);
    echo json_encode($result);
}

/**
 * Handle get help
 */
function handleGetHelp() {
    echo json_encode([
        'status' => 'success',
        'documentation' => 'https://alkebulan.local/docs/image-api',
        'endpoints' => [
            [
                'method' => 'POST',
                'path' => '/alkebulan/action/image/generate',
                'description' => 'Generate image from prompt',
                'parameters' => [
                    'prompt' => 'Text description (required)',
                    'style' => 'Visual style (optional)',
                    'width' => 'Image width in pixels (optional)',
                    'height' => 'Image height in pixels (optional)',
                    'format' => 'Output format: png, jpg, webp (optional)',
                    'quality' => 'Quality preset: draft, standard, hd, ultra (optional)',
                    'lighting' => 'Lighting style (optional)',
                    'angle' => 'Camera angle (optional)',
                    'colors' => 'Custom colors array (optional)',
                    'tags' => 'Comma-separated tags (optional)',
                    'is_public' => 'Make public: 0 or 1 (optional)'
                ]
            ],
            [
                'method' => 'POST',
                'path' => '/alkebulan/action/image/variations',
                'description' => 'Generate image variations',
                'parameters' => [
                    'image_id' => 'Original image ID (required)',
                    'count' => 'Number of variations 1-10 (optional)',
                    'style' => 'Override style (optional)',
                    'angle' => 'Override angle (optional)',
                    'lighting' => 'Override lighting (optional)'
                ]
            ],
            [
                'method' => 'POST',
                'path' => '/alkebulan/action/image/style_transfer',
                'description' => 'Apply different style to image',
                'parameters' => [
                    'image_id' => 'Image ID (required)',
                    'target_style' => 'Target style (required)'
                ]
            ],
            [
                'method' => 'POST',
                'path' => '/alkebulan/action/image/upscale',
                'description' => 'Upscale image resolution',
                'parameters' => [
                    'image_id' => 'Image ID (required)',
                    'scale' => 'Scale factor: 2x or 4x (optional)'
                ]
            ],
            [
                'method' => 'GET',
                'path' => '/alkebulan/action/image/gallery',
                'description' => 'Get user image gallery',
                'parameters' => [
                    'limit' => 'Items per page 1-100 (optional)',
                    'offset' => 'Pagination offset (optional)'
                ]
            ],
            [
                'method' => 'GET',
                'path' => '/alkebulan/action/image/search',
                'description' => 'Search user images',
                'parameters' => [
                    'query' => 'Search query (optional)',
                    'style' => 'Filter by style (optional)',
                    'format' => 'Filter by format (optional)',
                    'min_rating' => 'Minimum rating 1-5 (optional)',
                    'limit' => 'Results limit (optional)'
                ]
            ],
            [
                'method' => 'GET',
                'path' => '/alkebulan/action/image/get',
                'description' => 'Get image details',
                'parameters' => [
                    'image_id' => 'Image ID (required)'
                ]
            ],
            [
                'method' => 'POST',
                'path' => '/alkebulan/action/image/delete',
                'description' => 'Delete image',
                'parameters' => [
                    'image_id' => 'Image ID (required)'
                ]
            ],
            [
                'method' => 'POST',
                'path' => '/alkebulan/action/image/rate',
                'description' => 'Rate image 1-5 stars',
                'parameters' => [
                    'image_id' => 'Image ID (required)',
                    'rating' => 'Rating 1-5 (required)'
                ]
            ],
            [
                'method' => 'GET',
                'path' => '/alkebulan/action/image/stats',
                'description' => 'Get generation statistics',
                'parameters' => []
            ],
            [
                'method' => 'GET',
                'path' => '/alkebulan/action/image/trending',
                'description' => 'Get trending prompts',
                'parameters' => [
                    'limit' => 'Number of results 1-50 (optional)'
                ]
            ],
            [
                'method' => 'GET',
                'path' => '/alkebulan/action/image/styles',
                'description' => 'Get supported styles',
                'parameters' => []
            ],
            [
                'method' => 'GET',
                'path' => '/alkebulan/action/image/formats',
                'description' => 'Get supported formats',
                'parameters' => []
            ],
            [
                'method' => 'GET',
                'path' => '/alkebulan/action/image/types',
                'description' => 'Get image types',
                'parameters' => []
            ],
            [
                'method' => 'GET',
                'path' => '/alkebulan/action/image/presets',
                'description' => 'Get quality presets',
                'parameters' => []
            ]
        ],
        'examples' => [
            'simple_generation' => [
                'method' => 'POST',
                'url' => '/alkebulan/action/image/generate',
                'data' => [
                    'prompt' => 'Beautiful sunset over ocean waves',
                    'style' => 'photorealistic',
                    'width' => 1024,
                    'height' => 1024,
                    'quality' => 'hd'
                ]
            ],
            'advanced_generation' => [
                'method' => 'POST',
                'url' => '/alkebulan/action/image/generate',
                'data' => [
                    'prompt' => 'Cyberpunk city street at night',
                    'style' => 'cyberpunk',
                    'width' => 1024,
                    'height' => 1024,
                    'quality' => 'hd',
                    'lighting' => 'neon',
                    'angle' => 'worm_eye',
                    'colors' => [255, 0, 255]
                ]
            ]
        ]
    ]);
}

/**
 * Sanitize user input
 */
function sanitize_user_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

?>
