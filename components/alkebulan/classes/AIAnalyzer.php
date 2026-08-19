<?php
/**
 * AIAnalyzer Class - Content Analysis Engine v2.0
 * Analyzes text, sentiment, entity recognition, and content classification
 * Enhanced with intelligent caching and query optimization
 */

class AIAnalyzer {
    private $db;
    private $user_id;
    private $cache_manager;
    private $query_optimizer;
    private $memory_cache = [];
    
    public function __construct($user_id = null) {
        $this->db = ossn_get_database();
        $this->user_id = $user_id;
        $this->cache_manager = new CacheManager();
        $this->query_optimizer = new QueryOptimizer();
    }
    
    /**
     * Analyze content for sentiment - with caching
     */
    public function analyzeSentiment($text) {
        // Check cache first
        $cache_key = 'sentiment_' . md5($text);
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $analysis = [
            'text' => $text,
            'type' => 'sentiment',
            'sentiment' => $this->detectSentiment($text),
            'confidence' => $this->calculateConfidence($text),
            'emotions' => $this->extractEmotions($text),
            'keywords' => $this->extractKeywords($text),
            'timestamp' => time()
        ];
        
        $result = $this->storeAnalysis($analysis);
        
        // Cache for 24 hours
        $this->cache_manager->set($cache_key, $result, 86400);
        
        return $result;
    }
    
    /**
     * Detect overall sentiment
     */
    private function detectSentiment($text) {
        $positive_words = ['good', 'great', 'awesome', 'excellent', 'love', 'happy', 'wonderful', 'fantastic'];
        $negative_words = ['bad', 'terrible', 'awful', 'hate', 'sad', 'horrible', 'disappointing'];
        
        $text_lower = strtolower($text);
        $pos_count = 0;
        $neg_count = 0;
        
        foreach($positive_words as $word) {
            $pos_count += substr_count($text_lower, $word);
        }
        foreach($negative_words as $word) {
            $neg_count += substr_count($text_lower, $word);
        }
        
        if($pos_count > $neg_count) return 'positive';
        if($neg_count > $pos_count) return 'negative';
        return 'neutral';
    }
    
    /**
     * Calculate confidence score
     */
    private function calculateConfidence($text) {
        $length = strlen($text);
        $words = str_word_count($text);
        
        if($words < 5) return 0.4;
        if($words < 20) return 0.6;
        if($words < 100) return 0.8;
        return 0.95;
    }
    
    /**
     * Extract emotions from text
     */
    private function extractEmotions($text) {
        $emotions = [];
        $emotion_map = [
            'happy' => ['happy', 'glad', 'joyful', 'delighted'],
            'sad' => ['sad', 'upset', 'depressed', 'unhappy'],
            'angry' => ['angry', 'furious', 'mad', 'irritated'],
            'excited' => ['excited', 'thrilled', 'eager', 'enthusiastic'],
            'confused' => ['confused', 'puzzled', 'bewildered', 'uncertain']
        ];
        
        $text_lower = strtolower($text);
        foreach($emotion_map as $emotion => $keywords) {
            foreach($keywords as $keyword) {
                if(strpos($text_lower, $keyword) !== false) {
                    $emotions[$emotion] = true;
                }
            }
        }
        
        return array_keys($emotions);
    }
    
    /**
     * Extract keywords from text
     */
    private function extractKeywords($text, $limit = 10) {
        $words = str_word_count($text, 1);
        $words = array_map('strtolower', $words);
        
        // Remove common stopwords
        $stopwords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'is', 'was', 'are'];
        $words = array_diff($words, $stopwords);
        
        // Count word frequency
        $frequency = array_count_values($words);
        arsort($frequency);
        
        return array_slice(array_keys($frequency), 0, $limit);
    }
    
    /**
     * Analyze content category - with caching
     */
    public function categorizeContent($text) {
        // Check cache first
        $cache_key = 'category_' . md5($text);
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $categories = [];
        
        // Check for various content types
        if($this->containsCategory($text, ['tech', 'code', 'software', 'app', 'develop'])) {
            $categories[] = 'technology';
        }
        if($this->containsCategory($text, ['business', 'sales', 'money', 'finance', 'invest'])) {
            $categories[] = 'business';
        }
        if($this->containsCategory($text, ['health', 'medical', 'doctor', 'fitness', 'exercise'])) {
            $categories[] = 'health';
        }
        if($this->containsCategory($text, ['art', 'music', 'design', 'creative', 'culture'])) {
            $categories[] = 'arts';
        }
        if($this->containsCategory($text, ['sports', 'game', 'play', 'team', 'match'])) {
            $categories[] = 'sports';
        }
        
        $result = $categories ?: ['general'];
        
        // Cache for 24 hours
        $this->cache_manager->set($cache_key, $result, 86400);
        
        return $result;
    }
    
    /**
     * Check if text contains category keywords
     */
    private function containsCategory($text, $keywords) {
        $text_lower = strtolower($text);
        foreach($keywords as $keyword) {
            if(strpos($text_lower, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Entity recognition - extract names, places, organizations
     */
    public function recognizeEntities($text) {
        $entities = [
            'persons' => [],
            'places' => [],
            'organizations' => []
        ];
        
        // Simple implementation - in production use NLP library
        $words = explode(' ', $text);
        foreach($words as $word) {
            // Check for capitalized words (potential entities)
            if(ctype_upper($word[0]) && strlen($word) > 2) {
                $entities['persons'][] = $word;
            }
        }
        
        return $entities;
    }
    
    /**
     * Store analysis in database
     */
    private function storeAnalysis($analysis) {
        $data = [
            'user_id' => $this->user_id ?: 0,
            'analysis_type' => $analysis['type'],
            'input_text' => substr($analysis['text'], 0, 1000),
            'output_data' => json_encode($analysis),
            'confidence_score' => $analysis['confidence'] ?? 0.5,
            'status' => 'completed',
            'created' => time()
        ];
        
        $query = $this->db->insert('alkebulan_analysis', $data);
        $analysis['id'] = $query;
        
        return $analysis;
    }
    
    /**
     * Batch analyze multiple texts
     */
    public function batchAnalyze($texts) {
        $results = [];
        foreach($texts as $text) {
            $results[] = $this->analyzeSentiment($text);
        }
        return $results;
    }
    
    /**
     * Get analysis history - optimized with query optimizer
     */
    public function getAnalysisHistory($limit = 20) {
        $cache_key = 'analysis_history_' . $this->user_id . '_' . $limit;
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $query = $this->query_optimizer->executeOptimized(
            'SELECT * FROM alkebulan_analysis WHERE user_id = ? ORDER BY created DESC LIMIT ?',
            [$this->user_id, $limit],
            'Get analysis history for user'
        );
        
        // Cache for 1 hour
        $this->cache_manager->set($cache_key, $query ?: [], 3600);
        
        return $query ?: [];
    }
    
    /**
     * Get cache statistics
     */
    public function getCacheStats() {
        return $this->cache_manager->getStats();
    }
    
    /**
     * Get query performance statistics
     */
    public function getQueryStats() {
        return $this->query_optimizer->getPerformanceStats();
    }
    
    /**
     * Clear all caches
     */
    public function clearCache() {
        return $this->cache_manager->clear();
    }
}
?>
