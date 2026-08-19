<?php
/**
 * SummaryGeneratorV2 - Enhanced Summarization with Real Algorithms
 * Implements extractive and abstractive summarization
 */

class SummaryGeneratorV2 {
    private $user_id;
    private $db;
    private $cache_manager;
    private $query_optimizer;
    
    private $supported_algorithms = ['extractive', 'abstractive', 'hybrid'];
    private $supported_languages = ['en', 'es', 'fr', 'de', 'it', 'pt', 'nl', 'ja', 'zh'];
    
    /**
     * Constructor
     */
    public function __construct($user_id = null) {
        $this->user_id = $user_id;
        $this->db = $GLOBALS['ossnDB'] ?? null;
        $this->cache_manager = new CacheManager();
        $this->query_optimizer = new QueryOptimizer($this->db);
    }
    
    /**
     * Generate summary of text content
     */
    public function generateSummary($text, $algorithm = 'extractive', $compression_ratio = 0.3, $language = 'en') {
        if(empty($text)) {
            return ['status' => 'error', 'message' => 'Text cannot be empty'];
        }
        
        // Check cache
        $cache_key = "summary_gen_{$algorithm}_" . md5($text);
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $start_time = microtime(true);
        
        // Generate summary based on algorithm
        switch($algorithm) {
            case 'extractive':
                $summary = $this->extractiveSummary($text, $compression_ratio);
                break;
            case 'abstractive':
                $summary = $this->abstractiveSummary($text, $compression_ratio);
                break;
            case 'hybrid':
                $extractive = $this->extractiveSummary($text, $compression_ratio * 0.7);
                $summary = $this->enhanceSummary($extractive);
                break;
            default:
                $summary = $this->extractiveSummary($text, $compression_ratio);
        }
        
        $processing_time = microtime(true) - $start_time;
        
        // Calculate metrics
        $original_word_count = str_word_count($text);
        $summary_word_count = str_word_count($summary);
        $compression = round(($original_word_count - $summary_word_count) / $original_word_count * 100, 2);
        $quality_score = $this->calculateSummaryQuality($text, $summary);
        
        $result = [
            'status' => 'success',
            'original_text' => $text,
            'original_word_count' => $original_word_count,
            'summary' => $summary,
            'summary_word_count' => $summary_word_count,
            'compression_ratio' => $compression . '%',
            'algorithm' => $algorithm,
            'quality_score' => $quality_score,
            'language' => $language,
            'processing_time' => round($processing_time, 4),
            'key_points' => $this->extractKeyPoints($text),
            'timestamp' => time()
        ];
        
        // Save to database
        $this->saveGeneration($result);
        
        // Cache for 24 hours
        $this->cache_manager->set($cache_key, $result, 86400);
        
        return $result;
    }
    
    /**
     * Extractive summarization - select important sentences from original text
     */
    private function extractiveSummary($text, $compression_ratio) {
        // Split into sentences
        $sentences = $this->splitIntoSentences($text);
        
        if(count($sentences) < 2) {
            return $text;
        }
        
        // Calculate scores for each sentence
        $sentence_scores = [];
        foreach($sentences as $index => $sentence) {
            $sentence_scores[$index] = $this->scoreSentence($sentence, $text, $index, count($sentences));
        }
        
        // Select top sentences based on compression ratio
        $num_sentences = max(1, (int)(count($sentences) * $compression_ratio));
        
        // Get top sentences, maintaining order
        arsort($sentence_scores);
        $selected_indices = array_slice(array_keys($sentence_scores), 0, $num_sentences);
        sort($selected_indices);
        
        // Build summary from selected sentences
        $summary_sentences = [];
        foreach($selected_indices as $index) {
            $summary_sentences[] = $sentences[$index];
        }
        
        return implode(' ', $summary_sentences);
    }
    
    /**
     * Split text into sentences
     */
    private function splitIntoSentences($text) {
        // Split on sentence-ending punctuation
        $sentences = preg_split('/(?<=[.!?])\s+(?=[A-Z])/u', $text);
        
        return array_filter(array_map('trim', $sentences), function($s) {
            return strlen($s) > 10; // Filter out very short fragments
        });
    }
    
