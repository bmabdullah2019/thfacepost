<?php
/**
 * Alkebulan AI Component - v2.0
 * Advanced AI-Powered Component for OSSN
 * Features: Content Analysis, Smart Recommendations, Chat Assistant, Image Generation, Analytics
 */

define('__OSSN_ALKEBULAN__', ossn_route()->com . 'alkebulan/');

// Include required classes
require_once(__OSSN_ALKEBULAN__ . 'classes/AIAnalyzer.php');
require_once(__OSSN_ALKEBULAN__ . 'classes/AIRecommender.php');
require_once(__OSSN_ALKEBULAN__ . 'classes/ChatAssistant.php');
require_once(__OSSN_ALKEBULAN__ . 'classes/AIAnalytics.php');
require_once(__OSSN_ALKEBULAN__ . 'classes/AIImageGenerator.php');

/**
 * Initialize Alkebulan AI Component
 * 
 * @return void
 * @access private
 */
function ossn_alkebulan() {
    
    // Register page handler
    ossn_register_page('alkebulan', 'alkebulan_page_handler');
    
    // Register actions for API endpoints
    if(ossn_isLoggedin()) {
        ossn_register_action('alkebulan/analyze', __OSSN_ALKEBULAN__ . 'actions/analyze.php');
        ossn_register_action('alkebulan/recommend', __OSSN_ALKEBULAN__ . 'actions/recommend.php');
        ossn_register_action('alkebulan/chat', __OSSN_ALKEBULAN__ . 'actions/chat.php');
        ossn_register_action('alkebulan/analytics', __OSSN_ALKEBULAN__ . 'actions/analytics.php');
        ossn_register_action('alkebulan/image/generate', __OSSN_ALKEBULAN__ . 'actions/image.php');
        ossn_register_action('alkebulan/image/gallery', __OSSN_ALKEBULAN__ . 'actions/image.php');
        ossn_register_action('alkebulan/image/delete', __OSSN_ALKEBULAN__ . 'actions/image.php');
        ossn_register_action('alkebulan/image/stats', __OSSN_ALKEBULAN__ . 'actions/image.php');
        ossn_register_action('alkebulan/image/trending', __OSSN_ALKEBULAN__ . 'actions/image.php');
        ossn_register_action('alkebulan/image/download', __OSSN_ALKEBULAN__ . 'actions/image.php');
        ossn_register_action('alkebulan/video/generate', __OSSN_ALKEBULAN__ . 'actions/video.php');
        ossn_register_action('alkebulan/video/gallery', __OSSN_ALKEBULAN__ . 'actions/video.php');
        ossn_register_action('alkebulan/video/delete', __OSSN_ALKEBULAN__ . 'actions/video.php');
        ossn_register_action('alkebulan/video/stats', __OSSN_ALKEBULAN__ . 'actions/video.php');
        ossn_register_action('alkebulan/video/download', __OSSN_ALKEBULAN__ . 'actions/video.php');
        ossn_register_action('alkebulan/audio/generate', __OSSN_ALKEBULAN__ . 'actions/audio.php');
        ossn_register_action('alkebulan/audio/download', __OSSN_ALKEBULAN__ . 'actions/audio.php');
        ossn_register_action('alkebulan/analyzer/analyze', __OSSN_ALKEBULAN__ . 'actions/analyzer.php');
        ossn_register_action('alkebulan/insights/get', __OSSN_ALKEBULAN__ . 'actions/insights.php');
        ossn_register_action('alkebulan/insights/trending', __OSSN_ALKEBULAN__ . 'actions/insights.php');
    }
    
    // Defer database initialization to after OSSN system is ready
    ossn_add_hook('system:init', 'alkebulan:init:database', 'alkebulan_init_db_hook');
    
    // Register CSS and view paths
    ossn_extend_view('css/ossn.default', 'alkebulan/css');
    
    // Register menu items for logged-in users
    if(ossn_isLoggedin()) {
        ossn_register_sections_menu('newsfeed', array(
            'name' => 'alkebulan',
            'text' => 'Alkebulan AI',
            'url' => ossn_site_url('alkebulan/dashboard'),
            'parent' => 'links',
        ));
    }
    
    // Add hooks for system integration
    ossn_add_hook('admin:load', 'alkebulan:admin:menu', 'alkebulan_admin_menu');
}

/**
 * Initialize database hook - called after system init
 * 
 * @return void
 */
function alkebulan_init_db_hook() {
    alkebulan_init_database();
}

/**
 * Admin menu hook
 * 
 * @return void
 */
function alkebulan_admin_menu() {
    ossn_register_admin_menu('alkebulan', 'Alkebulan AI', 'alkebulan:admin');
}

