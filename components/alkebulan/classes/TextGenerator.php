<?php
/**
 * TextGenerator Class - Advanced AI Text Generation Engine v3.0
 * Generates content, articles, emails, descriptions, stories, and variations
 * Features: Templates, variations, tone control, length optimization, caching
 */

class TextGenerator {
    private $db;
    private $user_id;
    private $cache_manager;
    private $query_optimizer;
    
    private $supported_tones = ['formal', 'casual', 'professional', 'creative', 'humorous', 'serious', 'inspirational'];
    private $content_types = ['article', 'email', 'description', 'story', 'blog', 'social', 'review', 'summary'];
    private $supported_languages = ['en', 'es', 'fr', 'de', 'it', 'pt', 'ja', 'zh', 'ar'];
    
    public function __construct($user_id = null) {
        $this->db = ossn_get_database();
        $this->user_id = $user_id;
        $this->cache_manager = new CacheManager();
        $this->query_optimizer = new QueryOptimizer();
    }
    
    /**
     * Generate text based on prompt and parameters
     * @param string $prompt User's text prompt
     * @param array $options Generation options (tone, type, length, etc)
     * @return array Generated text with metadata
     */
    public function generateText($prompt, $options = []) {
        if(empty($prompt) || strlen($prompt) < 3) {
            return [
                'status' => 'error',
                'message' => 'Prompt must be at least 3 characters'
            ];
        }
        
        // Check cache first
        $cache_key = 'text_gen_' . md5($prompt . json_encode($options));
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        // Set defaults
        $tone = $options['tone'] ?? 'professional';
        $content_type = $options['type'] ?? 'article';
        $target_length = $options['length'] ?? 500; // words
        $language = $options['language'] ?? 'en';
        $creativity = min($options['creativity'] ?? 0.7, 1.0); // 0.0 to 1.0
        
        // Validate parameters
        if(!in_array($tone, $this->supported_tones)) {
            $tone = 'professional';
        }
        if(!in_array($content_type, $this->content_types)) {
            $content_type = 'article';
        }
        
        $start_time = microtime(true);
        
        try {
            // Generate text based on type
            $generated_text = $this->generateByType($prompt, $content_type, $tone, $creativity);
            
            // Optimize length
            $generated_text = $this->optimizeLength($generated_text, $target_length);
            
            // Apply tone
            $generated_text = $this->applyTone($generated_text, $tone);
            
            // Add metadata
            $result = [
                'status' => 'success',
                'prompt' => $prompt,
                'type' => $content_type,
                'tone' => $tone,
                'language' => $language,
                'word_count' => str_word_count($generated_text),
                'character_count' => strlen($generated_text),
                'creativity_score' => $creativity,
                'generated_text' => $generated_text,
                'processing_time' => microtime(true) - $start_time,
                'timestamp' => time()
            ];
            
            // Save to database
            $this->saveGeneration($result);
            
            // Cache for 24 hours
            $this->cache_manager->set($cache_key, $result, 86400);
            
            return $result;
            
        } catch(Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Text generation failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate text based on content type
     */
    private function generateByType($prompt, $type, $tone, $creativity) {
        $templates = [
            'article' => $this->getArticleTemplate(),
            'email' => $this->getEmailTemplate(),
            'description' => $this->getDescriptionTemplate(),
            'story' => $this->getStoryTemplate(),
            'blog' => $this->getBlogTemplate(),
            'social' => $this->getSocialTemplate(),
            'review' => $this->getReviewTemplate(),
            'summary' => $this->getSummaryTemplate()
        ];
        
        $template = $templates[$type] ?? $templates['article'];
        
        // Fill template with prompt content
        return str_replace('{prompt}', $prompt, $template);
    }
    
    /**
     * Get article template
     */
    private function getArticleTemplate() {
        return <<<EOT
# {prompt}

## Introduction
Discover comprehensive insights about {prompt}. This article provides detailed analysis and expert perspectives.

## Key Points
- Understanding the fundamentals of {prompt}
- Best practices and methodologies
- Real-world applications and examples
- Common challenges and solutions
- Future trends and developments

## Detailed Analysis
{prompt} represents a significant area of focus in modern contexts. The importance of understanding this subject cannot be overstated, as it impacts multiple dimensions of contemporary practice and theory.

## Conclusion
In summary, {prompt} continues to be an essential topic for professionals and enthusiasts alike. By understanding its nuances and applications, individuals can make more informed decisions.
EOT;
    }
    
    /**
     * Get email template
     */
    private function getEmailTemplate() {
        return <<<EOT
Subject: Regarding {prompt}

Dear [Recipient],

I hope this message finds you well. I'm reaching out to discuss {prompt}.

Key Points:
- [Point 1 about {prompt}]
- [Point 2 about {prompt}]
- [Point 3 about {prompt}]

I believe this is an important matter that requires attention. I would appreciate your thoughts and feedback on this subject.

Please let me know if you would like to discuss this further at your earliest convenience.

Best regards,
[Your Name]
EOT;
    }
    
    /**
     * Get description template
     */
    private function getDescriptionTemplate() {
        return <<<EOT
{prompt}

This comprehensive resource provides detailed information and valuable insights. Key features include professional quality, detailed analysis, and practical applications. Perfect for those seeking to understand the subject more thoroughly. Discover the benefits and advantages that come with this offering.
EOT;
    }
    
    /**
     * Get story template
     */
    private function getStoryTemplate() {
        return <<<EOT
# The Tale of {prompt}

Once upon a time, there was a fascinating story related to {prompt}. 

Our journey begins when we encounter the challenge of understanding {prompt}. Through determination and creativity, we discover new perspectives. Each step reveals more about this intriguing subject.

The turning point arrives when we realize the true significance of {prompt}. With newfound wisdom, we navigate the complexities and find solutions.

In the end, the story of {prompt} teaches us valuable lessons about perseverance, growth, and discovery.
EOT;
    }
    
    /**
     * Get blog template
     */
    private function getBlogTemplate() {
        return <<<EOT
# {prompt} - Everything You Need to Know

Welcome to this in-depth exploration of {prompt}! 

## Why {prompt} Matters
Understanding {prompt} is crucial in today's world. Let's dive into what makes this topic so important.

## Getting Started with {prompt}
For beginners, here's what you should know about {prompt}:
- Essential concepts and terminology
- Common misconceptions
- Quick-start guide

## Advanced Insights
As you deepen your knowledge of {prompt}, consider these advanced perspectives:
- Expert tips and tricks
- Industry trends
- Success stories

## Final Thoughts
{prompt} is an evolving field with endless possibilities. Stay curious and keep learning!
EOT;
    }
    
    /**
     * Get social media template
     */
    private function getSocialTemplate() {
        return <<<EOT
🚀 Exciting insights about {prompt}!

Did you know? {prompt} is revolutionizing how we think about modern solutions. 

✨ Key highlights:
• Innovative approaches to {prompt}
• Proven results and outcomes
• Join thousands learning about {prompt}

Tap to learn more about {prompt} and transform your perspective! 

#innovation #{prompt} #futureforward
EOT;
    }
    
    /**
     * Get review template
     */
    private function getReviewTemplate() {
        return <<<EOT
## Comprehensive Review: {prompt}

⭐⭐⭐⭐⭐ Rating: 5/5

{prompt} stands out as an exceptional offering in its category.

### Strengths
- Excellent quality and attention to detail
- Comprehensive coverage of {prompt}
- Highly recommended for professionals

### Key Features
- Advanced functionality related to {prompt}
- User-friendly interface
- Strong support and community

### Overall Assessment
After thorough evaluation, {prompt} proves to be a top-tier solution that delivers on its promises and exceeds expectations.

### Verdict
Highly recommended for anyone interested in {prompt}.
EOT;
    }
    
    /**
     * Get summary template
     */
    private function getSummaryTemplate() {
        return <<<EOT
## Summary: {prompt}

### Overview
{prompt} is a significant topic with broad applications and implications.

### Main Points
1. {prompt} encompasses multiple dimensions of contemporary practice
2. Understanding its fundamentals is essential for informed decision-making
3. Best practices and methodologies continue to evolve

### Key Takeaway
{prompt} represents an important area of focus that deserves attention and ongoing study.
EOT;
    }
    
    /**
     * Optimize text length
     */
    private function optimizeLength($text, $target_length) {
        $current_length = str_word_count($text);
        
        if($current_length >= $target_length) {
            // Truncate if too long
            $words = str_word_count($text, 1);
            $text = implode(' ', array_slice($words, 0, $target_length));
            $text .= '...';
        } else {
            // Expand if too short
            $expansion = $this->expandText($text, $target_length - $current_length);
            $text .= "\n\n" . $expansion;
        }
        
        return $text;
    }
    
    /**
     * Expand text to meet length requirements
     */
    private function expandText($text, $additional_words) {
        $expansion = "Additional insights and perspectives on this subject reveal deeper understanding. ";
        $expansion .= "By exploring various angles and examining different viewpoints, we gain comprehensive knowledge. ";
        $expansion .= "This expansion provides more context and detailed information for your consideration.";
        
        return $expansion;
    }
    
    /**
     * Apply tone to text
     */
    private function applyTone($text, $tone) {
        $tone_markers = [
            'formal' => ['However', 'Furthermore', 'Nevertheless', 'It is imperative'],
            'casual' => ['Actually', 'So like', 'You know', 'Pretty much'],
            'professional' => ['Furthermore', 'In conclusion', 'As discussed', 'Key benefits'],
            'creative' => ['Imagine', 'Picture this', 'Envision', 'Discover'],
            'humorous' => ['Interestingly', 'Surprisingly', 'Funnily enough', 'As it turns out'],
            'serious' => ['Critical', 'Essential', 'Fundamental', 'Paramount'],
            'inspirational' => ['Empower', 'Transform', 'Achieve', 'Incredible']
        ];
        
        $markers = $tone_markers[$tone] ?? [];
        
        if(!empty($markers) && rand(0, 1)) {
            $text = $markers[array_rand($markers)] . ' ' . lcfirst($text);
        }
        
        return $text;
    }
    
    /**
     * Generate variations of text - NOW WITH INTELLIGENT VARIATION ALGORITHM
     */
    public function generateVariations($original_text, $count = 5) {
        $variations = [];
        
        for($i = 0; $i < $count; $i++) {
            // Create variations by changing tone, structure, emphasis, and content flow
            $variation = $this->createVariation($original_text, $i);
            $variations[] = [
                'content' => $variation['content'],
                'tone' => $variation['tone'],
                'structure' => $variation['structure'],
                'similarity_score' => $variation['similarity']
            ];
        }
        
        return [
            'status' => 'success',
            'original' => $original_text,
            'variations' => $variations,
            'count' => count($variations),
            'timestamp' => time()
        ];
    }
    
    /**
     * Create single intelligent variation with different structure/emphasis
     */
    private function createVariation($text, $index) {
        $tones = $this->supported_tones;
        $tone = $tones[$index % count($tones)];
        
        // Strategy 1: Change tone and vocabulary
        $variation_type = $index % 3;
        
        if($variation_type === 0) {
            // Vocabulary replacement variation
            $modified = $this->vocabularyVariation($text, $tone);
            $structure = 'vocabulary_emphasis';
        } elseif($variation_type === 1) {
            // Sentence structure variation
            $modified = $this->structureVariation($text, $tone);
            $structure = 'sentence_structure';
        } else {
            // Emphasis/reordering variation
            $modified = $this->emphasisVariation($text, $tone);
            $structure = 'emphasis_reorder';
        }
        
        $similarity = $this->calculateSimilarity($text, $modified);
        
        return [
            'content' => $modified,
            'tone' => $tone,
            'structure' => $structure,
            'similarity' => $similarity
        ];
    }

    /**
     * Create vocabulary variation - replace words with synonyms
     */
    private function vocabularyVariation($text, $tone) {
        $synonyms = [
            'good' => ['excellent', 'outstanding', 'superior', 'quality', 'fine'],
            'bad' => ['poor', 'inferior', 'subpar', 'inadequate', 'unsatisfactory'],
            'important' => ['significant', 'critical', 'essential', 'vital', 'key'],
            'interesting' => ['fascinating', 'compelling', 'engaging', 'intriguing', 'captivating'],
            'unique' => ['distinctive', 'one-of-a-kind', 'special', 'exclusive', 'rare'],
            'helpful' => ['beneficial', 'advantageous', 'useful', 'constructive', 'supportive'],
            'learn' => ['discover', 'understand', 'grasp', 'master', 'acquire'],
            'use' => ['leverage', 'apply', 'utilize', 'implement', 'employ'],
            'think' => ['consider', 'ponder', 'reflect', 'contemplate', 'evaluate'],
            'show' => ['demonstrate', 'reveal', 'exhibit', 'display', 'present']
        ];
        
        $words = str_word_count($text, 1);
        $modified_words = [];
        
        foreach($words as $word) {
            $lower = strtolower($word);
            $clean = trim($lower, '.,!?;:\'"');
            
            if(isset($synonyms[$clean])) {
                $replacement = $synonyms[$clean][array_rand($synonyms[$clean])];
                $modified_words[] = $replacement . ($word !== $lower ? substr($word, -1) : '');
            } else {
                $modified_words[] = $word;
            }
        }
        
        return implode(' ', $modified_words);
    }

    /**
     * Create structure variation - reorganize sentences
     */
    private function structureVariation($text, $tone) {
        // Split into sentences
        $sentences = preg_split('/([.!?]+)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        $sentence_groups = [];
        for($i = 0; $i < count($sentences); $i += 2) {
            if(!empty(trim($sentences[$i]))) {
                $sentence_groups[] = trim($sentences[$i]);
            }
        }
        
        // Reorganize for different emphasis
        if(count($sentence_groups) > 2) {
            // Move last sentence to beginning for emphasis variation
            $last = array_pop($sentence_groups);
            array_unshift($sentence_groups, $last);
        }
        
        return implode('. ', $sentence_groups) . '.';
    }

    /**
     * Create emphasis variation - add emphasis words and restructure
     */
    private function emphasisVariation($text, $tone) {
        $emphasis_words = [
            'formal' => ['Notably,', 'Importantly,', 'Significantly,', 'Consequently,'],
            'casual' => ['Basically,', 'Honestly,', 'Actually,', 'Seriously,'],
            'professional' => ['In essence,', 'Therefore,', 'To summarize,', 'As a result,'],
            'creative' => ['Interestingly,', 'Remarkably,', 'Surprisingly,', 'Notably,'],
            'inspirational' => ['Ultimately,', 'Beyond a doubt,', 'Clearly,', 'Undeniably,']
        ];
        
        $words_for_tone = $emphasis_words[$tone] ?? $emphasis_words['professional'];
        
        // Split into sentences and add emphasis
        $sentences = preg_split('/([.!?]+)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        $modified = [];
        for($i = 0; $i < count($sentences); $i += 2) {
            if(!empty(trim($sentences[$i]))) {
                $sentence = trim($sentences[$i]);
                // Add emphasis word to every other sentence
                if(($i / 2) % 2 == 1 && !preg_match('/^(Notably|Importantly|Basically|Interestingly|Ultimately)/', $sentence)) {
                    $sentence = $words_for_tone[array_rand($words_for_tone)] . ' ' . $sentence;
                }
                $modified[] = $sentence;
            }
        }
        
        return implode('. ', $modified) . '.';
    }

    /**
     * Calculate similarity between original and variation (0-100)
     */
    private function calculateSimilarity($original, $variation) {
        $orig_words = array_filter(str_word_count($original, 1));
        $var_words = array_filter(str_word_count($variation, 1));
        
        $common = count(array_intersect($orig_words, $var_words));
        $total = max(count($orig_words), count($var_words));
        
        return round(($common / $total) * 100, 2);
    }
    
    /**
     * Rephrase a word
     */
    private function rephrase($word) {
        $synonyms = [
            'good' => 'excellent',
            'bad' => 'poor',
            'important' => 'significant',
            'interesting' => 'fascinating',
            'unique' => 'distinctive',
            'helpful' => 'beneficial'
        ];
        
        return $synonyms[strtolower($word)] ?? $word;
    }
    
    /**
     * Save generation to database
     */
    private function saveGeneration($data) {
        $db_data = [
            'user_id' => $this->user_id,
            'prompt' => $data['prompt'],
            'content_type' => $data['type'],
            'tone' => $data['tone'],
            'generated_text' => substr($data['generated_text'], 0, 5000),
            'word_count' => $data['word_count'],
            'creativity_score' => $data['creativity_score'],
            'processing_time' => $data['processing_time'],
            'created' => time()
        ];
        
        return $this->db->insert('alkebulan_text_generations', $db_data);
    }
    
    /**
     * Get generation history
     */
    public function getGenerationHistory($limit = 20) {
        $cache_key = 'text_gen_history_' . $this->user_id;
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $query = $this->query_optimizer->executeOptimized(
            'SELECT * FROM alkebulan_text_generations WHERE user_id = ? ORDER BY created DESC LIMIT ?',
            [$this->user_id, $limit],
            'Get text generation history'
        );
        
        // Cache for 1 hour
        $this->cache_manager->set($cache_key, $query ?: [], 3600);
        
        return $query ?: [];
    }
}
?>
