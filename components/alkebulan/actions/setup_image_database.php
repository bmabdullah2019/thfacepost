<?php
/**
 * Alkebulan AI - Image Generation Database Setup
 * SQL schema for image generation, caching, and analytics tables
 * 
 * Run this script once to initialize database tables
 */

// Database connection
$db = ossn_get_database();

try {
    
    /**
     * Main Images Table
     * Stores all generated images and their metadata
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_images (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            prompt VARCHAR(2000),
            enhanced_prompt LONGTEXT,
            style VARCHAR(50),
            image_type VARCHAR(50),
            width INT DEFAULT 800,
            height INT DEFAULT 800,
            format VARCHAR(10) DEFAULT 'png',
            quality VARCHAR(20) DEFAULT 'standard',
            lighting VARCHAR(50),
            angle VARCHAR(50),
            colors JSON,
            image_path VARCHAR(500),
            image_url VARCHAR(500),
            filename VARCHAR(255),
            file_size BIGINT DEFAULT 0,
            processing_time FLOAT DEFAULT 0,
            rating INT DEFAULT 0,
            downloads INT DEFAULT 0,
            views INT DEFAULT 0,
            tags VARCHAR(500),
            is_public BOOLEAN DEFAULT 0,
            style_transfer_of INT,
            parent_id INT,
            created BIGINT,
            updated BIGINT,
            INDEX idx_user_created (user_id, created),
            INDEX idx_style (style),
            INDEX idx_format (format),
            INDEX idx_public (is_public),
            INDEX idx_rating (rating),
            FULLTEXT idx_search (prompt, tags)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Image Generation Cache Table
     * Caches generated images to avoid regeneration
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_image_cache (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            prompt_hash VARCHAR(64),
            style VARCHAR(50),
            quality VARCHAR(20),
            cache_key VARCHAR(255) UNIQUE,
            image_data LONGBLOB,
            image_id INT,
            metadata JSON,
            expires BIGINT,
            created BIGINT,
            INDEX idx_user_hash (user_id, prompt_hash),
            INDEX idx_expires (expires)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Image Generation History Table
     * Tracks all generation requests for analytics
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_image_history (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            image_id INT,
            prompt VARCHAR(2000),
            style VARCHAR(50),
            quality VARCHAR(20),
            width INT,
            height INT,
            processing_time FLOAT,
            success BOOLEAN DEFAULT 1,
            error_message VARCHAR(500),
            cache_hit BOOLEAN DEFAULT 0,
            created BIGINT,
            INDEX idx_user_created (user_id, created),
            INDEX idx_image (image_id),
            INDEX idx_success (success)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Image Collections Table
     * User-created collections/albums of images
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_image_collections (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            name VARCHAR(255),
            description TEXT,
            cover_image_id INT,
            is_public BOOLEAN DEFAULT 0,
            image_count INT DEFAULT 0,
            created BIGINT,
            updated BIGINT,
            INDEX idx_user (user_id),
            INDEX idx_public (is_public)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Collection Images Junction Table
     * Maps images to collections (many-to-many)
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_collection_images (
            id INT PRIMARY KEY AUTO_INCREMENT,
            collection_id INT NOT NULL,
            image_id INT NOT NULL,
            position INT,
            added_at BIGINT,
            UNIQUE KEY unique_collection_image (collection_id, image_id),
            INDEX idx_image (image_id),
            FOREIGN KEY (collection_id) REFERENCES alkebulan_image_collections(id) ON DELETE CASCADE,
            FOREIGN KEY (image_id) REFERENCES alkebulan_images(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Image Sharing Table
     * Track shared images and share links
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_image_shares (
            id INT PRIMARY KEY AUTO_INCREMENT,
            image_id INT NOT NULL,
            user_id INT NOT NULL,
            share_token VARCHAR(64) UNIQUE,
            shared_with_user_id INT,
            share_type VARCHAR(20), -- 'public', 'private', 'group'
            expires BIGINT,
            view_count INT DEFAULT 0,
            created BIGINT,
            INDEX idx_token (share_token),
            INDEX idx_image (image_id),
            INDEX idx_user (user_id),
            FOREIGN KEY (image_id) REFERENCES alkebulan_images(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Favorite Images Table
     * Track user's favorite images
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_favorite_images (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            image_id INT NOT NULL,
            added_at BIGINT,
            UNIQUE KEY unique_user_image (user_id, image_id),
            INDEX idx_user (user_id),
            FOREIGN KEY (image_id) REFERENCES alkebulan_images(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Image Comments Table
     * Comments on generated images
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_image_comments (
            id INT PRIMARY KEY AUTO_INCREMENT,
            image_id INT NOT NULL,
            user_id INT NOT NULL,
            comment TEXT,
            rating INT,
            is_deleted BOOLEAN DEFAULT 0,
            created BIGINT,
            updated BIGINT,
            INDEX idx_image (image_id),
            INDEX idx_user (user_id),
            FOREIGN KEY (image_id) REFERENCES alkebulan_images(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Image Analytics Table
     * Detailed analytics and metrics per image
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_image_analytics (
            id INT PRIMARY KEY AUTO_INCREMENT,
            image_id INT NOT NULL,
            user_id INT NOT NULL,
            views INT DEFAULT 0,
            downloads INT DEFAULT 0,
            shares INT DEFAULT 0,
            likes INT DEFAULT 0,
            avg_rating FLOAT DEFAULT 0,
            rating_count INT DEFAULT 0,
            comment_count INT DEFAULT 0,
            social_shares INT DEFAULT 0,
            created BIGINT,
            updated BIGINT,
            UNIQUE KEY unique_image (image_id),
            INDEX idx_user (user_id),
            FOREIGN KEY (image_id) REFERENCES alkebulan_images(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Image Queue Table
     * Queue for batch image processing
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_image_queue (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            prompt VARCHAR(2000),
            style VARCHAR(50),
            quality VARCHAR(20),
            options JSON,
            status VARCHAR(20), -- 'pending', 'processing', 'completed', 'failed'
            result_image_id INT,
            error_message VARCHAR(500),
            priority INT DEFAULT 5,
            attempts INT DEFAULT 0,
            max_attempts INT DEFAULT 3,
            created BIGINT,
            started BIGINT,
            completed BIGINT,
            INDEX idx_user_status (user_id, status),
            INDEX idx_priority (priority),
            INDEX idx_created (created)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Generation Stats Table
     * Cached statistics for fast retrieval
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_generation_stats (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            stat_type VARCHAR(50), -- 'user_stats', 'global_stats', 'daily_stats'
            total_images INT DEFAULT 0,
            total_storage BIGINT DEFAULT 0,
            avg_processing_time FLOAT DEFAULT 0,
            style_breakdown JSON,
            format_breakdown JSON,
            quality_breakdown JSON,
            hourly_count JSON,
            daily_count JSON,
            computed_at BIGINT,
            valid_until BIGINT,
            UNIQUE KEY unique_stat (user_id, stat_type),
            INDEX idx_valid (valid_until)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Trending Prompts Table
     * Cache of trending prompts and styles
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_trending_prompts (
            id INT PRIMARY KEY AUTO_INCREMENT,
            prompt VARCHAR(500),
            prompt_hash VARCHAR(64),
            style VARCHAR(50),
            usage_count INT DEFAULT 0,
            avg_rating FLOAT DEFAULT 0,
            rating_count INT DEFAULT 0,
            trending_score FLOAT DEFAULT 0,
            timeframe VARCHAR(20), -- '7_days', '30_days', 'all_time'
            last_used BIGINT,
            updated BIGINT,
            UNIQUE KEY unique_prompt (prompt_hash, style, timeframe),
            INDEX idx_trending (trending_score),
            INDEX idx_timeframe (timeframe),
            INDEX idx_updated (updated)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    /**
     * Image Variations Table
     * Track variations and their relationships
     */
    $db->exec("
        CREATE TABLE IF NOT EXISTS alkebulan_image_variations (
            id INT PRIMARY KEY AUTO_INCREMENT,
            parent_image_id INT NOT NULL,
            variant_image_id INT NOT NULL,
            variation_type VARCHAR(50), -- 'composition', 'angle', 'lighting', 'style', 'color'
            parameters JSON,
            created BIGINT,
            UNIQUE KEY unique_variant (parent_image_id, variant_image_id),
            INDEX idx_parent (parent_image_id),
            FOREIGN KEY (parent_image_id) REFERENCES alkebulan_images(id) ON DELETE CASCADE,
            FOREIGN KEY (variant_image_id) REFERENCES alkebulan_images(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    echo json_encode([
        'status' => 'success',
        'message' => 'All database tables created successfully',
        'tables_created' => [
            'alkebulan_images',
            'alkebulan_image_cache',
            'alkebulan_image_history',
            'alkebulan_image_collections',
            'alkebulan_collection_images',
            'alkebulan_image_shares',
            'alkebulan_favorite_images',
            'alkebulan_image_comments',
            'alkebulan_image_analytics',
            'alkebulan_image_queue',
            'alkebulan_generation_stats',
            'alkebulan_trending_prompts',
            'alkebulan_image_variations'
        ],
        'total_tables' => 13
    ]);
    
} catch(Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database setup failed: ' . $e->getMessage()
    ]);
}

?>
