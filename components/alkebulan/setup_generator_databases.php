<?php
/**
 * Alkebulan AI - Generator API Database Setup Script
 * Creates all necessary database tables for enhanced generators
 * Run this ONCE after deploying Phase 4 enhancements
 */

// Execute setup
setupGeneratorDatabases();

function setupGeneratorDatabases() {
    echo "🔧 Setting up Alkebulan Generator Databases...\n\n";
    
    // Get database connection
    $dbhost = defined('OSSN_DB_HOST') ? OSSN_DB_HOST : 'localhost';
    $dbuser = defined('OSSN_DB_USER') ? OSSN_DB_USER : 'root';
    $dbpass = defined('OSSN_DB_PASS') ? OSSN_DB_PASS : '';
    $dbname = defined('OSSN_DB_NAME') ? OSSN_DB_NAME : 'ossn';
    
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    
    if($conn->connect_error) {
        die("❌ Connection failed: " . $conn->connect_error . "\n");
    }
    
    echo "✅ Database connected\n\n";
    
    // Create all tables
    $tables = [
        'text_generation' => createTextTables(),
        'code_generation' => createCodeTables(),
        'summarization' => createSummaryTables(),
        'prompt_optimization' => createPromptTables(),
        'translation' => createTranslationTables(),
        'shared_analytics' => createAnalyticsTables()
    ];
    
    $success_count = 0;
    foreach($tables as $module => $sql_queries) {
        echo "📦 Setting up $module...\n";
        foreach($sql_queries as $query) {
            if($conn->query($query)) {
                echo "   ✅ Table created successfully\n";
                $success_count++;
            } else {
                echo "   ⚠️  " . $conn->error . "\n";
            }
        }
        echo "\n";
    }
    
    $conn->close();
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ Database Setup Complete!\n";
    echo "📊 Total tables created/updated: $success_count\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
}

