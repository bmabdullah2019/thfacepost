<?php
/**
 * INTEGRATION TEMPLATE
 * 
 * Copy this template into each generator class to add analytics tracking.
 * This shows the exact pattern to follow for TextGeneratorV2, CodeGeneratorV2, etc.
 */

// ============================================================================
// STEP 1: Add this include at the top of your generator class file
// ============================================================================

require_once __DIR__ . '/AnalyticsTracker.php';

// ============================================================================
// STEP 2: Add these helper methods to your generator class
// ============================================================================

class GeneratorTemplate {
    
    protected $user_id;
    
    /**
     * Helper: Get tokens from text
     * Rough estimate: 1 token ≈ 4 characters
     */
    protected function getTokensUsed($text) {
        return max(1, ceil(strlen($text) / 4));
    }
    
    /**
     * Helper: Get tokens from multiple pieces
     */
    protected function calculateTokens($input, $output) {
        return $this->getTokensUsed($input) + $this->getTokensUsed($output);
    }

    // ========================================================================
    // STEP 3: Wrap your main generation methods with this pattern
    // ========================================================================
    
    /**
     * TEMPLATE: Text Generation Method with Analytics
     * 
     * Replace {ACTION_NAME} with your action name
     * Replace {TEXT_VARIABLE} with your generated text variable
     */
    public function {GENERATOR_METHOD}($input_param, $options = []) {
        
        // === INITIALIZATION ===
        if (!defined('ANALYTICS_DISABLED')) {
            AnalyticsTracker::init($this->user_id);
        }
        
        // === START TIMING ===
        $start_time = microtime(true);
        $start_memory = memory_get_usage();
        
        try {
            // === YOUR EXISTING GENERATION CODE ===
            // Replace this with your actual generation logic
            $generated_text = $this->generateContent($input_param, $options);
            
            // === CALCULATE METRICS ===
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            $memory_used_mb = (memory_get_usage() - $start_memory) / (1024 * 1024);
            $tokens_used = $this->calculateTokens($input_param, $generated_text);
            
            // === TRACK SUCCESS ===
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackTextGeneration(
                    '{ACTION_NAME}',           // action name
                    strlen($input_param),      // input length
                    strlen($generated_text),   // output length
                    $response_time_ms,         // response time
                    $tokens_used,              // tokens
                    'success',                 // status
                    null,                      // error message
                    $memory_used_mb            // memory used
                );
            }
            
            return $generated_text;
            
        } catch (Exception $e) {
            
            // === CALCULATE ERROR METRICS ===
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            
            // === TRACK ERROR ===
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackTextGeneration(
                    '{ACTION_NAME}',           // action name
                    strlen($input_param),      // input length
                    0,                         // no output
                    $response_time_ms,         // response time
                    0,                         // no tokens
                    'error',                   // status
                    $e->getMessage(),          // error details
                    0                          // no memory
                );
            }
            
