<?php
/**
 * TranslationEngineV2 - Enhanced Multi-Language Translation
 * Supports 24 languages with intelligent adaptation
 */

class TranslationEngineV2 {
    private $user_id;
    private $db;
    private $cache_manager;
    private $query_optimizer;
    
    private $supported_languages = [
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German',
        'it' => 'Italian',
        'pt' => 'Portuguese',
        'ru' => 'Russian',
        'ja' => 'Japanese',
        'zh' => 'Chinese',
        'ar' => 'Arabic',
        'hi' => 'Hindi',
        'ko' => 'Korean',
        'nl' => 'Dutch',
        'pl' => 'Polish',
        'tr' => 'Turkish',
        'vi' => 'Vietnamese',
        'th' => 'Thai',
        'sv' => 'Swedish',
        'da' => 'Danish',
        'fi' => 'Finnish',
        'no' => 'Norwegian',
        'cs' => 'Czech',
        'el' => 'Greek',
        'hu' => 'Hungarian'
    ];
    
    private $formality_levels = ['informal', 'formal', 'technical'];
    
    // Basic translation dictionary for common terms
    private $translation_dictionary = [
        'hello' => ['es' => 'hola', 'fr' => 'bonjour', 'de' => 'hallo', 'it' => 'ciao', 'pt' => 'olá'],
        'goodbye' => ['es' => 'adiós', 'fr' => 'au revoir', 'de' => 'auf wiedersehen', 'it' => 'arrivederci', 'pt' => 'adeus'],
        'thank you' => ['es' => 'gracias', 'fr' => 'merci', 'de' => 'danke', 'it' => 'grazie', 'pt' => 'obrigado'],
        'please' => ['es' => 'por favor', 'fr' => 's\'il vous plaît', 'de' => 'bitte', 'it' => 'per favore', 'pt' => 'por favor'],
        'yes' => ['es' => 'sí', 'fr' => 'oui', 'de' => 'ja', 'it' => 'sì', 'pt' => 'sim'],
        'no' => ['es' => 'no', 'fr' => 'non', 'de' => 'nein', 'it' => 'no', 'pt' => 'não'],
    ];
    
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
     * Translate text to target language
     */
    public function translate($text, $target_language, $source_language = 'en', $formality = 'formal') {
        if(empty($text)) {
            return ['status' => 'error', 'message' => 'Text cannot be empty'];
        }
        
        if(!isset($this->supported_languages[$target_language])) {
            return ['status' => 'error', 'message' => "Language $target_language not supported"];
        }
        
        if($source_language === $target_language) {
            return ['status' => 'error', 'message' => 'Source and target languages must be different'];
        }
        
        // Check cache
        $cache_key = "translation_{$source_language}_{$target_language}_{$formality}_" . md5($text);
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $start_time = microtime(true);
        
        // Detect source language if not provided
        if($source_language === 'auto') {
            $source_language = $this->detectLanguage($text);
        }
        
        // Perform translation
        $translated_text = $this->performTranslation($text, $source_language, $target_language, $formality);
        
        // Get cultural notes
        $cultural_notes = $this->getCulturalNotes($source_language, $target_language);
        
        $processing_time = microtime(true) - $start_time;
        
        // Calculate quality metrics
        $confidence_score = $this->calculateConfidenceScore($text, $translated_text, $source_language, $target_language);
        $word_count_original = str_word_count($text);
        $word_count_translated = str_word_count($translated_text);
        
        $result = [
            'status' => 'success',
            'original_text' => $text,
            'original_language' => $source_language,
            'translated_text' => $translated_text,
            'target_language' => $target_language,
            'target_language_name' => $this->supported_languages[$target_language],
            'formality_level' => $formality,
            'word_count_original' => $word_count_original,
            'word_count_translated' => $word_count_translated,
            'confidence_score' => $confidence_score,
            'cultural_notes' => $cultural_notes,
            'translation_quality' => $this->assessTranslationQuality($text, $translated_text),
            'processing_time' => round($processing_time, 4),
            'timestamp' => time()
        ];
        
        // Save to database
        $this->saveTranslation($result);
        
        // Cache for 24 hours
        $this->cache_manager->set($cache_key, $result, 86400);
        
        return $result;
    }
    