function createTextTables() {
    return [
        // Main storage for text generations
        "CREATE TABLE IF NOT EXISTS alkebulan_text_generations (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            prompt VARCHAR(1000),
            generated_text LONGTEXT,
            tone VARCHAR(50),
            type VARCHAR(50),
            language VARCHAR(10) DEFAULT 'en',
            quality_score FLOAT DEFAULT 0,
            is_public BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_user (user_id),
            INDEX idx_created (created_at),
            INDEX idx_quality (quality_score)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Cache layer for text generations
        "CREATE TABLE IF NOT EXISTS alkebulan_text_cache (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            prompt_hash VARCHAR(64),
            generated_text LONGTEXT,
            hit_count INT DEFAULT 0,
            expiry_time TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_hash (user_id, prompt_hash),
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Ratings and reviews
        "CREATE TABLE IF NOT EXISTS alkebulan_text_ratings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            generation_id INT,
            user_id INT,
            rating INT CHECK (rating >= 1 AND rating <= 5),
            review TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (generation_id) REFERENCES alkebulan_text_generations(id) ON DELETE CASCADE,
            INDEX idx_generation (generation_id),
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Favorites
        "CREATE TABLE IF NOT EXISTS alkebulan_text_favorites (
            id INT PRIMARY KEY AUTO_INCREMENT,
            generation_id INT,
            user_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_favorite (generation_id, user_id),
            FOREIGN KEY (generation_id) REFERENCES alkebulan_text_generations(id) ON DELETE CASCADE,
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Analytics
        "CREATE TABLE IF NOT EXISTS alkebulan_text_analytics (
            id INT PRIMARY KEY AUTO_INCREMENT,
            tone VARCHAR(50),
            type VARCHAR(50),
            count INT DEFAULT 0,
            total_quality_score FLOAT DEFAULT 0,
            avg_rating FLOAT DEFAULT 0,
            trend_score FLOAT DEFAULT 0,
            date DATE,
            UNIQUE KEY unique_stat (tone, type, date),
            INDEX idx_date (date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    ];
}

function createCodeTables() {
    return [
        // Main storage for code generations
        "CREATE TABLE IF NOT EXISTS alkebulan_code_generations (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            description VARCHAR(1000),
            generated_code LONGTEXT,
            language VARCHAR(50),
            code_type VARCHAR(50),
            documentation LONGTEXT,
            tests LONGTEXT,
            complexity_score FLOAT DEFAULT 0,
            is_public BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_user (user_id),
            INDEX idx_language (language),
            INDEX idx_type (code_type),
            INDEX idx_complexity (complexity_score),
            INDEX idx_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Cache layer
        "CREATE TABLE IF NOT EXISTS alkebulan_code_cache (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            code_hash VARCHAR(64),
            generated_code LONGTEXT,
            language VARCHAR(50),
            hit_count INT DEFAULT 0,
            expiry_time TIMESTAMP,
            UNIQUE KEY unique_hash (user_id, code_hash),
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Ratings
        "CREATE TABLE IF NOT EXISTS alkebulan_code_ratings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            generation_id INT,
            user_id INT,
            rating INT CHECK (rating >= 1 AND rating <= 5),
            review TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (generation_id) REFERENCES alkebulan_code_generations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Favorites
        "CREATE TABLE IF NOT EXISTS alkebulan_code_favorites (
            id INT PRIMARY KEY AUTO_INCREMENT,
            generation_id INT,
            user_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_favorite (generation_id, user_id),
            FOREIGN KEY (generation_id) REFERENCES alkebulan_code_generations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Analytics for languages and types
        "CREATE TABLE IF NOT EXISTS alkebulan_code_analytics (
            id INT PRIMARY KEY AUTO_INCREMENT,
            language VARCHAR(50),
            code_type VARCHAR(50),
            count INT DEFAULT 0,
            total_complexity FLOAT DEFAULT 0,
            trend_score FLOAT DEFAULT 0,
            date DATE,
            UNIQUE KEY unique_stat (language, code_type, date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    ];
}

function createSummaryTables() {
    return [
        // Main storage
        "CREATE TABLE IF NOT EXISTS alkebulan_summaries (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            original_content LONGTEXT,
            summary LONGTEXT,
            summary_type VARCHAR(50),
            compression_ratio FLOAT DEFAULT 0,
            readability_score FLOAT DEFAULT 0,
            is_public BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_user (user_id),
            INDEX idx_type (summary_type),
            INDEX idx_readability (readability_score),
            INDEX idx_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Cache
        "CREATE TABLE IF NOT EXISTS alkebulan_summary_cache (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            content_hash VARCHAR(64),
            summary LONGTEXT,
            hit_count INT DEFAULT 0,
            expiry_time TIMESTAMP,
            UNIQUE KEY unique_hash (user_id, content_hash),
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Ratings
        "CREATE TABLE IF NOT EXISTS alkebulan_summary_ratings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            summary_id INT,
            user_id INT,
            rating INT CHECK (rating >= 1 AND rating <= 5),
            review TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (summary_id) REFERENCES alkebulan_summaries(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Favorites
        "CREATE TABLE IF NOT EXISTS alkebulan_summary_favorites (
            id INT PRIMARY KEY AUTO_INCREMENT,
            summary_id INT,
            user_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_favorite (summary_id, user_id),
            FOREIGN KEY (summary_id) REFERENCES alkebulan_summaries(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Analytics
        "CREATE TABLE IF NOT EXISTS alkebulan_summary_analytics (
            id INT PRIMARY KEY AUTO_INCREMENT,
            summary_type VARCHAR(50),
            count INT DEFAULT 0,
            avg_compression_ratio FLOAT DEFAULT 0,
            avg_readability FLOAT DEFAULT 0,
            trend_score FLOAT DEFAULT 0,
            date DATE,
            UNIQUE KEY unique_stat (summary_type, date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    ];
}

function createPromptTables() {
    return [
        // Main storage
        "CREATE TABLE IF NOT EXISTS alkebulan_prompt_optimizations (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            original_prompt TEXT,
            optimized_prompt LONGTEXT,
            quality_before FLOAT DEFAULT 0,
            quality_after FLOAT DEFAULT 0,
            improvement_percentage FLOAT DEFAULT 0,
            techniques_used VARCHAR(500),
            is_public BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_user (user_id),
            INDEX idx_improvement (improvement_percentage),
            INDEX idx_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Cache
        "CREATE TABLE IF NOT EXISTS alkebulan_prompt_cache (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            prompt_hash VARCHAR(64),
            optimized_prompt LONGTEXT,
            hit_count INT DEFAULT 0,
            expiry_time TIMESTAMP,
            UNIQUE KEY unique_hash (user_id, prompt_hash),
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Ratings
        "CREATE TABLE IF NOT EXISTS alkebulan_prompt_ratings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            optimization_id INT,
            user_id INT,
            rating INT CHECK (rating >= 1 AND rating <= 5),
            review TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (optimization_id) REFERENCES alkebulan_prompt_optimizations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Favorites
        "CREATE TABLE IF NOT EXISTS alkebulan_prompt_favorites (
            id INT PRIMARY KEY AUTO_INCREMENT,
            optimization_id INT,
            user_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_favorite (optimization_id, user_id),
            FOREIGN KEY (optimization_id) REFERENCES alkebulan_prompt_optimizations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Technique tracking
        "CREATE TABLE IF NOT EXISTS alkebulan_prompt_techniques (
            id INT PRIMARY KEY AUTO_INCREMENT,
            technique_name VARCHAR(100),
            usage_count INT DEFAULT 0,
            avg_improvement FLOAT DEFAULT 0,
            popularity_score FLOAT DEFAULT 0,
            last_used TIMESTAMP,
            UNIQUE KEY unique_technique (technique_name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Analytics
        "CREATE TABLE IF NOT EXISTS alkebulan_prompt_analytics (
            id INT PRIMARY KEY AUTO_INCREMENT,
            technique VARCHAR(100),
            count INT DEFAULT 0,
            total_improvement FLOAT DEFAULT 0,
            trend_score FLOAT DEFAULT 0,
            date DATE,
            UNIQUE KEY unique_stat (technique, date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    ];
}

function createTranslationTables() {
    return [
        // Main storage
        "CREATE TABLE IF NOT EXISTS alkebulan_translations (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            source_content LONGTEXT,
            target_content LONGTEXT,
            source_language VARCHAR(10),
            target_language VARCHAR(10),
            formality_level VARCHAR(20),
            quality_score FLOAT DEFAULT 0,
            is_public BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_user (user_id),
            INDEX idx_languages (source_language, target_language),
            INDEX idx_quality (quality_score),
            INDEX idx_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Cache
        "CREATE TABLE IF NOT EXISTS alkebulan_translation_cache (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            content_hash VARCHAR(64),
            translation LONGTEXT,
            language_pair VARCHAR(10),
            hit_count INT DEFAULT 0,
            expiry_time TIMESTAMP,
            UNIQUE KEY unique_hash (user_id, content_hash, language_pair),
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Ratings
        "CREATE TABLE IF NOT EXISTS alkebulan_translation_ratings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            translation_id INT,
            user_id INT,
            rating INT CHECK (rating >= 1 AND rating <= 5),
            review TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (translation_id) REFERENCES alkebulan_translations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Favorites
        "CREATE TABLE IF NOT EXISTS alkebulan_translation_favorites (
            id INT PRIMARY KEY AUTO_INCREMENT,
            translation_id INT,
            user_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_favorite (translation_id, user_id),
            FOREIGN KEY (translation_id) REFERENCES alkebulan_translations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Language pair analytics
        "CREATE TABLE IF NOT EXISTS alkebulan_translation_pairs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            source_language VARCHAR(10),
            target_language VARCHAR(10),
            usage_count INT DEFAULT 0,
            avg_quality FLOAT DEFAULT 0,
            popularity_score FLOAT DEFAULT 0,
            last_used TIMESTAMP,
            UNIQUE KEY unique_pair (source_language, target_language)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Analytics
        "CREATE TABLE IF NOT EXISTS alkebulan_translation_analytics (
            id INT PRIMARY KEY AUTO_INCREMENT,
            source_language VARCHAR(10),
            target_language VARCHAR(10),
            count INT DEFAULT 0,
            avg_quality FLOAT DEFAULT 0,
            trend_score FLOAT DEFAULT 0,
            date DATE,
            UNIQUE KEY unique_stat (source_language, target_language, date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    ];
}

function createAnalyticsTables() {
    return [
        // General usage analytics
        "CREATE TABLE IF NOT EXISTS alkebulan_generator_analytics (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            generator_type VARCHAR(50),
            action VARCHAR(50),
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            execution_time FLOAT DEFAULT 0,
            success BOOLEAN DEFAULT TRUE,
            INDEX idx_user (user_id),
            INDEX idx_type (generator_type),
            INDEX idx_timestamp (timestamp)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        // Daily statistics
        "CREATE TABLE IF NOT EXISTS alkebulan_daily_stats (
            id INT PRIMARY KEY AUTO_INCREMENT,
            date DATE,
            generator_type VARCHAR(50),
            total_operations INT DEFAULT 0,
            avg_execution_time FLOAT DEFAULT 0,
            success_rate FLOAT DEFAULT 0,
            error_count INT DEFAULT 0,
            unique_users INT DEFAULT 0,
            UNIQUE KEY unique_stat (date, generator_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    ];
}

?>
