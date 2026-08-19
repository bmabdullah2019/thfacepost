<?php

/**
 * Analytics Integration Handler
 * Middleware to automatically track AI generator usage across the system
 */

class AnalyticsTracker {
    
    private static $analytics = null;
    private static $tracking_start_time = null;
    private static $tracking_start_memory = null;
    
    /**
     * Initialize tracker
     */
    public static function init($user_id = null) {
        if (class_exists('AIAnalyticsV2')) {
            self::$analytics = new AIAnalyticsV2($user_id);
            self::$tracking_start_time = microtime(true);
            self::$tracking_start_memory = memory_get_usage(true);
        }
    }
    
    /**
     * Get analytics instance
     */
    public static function getInstance() {
        return self::$analytics;
    }
    
    /**
     * Track text generation
     */
    public static function trackTextGeneration(
        $action,
        $input_text,
        $output_text,
        $response_time_ms,
        $tokens_used = 0,
        $status = 'success',
        $error = null
    ) {
        if (self::$analytics) {
            $memory_used = (memory_get_usage(true) - self::$tracking_start_memory) / (1024 * 1024);
            
            self::$analytics->trackGeneratorUsage(
                'text',
                $action,
                strlen($input_text),
                strlen($output_text),
                $response_time_ms,
                $tokens_used,
                $status,
                $error,
                'procedural',
                'TextGeneratorV2',
                $memory_used
            );
        }
    }
    
    /**
     * Track code generation
     */
    public static function trackCodeGeneration(
        $language,
        $code_type,
        $input_prompt,
        $generated_code,
        $response_time_ms,
        $tokens_used = 0,
        $status = 'success',
        $error = null
    ) {
        if (self::$analytics) {
            $memory_used = (memory_get_usage(true) - self::$tracking_start_memory) / (1024 * 1024);
            
            self::$analytics->trackGeneratorUsage(
                'code',
                "generate_$language" . "_$code_type",
                strlen($input_prompt),
                strlen($generated_code),
                $response_time_ms,
                $tokens_used,
                $status,
                $error,
                'procedural',
                "CodeGeneratorV2($language)",
                $memory_used
            );
        }
    }
    
    /**
     * Track summarization
     */
    public static function trackSummarization(
        $algorithm,
        $input_text,
        $summary,
        $response_time_ms,
        $tokens_used = 0,
        $status = 'success',
        $error = null
    ) {
        if (self::$analytics) {
            $memory_used = (memory_get_usage(true) - self::$tracking_start_memory) / (1024 * 1024);
            
            self::$analytics->trackGeneratorUsage(
                'summary',
                $algorithm,
                strlen($input_text),
                strlen($summary),
                $response_time_ms,
                $tokens_used,
                $status,
                $error,
                'procedural',
                "SummaryGeneratorV2($algorithm)",
                $memory_used
            );
        }
    }
    
    /**
     * Track prompt optimization
     */
    public static function trackPromptOptimization(
        $optimization_type,
        $original_prompt,
        $optimized_prompt,
        $response_time_ms,
        $tokens_used = 0,
        $quality_score = 0,
        $status = 'success',
        $error = null
    ) {
        if (self::$analytics) {
            $memory_used = (memory_get_usage(true) - self::$tracking_start_memory) / (1024 * 1024);
            
            self::$analytics->trackGeneratorUsage(
                'prompt',
                $optimization_type,
                strlen($original_prompt),
                strlen($optimized_prompt),
                $response_time_ms,
                $tokens_used,
                $status,
                $error,
                'procedural',
                "PromptOptimizerV2",
                $memory_used
            );
            
            // Track quality
            if (self::$analytics) {
                self::$analytics->trackQuality(
                    0,
                    'prompt',
                    $quality_score
                );
            }
        }
    }
    
    /**
     * Track translation
     */
    public static function trackTranslation(
        $source_language,
        $target_language,
        $input_text,
        $translated_text,
        $response_time_ms,
        $tokens_used = 0,
        $confidence = 0,
        $status = 'success',
        $error = null
    ) {
        if (self::$analytics) {
            $memory_used = (memory_get_usage(true) - self::$tracking_start_memory) / (1024 * 1024);
            
            self::$analytics->trackGeneratorUsage(
                'translation',
                "$source_language" . "_to_$target_language",
                strlen($input_text),
                strlen($translated_text),
                $response_time_ms,
                $tokens_used,
                $status,
                $error,
                'procedural',
                "TranslationEngineV2",
                $memory_used
            );
            
            // Track quality
            if (self::$analytics) {
                self::$analytics->trackQuality(
                    0,
                    'translation',
                    $confidence
                );
            }
        }
    }
    
    /**
     * Track image generation
     */
    public static function trackImageGeneration(
        $prompt,
        $method,
        $style,
        $width,
        $height,
        $response_time_ms,
        $tokens_used = 0,
        $file_size = 0,
        $status = 'success',
        $error = null
    ) {
        if (self::$analytics) {
            $memory_used = (memory_get_usage(true) - self::$tracking_start_memory) / (1024 * 1024);
            
            self::$analytics->trackGeneratorUsage(
                'image',
                "$method" . "_$style",
                strlen($prompt),
                $file_size,
                $response_time_ms,
                $tokens_used,
                $status,
                $error,
                $method,
                "ImageGeneratorV3",
                $memory_used
            );
        }
    }
    
    /**
     * Get current analytics
     */
    public static function getAnalytics($period = 'today') {
        if (self::$analytics) {
            return self::$analytics->getRealUsageStats($period);
        }
        return null;
    }
    
    /**
     * Get generator breakdown
     */
    public static function getGeneratorBreakdown($period = 'week') {
        if (self::$analytics) {
            return self::$analytics->getGeneratorBreakdown($period);
        }
        return [];
    }
    
    /**
     * Get performance trends
     */
    public static function getPerformanceTrends($days = 7) {
        if (self::$analytics) {
            return self::$analytics->getPerformanceTrends($days);
        }
        return [];
    }
    
    /**
     * Get quality metrics
     */
    public static function getQualityAnalytics($period = 'month') {
        if (self::$analytics) {
            return self::$analytics->getQualityAnalytics($period);
        }
        return [];
    }
    
    /**
     * Get cost tracking
     */
    public static function getCostTracking($month = null) {
        if (self::$analytics) {
            return self::$analytics->getCostTracking($month);
        }
        return [];
    }
    
    /**
     * Get insights
     */
    public static function getInsights($limit = 10) {
        if (self::$analytics) {
            return self::$analytics->getInsightsAndRecommendations($limit);
        }
        return [];
    }
    
    /**
     * Generate full report
     */
    public static function generateReport($period = 'month') {
        if (self::$analytics) {
            return self::$analytics->generateAnalyticsReport($period);
        }
        return null;
    }
    
    /**
     * Export report
     */
    public static function exportReport($period = 'month', $format = 'json') {
        if (self::$analytics) {
            return self::$analytics->exportReport($period, $format);
        }
        return null;
    }
}

?>