            // Re-throw the exception
            throw $e;
        }
    }
    
    // ========================================================================
    // STEP 4: For other generators, use the appropriate tracking method
    // ========================================================================
    
    // Example: CODE GENERATION
    public function generateCode($prompt, $language = 'php', $options = []) {
        
        if (!defined('ANALYTICS_DISABLED')) {
            AnalyticsTracker::init($this->user_id);
        }
        
        $start_time = microtime(true);
        $start_memory = memory_get_usage();
        
        try {
            $code = $this->generate($prompt, $language, $options);
            
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            $memory_used_mb = (memory_get_usage() - $start_memory) / (1024 * 1024);
            
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackCodeGeneration(  // ← Different tracking method
                    'generateCode_' . $language,
                    strlen($prompt),
                    strlen($code),
                    $response_time_ms,
                    $this->getTokensUsed($code),
                    'success',
                    null,
                    $memory_used_mb
                );
            }
            
            return $code;
            
        } catch (Exception $e) {
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackCodeGeneration(  // ← Same method
                    'generateCode_' . $language,
                    strlen($prompt),
                    0,
                    $response_time_ms,
                    0,
                    'error',
                    $e->getMessage()
                );
            }
            
            throw $e;
        }
    }
    
    // Example: IMAGE GENERATION
    public function generateImage($prompt, $style = 'abstract', $size = '512x512') {
        
        if (!defined('ANALYTICS_DISABLED')) {
            AnalyticsTracker::init($this->user_id);
        }
        
        $start_time = microtime(true);
        $start_memory = memory_get_usage();
        
        try {
            $image_path = $this->generate($prompt, $style, $size);
            $image_size = file_exists($image_path) ? filesize($image_path) : 0;
            
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            $memory_used_mb = (memory_get_usage() - $start_memory) / (1024 * 1024);
            
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackImageGeneration(  // ← Image tracking
                    'generateImage_' . $style . '_' . $size,
                    strlen($prompt),
                    $image_size,
                    $response_time_ms,
                    0,  // Images don't use tokens
                    'success',
                    null,
                    $memory_used_mb
                );
            }
            
            return $image_path;
            
        } catch (Exception $e) {
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackImageGeneration(
                    'generateImage_' . $style . '_' . $size,
                    strlen($prompt),
                    0,
                    $response_time_ms,
                    0,
                    'error',
                    $e->getMessage()
                );
            }
            
            throw $e;
        }
    }
    
    // Example: SUMMARIZATION
    public function summarize($text, $level = 'medium') {
        
        if (!defined('ANALYTICS_DISABLED')) {
            AnalyticsTracker::init($this->user_id);
        }
        
        $start_time = microtime(true);
        $start_memory = memory_get_usage();
        
        try {
            $summary = $this->doSummarization($text, $level);
            
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            $memory_used_mb = (memory_get_usage() - $start_memory) / (1024 * 1024);
            
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackSummarization(  // ← Summarization tracking
                    'summarize_' . $level,
                    strlen($text),
                    strlen($summary),
                    $response_time_ms,
                    $this->getTokensUsed($text),
                    'success',
                    null,
                    $memory_used_mb
                );
            }
            
            return $summary;
            
        } catch (Exception $e) {
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackSummarization(
                    'summarize_' . $level,
                    strlen($text),
                    0,
                    $response_time_ms,
                    0,
                    'error',
                    $e->getMessage()
                );
            }
            
            throw $e;
        }
    }
    
    // Example: TRANSLATION
    public function translate($text, $from_lang, $to_lang) {
        
        if (!defined('ANALYTICS_DISABLED')) {
            AnalyticsTracker::init($this->user_id);
        }
        
        $start_time = microtime(true);
        $start_memory = memory_get_usage();
        
        try {
            $translated = $this->doTranslation($text, $from_lang, $to_lang);
            
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            $memory_used_mb = (memory_get_usage() - $start_memory) / (1024 * 1024);
            
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackTranslation(  // ← Translation tracking
                    'translate_' . $from_lang . '_to_' . $to_lang,
                    strlen($text),
                    strlen($translated),
                    $response_time_ms,
                    $this->getTokensUsed($text),
                    'success',
                    null,
                    $memory_used_mb
                );
            }
            
            return $translated;
            
        } catch (Exception $e) {
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackTranslation(
                    'translate_' . $from_lang . '_to_' . $to_lang,
                    strlen($text),
                    0,
                    $response_time_ms,
                    0,
                    'error',
                    $e->getMessage()
                );
            }
            
            throw $e;
        }
    }
    
    // Example: PROMPT OPTIMIZATION
    public function optimizePrompt($prompt, $type = 'standard') {
        
        if (!defined('ANALYTICS_DISABLED')) {
            AnalyticsTracker::init($this->user_id);
        }
        
        $start_time = microtime(true);
        $start_memory = memory_get_usage();
        
        try {
            $optimized = $this->doOptimize($prompt, $type);
            
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            $memory_used_mb = (memory_get_usage() - $start_memory) / (1024 * 1024);
            
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackPromptOptimization(  // ← Prompt tracking
                    'optimize_' . $type,
                    strlen($prompt),
                    strlen($optimized),
                    $response_time_ms,
                    $this->getTokensUsed($optimized),
                    'success',
                    null,
                    $memory_used_mb
                );
            }
            
            return $optimized;
            
        } catch (Exception $e) {
            $response_time_ms = (microtime(true) - $start_time) * 1000;
            
            if (!defined('ANALYTICS_DISABLED')) {
                AnalyticsTracker::trackPromptOptimization(
                    'optimize_' . $type,
                    strlen($prompt),
                    0,
                    $response_time_ms,
                    0,
                    'error',
                    $e->getMessage()
                );
            }
            
            throw $e;
        }
    }
}

// ============================================================================
// QUICK REFERENCE: Tracking Methods to Use
// ============================================================================
/*
 * TextGeneratorV2:       AnalyticsTracker::trackTextGeneration()
 * CodeGeneratorV2:       AnalyticsTracker::trackCodeGeneration()
 * SummaryGeneratorV2:    AnalyticsTracker::trackSummarization()
 * PromptOptimizerV2:     AnalyticsTracker::trackPromptOptimization()
 * TranslationEngineV2:   AnalyticsTracker::trackTranslation()
 * ImageGeneratorV3:      AnalyticsTracker::trackImageGeneration()
 * 
 * All methods have signature:
 * track*(
 *     $action,                // What was done (e.g., 'generateArticle')
 *     $input_length,          // Length of input
 *     $output_length,         // Length of output
 *     $response_time_ms,      // How long it took (milliseconds)
 *     $tokens_used,           // Tokens consumed (or 0 for images)
 *     $status,                // 'success' or 'error'
 *     $error_message = null,  // Error details if failed
 *     $memory_used_mb = 0     // RAM used (optional)
 * )
 */

// ============================================================================
// INTEGRATION CHECKLIST
// ============================================================================
/*
 * [ ] Add require_once for AnalyticsTracker at top of class
 * [ ] Add getTokensUsed() helper method
 * [ ] Wrap first main method with pattern
 * [ ] Test that tracking works
 * [ ] Wrap remaining methods
 * [ ] Test all methods work
 * [ ] Verify database tables have data
 * [ ] Check API endpoints return real data
 * [ ] Create test file showing real tracking
 * [ ] Document changes
 * [ ] Mark as complete
 */

// ============================================================================
// EXAMPLE TEST FILE
// ============================================================================
/*
 * <?php
 * require_once 'classes/TextGeneratorV2.php';
 * require_once 'classes/AnalyticsTracker.php';
 * require_once 'classes/AIAnalyticsV2.php';
 * 
 * $user_id = 123;
 * $generator = new TextGeneratorV2($user_id);
 * 
 * // Generate something (will be tracked automatically)
 * $result = $generator->generateArticle('Climate Change', ['length' => 'long']);
 * 
 * // Get analytics
 * $analytics = new AIAnalyticsV2($user_id);
 * $stats = $analytics->getRealUsageStats('today');
 * 
 * echo "Requests: " . $stats['total_requests'] . "\n";
 * echo "Cost: $" . $stats['total_cost'] . "\n";
 * ?>
 */
?>