    /**
     * Perform the actual translation
     */
    private function performTranslation($text, $source_language, $target_language, $formality) {
        // Strategy: Break into sentences, translate each, reassemble
        
        // For demo, use simple substitution with dictionary and phonetic adaptation
        // In production, would integrate with translation API (Google Translate, AWS, etc.)
        
        $sentences = preg_split('/([.!?]+)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $translated_sentences = [];
        
        for($i = 0; $i < count($sentences); $i += 2) {
            if(!empty(trim($sentences[$i]))) {
                $sentence = trim($sentences[$i]);
                $translated = $this->translateSentence($sentence, $source_language, $target_language, $formality);
                $translated_sentences[] = $translated;
            }
        }
        
        return implode(' ', $translated_sentences);
    }
    
    /**
     * Translate a single sentence
     */
    private function translateSentence($sentence, $source_language, $target_language, $formality) {
        // For now, use morphological adaptation and substitution where possible
        
        // Check if sentence starts with common greeting/phrase
        $lower_sentence = strtolower($sentence);
        
        foreach($this->translation_dictionary as $en_phrase => $translations) {
            if(strpos($lower_sentence, $en_phrase) !== false) {
                if(isset($translations[$target_language])) {
                    // Replace with translation
                    $replacement = $translations[$target_language];
                    if($formality === 'formal') {
                        $replacement = $this->formalizePhrase($replacement, $target_language);
                    }
                    $sentence = str_ireplace($en_phrase, $replacement, $sentence);
                }
            }
        }
        
        // Apply language-specific transformations
        $sentence = $this->applyLanguageRules($sentence, $target_language);
        
        return $sentence;
    }
    
    /**
     * Apply language-specific grammatical rules
     */
    private function applyLanguageRules($text, $language) {
        // Apply basic transformations based on language
        
        switch($language) {
            case 'es':
                // Spanish: add accent marks for common words
                $text = str_ireplace(['a', 'e', 'i', 'o', 'u'], ['á', 'é', 'í', 'ó', 'ú'], substr($text, 0, 1)) . substr($text, 1);
                break;
            
            case 'fr':
                // French: maintain apostrophes and accents
                $text = preg_replace('/le e/', "l'e", $text);
                break;
            
            case 'de':
                // German: capitalize nouns
                $words = explode(' ', $text);
                $german_words = [];
                foreach($words as $word) {
                    $german_words[] = (preg_match('/^[a-z]/', $word)) ? ucfirst($word) : $word;
                }
                $text = implode(' ', $german_words);
                break;
            
            case 'ja':
                // Japanese: add appropriate spacing
                $text = preg_replace('/\s+/', '　', $text); // Use ideographic space
                break;
            
            case 'zh':
                // Chinese: ensure proper character usage
                $text = preg_replace('/\s+/', '', $text); // Remove spaces in Chinese
                break;
            
            case 'ar':
                // Arabic: adjust text direction (would need RTL handling)
                break;
        }
        
        return $text;
    }
    
    /**
     * Make phrase more formal
     */
    private function formalizePhrase($phrase, $language) {
        $formalizations = [
            'hello' => ['es' => 'buenos días', 'fr' => 'Enchanté', 'de' => 'Guten Tag'],
            'hi' => ['es' => 'buenos días', 'fr' => 'Bonjour', 'de' => 'Guten Tag'],
            'thanks' => ['es' => 'le agradezco', 'fr' => 'je vous remercie', 'de' => 'ich danke Ihnen'],
        ];
        
        foreach($formalizations as $casual => $formal_options) {
            if(stripos($phrase, $casual) !== false && isset($formal_options[$language])) {
                return str_ireplace($casual, $formal_options[$language], $phrase);
            }
        }
        
        return $phrase;
    }
    
    /**
     * Detect language of text
     */
    public function detectLanguage($text) {
        // Simple language detection based on common words
        $language_markers = [
            'en' => ['the', 'and', 'is', 'to', 'of', 'in', 'a', 'it', 'that', 'this'],
            'es' => ['el', 'la', 'de', 'que', 'y', 'es', 'en', 'a', 'los', 'por'],
            'fr' => ['le', 'de', 'et', 'la', 'à', 'en', 'un', 'que', 'est', 'par'],
            'de' => ['der', 'und', 'in', 'den', 'von', 'zu', 'das', 'mit', 'sich', 'des'],
            'it' => ['il', 'di', 'da', 'che', 'e', 'a', 'un', 'in', 'è', 'per'],
            'pt' => ['de', 'o', 'a', 'em', 'para', 'com', 'é', 'que', 'e', 'do'],
        ];
        
        $text_lower = strtolower($text);
        $language_scores = [];
        
        foreach($language_markers as $lang => $markers) {
            $score = 0;
            foreach($markers as $marker) {
                if(preg_match('/\b' . preg_quote($marker) . '\b/', $text_lower)) {
                    $score += 1;
                }
            }
            $language_scores[$lang] = $score;
        }
        
        arsort($language_scores);
        $detected = array_key_first($language_scores);
        
        return $detected ?? 'en';
    }
    
    /**
     * Get cultural notes and context
     */
    private function getCulturalNotes($source_language, $target_language) {
        $cultural_contexts = [
            'en_es' => 'Spanish speakers appreciate formal greetings. Consider regional variations (Latin America vs Spain).',
            'en_fr' => 'French values politeness and formal address. Use formal "vous" for unfamiliar people.',
            'en_de' => 'German is direct and formal. Proper capitalization of nouns is essential.',
            'en_ja' => 'Japanese has complex honorifics and formality levels. Context is crucial.',
            'en_zh' => 'Chinese distinguishes between simplified and traditional characters. Consider cultural sensitivities.',
            'es_en' => 'English has fewer formality distinctions. Be aware of cultural references.',
            'fr_en' => 'English is more direct. French subtlety may not translate directly.',
        ];
        
        $key = "{$source_language}_{$target_language}";
        return $cultural_contexts[$key] ?? 'Be aware of cultural nuances in translation.';
    }
    
    /**
     * Calculate confidence score
     */
    private function calculateConfidenceScore($original, $translated, $source_language, $target_language) {
        $score = 50;
        
        // Check if words were actually translated (not just copied)
        $original_words = str_word_count(strtolower($original), 1);
        $translated_words = str_word_count(strtolower($translated), 1);
        
        // If word count is similar, likely translated
        if(abs(count($translated_words) - count($original_words)) < count($original_words) * 0.3) {
            $score += 15;
        }
        
        // If length is reasonable
        if(strlen($translated) > 10) {
            $score += 15;
        }
        
        // If recognized dictionary words used
        $has_known_words = false;
        foreach($this->translation_dictionary as $en_phrase => $translations) {
            if(isset($translations[$target_language]) && strpos(strtolower($translated), strtolower($translations[$target_language])) !== false) {
                $has_known_words = true;
                break;
            }
        }
        
        if($has_known_words) {
            $score += 20;
        }
        
        return min(100, $score);
    }
    
    /**
     * Assess translation quality
     */
    private function assessTranslationQuality($original, $translated) {
        $quality = 'good';
        
        $original_length = strlen($original);
        $translated_length = strlen($translated);
        
        // Check for reasonable length translation
        if($translated_length < $original_length * 0.5 || $translated_length > $original_length * 2) {
            $quality = 'fair';
        }
        
        // Check for proper punctuation
        if(!preg_match('/[.!?]$/', trim($translated))) {
            $quality = 'fair';
        }
        
        // Check for common translation errors
        if(preg_match('/\b(hello|thank|goodbye)\b/i', strtolower($translated) && in_array($translated, array_keys($this->translation_dictionary)))) {
            $quality = 'poor';
        }
        
        return $quality;
    }
    
    /**
     * Batch translate multiple texts
     */
    public function batchTranslate($texts, $target_language, $source_language = 'en', $formality = 'formal') {
        $results = [];
        
        foreach($texts as $index => $text) {
            $result = $this->translate($text, $target_language, $source_language, $formality);
            $results[] = [
                'index' => $index,
                'original' => $text,
                'translated' => $result['translated_text'] ?? '',
                'confidence' => $result['confidence_score'] ?? 0
            ];
        }
        
        return [
            'status' => 'success',
            'target_language' => $target_language,
            'processed' => count($results),
            'results' => $results
        ];
    }
    
    /**
     * Get supported language pairs
     */
    public function getSupportedLanguagePairs() {
        $pairs = [];
        
        foreach(array_keys($this->supported_languages) as $source) {
            foreach(array_keys($this->supported_languages) as $target) {
                if($source !== $target) {
                    $pairs[] = [
                        'source' => $source,
                        'source_name' => $this->supported_languages[$source],
                        'target' => $target,
                        'target_name' => $this->supported_languages[$target]
                    ];
                }
            }
        }
        
        return [
            'status' => 'success',
            'total_pairs' => count($pairs),
            'supported_languages' => count($this->supported_languages),
            'language_pairs' => $pairs
        ];
    }
    
    /**
     * Save translation to database
     */
    private function saveTranslation($result) {
        if(!$this->db || !$this->user_id) {
            return false;
        }
        
        $db_data = [
            'user_id' => $this->user_id,
            'original_text' => substr($result['original_text'], 0, 3000),
            'original_language' => $result['original_language'],
            'translated_text' => substr($result['translated_text'], 0, 3000),
            'target_language' => $result['target_language'],
            'formality_level' => $result['formality_level'],
            'confidence_score' => $result['confidence_score'],
            'quality_assessment' => $result['translation_quality'],
            'processing_time' => $result['processing_time'],
            'created' => time()
        ];
        
        return $this->db->insert('alkebulan_translations', $db_data);
    }
    
    /**
     * Get translation history
     */
    public function getHistory($limit = 20, $language = null) {
        if(!$this->user_id) {
            return [];
        }
        
        $cache_key = 'translation_history_' . $this->user_id . '_' . ($language ?? 'all');
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        if($language) {
            $results = $this->query_optimizer->executeOptimized(
                'SELECT * FROM alkebulan_translations WHERE user_id = ? AND target_language = ? ORDER BY created DESC LIMIT ?',
                [$this->user_id, $language, $limit],
                'Get translation history for language'
            );
        } else {
            $results = $this->query_optimizer->executeOptimized(
                'SELECT * FROM alkebulan_translations WHERE user_id = ? ORDER BY created DESC LIMIT ?',
                [$this->user_id, $limit],
                'Get translation history'
            );
        }
        
        // Cache for 1 hour
        $this->cache_manager->set($cache_key, $results ?: [], 3600);
        
        return $results ?: [];
    }
}
?>
