<?php
/**
 * Alkebulan AI - Image Generation API Handler
 * Endpoints for image generation and management
 */

$action = $request->urlSegments[2] ?? '';

switch($action) {
    
    case 'generate':
        /**
         * Generate image from prompt
         * POST /action/alkebulan/image/generate
         * 
         * Parameters:
         * - prompt: Text prompt for image generation
         * - style: Visual style (abstract, minimalist, colorful, dark, gradient, geometric)
         * - width: Image width (default 800, max 1024)
         * - height: Image height (default 600, max 1024)
         * - format: Image format (png, jpg, webp) - default png
         * - colors: Custom color scheme
         * - is_public: Make image public (0/1)
         */
        
        if(!ossn_loggedin_user()) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        $prompt = $_REQUEST['prompt'] ?? '';
        
        if(empty($prompt)) {
            echo json_encode(['status' => 'error', 'message' => 'Prompt is required']);
            return;
        }
        
        $generator = new AIImageGenerator();
        
        $options = [
            'width' => min((int)$_REQUEST['width'] ?? 800, 1024),
            'height' => min((int)$_REQUEST['height'] ?? 600, 1024),
            'style' => $_REQUEST['style'] ?? 'colorful',
            'format' => $_REQUEST['format'] ?? 'png',
            'is_public' => (int)$_REQUEST['is_public'] ?? 1
        ];
        
        // Handle custom colors if provided
        if(!empty($_REQUEST['colors'])) {
            $options['colors'] = json_decode($_REQUEST['colors'], true);
        }
        
        $result = $generator->generateImage($prompt, $options);
        
        // Log usage
        $logger = new AIAnalytics();
        $logger->logUsage(ossn_loggedin_user()->guid, 'image_generation', [
            'prompt_length' => strlen($prompt),
            'style' => $options['style'],
            'dimensions' => $options['width'] . 'x' . $options['height']
        ]);
        
        echo json_encode($result);
        break;
    
    case 'gallery':
        /**
         * Get user's generated images
         * GET /action/alkebulan/image/gallery
         * 
         * Parameters:
         * - limit: Results per page (default 20)
         * - page: Page number (default 1)
         */
        
        if(!ossn_loggedin_user()) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        $limit = min((int)$_REQUEST['limit'] ?? 20, 50);
        $page = max((int)$_REQUEST['page'] ?? 1, 1);
        $offset = ($page - 1) * $limit;
        
        $generator = new AIImageGenerator();
        $images = $generator->getUserImages(ossn_loggedin_user()->guid, $limit, $offset);
        
        echo json_encode([
            'status' => 'success',
            'images' => $images,
            'page' => $page,
            'limit' => $limit,
            'count' => count($images)
        ]);
        break;
    
    case 'delete':
        /**
         * Delete generated image
         * POST /action/alkebulan/image/delete
         * 
         * Parameters:
         * - image_id: Image record ID to delete
         */
        
        if(!ossn_loggedin_user()) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        $image_id = (int)$_REQUEST['image_id'] ?? 0;
        
        if(empty($image_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Image ID is required']);
            return;
        }
        
        $generator = new AIImageGenerator();
        $success = $generator->deleteImage($image_id, ossn_loggedin_user()->guid);
        
        if($success) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Image deleted successfully'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to delete image or permission denied'
            ]);
        }
        break;
    
    case 'stats':
        /**
         * Get image generation statistics
         * GET /action/alkebulan/image/stats
         */
        
        if(!ossn_loggedin_user()) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        $generator = new AIImageGenerator();
        $stats = $generator->getImageStats(ossn_loggedin_user()->guid);
        
        echo json_encode([
            'status' => 'success',
            'stats' => $stats
        ]);
        break;
    
    case 'trending':
        /**
         * Get trending prompts
         * GET /action/alkebulan/image/trending
         * 
         * Parameters:
         * - limit: Number of trending prompts (default 10)
         */
        
        $limit = min((int)$_REQUEST['limit'] ?? 10, 50);
        
        $generator = new AIImageGenerator();
        $trending = $generator->getTrendingPrompts($limit);
        
        echo json_encode([
            'status' => 'success',
            'trending' => $trending,
            'count' => count($trending)
        ]);
        break;
    
    case 'download':
        /**
         * Download generated image
         * GET /action/alkebulan/image/download?image_id=123
         */
        
        if(!ossn_loggedin_user()) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        $image_id = (int)$_REQUEST['image_id'] ?? 0;
        
        if(empty($image_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Image ID is required']);
            return;
        }
        
        $db = ossn_get_database();
        $image = $db->query(
            "SELECT * FROM alkebulan_images WHERE id = :id",
            [':id' => $image_id]
        )->fetch(PDO::FETCH_ASSOC);
        
        if(!$image) {
            echo json_encode(['status' => 'error', 'message' => 'Image not found']);
            return;
        }
        
        if(!file_exists($image['image_path'])) {
            echo json_encode(['status' => 'error', 'message' => 'Image file not found']);
            return;
        }
        
        // Increment download counter
        $db->query(
            "UPDATE alkebulan_images SET downloads = downloads + 1 WHERE id = :id",
            [':id' => $image_id]
        );
        
        // Download file
        header('Content-Type: image/' . pathinfo($image['image_path'], PATHINFO_EXTENSION));
        header('Content-Length: ' . filesize($image['image_path']));
        header('Content-Disposition: attachment; filename="' . $image['filename'] . '"');
        readfile($image['image_path']);
        exit;
        break;
    
    default:
        /**
         * Invalid action
         */
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid image action',
            'available_actions' => [
                'generate' => 'Generate image from prompt',
                'gallery' => 'Get user gallery',
                'delete' => 'Delete image',
                'stats' => 'Get user statistics',
                'trending' => 'Get trending prompts',
                'download' => 'Download image'
            ]
        ]);
        break;
}
?>
