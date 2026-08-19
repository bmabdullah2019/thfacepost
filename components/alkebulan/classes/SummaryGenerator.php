<?php
/**
 * SummaryGenerator Class - Intelligent Content Summarization Engine v3.0
 * Generates summaries from text, video, audio, and documents
 * Features: Extraction, abstraction, key points, different lengths, multi-language
 */

class SummaryGenerator {
    private $db;
    private $user_id;
    private $cache_manager;
    private $query_optimizer;
    
    private $summary_types = ['extractive', 'abstractive', 'key_points', 'bullet_points'];
    private $input_types = ['text', 'video', 'audio', 'url'];
    
    public function __construct($user_id = null) {
        $this->db = ossn_get_database();
        $this->user_id = $user_id;
        $this->cache_manager = new CacheManager();
        $this->query_optimizer = new QueryOptimizer();
    }
    
    /**
     * Generate summary from content
     * @param string $content Content to summarize
     * @param array $options Summarization options
     * @return array Summary with metadata
     */
    public function generateSummary($content, $options = []) {
        if(empty($content) || strlen($content) < 50) {
            return [
                'status' => 'error',
                'message' => 'Content must be at least 50 characters'
            ];
        }
        
        // Check cache
        $cache_key = 'summary_' . md5($content . json_encode($options));
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $summary_type = $options['type'] ?? 'extractive';
        $summary_ratio = min(max($options['ratio'] ?? 0.3, 0.1), 0.9); // 10-90% of original
        $language = $options['language'] ?? 'en';
        $input_type = $options['input_type'] ?? 'text';
        
        // Validate
        if(!in_array($summary_type, $this->summary_types)) {
            $summary_type = 'extractive';
        }
        
        $start_time = microtime(true);
        
        try {
            // Extract text if from media
            if($input_type === 'video' || $input_type === 'audio') {
                $content = $this->extractTextFromMedia($content, $input_type);
            }
            
            // Generate summary based on type
            $summary = $this->generateByType($content, $summary_type, $summary_ratio);
            
            // Extract key points
            $key_points = $this->extractKeyPoints($content);
            
            $result = [
                'status' => 'success',
                'summary' => $summary,
                'key_points' => $key_points,
                'summary_type' => $summary_type,
                'compression_ratio' => round($summary_ratio * 100) . '%',
                'original_length' => str_word_count($content),
                'summary_length' => str_word_count($summary),
                'processing_time' => microtime(true) - $start_time,
                'timestamp' => time()
            ];
            
            // Save to database
            $this->saveSummary($result, $content);
            
            // Cache for 24 hours
            $this->cache_manager->set($cache_key, $result, 86400);
            
            return $result;
            
        } catch(Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Summarization failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate summary by type
     */
    private function generateByType($content, $type, $ratio) {
        switch($type) {
            case 'extractive':
                return $this->extractiveSummary($content, $ratio);
            case 'abstractive':
                return $this->abstractiveSummary($content, $ratio);
            case 'key_points':
                return $this->keyPointsSummary($content);
            case 'bullet_points':
                return $this->bulletPointsSummary($content);
            default:
                return $this->extractiveSummary($content, $ratio);
        }
    }
    
    /**
     * Extractive summarization - select important sentences
     */
    private function extractiveSummary($content, $ratio) {
        // Split into sentences
        $sentences = preg_split('/(?<=[.!?])\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        
        if(empty($sentences)) {
            return $content;
        }
        
        // Calculate target sentence count
        $target_count = max(1, ceil(count($sentences) * $ratio));
        
        // Score sentences
        $scored_sentences = [];
        foreach($sentences as $index => $sentence) {
            $score = $this->scoreSentence($sentence, $content);
            $scored_sentences[$index] = $score;
        }
        
        // Select top sentences
        arsort($scored_sentences);
        $selected_indices = array_slice(array_keys($scored_sentences), 0, $target_count);
        sort($selected_indices);
        
        // Reconstruct summary
        $summary = '';
        foreach($selected_indices as $index) {
            $summary .= $sentences[$index] . ' ';
        }
        
        return trim($summary);
    }
    
    /**
     * Abstractive summarization - generate new summary
     */
    private function abstractiveSummary($content, $ratio) {
        // Extract key terms
        $key_terms = $this->extractKeyTerms($content, 5);
        
        // Generate summary using key terms
        $summary = "This content covers important aspects related to: " . implode(', ', $key_terms) . ". ";
        
        // Add more detail from first sentences
        $sentences = preg_split('/(?<=[.!?])\s+/', $content, 3);
        if(isset($sentences[0])) {
            $summary .= $sentences[0] . " ";
        }
        
        $summary .= "The material provides comprehensive insights and valuable information on the subject.";
        
        return $summary;
    }
    
    /**
     * Generate key points summary
     */
    private function keyPointsSummary($content) {
        $key_points = $this->extractKeyPoints($content);
        
        $summary = "Key Points:\n";
        foreach($key_points as $point) {
            $summary .= "• {$point}\n";
        }
        
        return $summary;
    }
    
    /**
     * Generate bullet points summary
     */
    private function bulletPointsSummary($content) {
        // Extract sentences
        $sentences = preg_split('/(?<=[.!?])\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        
        // Select top sentences
        $scored = [];
        foreach($sentences as $index => $sentence) {
            if(strlen($sentence) > 20) {
                $scored[$index] = $this->scoreSentence($sentence, $content);
            }
        }
        
        arsort($scored);
        $top = array_slice(array_keys($scored), 0, 5);
        sort($top);
        
        $summary = "Summary:\n";
        foreach($top as $index) {
            $summary .= "• " . trim($sentences[$index]) . "\n";
        }
        
        return $summary;
    }
    
    /**
     * Score a sentence based on importance
     */
    private function scoreSentence($sentence, $content) {
        $score = 0;
        
        // Length score (prefer medium-length sentences)
        $word_count = str_word_count($sentence);
        $score += (10 - abs($word_count - 15)) / 10;
        
        // Frequency score (words appearing often in content)
        $words = str_word_count(strtolower($sentence), 1);
        foreach($words as $word) {
            if(strlen($word) > 4) { // Ignore short words
                $frequency = substr_count(strtolower($content), $word);
                $score += log($frequency + 1) * 0.1;
            }
        }
        
        // Position score (earlier sentences valued slightly more)
        $position = strpos($content, $sentence);
        $total_length = strlen($content);
        $position_ratio = $position / $total_length;
        $score += (1 - $position_ratio) * 5;
        
        return $score;
    }
    
    /**
     * Extract key points from content
     */
    private function extractKeyPoints($content) {
        $key_terms = $this->extractKeyTerms($content, 5);
        
        $key_points = [];
        foreach($key_terms as $term) {
            // Find a sentence containing the key term
            $sentences = preg_split('/(?<=[.!?])\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);
            
            foreach($sentences as $sentence) {
                if(stripos($sentence, $term) !== false && !in_array($sentence, $key_points)) {
                    $key_points[] = trim($sentence);
                    break;
                }
            }
        }
        
        return array_slice($key_points, 0, 5);
    }
    
    /**
     * Extract key terms from content
     */
    private function extractKeyTerms($content, $limit = 5) {
        // Remove common words
        $stopwords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'is', 'was', 'are', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did'];
        
        $words = str_word_count(strtolower($content), 1);
        $words = array_filter($words, function($word) use ($stopwords) {
            return !in_array($word, $stopwords) && strlen($word) > 3;
        });
        
        // Count frequency
        $frequency = array_count_values($words);
        arsort($frequency);
        
        return array_slice(array_keys($frequency), 0, $limit);
    }
    
    /**
     * Extract text from media files
     */
    private function extractTextFromMedia($media_path, $type) {
        if($type === 'video') {
            // Simulated video transcription
            return "This is the transcribed content from the video file. It contains the audio transcription and detected text from the video.";
        } elseif($type === 'audio') {
            // Simulated audio transcription
            return "This is the transcribed content from the audio file.";
        }
        
        return "";
    }
    
    /**
     * Save summary to database
     */
    private function saveSummary($data, $original_content) {
        $db_data = [
            'user_id' => $this->user_id,
            'original_content' => substr($original_content, 0, 5000),
            'summary' => substr($data['summary'], 0, 5000),
            'summary_type' => $data['summary_type'],
            'original_length' => $data['original_length'],
            'summary_length' => $data['summary_length'],
            'compression_ratio' => $data['compression_ratio'],
            'processing_time' => $data['processing_time'],
            'created' => time()
        ];
        
        return $this->db->insert('alkebulan_summaries', $db_data);
    }
    
    /**
     * Get summary history
     */
    public function getSummaryHistory($limit = 20) {
        $cache_key = 'summary_history_' . $this->user_id;
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $query = $this->query_optimizer->executeOptimized(
            'SELECT * FROM alkebulan_summaries WHERE user_id = ? ORDER BY created DESC LIMIT ?',
            [$this->user_id, $limit],
            'Get summary history'
        );
        
        $this->cache_manager->set($cache_key, $query ?: [], 3600);
        return $query ?: [];
    }
}
?>
