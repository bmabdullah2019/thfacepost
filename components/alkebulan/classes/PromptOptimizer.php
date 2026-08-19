<?php
/**
 * PromptOptimizer Class - Intelligent Prompt Enhancement Engine v3.0
 * Optimizes and enhances AI prompts for better results
 * Features: Prompt improvement, expansion, refinement, variation generation
 */

class PromptOptimizer {
    private $db;
    private $user_id;
    private $cache_manager;
    
    private $optimization_techniques = [
        'specificity' => 'Add specific details and constraints',
        'clarity' => 'Improve clarity and remove ambiguity',
        'structure' => 'Organize prompt with better structure',
        'context' => 'Add relevant background context',
        'examples' => 'Include examples in prompt',
        'constraints' => 'Add constraints and guidelines'
    ];
    
    public function __construct($user_id = null) {
        $this->db = ossn_get_database();
        $this->user_id = $user_id;
        $this->cache_manager = new CacheManager();
    }
    
    /**
     * Optimize a prompt for better AI results
     * @param string $prompt Original prompt
     * @param array $options Optimization options
     * @return array Optimized prompt with analysis
     */
    public function optimizePrompt($prompt, $options = []) {
        if(empty($prompt) || strlen($prompt) < 5) {
            return [
                'status' => 'error',
                'message' => 'Prompt must be at least 5 characters'
            ];
        }
        
        // Check cache
        $cache_key = 'prompt_opt_' . md5($prompt . json_encode($options));
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $optimization_level = $options['level'] ?? 'standard'; // basic, standard, advanced
        $techniques = $options['techniques'] ?? array_keys($this->optimization_techniques);
        
        $start_time = microtime(true);
        
        try {
            // Analyze original prompt
            $analysis = $this->analyzePrompt($prompt);
            
            // Optimize prompt
            $optimized = $this->applyOptimizations($prompt, $techniques, $optimization_level);
            
            // Generate variations
            $variations = $this->generateVariations($optimized, 3);
            
            // Score improvement
            $improvement_score = $this->calculateImprovement($analysis, $optimized);
            
            $result = [
                'status' => 'success',
                'original_prompt' => $prompt,
                'optimized_prompt' => $optimized,
                'variations' => $variations,
                'analysis' => $analysis,
                'improvement_score' => $improvement_score,
                'techniques_applied' => $techniques,
                'optimization_level' => $optimization_level,
                'processing_time' => microtime(true) - $start_time,
                'timestamp' => time()
            ];
            
            // Save to database
            $this->saveOptimization($result);
            
            // Cache for 24 hours
            $this->cache_manager->set($cache_key, $result, 86400);
            
            return $result;
            
        } catch(Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Prompt optimization failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Analyze prompt quality
     */
    private function analyzePrompt($prompt) {
        $analysis = [
            'length' => strlen($prompt),
            'word_count' => str_word_count($prompt),
            'clarity_score' => $this->calculateClarity($prompt),
            'specificity_score' => $this->calculateSpecificity($prompt),
            'structure_score' => $this->calculateStructure($prompt),
            'completeness_score' => $this->calculateCompleteness($prompt),
            'overall_quality' => 0
        ];
        
        // Calculate overall quality (0-100)
        $analysis['overall_quality'] = round(
            ($analysis['clarity_score'] + 
             $analysis['specificity_score'] + 
             $analysis['structure_score'] + 
             $analysis['completeness_score']) / 4
        );
        
        // Identify issues
        $analysis['issues'] = $this->identifyIssues($prompt, $analysis);
        $analysis['recommendations'] = $this->generateRecommendations($analysis);
        
        return $analysis;
    }
    
    /**
     * Calculate clarity score
     */
    private function calculateClarity($prompt) {
        $clarity = 50; // Base score
        
        // Check for clear language
        $complex_words = ['utilize', 'implement', 'facilitate', 'optimize'];
        $complex_count = 0;
        foreach($complex_words as $word) {
            $complex_count += substr_count(strtolower($prompt), $word);
        }
        
        // Check for questions
        $question_marks = substr_count($prompt, '?');
        
        // Adjust score
        $clarity -= ($complex_count * 5);
        $clarity += ($question_marks * 5);
        
        return max(0, min(100, $clarity));
    }
    
    /**
     * Calculate specificity score
     */
    private function calculateSpecificity($prompt) {
        $specificity = 50; // Base score
        
        // Check for numbers/measurements
        $numbers = preg_match_all('/\b\d+\b/', $prompt);
        $specificity += ($numbers * 10);
        
        // Check for specific keywords
        $specific_words = ['specific', 'exact', 'detailed', 'particular', 'precisely'];
        $specific_count = 0;
        foreach($specific_words as $word) {
            $specific_count += substr_count(strtolower($prompt), $word);
        }
        
        $specificity += ($specific_count * 8);
        
        // Check for vague words
        $vague_words = ['something', 'some', 'anything', 'maybe', 'probably', 'might'];
        $vague_count = 0;
        foreach($vague_words as $word) {
            $vague_count += substr_count(strtolower($prompt), $word);
        }
        
        $specificity -= ($vague_count * 10);
        
        return max(0, min(100, $specificity));
    }
    
    /**
     * Calculate structure score
     */
    private function calculateStructure($prompt) {
        $structure = 50; // Base score
        
        // Check for organization
        $has_sections = preg_match_all('/[0-9]+\.|[-•*]/', $prompt);
        $structure += ($has_sections * 10);
        
        // Check for paragraphs
        $paragraphs = count(explode("\n", $prompt));
        $structure += min($paragraphs * 5, 30);
        
        // Check for line breaks
        $line_breaks = substr_count($prompt, "\n");
        $structure += min($line_breaks * 3, 20);
        
        return max(0, min(100, $structure));
    }
    
    /**
     * Calculate completeness score
     */
    private function calculateCompleteness($prompt) {
        $completeness = 50; // Base score
        
        // Check for context
        $context_words = ['background', 'context', 'given', 'considering', 'for', 'because'];
        $context_count = 0;
        foreach($context_words as $word) {
            $context_count += substr_count(strtolower($prompt), $word);
        }
        
        $completeness += ($context_count * 5);
        
        // Check for desired output specification
        $output_words = ['output', 'result', 'format', 'generate', 'create', 'produce'];
        $output_count = 0;
        foreach($output_words as $word) {
            $output_count += substr_count(strtolower($prompt), $word);
        }
        
        $completeness += ($output_count * 5);
        
        return max(0, min(100, $completeness));
    }
    
    /**
     * Identify issues in prompt
     */
    private function identifyIssues($prompt, $analysis) {
        $issues = [];
        
        if($analysis['clarity_score'] < 60) {
            $issues[] = 'Prompt may be unclear or use complex language';
        }
        
        if($analysis['specificity_score'] < 60) {
            $issues[] = 'Prompt lacks specific details and constraints';
        }
        
        if($analysis['structure_score'] < 60) {
            $issues[] = 'Prompt structure could be improved';
        }
        
        if(strlen($prompt) < 50) {
            $issues[] = 'Prompt is quite short, may lack detail';
        }
        
        if(substr_count($prompt, '?') > 2) {
            $issues[] = 'Multiple questions may confuse the AI model';
        }
        
        return $issues;
    }
    
    /**
     * Generate recommendations
     */
    private function generateRecommendations($analysis) {
        $recommendations = [];
        
        if($analysis['clarity_score'] < 60) {
            $recommendations[] = 'Use simple, clear language and avoid jargon';
        }
        
        if($analysis['specificity_score'] < 60) {
            $recommendations[] = 'Add specific numbers, examples, or constraints';
        }
        
        if($analysis['structure_score'] < 60) {
            $recommendations[] = 'Organize prompt with numbered steps or bullet points';
        }
        
        if($analysis['completeness_score'] < 60) {
            $recommendations[] = 'Provide more context and specify desired output format';
        }
        
        return $recommendations;
    }
    
    /**
     * Apply optimizations
     */
    private function applyOptimizations($prompt, $techniques, $level) {
        $optimized = $prompt;
        
        foreach($techniques as $technique) {
            switch($technique) {
                case 'specificity':
                    $optimized = $this->addSpecificity($optimized);
                    break;
                case 'clarity':
                    $optimized = $this->improveClarity($optimized);
                    break;
                case 'structure':
                    $optimized = $this->improveStructure($optimized);
                    break;
                case 'context':
                    $optimized = $this->addContext($optimized);
                    break;
                case 'examples':
                    $optimized = $this->addExamples($optimized);
                    break;
                case 'constraints':
                    $optimized = $this->addConstraints($optimized);
                    break;
            }
        }
        
        // Add thinking instructions for advanced level
        if($level === 'advanced') {
            $optimized = "Think step-by-step about the following:\n\n" . $optimized;
        }
        
        return $optimized;
    }
    
    /**
     * Add specificity
     */
    private function addSpecificity($prompt) {
        return $prompt . "\n\nSpecifically, please focus on concrete details and measurable outcomes.";
    }
    
    /**
     * Improve clarity
     */
    private function improveClarity($prompt) {
        $replacements = [
            'utilize' => 'use',
            'implement' => 'add',
            'facilitate' => 'help',
            'optimize' => 'improve'
        ];
        
        $improved = $prompt;
        foreach($replacements as $complex => $simple) {
            $improved = str_ireplace($complex, $simple, $improved);
        }
        
        return $improved;
    }
    
    /**
     * Improve structure
     */
    private function improveStructure($prompt) {
        return $prompt . "\n\nPlease organize your response clearly with sections or steps.";
    }
    
    /**
     * Add context
     */
    private function addContext($prompt) {
        return "Context: This is part of a professional workflow.\n\n" . $prompt;
    }
    
    /**
     * Add examples
     */
    private function addExamples($prompt) {
        return $prompt . "\n\nProvide examples to illustrate key points.";
    }
    
    /**
     * Add constraints
     */
    private function addConstraints($prompt) {
        return $prompt . "\n\nConstraints: Keep response concise and practical. Focus on actionable insights.";
    }
    
    /**
     * Generate variations
     */
    private function generateVariations($prompt, $count = 3) {
        $variations = [];
        
        $angles = [
            'From a business perspective: ' . $prompt,
            'For a technical implementation: ' . $prompt,
            'In simple terms: ' . $prompt
        ];
        
        for($i = 0; $i < $count && $i < count($angles); $i++) {
            $variations[] = $angles[$i];
        }
        
        return $variations;
    }
    
    /**
     * Calculate improvement score
     */
    private function calculateImprovement($original_analysis, $optimized) {
        // Calculate optimized analysis
        $optimized_analysis = $this->analyzePrompt($optimized);
        
        $improvement = round(
            (($optimized_analysis['overall_quality'] - $original_analysis['overall_quality']) / 100) * 100
        );
        
        return max(0, min(50, $improvement));
    }
    
    /**
     * Save optimization to database
     */
    private function saveOptimization($data) {
        $db_data = [
            'user_id' => $this->user_id,
            'original_prompt' => substr($data['original_prompt'], 0, 1000),
            'optimized_prompt' => substr($data['optimized_prompt'], 0, 1000),
            'improvement_score' => $data['improvement_score'],
            'optimization_level' => $data['optimization_level'],
            'techniques_applied' => json_encode($data['techniques_applied']),
            'processing_time' => $data['processing_time'],
            'created' => time()
        ];
        
        return $this->db->insert('alkebulan_prompt_optimizations', $db_data);
    }
    
    /**
     * Get optimization history
     */
    public function getOptimizationHistory($limit = 20) {
        $cache_key = 'prompt_opt_history_' . $this->user_id;
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $query = $this->db->select('alkebulan_prompt_optimizations')
            ->where('user_id', $this->user_id)
            ->order_by('created', 'DESC')
            ->limit($limit)
            ->execute()
            ->fetch();
        
        $this->cache_manager->set($cache_key, $query ?: [], 3600);
        return $query ?: [];
    }
}
?>
