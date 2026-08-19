<?php
/**
 * QueryOptimizer Class - Database Performance Enhancement
 * Analyzes, optimizes, and caches database queries
 * Features: Query profiling, index recommendations, slow query detection, batch operations
 */

class QueryOptimizer {
    private $db;
    private $query_log = [];
    private $slow_query_threshold = 1.0; // seconds
    private $enable_logging = true;
    private $cache_manager;
    
    public function __construct() {
        $this->db = ossn_get_database();
        $this->cache_manager = new CacheManager();
    }
    
    /**
     * Execute optimized query with logging
     * @param string $query SQL query
     * @param array $params Query parameters
     * @param string $description Query description
     * @return mixed Query result
     */
    public function executeOptimized($query, $params = [], $description = '') {
        $start_time = microtime(true);
        
        // Check if query result is cached
        $cache_key = 'query_' . md5($query . json_encode($params));
        $cached = $this->cache_manager->get($cache_key);
        if($cached !== null) {
            $this->logQuery($description, $query, microtime(true) - $start_time, true);
            return $cached;
        }
        
        // Execute query
        $result = $this->db->query($query, $params)->fetch();
        $execution_time = microtime(true) - $start_time;
        
        // Log query performance
        $this->logQuery($description, $query, $execution_time);
        
        // Cache result for 1 hour
        if($result) {
            $this->cache_manager->set($cache_key, $result, 3600);
        }
        
        return $result;
    }
    
    /**
     * Log query performance
     */
    private function logQuery($description, $query, $execution_time, $from_cache = false) {
        if(!$this->enable_logging) {
            return;
        }
        
        $is_slow = $execution_time > $this->slow_query_threshold;
        
        $log_entry = [
            'timestamp' => time(),
            'description' => $description,
            'query' => $query,
            'execution_time' => $execution_time,
            'is_slow' => $is_slow,
            'from_cache' => $from_cache
        ];
        
        $this->query_log[] = $log_entry;
        
        // Store slow queries
        if($is_slow && !$from_cache) {
            $this->storeSlowQuery($log_entry);
        }
    }
    
    /**
     * Store slow query for analysis
     */
    private function storeSlowQuery($log_entry) {
        $data = [
            'query' => $log_entry['query'],
            'description' => $log_entry['description'],
            'execution_time' => $log_entry['execution_time'],
            'created' => time()
        ];
        
        $this->db->insert('alkebulan_slow_queries', $data);
    }
    
    /**
     * Batch insert multiple records
     * @param string $table Table name
     * @param array $records Array of records to insert
     * @return int Number of affected rows
     */
    public function batchInsert($table, $records) {
        if(empty($records)) {
            return 0;
        }
        
        $count = 0;
        
        // Insert in batches of 100 for better performance
        $batch_size = 100;
        $batches = array_chunk($records, $batch_size);
        
        foreach($batches as $batch) {
            $placeholders = [];
            $values = [];
            
            foreach($batch as $record) {
                $placeholders[] = '(' . implode(',', array_fill(0, count($record), '?')) . ')';
                $values = array_merge($values, array_values($record));
            }
            
            $columns = array_keys(reset($batch));
            $query = 'INSERT INTO ' . $table . ' (' . implode(',', $columns) . ') VALUES ' . implode(',', $placeholders);
            
            $result = $this->db->query($query, $values);
            if($result) {
                $count += count($batch);
            }
        }
        
        return $count;
    }
    
    /**
     * Batch update multiple records
     * @param string $table Table name
     * @param array $records Records with id and fields to update
     * @return int Number of affected rows
     */
    public function batchUpdate($table, $records) {
        if(empty($records)) {
            return 0;
        }
        
        $count = 0;
        
        foreach($records as $record) {
            $id = $record['id'];
            unset($record['id']);
            
            $this->db->update($table, $record)
                ->where('id', $id)
                ->execute();
            
            $count++;
        }
        
        return $count;
    }
    