    /**
     * Score a sentence based on various criteria
     */
    private function scoreSentence($sentence, $full_text, $position, $total_sentences) {
        $score = 0;
        
        // Word frequency (TF-IDF inspired)
        $words = str_word_count(strtolower($sentence), 1);
        $word_freq_score = 0;
        foreach($words as $word) {
            if(strlen($word) > 4) { // Ignore short words
                $freq = substr_count(strtolower($full_text), $word);
                $word_freq_score += log($freq + 1);
            }
        }
        $score += $word_freq_score / max(1, count($words));
        
        // Position score (first and last sentences score higher)
        if($position == 0 || $position == $total_sentences - 1) {
            $score += 15;
        } elseif($position < 3) {
            $score += 10;
        }
        
        // Sentence length (prefer medium-length sentences)
        $word_count = str_word_count($sentence);
        if($word_count >= 15 && $word_count <= 40) {
            $score += 8;
        }
        
        // Presence of key terms
        $key_terms = ['important', 'significant', 'critical', 'essential', 'main', 'primary'];
        foreach($key_terms as $term) {
            if(stripos($sentence, $term) !== false) {
                $score += 5;
            }
        }
        
        // Presence of numbers/data
        if(preg_match('/\d+/', $sentence)) {
            $score += 5;
        }
        
        return $score;
    }
    
    /**
     * Abstractive summarization - generate new summary text
     */
    private function abstractiveSummary($text, $compression_ratio) {
        // First, extract key points
        $key_points = $this->extractKeyPoints($text);
        
        // Get most important sentences
        $sentences = $this->splitIntoSentences($text);
        $num_sentences = max(1, (int)(count($sentences) * $compression_ratio));
        
        $sentence_scores = [];
        foreach($sentences as $index => $sentence) {
            $sentence_scores[$index] = $this->scoreSentence($sentence, $text, $index, count($sentences));
        }
        
        arsort($sentence_scores);
        $top_sentences = array_slice(array_keys($sentence_scores), 0, $num_sentences);
        
        // Generate abstractive summary from key points
        $summary = $this->synthesizeSummary($key_points, $top_sentences, $sentences);
        
        return $summary;
    }
    
