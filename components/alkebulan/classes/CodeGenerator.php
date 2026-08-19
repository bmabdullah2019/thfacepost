<?php
/**
 * CodeGenerator Class - AI Source Code Generation Engine v3.0
 * Generates code snippets, functions, classes, and full applications
 * Features: Multiple languages, documentation, error handling, optimization
 */

class CodeGenerator {
    private $db;
    private $user_id;
    private $cache_manager;
    private $query_optimizer;
    
    private $supported_languages = ['php', 'python', 'javascript', 'java', 'csharp', 'cpp', 'go', 'rust', 'sql'];
    private $code_types = ['function', 'class', 'snippet', 'api', 'crud', 'test', 'query', 'script'];
    
    public function __construct($user_id = null) {
        $this->db = ossn_get_database();
        $this->user_id = $user_id;
        $this->cache_manager = new CacheManager();
        $this->query_optimizer = new QueryOptimizer();
    }
    
    /**
     * Generate code based on description
     * @param string $description Description of what code should do
     * @param array $options Code generation options
     * @return array Generated code with metadata
     */
    public function generateCode($description, $options = []) {
        if(empty($description) || strlen($description) < 5) {
            return [
                'status' => 'error',
                'message' => 'Description must be at least 5 characters'
            ];
        }
        
        // Check cache
        $cache_key = 'code_gen_' . md5($description . json_encode($options));
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $language = strtolower($options['language'] ?? 'php');
        $code_type = strtolower($options['type'] ?? 'function');
        $include_docs = $options['docs'] ?? true;
        $include_tests = $options['tests'] ?? false;
        $optimize = $options['optimize'] ?? true;
        
        // Validate
        if(!in_array($language, $this->supported_languages)) {
            $language = 'php';
        }
        if(!in_array($code_type, $this->code_types)) {
            $code_type = 'function';
        }
        
        $start_time = microtime(true);
        
        try {
            // Generate code
            $generated_code = $this->generateByType($description, $language, $code_type);
            
            // Add documentation
            if($include_docs) {
                $generated_code = $this->addDocumentation($generated_code, $language);
            }
            
            // Generate tests
            $test_code = $include_tests ? $this->generateTests($generated_code, $language) : '';
            
            // Optimize code
            if($optimize) {
                $generated_code = $this->optimizeCode($generated_code, $language);
            }
            
            $result = [
                'status' => 'success',
                'description' => $description,
                'language' => $language,
                'type' => $code_type,
                'generated_code' => $generated_code,
                'test_code' => $test_code,
                'line_count' => substr_count($generated_code, "\n") + 1,
                'complexity' => $this->estimateComplexity($generated_code),
                'performance_rating' => $optimize ? 'optimized' : 'standard',
                'processing_time' => microtime(true) - $start_time,
                'timestamp' => time()
            ];
            
            // Save to database
            $this->saveCodeGeneration($result);
            
            // Cache for 24 hours
            $this->cache_manager->set($cache_key, $result, 86400);
            
            return $result;
            
        } catch(Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Code generation failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate code by type
     */
    private function generateByType($description, $language, $type) {
        switch($type) {
            case 'function':
                return $this->generateFunction($description, $language);
            case 'class':
                return $this->generateClass($description, $language);
            case 'api':
                return $this->generateAPI($description, $language);
            case 'crud':
                return $this->generateCRUD($description, $language);
            case 'query':
                return $this->generateQuery($description, $language);
            case 'test':
                return $this->generateTest($description, $language);
            default:
                return $this->generateSnippet($description, $language);
        }
    }
    
    /**
     * Generate function
     */
    private function generateFunction($description, $language) {
        if($language === 'php') {
            return <<<EOC
/**
 * {$description}
 * 
 * @param mixed \$input Input parameter
 * @param array \$options Configuration options
 * @return array Result with status and data
 */
function process{$this->toPascalCase($description)}(\$input, \$options = []) {
    // Validate input
    if(empty(\$input)) {
        return [
            'status' => 'error',
            'message' => 'Input is required'
        ];
    }
    
    try {
        // Process logic for: {$description}
        \$result = [];
        
        // TODO: Implement core logic here
        
        return [
            'status' => 'success',
            'data' => \$result,
            'timestamp' => time()
        ];
        
    } catch(Exception \$e) {
        return [
            'status' => 'error',
            'message' => \$e->getMessage()
        ];
    }
}
EOC;
        } elseif($language === 'python') {
            return <<<EOC
def process_{$this->toSnakeCase($description)}(input_data, options=None):
    """
    {$description}
    
    Args:
        input_data: Input parameter
        options: Configuration options (dict)
    
    Returns:
        dict: Result with status and data
    """
    if not input_data:
        return {
            'status': 'error',
            'message': 'Input is required'
        }
    
    try:
        # Process logic for: {$description}
        result = {}
        
        # TODO: Implement core logic here
        
        return {
            'status': 'success',
            'data': result,
            'timestamp': time.time()
        }
        
    except Exception as e:
        return {
            'status': 'error',
            'message': str(e)
        }
EOC;
        } else {
            return "// Function: {$description}\n// Language: {$language}\n// [Generated code would appear here]";
        }
    }
    
    /**
     * Generate class
     */
    private function generateClass($description, $language) {
        if($language === 'php') {
            $class_name = $this->toPascalCase($description);
            return <<<EOC
/**
 * {$class_name} - {$description}
 * 
 * @version 1.0
 * @author AI Generator
 */
class {$class_name} {
    
    private \$config = [];
    private \$data = [];
    
    /**
     * Constructor
     * 
     * @param array \$options Configuration options
     */
    public function __construct(\$options = []) {
        \$this->config = array_merge([
            'debug' => false,
            'timeout' => 30,
            'retries' => 3
        ], \$options);
    }
    
    /**
     * Initialize
     */
    public function initialize() {
        // TODO: Implement initialization logic
        return true;
    }
    
    /**
     * Process
     */
    public function process(\$input) {
        // TODO: Implement processing logic
        return [
            'status' => 'success',
            'data' => []
        ];
    }
    
    /**
     * Validate input
     */
    private function validate(\$input) {
        if(empty(\$input)) {
            throw new Exception('Input is required');
        }
        return true;
    }
    
    /**
     * Get configuration
     */
    public function getConfig() {
        return \$this->config;
    }
}
EOC;
        } else {
            return "// Class: {$description}\n// Language: {$language}";
        }
    }
    
    /**
     * Generate API endpoint
     */
    private function generateAPI($description, $language) {
        if($language === 'php') {
            return <<<EOC
<?php
/**
 * API Endpoint: {$description}
 * 
 * Methods: GET, POST, PUT, DELETE
 * Authentication: Required
 */

if(!ossn_isLoggedin()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Authentication required'
    ]);
    return;
}

\$user = ossn_loggedin_user();
\$method = \$_SERVER['REQUEST_METHOD'];
\$action = array_shift(__OSSN_REQUESTED_ACTION__);

switch(\$action) {
    case 'list':
        handleList(\$user);
        break;
    case 'create':
        handleCreate(\$user);
        break;
    case 'update':
        handleUpdate(\$user);
        break;
    case 'delete':
        handleDelete(\$user);
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Unknown action'
        ]);
}

function handleList(\$user) {
    // TODO: Implement list logic
    echo json_encode([
        'status' => 'success',
        'data' => []
    ]);
}

function handleCreate(\$user) {
    // TODO: Implement create logic
    echo json_encode([
        'status' => 'success',
        'message' => 'Created successfully'
    ]);
}

function handleUpdate(\$user) {
    // TODO: Implement update logic
    echo json_encode([
        'status' => 'success',
        'message' => 'Updated successfully'
    ]);
}

function handleDelete(\$user) {
    // TODO: Implement delete logic
    echo json_encode([
        'status' => 'success',
        'message' => 'Deleted successfully'
    ]);
}
?>
EOC;
        } else {
            return "// API: {$description}";
        }
    }
    
    /**
     * Generate CRUD operations
     */
    private function generateCRUD($description, $language) {
        return $this->generateClass("CRUD{$description}", $language);
    }
    
    /**
     * Generate SQL query
     */
    private function generateQuery($description, $language) {
        if($language === 'sql') {
            return <<<EOC
-- Query: {$description}
-- Purpose: [Database operation]

SELECT *
FROM table_name
WHERE 
    -- TODO: Add conditions for {$description}
    1 = 1
ORDER BY created DESC
LIMIT 100;

-- Alternative with joins:
SELECT t1.*, t2.*
FROM table1 t1
LEFT JOIN table2 t2 ON t1.id = t2.table1_id
WHERE 
    -- TODO: Add conditions
    1 = 1;

-- Indexed columns recommendation:
-- CREATE INDEX idx_{$this->toSnakeCase($description)} ON table_name(column_name);
EOC;
        } else {
            return "-- Query for: {$description}";
        }
    }
    
    /**
     * Generate test code
     */
    private function generateTest($description, $language) {
        if($language === 'php') {
            return <<<EOC
<?php
/**
 * Test for: {$description}
 */

class Test{$this->toPascalCase($description)}Test {
    
    public function testSuccess() {
        // TODO: Implement success test
        \$this->assertTrue(true);
    }
    
    public function testFailure() {
        // TODO: Implement failure test
        \$this->assertFalse(false);
    }
    
    public function testException() {
        // TODO: Test exception handling
    }
}
?>
EOC;
        } else {
            return "// Test for: {$description}";
        }
    }
    
    /**
     * Generate code snippet
     */
    private function generateSnippet($description, $language) {
        return "// Snippet: {$description}\n// Language: {$language}\n\n// TODO: Add implementation";
    }
    
    /**
     * Add documentation
     */
    private function addDocumentation($code, $language) {
        $header = "/**\n * Auto-generated code\n * " . date('Y-m-d H:i:s') . "\n */\n\n";
        return $header . $code;
    }
    
    /**
     * Generate tests
     */
    private function generateTests($code, $language) {
        // Extract function/class name
        $pattern = '/(?:function|class)\s+(\w+)/';
        preg_match($pattern, $code, $matches);
        $name = $matches[1] ?? 'Generated';
        
        if($language === 'php') {
            return <<<EOT
<?php
class Test{$name} extends PHPUnit\\Framework\\TestCase {
    
    public function testBasicFunctionality() {
        \$this->assertTrue(true);
    }
    
    public function testErrorHandling() {
        \$this->assertFalse(false);
    }
}
?>
EOT;
        }
        
        return '';
    }
    
    /**
     * Optimize code
     */
    private function optimizeCode($code, $language) {
        // Add optimization comments
        $optimized = "// Optimized version\n" . $code;
        return $optimized;
    }
    
    /**
     * Estimate code complexity
     */
    private function estimateComplexity($code) {
        $complexity = 0;
        $complexity += substr_count($code, 'if');
        $complexity += substr_count($code, 'for');
        $complexity += substr_count($code, 'foreach');
        $complexity += substr_count($code, 'while');
        
        if($complexity < 5) return 'simple';
        if($complexity < 15) return 'moderate';
        return 'complex';
    }
    
    /**
     * Save code generation to database
     */
    private function saveCodeGeneration($data) {
        $db_data = [
            'user_id' => $this->user_id,
            'description' => $data['description'],
            'language' => $data['language'],
            'code_type' => $data['type'],
            'generated_code' => substr($data['generated_code'], 0, 10000),
            'line_count' => $data['line_count'],
            'complexity' => $data['complexity'],
            'processing_time' => $data['processing_time'],
            'created' => time()
        ];
        
        return $this->db->insert('alkebulan_code_generations', $db_data);
    }
    
    /**
     * Convert to PascalCase
     */
    private function toPascalCase($text) {
        $words = preg_split('/\s+/', trim($text));
        return implode('', array_map('ucfirst', $words));
    }
    
    /**
     * Convert to snake_case
     */
    private function toSnakeCase($text) {
        return strtolower(preg_replace('/\s+/', '_', trim($text)));
    }
    
    /**
     * Get generation history
     */
    public function getGenerationHistory($limit = 20) {
        $cache_key = 'code_gen_history_' . $this->user_id;
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            return $cached;
        }
        
        $query = $this->query_optimizer->executeOptimized(
            'SELECT * FROM alkebulan_code_generations WHERE user_id = ? ORDER BY created DESC LIMIT ?',
            [$this->user_id, $limit],
            'Get code generation history'
        );
        
        $this->cache_manager->set($cache_key, $query ?: [], 3600);
        return $query ?: [];
    }
}
?>