/**
 * Initialize Alkebulan database tables
 * 
 * @return void
 */
function alkebulan_init_database() {
    $db = ossn_get_database();
    
    // AI Analysis Results Table
    if(!$db->table_exists('alkebulan_analysis')) {
        $db->query("
            CREATE TABLE IF NOT EXISTS `alkebulan_analysis` (
                `id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
                `user_id` BIGINT(20) NOT NULL,
                `content_id` BIGINT(20),
                `content_type` VARCHAR(50),
                `analysis_type` VARCHAR(100),
                `input_text` LONGTEXT,
                `output_data` LONGTEXT,
                `confidence_score` FLOAT DEFAULT 0.0,
                `processing_time` INT DEFAULT 0,
                `status` VARCHAR(20) DEFAULT 'completed',
                `created` BIGINT(20) NOT NULL,
                KEY `idx_user` (`user_id`),
                KEY `idx_type` (`analysis_type`),
                KEY `idx_created` (`created`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    // AI Recommendations Table
    if(!$db->table_exists('alkebulan_recommendations')) {
        $db->query("
            CREATE TABLE IF NOT EXISTS `alkebulan_recommendations` (
                `id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
                `user_id` BIGINT(20) NOT NULL,
                `recommendation_type` VARCHAR(100),
                `recommended_item_id` BIGINT(20),
                `recommended_item_type` VARCHAR(50),
                `relevance_score` FLOAT DEFAULT 0.0,
                `recommendation_data` LONGTEXT,
                `viewed` BOOLEAN DEFAULT FALSE,
                `acted_upon` BOOLEAN DEFAULT FALSE,
                `created` BIGINT(20) NOT NULL,
                KEY `idx_user` (`user_id`),
                KEY `idx_type` (`recommendation_type`),
                KEY `idx_created` (`created`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    // Chat Sessions Table
    if(!$db->table_exists('alkebulan_chat_sessions')) {
        $db->query("
            CREATE TABLE IF NOT EXISTS `alkebulan_chat_sessions` (
                `id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
                `user_id` BIGINT(20) NOT NULL,
                `session_key` VARCHAR(255) UNIQUE,
                `context` LONGTEXT,
                `conversation_history` LONGTEXT,
                `total_messages` INT DEFAULT 0,
                `status` VARCHAR(20) DEFAULT 'active',
                `created` BIGINT(20) NOT NULL,
                `last_active` BIGINT(20) NOT NULL,
                KEY `idx_user` (`user_id`),
                KEY `idx_session` (`session_key`),
                KEY `idx_active` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    // Chat Messages Table
    if(!$db->table_exists('alkebulan_chat_messages')) {
        $db->query("
            CREATE TABLE IF NOT EXISTS `alkebulan_chat_messages` (
                `id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
                `session_id` BIGINT(20) NOT NULL,
                `sender_type` VARCHAR(20),
                `message_text` LONGTEXT,
                `message_type` VARCHAR(50),
                `metadata` LONGTEXT,
                `created` BIGINT(20) NOT NULL,
                KEY `idx_session` (`session_id`),
                KEY `idx_created` (`created`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    // AI Analytics Table
    if(!$db->table_exists('alkebulan_analytics')) {
        $db->query("
            CREATE TABLE IF NOT EXISTS `alkebulan_analytics` (
                `id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
                `metric_type` VARCHAR(100),
                `metric_name` VARCHAR(255),
                `metric_value` DECIMAL(10, 2),
                `user_id` BIGINT(20),
                `aggregation_period` VARCHAR(20),
                `analytics_data` LONGTEXT,
                `created` BIGINT(20) NOT NULL,
                KEY `idx_metric` (`metric_type`),
                KEY `idx_user` (`user_id`),
                KEY `idx_created` (`created`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    // AI User Preferences Table
    if(!$db->table_exists('alkebulan_user_prefs')) {
        $db->query("
            CREATE TABLE IF NOT EXISTS `alkebulan_user_prefs` (
                `id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
                `user_id` BIGINT(20) NOT NULL UNIQUE,
                `ai_enabled` BOOLEAN DEFAULT TRUE,
                `analysis_preference` VARCHAR(50),
                `recommendation_frequency` VARCHAR(50),
                `chat_enabled` BOOLEAN DEFAULT TRUE,
                `analytics_opt_in` BOOLEAN DEFAULT TRUE,
                `language_preference` VARCHAR(10),
                `tone_preference` VARCHAR(50),
                `custom_settings` LONGTEXT,
                `created` BIGINT(20) NOT NULL,
                KEY `idx_user` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    // AI Usage Log Table
    if(!$db->table_exists('alkebulan_usage_log')) {
        $db->query("
            CREATE TABLE IF NOT EXISTS `alkebulan_usage_log` (
                `id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
                `user_id` BIGINT(20) NOT NULL,
                `feature_used` VARCHAR(100),
                `tokens_used` INT DEFAULT 0,
                `cost` DECIMAL(8, 2) DEFAULT 0.00,
                `status` VARCHAR(20) DEFAULT 'success',
                `response_time` INT DEFAULT 0,
                `metadata` LONGTEXT,
                `created` BIGINT(20) NOT NULL,
                KEY `idx_user` (`user_id`),
                KEY `idx_feature` (`feature_used`),
                KEY `idx_created` (`created`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    // AI Configuration Table
    if(!$db->table_exists('alkebulan_config')) {
        $db->query("
            CREATE TABLE IF NOT EXISTS `alkebulan_config` (
                `id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
                `config_key` VARCHAR(255) UNIQUE,
                `config_value` LONGTEXT,
                `config_type` VARCHAR(50),
                `updated` BIGINT(20) NOT NULL,
                KEY `idx_key` (`config_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    // Image Generation Table - NEW V2.0
    if(!$db->table_exists('alkebulan_images')) {
        $db->query("
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
    
    // Video Generation Table - NEW V2.1
    if(!$db->table_exists('alkebulan_videos')) {
        $db->query("
            CREATE TABLE IF NOT EXISTS `alkebulan_videos` (
                `id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
                `user_id` BIGINT(20) NOT NULL,
                `prompt` TEXT,
                `video_path` VARCHAR(500),
                `filename` VARCHAR(200),
                `duration` INT DEFAULT 10,
                `fps` INT DEFAULT 30,
                `quality` VARCHAR(50) DEFAULT '1080p',
                `style` VARCHAR(50),
                `with_music` TINYINT DEFAULT 1,
                `format` VARCHAR(10) DEFAULT 'mp4',
                `generation_time` VARCHAR(50),
                `file_size` INT,
                `is_public` TINYINT DEFAULT 0,
                `downloads` INT DEFAULT 0,
                `status` VARCHAR(50) DEFAULT 'processing',
                `created` BIGINT(20) NOT NULL,
                KEY `idx_user` (`user_id`),
                KEY `idx_status` (`status`),
                KEY `idx_created` (`created`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
}

/**
 * Page handler for Alkebulan pages
 * 
 * @param array $page Page segments
 * @return mixed Page view or content
 */
function alkebulan_page_handler($page) {
    // Check if user is logged in
    if(!ossn_isLoggedin()) {
        redirect(ossn_site_url() . 'login');
    }
    
    // Load CSS and JavaScript files
    ossn_load_external_css('alkebulan.css');
    ossn_load_external_js('alkebulan.js');
    
    $page = array_shift($page);
    
    // Get page title
    $title = 'Alkebulan AI';
    
    // Determine which page to load
    if (empty($page)) {
        $page = 'dashboard';
    }
    
    // Get page content based on page type - use direct file inclusion
    switch($page) {
        case 'dashboard':
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/dashboard.php');
            $content = ob_get_clean();
            break;
        case 'features':
            $title = 'Features';
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/features.php');
            $content = ob_get_clean();
            break;
        case 'assistant':
            $title = 'Chat Assistant';
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/assistant.php');
            $content = ob_get_clean();
            break;
        case 'analytics':
            $title = 'Analytics';
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/analytics.php');
            $content = ob_get_clean();
            break;
        case 'settings':
            $title = 'Settings';
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/settings.php');
            $content = ob_get_clean();
            break;
        case 'image-generator':
            $title = 'Image Generator';
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/image-generator.php');
            $content = ob_get_clean();
            break;
        case 'video-generator':
            $title = 'Video Generator';
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/video-generator.php');
            $content = ob_get_clean();
            break;
        case 'audio-generator':
            $title = 'Audio Generator';
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/audio-generator.php');
            $content = ob_get_clean();
            break;
        case 'analyzer':
            $title = 'Content Analyzer';
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/analyzer.php');
            $content = ob_get_clean();
            break;
        case 'insights':
            $title = 'AI Insights';
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/insights.php');
            $content = ob_get_clean();
            break;
        default:
            ob_start();
            include(__OSSN_ALKEBULAN__ . 'plugins/default/views/dashboard.php');
            $content = ob_get_clean();
    }
    
    // Wrap content with OSSN page layout
    $page_content = ossn_set_page_layout('newsfeed', array(
        'content' => $content,
    ));
    
    // Echo the complete page with title and layout
    echo ossn_view_page($title, $page_content);
}

// Initialize the component
ossn_alkebulan();

?>
