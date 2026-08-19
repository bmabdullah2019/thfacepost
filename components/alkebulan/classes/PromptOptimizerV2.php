<?php
/**
 * PromptOptimizerV2 - Enhanced Prompt Optimization
 * Implements 6 optimization techniques for AI prompt improvement
 */

class PromptOptimizerV2 {
    private $user_id;
    private $db;
    private $cache_manager;
    private $query_optimizer;
    
    private $optimization_techniques = [
        'specificity' => 'Add specific details and constraints to improve clarity',
        'clarity' => 'Remove ambiguity and make instructions explicit',
        'structure' => 'Organize prompt with better structure and formatting',
        'context' => 'Add relevant background context for better understanding',
        'examples' => 'Include examples to guide the AI model',
        'constraints' => 'Define clear boundaries and limitations'
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
     * Analyze and optimize a prompt
     */
    public function optimizePrompt($prompt, $techniques = ['specificity', 'clarity', 'structure']) {
        if(empty($prompt)) {
            return ['status' => 'error', 'message' => 'Prompt cannot be empty'];
        }
        
        // Validate techniques
        $valid_techniques = [];
        foreach($techniques as $technique) {
            if(isset($this->optimization_techniques[$technique])) {
                $valid_techniques[] = $technique;
            }
        }
        
        if(empty($valid_techniques)) {
            $valid_techniques = array_keys($this->optimization_techniques);
        }
        
        // Check cache
        $cache_key = "prompt_opt_" . implode('_', $valid_techniques) . "_" . md5($prompt);
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $start_time = microtime(true);
        
        // Analyze original prompt
        $analysis = $this->analyzePrompt($prompt);
        
        // Apply optimizations
        $optimized_prompt = $prompt;
        $optimizations_applied = [];
        
        foreach($valid_techniques as $technique) {
            switch($technique) {
                case 'specificity':
                    $optimized_prompt = $this->addSpecificity($optimized_prompt);
                    $optimizations_applied[] = 'Added specific details and constraints';
                    break;
                case 'clarity':
                    $optimized_prompt = $this->improveClarity($optimized_prompt);
                    $optimizations_applied[] = 'Improved clarity and removed ambiguity';
                    break;
                case 'structure':
                    $optimized_prompt = $this->addStructure($optimized_prompt);
                    $optimizations_applied[] = 'Added structured formatting';
                    break;
                case 'context':
                    $optimized_prompt = $this->addContext($optimized_prompt);
                    $optimizations_applied[] = 'Added relevant context';
                    break;
                case 'examples':
                    $optimized_prompt = $this->addExamples($optimized_prompt);
                    $optimizations_applied[] = 'Included examples';
                    break;
                case 'constraints':
                    $optimized_prompt = $this->addConstraints($optimized_prompt);
                    $optimizations_applied[] = 'Defined constraints';
                    break;
            }
        }
        
        $processing_time = microtime(true) - $start_time;
        
        // Calculate quality metrics
        $original_quality = $analysis['quality_score'];
        $optimized_analysis = $this->analyzePrompt($optimized_prompt);
        $optimized_quality = $optimized_analysis['quality_score'];
        $improvement = $optimized_quality - $original_quality;
        
        $result = [
            'status' => 'success',
            'original_prompt' => $prompt,
            'optimized_prompt' => $optimized_prompt,
            'techniques_applied' => $valid_techniques,
            'optimization_details' => $optimizations_applied,
            'original_analysis' => $analysis,
            'optimized_analysis' => $optimized_analysis,
            'original_quality_score' => $original_quality,
            'optimized_quality_score' => $optimized_quality,
            'improvement_percentage' => round(($improvement / $original_quality) * 100, 2),
            'word_count_original' => str_word_count($prompt),
            'word_count_optimized' => str_word_count($optimized_prompt),
            'clarity_improvement' => $optimized_analysis['clarity_score'] - $analysis['clarity_score'],
            'processing_time' => round($processing_time, 4),
            'timestamp' => time()
        ];
        
        // Save to database
        $this->saveOptimization($result);
        
        // Cache for 2 hours
        $this->cache_manager->set($cache_key, $result, 7200);
        
        return $result;
    }
    
    /**
     * Analyze prompt quality
     */
    private function analyzePrompt($prompt) {
        $analysis = [
            'length' => strlen($prompt),
            'word_count' => str_word_count($prompt),
            'sentence_count' => count(preg_split('/[.!?]+/', $prompt)) - 1,
            'clarity_score' => 0,
            'specificity_score' => 0,
            'completeness_score' => 0,
            'quality_score' => 0,
            'issues' => [],
            'strengths' => []
        ];
        
        // Check clarity
        if(str_word_count($prompt) > 5 && str_word_count($prompt) < 200) {
            $analysis['clarity_score'] += 20;
        }
        if(strlen($prompt) > 30) {
            $analysis['clarity_score'] += 15;
        }
        
        // Check specificity
        if(preg_match('/\b(specifically|example|such as|in particular)\b/i', $prompt)) {
            $analysis['specificity_score'] += 20;
        }
        if(preg_match('/\d+/', $prompt)) {
            $analysis['specificity_score'] += 15;
        }
        
        // Check completeness
        if(preg_match('/\b(what|how|why|who|when|where)\b/i', $prompt)) {
            $analysis['completeness_score'] += 20;
        }
        if(preg_match('/\b(please|thank|please|kind)\b/i', $prompt)) {
            $analysis['completeness_score'] += 10;
        }
        
        // Identify issues
        if(strlen($prompt) < 20) {
            $analysis['issues'][] = 'Prompt is too short - add more detail';
        }
        if(strlen($prompt) > 500) {
            $analysis['issues'][] = 'Prompt is too long - consider condensing';
        }
        if(!preg_match('/[.!?]$/', trim($prompt))) {
            $analysis['issues'][] = 'Prompt ends abruptly - add proper punctuation';
        }
        if(preg_match('/\b(vague|unclear|maybe|sort of|kind of)\b/i', $prompt)) {
            $analysis['issues'][] = 'Prompt contains vague language - be more specific';
        }
        
        // Identify strengths
        if($analysis['clarity_score'] > 30) {
            $analysis['strengths'][] = 'Clear and well-written';
        }
        if($analysis['specificity_score'] > 20) {
            $analysis['strengths'][] = 'Specific with good detail';
        }
        if(preg_match('/\b(format|structure|organize|output)\b/i', $prompt)) {
            $analysis['strengths'][] = 'Clear output expectations';
        }
        
        // Calculate overall quality
        $analysis['quality_score'] = round(
            ($analysis['clarity_score'] * 0.4 + 
             $analysis['specificity_score'] * 0.3 + 
             $analysis['completeness_score'] * 0.3) / 3.5
        , 2);
        
        return $analysis;
    }
    
    /**
     * Technique 1: Add Specificity
     */
    private function addSpecificity($prompt) {
        $improved = $prompt;
        
        // Add specific indicators
        if(!preg_match('/\b(specifically|particularly|exactly|precisely)\b/i', $improved)) {
            $improved .= " Please be specific and precise in your response.";
        }
        
        // Add constraints if missing
        if(!preg_match('/\b(word|character|length|size|format)\b/i', $improved)) {
            $improved .= " Focus on the specific details requested.";
        }
        
        // Add scope if missing
        if(!preg_match('/\b(only|just|limited to|specific)\b/i', $improved)) {
            $improved .= " Address only the specific aspects mentioned.";
        }
        
        return $improved;
    }
    
    /**
     * Technique 2: Improve Clarity
     */
    private function improveClarity($prompt) {
        $improved = $prompt;
        
        // Replace vague language
        $replacements = [
            '/\bkind of\b/i' => 'somewhat',
            '/\bsort of\b/i' => 'somewhat',
            '/\bmaybe\b/i' => 'possibly',
            '/\bprobably\b/i' => 'likely',
            '/\bstuff\b/i' => 'items',
            '/\bthing\b/i' => 'item',
            '/\byou know\b/i' => '',
        ];
        
        foreach($replacements as $pattern => $replacement) {
            $improved = preg_replace($pattern, $replacement, $improved);
        }
        
        // Ensure clear structure
        if(strlen($improved) > 0 && !preg_match('/[.!?]$/', trim($improved))) {
            $improved .= '.';
        }
        
        return $improved;
    }
    
    /**
     * Technique 3: Add Structure
     */
    private function addStructure($prompt) {
        $improved = $prompt;
        
        // Add numbered list structure if multiple tasks
        if(preg_match('/\b(and|also|additionally|plus)\b/i', $improved) && !preg_match('/^\d+\./', $improved)) {
            // Convert to structured format
            $sentences = preg_split('/\s+(?:and|also|additionally|plus)\s+/i', $improved);
            
            if(count($sentences) > 1) {
                $improved = "Please complete the following tasks:\n";
                foreach($sentences as $index => $sentence) {
                    $improved .= ($index + 1) . ". " . trim($sentence) . "\n";
                }
            }
        }
        
        // Add role/context section if missing
        if(!preg_match('/\b(as a|you are|acting as|role|context)\b/i', $improved)) {
            $improved = "Context: [General Request]\n" . $improved;
        }
        
        // Add output format section if missing
        if(!preg_match('/\b(format|output|return|provide|give|show)\b/i', $improved)) {
            $improved .= "\n\nPlease provide your response in a clear and organized format.";
        }
        
        return $improved;
    }
    
    /**
     * Technique 4: Add Context
     */
    private function addContext($prompt) {
        $improved = $prompt;
        
        // Add background information
        $context_additions = [
            "This request is important for [purpose/goal].",
            "The intended use case is [specific application].",
            "The target audience is [specific audience].",
            "Background: [relevant context about the topic]."
        ];
        
        // Add one relevant context addition
        if(!preg_match('/\b(background|context|purpose|because|reason)\b/i', $improved)) {
            $improved .= "\n\nContext: This is for understanding better the subject matter.";
        }
        
        return $improved;
    }
    
    /**
     * Technique 5: Add Examples
     */
    private function addExamples($prompt) {
        $improved = $prompt;
        
        // Add example section if missing
        if(!preg_match('/\b(example|for instance|such as|like|e\.g|like)\b/i', $improved)) {
            $improved .= "\n\nExample output format or style: [Provide a clear example of expected output.]";
        }
        
        // Add scenario example
        if(!preg_match('/\b(scenario|situation|case|imagine|suppose)\b/i', $improved)) {
            $improved .= "\n\nScenario: Consider a practical example to better understand the request.";
        }
        
        return $improved;
    }
    
    /**
     * Technique 6: Add Constraints
     */
    private function addConstraints($prompt) {
        $improved = $prompt;
        
        // Add length constraint if missing
        if(!preg_match('/\b(word|length|size|limit|maximum|minimum)\b/i', $improved)) {
            $improved .= " Keep your response concise and focused.";
        }
        
        // Add scope constraint if missing
        if(!preg_match('/\b(only|just|exclude|avoid|not|don\'t)\b/i', $improved)) {
            $improved .= " Focus on the main points only.";
        }
        
        // Add style constraint if missing
        if(!preg_match('/\b(tone|style|formal|casual|professional|technical)\b/i', $improved)) {
            $improved .= " Use a clear and professional tone.";
        }
        
        return $improved;
    }
    
    /**
     * Get optimization suggestions for a prompt
     */
    public function getSuggestions($prompt) {
        $analysis = $this->analyzePrompt($prompt);
        
        $suggestions = [];
        
        // Based on scores, recommend optimizations
        if($analysis['clarity_score'] < 25) {
            $suggestions[] = [
                'priority' => 'high',
                'technique' => 'clarity',
                'suggestion' => 'Improve clarity by removing vague language and being more explicit'
            ];
        }
        
        if($analysis['specificity_score'] < 20) {
            $suggestions[] = [
                'priority' => 'high',
                'technique' => 'specificity',
                'suggestion' => 'Add more specific details, numbers, and concrete examples'
            ];
        }
        
        if($analysis['completeness_score'] < 20) {
            $suggestions[] = [
                'priority' => 'medium',
                'technique' => 'context',
                'suggestion' => 'Add more background context and explain the purpose'
            ];
        }
        
        if(count($analysis['issues']) > 0) {
            $suggestions[] = [
                'priority' => 'medium',
                'technique' => 'structure',
                'suggestion' => 'Improve structure: ' . implode(', ', $analysis['issues'])
            ];
        }
        
        return [
            'status' => 'success',
            'prompt' => $prompt,
            'analysis' => $analysis,
            'suggestions' => $suggestions,
            'recommended_techniques' => $this->getRecommendedTechniques($analysis)
        ];
    }
    
    /**
     * Get recommended optimization techniques based on analysis
     */
    private function getRecommendedTechniques($analysis) {
        $recommended = [];
        
        if($analysis['clarity_score'] < 30) {
            $recommended[] = 'clarity';
        }
        if($analysis['specificity_score'] < 25) {
            $recommended[] = 'specificity';
        }
        if($analysis['completeness_score'] < 25) {
            $recommended[] = 'context';
        }
        if(count($analysis['issues']) > 0) {
            $recommended[] = 'structure';
        }
        if(!preg_match('/\b(example|for instance)\b/i', $analysis)) {
            $recommended[] = 'examples';
        }
        
        $recommended[] = 'constraints'; // Always useful
        
        return array_unique($recommended);
    }
    
    /**
     * Compare two prompts
     */
    public function comparePrompts($original, $optimized) {
        $original_analysis = $this->analyzePrompt($original);
        $optimized_analysis = $this->analyzePrompt($optimized);
        
        return [
            'status' => 'success',
            'original' => [
                'prompt' => $original,
                'analysis' => $original_analysis
            ],
            'optimized' => [
                'prompt' => $optimized,
                'analysis' => $optimized_analysis
            ],
            'improvements' => [
                'clarity_improvement' => $optimized_analysis['clarity_score'] - $original_analysis['clarity_score'],
                'specificity_improvement' => $optimized_analysis['specificity_score'] - $original_analysis['specificity_score'],
                'completeness_improvement' => $optimized_analysis['completeness_score'] - $original_analysis['completeness_score'],
                'overall_quality_improvement' => $optimized_analysis['quality_score'] - $original_analysis['quality_score']
            ]
        ];
    }
    
    /**
     * Save optimization to database
     */
    private function saveOptimization($result) {
        if(!$this->db || !$this->user_id) {
            return false;
        }
        
        $db_data = [
            'user_id' => $this->user_id,
            'original_prompt' => substr($result['original_prompt'], 0, 2000),
            'optimized_prompt' => substr($result['optimized_prompt'], 0, 2000),
            'techniques_applied' => implode(',', $result['techniques_applied']),
            'original_quality_score' => $result['original_quality_score'],
            'optimized_quality_score' => $result['optimized_quality_score'],
            'improvement_percentage' => $result['improvement_percentage'],
            'original_word_count' => $result['word_count_original'],
            'optimized_word_count' => $result['word_count_optimized'],
            'processing_time' => $result['processing_time'],
            'created' => time()
        ];
        
        return $this->db->insert('alkebulan_prompt_optimizations', $db_data);
    }
    
    /**
     * Get optimization history
     */
    public function getHistory($limit = 20) {
        if(!$this->user_id) {
            return [];
        }
        
        $cache_key = 'prompt_opt_history_' . $this->user_id;
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $results = $this->query_optimizer->executeOptimized(
            'SELECT * FROM alkebulan_prompt_optimizations WHERE user_id = ? ORDER BY created DESC LIMIT ?',
            [$this->user_id, $limit],
            'Get prompt optimization history'
        );
        
        // Cache for 1 hour
        $this->cache_manager->set($cache_key, $results ?: [], 3600);
        
        return $results ?: [];
    }
}
?>