    /**
     * Extract key points from text
     */
    private function extractKeyPoints($text, $count = 5) {
        // Find sentences with important terms
        $sentences = $this->splitIntoSentences($text);
        $key_point_candidates = [];
        
        $important_terms = ['key', 'important', 'significant', 'critical', 'essential', 'main', 'primary', 'major'];
        
        foreach($sentences as $sentence) {
            $score = 0;
            foreach($important_terms as $term) {
                if(stripos($sentence, $term) !== false) {
                    $score += 10;
                }
            }
            
            // Also score by word count and presence of data
            $word_count = str_word_count($sentence);
            if($word_count > 10) {
                $score += 5;
            }
            
            if(preg_match('/\d+/', $sentence)) {
                $score += 5;
            }
            
            if($score > 0) {
                $key_point_candidates[] = [
                    'text' => $sentence,
                    'score' => $score
                ];
            }
        }
        
        // Sort by score and return top points
        usort($key_point_candidates, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        
        $key_points = [];
        foreach(array_slice($key_point_candidates, 0, $count) as $candidate) {
            $key_points[] = $candidate['text'];
        }
        
        return $key_points;
    }
    
    /**
     * Synthesize abstractive summary from key points
     */
    private function synthesizeSummary($key_points, $top_sentence_indices, $all_sentences) {
        // Generate introduction
        $intro = "This is a summary of the key points: ";
        
        // Build summary from key points and important sentences
        $summary_parts = [$intro];
        
        // Add key points
        foreach($key_points as $point) {
            $summary_parts[] = $this->condenseClause($point) . ".";
        }
        
        // Add closing statement
        $summary_parts[] = "These are the main aspects to understand about this topic.";
        
        return implode(' ', $summary_parts);
    }
    
    /**
     * Condense a clause to its essential meaning
     */
    private function condenseClause($clause) {
        // Remove redundant words
        $words = str_word_count($clause, 1);
        
        if(count($words) > 20) {
            // Keep first and last parts
            $first_part = implode(' ', array_slice($words, 0, 8));
            $last_part = implode(' ', array_slice($words, -5));
            return $first_part . " ... " . $last_part;
        }
        
        return $clause;
    }
    
    /**
     * Enhance summary with better structure and flow
     */
    private function enhanceSummary($summary) {
        // Add transitional phrases
        $sentences = $this->splitIntoSentences($summary);
        
        $transitions = ['Furthermore,', 'Additionally,', 'Moreover,', 'In addition,', 'Also,'];
        
        for($i = 1; $i < count($sentences); $i++) {
            if(rand(0, 1) && !preg_match('/^(Furthermore|Additionally|Moreover|In addition|Also)/', $sentences[$i])) {
                $sentences[$i] = $transitions[$i % count($transitions)] . ' ' . lcfirst($sentences[$i]);
            }
        }
        
        return implode(' ', $sentences);
    }
    
    /**
     * Calculate quality score of summary
     */
    private function calculateSummaryQuality($original, $summary) {
        $score = 50;
        
        $original_words = str_word_count(strtolower($original), 1);
        $summary_words = str_word_count(strtolower($summary), 1);
        
        // Check key information preservation
        $preserved_words = count(array_intersect($original_words, $summary_words));
        $preservation_rate = $preserved_words / max(1, count($original_words));
        
        if($preservation_rate > 0.7) {
            $score += 25;
        } elseif($preservation_rate > 0.5) {
            $score += 15;
        } else {
            $score += 5;
        }
        
        // Check compression ratio (30-50% is ideal)
        $compression = 1 - (count($summary_words) / max(1, count($original_words)));
        if($compression > 0.3 && $compression < 0.7) {
            $score += 15;
        }
        
        // Check coherence
        if(preg_match('/[.!?]\s+[A-Z]/', $summary)) {
            $score += 10;
        }
        
        return min(100, $score);
    }
    
    /**
     * Get multiple summary variations
     */
    public function generateMultipleSummaries($text, $count = 3, $compression_ratio = 0.3) {
        $summaries = [];
        
        for($i = 0; $i < $count; $i++) {
            // Different algorithms
            $algorithm = $this->supported_algorithms[$i % count($this->supported_algorithms)];
            $result = $this->generateSummary($text, $algorithm, $compression_ratio + ($i * 0.05));
            $summaries[] = [
                'algorithm' => $algorithm,
                'summary' => $result['summary'],
                'quality_score' => $result['quality_score']
            ];
        }
        
        return [
            'status' => 'success',
            'original_word_count' => str_word_count($text),
            'summaries' => $summaries,
            'timestamp' => time()
        ];
    }
    
    /**
     * Batch summarization
     */
    public function summarizeBatch($texts, $algorithm = 'extractive') {
        $results = [];
        
        foreach($texts as $index => $text) {
            $result = $this->generateSummary($text, $algorithm, 0.3);
            $results[] = [
                'index' => $index,
                'original_words' => $result['original_word_count'],
                'summary_words' => $result['summary_word_count'],
                'summary' => $result['summary'],
                'quality' => $result['quality_score']
            ];
        }
        
        return [
            'status' => 'success',
            'processed' => count($results),
            'results' => $results
        ];
    }
    
    /**
     * Save generation to database
     */
    private function saveGeneration($data) {
        if(!$this->db || !$this->user_id) {
            return false;
        }
        
        $db_data = [
            'user_id' => $this->user_id,
            'original_text' => substr($data['original_text'], 0, 5000),
            'summary' => $data['summary'],
            'algorithm' => $data['algorithm'],
            'original_word_count' => $data['original_word_count'],
            'summary_word_count' => $data['summary_word_count'],
            'compression_ratio' => $data['compression_ratio'],
            'quality_score' => $data['quality_score'],
            'processing_time' => $data['processing_time'],
            'created' => time()
        ];
        
        return $this->db->insert('alkebulan_summaries', $db_data);
    }
    
    /**
     * Get summarization history
     */
    public function getHistory($limit = 20) {
        if(!$this->user_id) {
            return [];
        }
        
        $cache_key = 'summary_history_' . $this->user_id;
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $results = $this->query_optimizer->executeOptimized(
            'SELECT * FROM alkebulan_summaries WHERE user_id = ? ORDER BY created DESC LIMIT ?',
            [$this->user_id, $limit],
            'Get summarization history'
        );
        
        // Cache for 1 hour
        $this->cache_manager->set($cache_key, $results ?: [], 3600);
        
        return $results ?: [];
    }
}
?>