    /**
     * Suggest indexes based on query patterns
     */
    public function suggestIndexes() {
        $suggestions = [];
        
        // Analyze query log for frequently queried columns
        $column_frequency = [];
        
        foreach($this->query_log as $entry) {
            // Simple regex to find WHERE clauses
            if(preg_match_all('/WHERE\s+(\w+)\s*=/', $entry['query'], $matches)) {
                foreach($matches[1] as $col) {
                    $column_frequency[$col] = ($column_frequency[$col] ?? 0) + 1;
                }
            }
        }
        
        // Suggest indexes for frequently used columns
        foreach($column_frequency as $column => $frequency) {
            if($frequency > 5) {
                $suggestions[] = [
                    'table' => $this->extractTableName($column),
                    'column' => $column,
                    'frequency' => $frequency,
                    'recommendation' => 'Consider creating index on: ' . $column
                ];
            }
        }
        
        return $suggestions;
    }
    
    /**
     * Extract table name from query
     */
    private function extractTableName($query) {
        if(preg_match('/FROM\s+(\w+)/', $query, $matches)) {
            return $matches[1];
        }
        return 'unknown';
    }
    
    /**
     * Get query performance statistics
     */
    public function getPerformanceStats() {
        if(empty($this->query_log)) {
            return [];
        }
        
        $total_queries = count($this->query_log);
        $slow_queries = array_filter($this->query_log, fn($q) => $q['is_slow']);
        $cached_queries = array_filter($this->query_log, fn($q) => $q['from_cache']);
        
        $total_time = array_sum(array_map(fn($q) => $q['execution_time'], $this->query_log));
        $avg_time = $total_time / $total_queries;
        $max_time = max(array_map(fn($q) => $q['execution_time'], $this->query_log));
        
        return [
            'total_queries' => $total_queries,
            'slow_queries' => count($slow_queries),
            'cached_queries' => count($cached_queries),
            'total_execution_time' => round($total_time, 4),
            'average_execution_time' => round($avg_time, 4),
            'max_execution_time' => round($max_time, 4),
            'cache_hit_rate' => round((count($cached_queries) / $total_queries) * 100, 2) . '%',
            'slow_query_threshold' => $this->slow_query_threshold . 's'
        ];
    }
    
    /**
     * Get slow queries log
     */
    public function getSlowQueries($limit = 50) {
        return $this->executeOptimized(
            'SELECT * FROM alkebulan_slow_queries ORDER BY created DESC LIMIT ?',
            [$limit],
            'Get slow queries'
        );
    }
    
    /**
     * Clear query log
     */
    public function clearLog() {
        $this->query_log = [];
    }
    
    /**
     * Enable/disable query logging
     */
    public function setLogging($enabled) {
        $this->enable_logging = $enabled;
    }
    
    /**
     * Get slow query threshold
     */
    public function setSlowQueryThreshold($seconds) {
        $this->slow_query_threshold = $seconds;
    }
    
    /**
     * Analyze table and provide optimization recommendations
     */
    public function analyzeTable($table_name) {
        $analysis = [
            'table' => $table_name,
            'row_count' => 0,
            'table_size' => 0,
            'fragmentation' => 0,
            'recommendations' => []
        ];
        
        // Get table stats
        $stats = $this->db->query("
            SELECT 
                TABLE_ROWS as rows,
                (DATA_LENGTH + INDEX_LENGTH) as size
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_NAME = ?
        ", [$table_name])->fetch_object();
        
        if($stats) {
            $analysis['row_count'] = $stats->rows;
            $analysis['table_size'] = $this->formatBytes($stats->size);
            
            // Add recommendations
            if($stats->rows > 1000000) {
                $analysis['recommendations'][] = 'Consider partitioning large table';
            }
            
            if($stats->size > 1073741824) { // 1GB
                $analysis['recommendations'][] = 'Table is large, ensure adequate disk space';
                $analysis['recommendations'][] = 'Review indexing strategy';
            }
        }
        
        return $analysis;
    }
    
    /**
     * Format bytes to human-readable format
     */
    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
?>
