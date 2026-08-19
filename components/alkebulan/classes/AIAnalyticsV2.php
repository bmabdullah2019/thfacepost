<?php

/**
 * AIAnalyticsV2 - Real Usage & Performance Tracking
 * 
 * Tracks ACTUAL user data instead of mock data:
 * - Real AI generator usage (all 5 V2 generators)
 * - Actual performance metrics (response times, resource usage)
 * - Real user insights (patterns, trends, optimization recommendations)
 * - Cost tracking & billing
 * - Quality metrics & success rates
 * 
 * @version 2.0
 * @author Alkebulan AI Team
 */

class AIAnalyticsV2 {
    
    private $db;
    private $user_id;
    private $cache_manager;
    private $table_prefix = 'alkebulan_analytics_';
    
    // Analytics tables
    private $tables = [
        'usage_log' => 'alkebulan_analytics_usage_log',
        'performance' => 'alkebulan_analytics_performance',
        'generator_stats' => 'alkebulan_analytics_generator_stats',
        'daily_summary' => 'alkebulan_analytics_daily_summary',
        'quality_metrics' => 'alkebulan_analytics_quality_metrics',
        'cost_tracking' => 'alkebulan_analytics_cost_tracking',
        'user_insights' => 'alkebulan_analytics_user_insights'
    ];
    
    // Generator types
    private $generators = [
        'text' => 'TextGeneratorV2',
        'code' => 'CodeGeneratorV2',
        'summary' => 'SummaryGeneratorV2',
        'prompt' => 'PromptOptimizerV2',
        'translation' => 'TranslationEngineV2',
        'image' => 'ImageGeneratorV3'
    ];
    
    public function __construct($user_id = null) {
        global $OSSN_DB;
        $this->db = $OSSN_DB;
        $this->user_id = $user_id;
        
        // Use CacheManager if available
        if (class_exists('CacheManager')) {
            $this->cache_manager = new CacheManager();
        }
        
        // Initialize database tables
        $this->initializeTables();
    }
    
    /**
     * Initialize analytics tables if they don't exist
     */
    private function initializeTables() {
        $tables_to_create = [
            'usage_log' => "
                CREATE TABLE IF NOT EXISTS {$this->tables['usage_log']} (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    generator_type VARCHAR(50),
                    generator_class VARCHAR(100),
                    action VARCHAR(100),
                    input_length INT,
                    output_length INT,
                    tokens_used INT DEFAULT 0,
                    response_time_ms INT,
                    memory_used_mb FLOAT,
                    status VARCHAR(20),
                    error_message TEXT,
                    cost DECIMAL(10,6),
                    api_method VARCHAR(50),
                    model_used VARCHAR(100),
                    created BIGINT,
                    KEY user_idx (user_id),
                    KEY generator_idx (generator_type),
                    KEY created_idx (created)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ",
            'performance' => "
                CREATE TABLE IF NOT EXISTS {$this->tables['performance']} (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    generator_type VARCHAR(50),
                    min_response_time INT,
                    max_response_time INT,
                    avg_response_time INT,
                    p95_response_time INT,
                    p99_response_time INT,
                    total_requests INT DEFAULT 0,
                    successful_requests INT DEFAULT 0,
                    failed_requests INT DEFAULT 0,
                    success_rate DECIMAL(5,2),
                    throughput_per_hour INT,
                    period_date DATE,
                    created BIGINT,
                    KEY user_idx (user_id),
                    KEY generator_idx (generator_type),
                    KEY date_idx (period_date)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ",
            'generator_stats' => "
                CREATE TABLE IF NOT EXISTS {$this->tables['generator_stats']} (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    generator_type VARCHAR(50),
                    total_uses INT DEFAULT 0,
                    total_tokens_generated INT DEFAULT 0,
                    total_cost DECIMAL(12,6) DEFAULT 0,
                    total_response_time_ms INT DEFAULT 0,
                    quality_score DECIMAL(5,2) DEFAULT 0,
                    error_count INT DEFAULT 0,
                    last_used BIGINT,
                    first_used BIGINT,
                    created BIGINT,
                    updated BIGINT,
                    KEY user_idx (user_id),
                    KEY generator_idx (generator_type)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ",
            'daily_summary' => "
                CREATE TABLE IF NOT EXISTS {$this->tables['daily_summary']} (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    summary_date DATE,
                    total_requests INT DEFAULT 0,
                    successful_requests INT DEFAULT 0,
                    failed_requests INT DEFAULT 0,
                    total_tokens_used INT DEFAULT 0,
                    total_cost DECIMAL(12,6) DEFAULT 0,
                    total_response_time_ms INT DEFAULT 0,
                    avg_response_time_ms INT DEFAULT 0,
                    peak_usage_hour INT,
                    most_used_generator VARCHAR(50),
                    quality_score DECIMAL(5,2),
                    created BIGINT,
                    KEY user_idx (user_id),
                    KEY date_idx (summary_date)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ",
            'quality_metrics' => "
                CREATE TABLE IF NOT EXISTS {$this->tables['quality_metrics']} (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    usage_log_id INT,
                    generator_type VARCHAR(50),
                    output_quality INT,
                    user_satisfaction INT,
                    uniqueness_score INT,
                    accuracy_score INT,
                    coherence_score INT,
                    relevance_score INT,
                    notes TEXT,
                    created BIGINT,
                    KEY user_idx (user_id),
                    KEY usage_idx (usage_log_id),
                    KEY generator_idx (generator_type)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ",
            'cost_tracking' => "
                CREATE TABLE IF NOT EXISTS {$this->tables['cost_tracking']} (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    month DATE,
                    generator_type VARCHAR(50),
                    api_calls INT DEFAULT 0,
                    tokens_used INT DEFAULT 0,
                    cost_per_token DECIMAL(10,8),
                    total_cost DECIMAL(12,6),
                    budget_limit DECIMAL(12,6),
                    alert_sent TINYINT DEFAULT 0,
                    created BIGINT,
                    KEY user_idx (user_id),
                    KEY month_idx (month)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ",
            'user_insights' => "
                CREATE TABLE IF NOT EXISTS {$this->tables['user_insights']} (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    insight_type VARCHAR(50),
                    insight_data JSON,
                    recommendation TEXT,
                    severity VARCHAR(20),
                    action_taken TINYINT DEFAULT 0,
                    created BIGINT,
                    KEY user_idx (user_id),
                    KEY type_idx (insight_type)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            "
        ];
        
        foreach ($tables_to_create as $table_key => $sql) {
            try {
                if ($this->db) {
                    $this->db->query($sql);
                }
            } catch (Exception $e) {
                // Table might already exist, continue
            }
        }
    }
    
    /**
     * Log actual AI generator usage
     * Call this when a generator is invoked
     */
    public function trackGeneratorUsage(
        $generator_type,      // 'text', 'code', 'summary', 'prompt', 'translation', 'image'
        $action,              // 'generateArticle', 'generateFunction', etc.
        $input_length,        // Length of input provided
        $output_length,       // Length of output generated
        $response_time_ms,    // How long it took
        $tokens_used = 0,     // Tokens consumed
        $status = 'success',  // 'success' or 'error'
        $error_message = null,
        $api_method = null,   // 'procedural', 'stable_diffusion', etc.
        $model_used = null,
        $memory_used_mb = 0
    ) {
        if (!$this->user_id || !$this->db) {
            return false;
        }
        
        // Calculate cost (example: $0.00015 per token)
        $cost = $tokens_used * 0.00015;
        
        try {
            // Log usage
            $usage_data = [
                'user_id' => $this->user_id,
                'generator_type' => $generator_type,
                'generator_class' => $this->generators[$generator_type] ?? 'Unknown',
                'action' => $action,
                'input_length' => $input_length,
                'output_length' => $output_length,
                'tokens_used' => $tokens_used,
                'response_time_ms' => $response_time_ms,
                'memory_used_mb' => $memory_used_mb,
                'status' => $status,
                'error_message' => $error_message,
                'cost' => $cost,
                'api_method' => $api_method,
                'model_used' => $model_used,
                'created' => time()
            ];
            
            $this->db->insert($this->tables['usage_log'], $usage_data);
            
            // Update generator statistics
            $this->updateGeneratorStats($generator_type, $response_time_ms, $tokens_used, $cost, $status);
            
            // Check for insights
            $this->generateUserInsights($generator_type, $response_time_ms, $status);
            
            return true;
        } catch (Exception $e) {
            error_log("AIAnalyticsV2 tracking error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update generator statistics
     */
    private function updateGeneratorStats($generator_type, $response_time, $tokens_used, $cost, $status) {
        try {
            // Check if stats exist for this generator
            $query = "SELECT * FROM {$this->tables['generator_stats']} 
                      WHERE user_id = ? AND generator_type = ?";
            $result = $this->db->query($query, [$this->user_id, $generator_type]);
            
            if ($result && count($result) > 0) {
                // Update existing
                $update = [
                    'total_uses' => new \PDOStatement("total_uses + 1"),
                    'total_tokens_generated' => new \PDOStatement("total_tokens_generated + $tokens_used"),
                    'total_cost' => new \PDOStatement("total_cost + $cost"),
                    'total_response_time_ms' => new \PDOStatement("total_response_time_ms + $response_time"),
                    'error_count' => $status === 'error' ? new \PDOStatement("error_count + 1") : 0,
                    'last_used' => time(),
                    'updated' => time()
                ];
                
                $this->db->update(
                    $this->tables['generator_stats'],
                    $update,
                    ['user_id' => $this->user_id, 'generator_type' => $generator_type]
                );
            } else {
                // Create new stats record
                $insert = [
                    'user_id' => $this->user_id,
                    'generator_type' => $generator_type,
                    'total_uses' => 1,
                    'total_tokens_generated' => $tokens_used,
                    'total_cost' => $cost,
                    'total_response_time_ms' => $response_time,
                    'error_count' => $status === 'error' ? 1 : 0,
                    'last_used' => time(),
                    'first_used' => time(),
                    'created' => time(),
                    'updated' => time()
                ];
                
                $this->db->insert($this->tables['generator_stats'], $insert);
            }
        } catch (Exception $e) {
            error_log("Generator stats update error: " . $e->getMessage());
        }
    }
    
    /**
     * Generate user insights from usage patterns
     */
    private function generateUserInsights($generator_type, $response_time, $status) {
        try {
            $insights = [];
            
            // Insight 1: High response times
            if ($response_time > 1000) {
                $insights[] = [
                    'type' => 'slow_performance',
                    'severity' => 'warning',
                    'message' => "$generator_type took {$response_time}ms - consider using faster method",
                    'recommendation' => 'Switch to procedural generation or local processing'
                ];
            }
            
            // Insight 2: High error rate
            if ($status === 'error') {
                $insights[] = [
                    'type' => 'error_occurred',
                    'severity' => 'critical',
                    'message' => "Error in $generator_type generation",
                    'recommendation' => 'Check input parameters and retry'
                ];
            }
            
            // Get usage stats to detect patterns
            $query = "SELECT COUNT(*) as count, 
                             SUM(CASE WHEN status = 'error' THEN 1 ELSE 0 END) as errors
                      FROM {$this->tables['usage_log']}
                      WHERE user_id = ? AND generator_type = ? AND created > ?";
            
            $hour_ago = time() - 3600;
            $result = $this->db->query($query, [$this->user_id, $generator_type, $hour_ago]);
            
            if ($result && count($result) > 0) {
                $stats = $result[0];
                $error_rate = $stats['count'] > 0 ? ($stats['errors'] / $stats['count']) * 100 : 0;
                
                if ($error_rate > 20) {
                    $insights[] = [
                        'type' => 'high_error_rate',
                        'severity' => 'critical',
                        'message' => "High error rate detected: {$error_rate}%",
                        'recommendation' => 'Review input data and generator configuration'
                    ];
                }
            }
            
            // Store insights
            foreach ($insights as $insight) {
                $this->db->insert($this->tables['user_insights'], [
                    'user_id' => $this->user_id,
                    'insight_type' => $insight['type'],
                    'insight_data' => json_encode($insight),
                    'recommendation' => $insight['recommendation'],
                    'severity' => $insight['severity'],
                    'created' => time()
                ]);
            }
        } catch (Exception $e) {
            error_log("Insight generation error: " . $e->getMessage());
        }
    }
    
    /**
     * Log quality metrics for generated content
     */
    public function trackQuality($usage_log_id, $generator_type, $quality_score, $uniqueness = null, $accuracy = null, $coherence = null, $relevance = null, $user_satisfaction = null) {
        try {
            $quality_data = [
                'user_id' => $this->user_id,
                'usage_log_id' => $usage_log_id,
                'generator_type' => $generator_type,
                'output_quality' => $quality_score,
                'user_satisfaction' => $user_satisfaction ?? 0,
                'uniqueness_score' => $uniqueness ?? 0,
                'accuracy_score' => $accuracy ?? 0,
                'coherence_score' => $coherence ?? 0,
                'relevance_score' => $relevance ?? 0,
                'created' => time()
            ];
            
            return $this->db->insert($this->tables['quality_metrics'], $quality_data);
        } catch (Exception $e) {
            error_log("Quality tracking error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get real usage statistics for a period
     */
    public function getRealUsageStats($period = 'today') {
        try {
            $start_time = $this->getStartTime($period);
            
            $query = "SELECT 
                        COUNT(*) as total_requests,
                        SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful,
                        SUM(CASE WHEN status = 'error' THEN 1 ELSE 0 END) as failed,
                        SUM(tokens_used) as total_tokens,
                        SUM(cost) as total_cost,
                        AVG(response_time_ms) as avg_response_time,
                        MIN(response_time_ms) as min_response_time,
                        MAX(response_time_ms) as max_response_time,
                        SUM(memory_used_mb) as total_memory_used,
                        COUNT(DISTINCT generator_type) as generators_used
                      FROM {$this->tables['usage_log']}
                      WHERE user_id = ? AND created >= ?";
            
            $result = $this->db->query($query, [$this->user_id, $start_time]);
            
            if (!$result || count($result) === 0) {
                return $this->emptyStats($period);
            }
            
            $data = $result[0];
            
            return [
                'period' => $period,
                'total_requests' => (int)$data['total_requests'],
                'successful_requests' => (int)$data['successful'],
                'failed_requests' => (int)$data['failed'],
                'success_rate' => $data['total_requests'] > 0 ? round(($data['successful'] / $data['total_requests']) * 100, 2) : 0,
                'total_tokens_used' => (int)$data['total_tokens'],
                'total_cost' => round($data['total_cost'] ?? 0, 4),
                'avg_response_time_ms' => round($data['avg_response_time'] ?? 0, 2),
                'min_response_time_ms' => (int)$data['min_response_time'],
                'max_response_time_ms' => (int)$data['max_response_time'],
                'total_memory_used_mb' => round($data['total_memory_used'] ?? 0, 2),
                'generators_used' => (int)$data['generators_used'],
                'timestamp' => time()
            ];
        } catch (Exception $e) {
            error_log("Usage stats error: " . $e->getMessage());
            return $this->emptyStats($period);
        }
    }
    
    /**
     * Get per-generator usage breakdown
     */
    public function getGeneratorBreakdown($period = 'week') {
        try {
            $start_time = $this->getStartTime($period);
            
            $query = "SELECT 
                        generator_type,
                        COUNT(*) as uses,
                        SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful,
                        SUM(CASE WHEN status = 'error' THEN 1 ELSE 0 END) as failed,
                        SUM(tokens_used) as tokens,
                        SUM(cost) as cost,
                        AVG(response_time_ms) as avg_time,
                        MAX(response_time_ms) as max_time
                      FROM {$this->tables['usage_log']}
                      WHERE user_id = ? AND created >= ?
                      GROUP BY generator_type
                      ORDER BY uses DESC";
            
            $results = $this->db->query($query, [$this->user_id, $start_time]);
            
            $breakdown = [];
            if ($results) {
                foreach ($results as $row) {
                    $breakdown[] = [
                        'generator_type' => $row['generator_type'],
                        'total_uses' => (int)$row['uses'],
                        'successful' => (int)$row['successful'],
                        'failed' => (int)$row['failed'],
                        'tokens_used' => (int)$row['tokens'],
                        'cost' => round($row['cost'] ?? 0, 4),
                        'avg_response_time_ms' => round($row['avg_time'] ?? 0, 2),
                        'max_response_time_ms' => (int)$row['max_time']
                    ];
                }
            }
            
            return $breakdown;
        } catch (Exception $e) {
            error_log("Generator breakdown error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get performance trends
     */
    public function getPerformanceTrends($days = 7) {
        try {
            $query = "SELECT 
                        DATE(FROM_UNIXTIME(created)) as date,
                        COUNT(*) as requests,
                        AVG(response_time_ms) as avg_time,
                        MAX(response_time_ms) as max_time,
                        SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful,
                        SUM(CASE WHEN status = 'error' THEN 1 ELSE 0 END) as failed
                      FROM {$this->tables['usage_log']}
                      WHERE user_id = ? AND created >= ?
                      GROUP BY DATE(FROM_UNIXTIME(created))
                      ORDER BY date ASC";
            
            $start_time = time() - ($days * 24 * 60 * 60);
            $results = $this->db->query($query, [$this->user_id, $start_time]);
            
            $trends = [];
            if ($results) {
                foreach ($results as $row) {
                    $trends[] = [
                        'date' => $row['date'],
                        'requests' => (int)$row['requests'],
                        'avg_response_time_ms' => round($row['avg_time'] ?? 0, 2),
                        'max_response_time_ms' => (int)$row['max_time'],
                        'successful' => (int)$row['successful'],
                        'failed' => (int)$row['failed'],
                        'success_rate' => $row['requests'] > 0 ? round(($row['successful'] / $row['requests']) * 100, 2) : 0
                    ];
                }
            }
            
            return $trends;
        } catch (Exception $e) {
            error_log("Performance trends error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get quality analytics
     */
    public function getQualityAnalytics($period = 'month') {
        try {
            $start_time = $this->getStartTime($period);
            
            $query = "SELECT 
                        generator_type,
                        AVG(output_quality) as quality,
                        AVG(uniqueness_score) as uniqueness,
                        AVG(accuracy_score) as accuracy,
                        AVG(coherence_score) as coherence,
                        AVG(relevance_score) as relevance,
                        AVG(user_satisfaction) as satisfaction,
                        COUNT(*) as measurements
                      FROM {$this->tables['quality_metrics']}
                      WHERE user_id = ? AND created >= ?
                      GROUP BY generator_type";
            
            $results = $this->db->query($query, [$this->user_id, $start_time]);
            
            $analytics = [];
            if ($results) {
                foreach ($results as $row) {
                    $analytics[] = [
                        'generator_type' => $row['generator_type'],
                        'avg_quality_score' => round($row['quality'] ?? 0, 2),
                        'avg_uniqueness' => round($row['uniqueness'] ?? 0, 2),
                        'avg_accuracy' => round($row['accuracy'] ?? 0, 2),
                        'avg_coherence' => round($row['coherence'] ?? 0, 2),
                        'avg_relevance' => round($row['relevance'] ?? 0, 2),
                        'avg_satisfaction' => round($row['satisfaction'] ?? 0, 2),
                        'measurements' => (int)$row['measurements']
                    ];
                }
            }
            
            return $analytics;
        } catch (Exception $e) {
            error_log("Quality analytics error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get cost tracking & budget status
     */
    public function getCostTracking($month = null) {
        try {
            if (!$month) {
                $month = date('Y-m-01');
            }
            
            $query = "SELECT 
                        generator_type,
                        COUNT(*) as calls,
                        SUM(tokens_used) as tokens,
                        SUM(cost) as cost
                      FROM {$this->tables['usage_log']}
                      WHERE user_id = ? AND DATE(FROM_UNIXTIME(created)) >= ?
                      GROUP BY generator_type
                      ORDER BY cost DESC";
            
            $start_of_month = strtotime($month);
            $results = $this->db->query($query, [$this->user_id, $start_of_month]);
            
            $breakdown = [];
            $total_cost = 0;
            
            if ($results) {
                foreach ($results as $row) {
                    $cost = round($row['cost'] ?? 0, 4);
                    $breakdown[] = [
                        'generator_type' => $row['generator_type'],
                        'api_calls' => (int)$row['calls'],
                        'tokens_used' => (int)$row['tokens'],
                        'cost' => $cost
                    ];
                    $total_cost += $cost;
                }
            }
            
            return [
                'month' => $month,
                'total_cost' => round($total_cost, 4),
                'breakdown' => $breakdown,
                'timestamp' => time()
            ];
        } catch (Exception $e) {
            error_log("Cost tracking error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get recent insights & recommendations
     */
    public function getInsightsAndRecommendations($limit = 10) {
        try {
            $query = "SELECT 
                        insight_type,
                        insight_data,
                        recommendation,
                        severity,
                        created
                      FROM {$this->tables['user_insights']}
                      WHERE user_id = ? AND action_taken = 0
                      ORDER BY created DESC
                      LIMIT ?";
            
            $results = $this->db->query($query, [$this->user_id, $limit]);
            
            $insights = [];
            if ($results) {
                foreach ($results as $row) {
                    $insights[] = [
                        'type' => $row['insight_type'],
                        'data' => json_decode($row['insight_data'], true),
                        'recommendation' => $row['recommendation'],
                        'severity' => $row['severity'],
                        'timestamp' => (int)$row['created']
                    ];
                }
            }
            
            return $insights;
        } catch (Exception $e) {
            error_log("Insights retrieval error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Generate comprehensive analytics report
     */
    public function generateAnalyticsReport($period = 'month') {
        return [
            'period' => $period,
            'generated_at' => date('Y-m-d H:i:s'),
            'usage_statistics' => $this->getRealUsageStats($period),
            'generator_breakdown' => $this->getGeneratorBreakdown($period),
            'performance_trends' => $this->getPerformanceTrends(30),
            'quality_analytics' => $this->getQualityAnalytics($period),
            'cost_tracking' => $this->getCostTracking(),
            'insights_and_recommendations' => $this->getInsightsAndRecommendations(5),
            'timestamp' => time()
        ];
    }
    
    /**
     * Export report as JSON
     */
    public function exportReport($period = 'month', $format = 'json') {
        $report = $this->generateAnalyticsReport($period);
        
        if ($format === 'json') {
            return json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        
        return $report;
    }
    
    /**
     * Get system-wide analytics (admin only)
     */
    public function getSystemAnalytics() {
        try {
            $query_total_users = "SELECT COUNT(DISTINCT user_id) as count FROM {$this->tables['usage_log']} 
                                  WHERE created >= ?";
            $week_ago = time() - (7 * 24 * 60 * 60);
            $total_users = $this->db->query($query_total_users, [$week_ago]);
            
            $query_total_requests = "SELECT COUNT(*) as count FROM {$this->tables['usage_log']}";
            $total_requests = $this->db->query($query_total_requests);
            
            $query_total_cost = "SELECT SUM(cost) as total FROM {$this->tables['usage_log']}";
            $total_cost = $this->db->query($query_total_cost);
            
            $query_top_generators = "SELECT generator_type, COUNT(*) as count 
                                    FROM {$this->tables['usage_log']}
                                    GROUP BY generator_type
                                    ORDER BY count DESC
                                    LIMIT 5";
            $top_generators = $this->db->query($query_top_generators);
            
            return [
                'total_active_users' => $total_users[0]['count'] ?? 0,
                'total_requests' => $total_requests[0]['count'] ?? 0,
                'total_cost' => round($total_cost[0]['total'] ?? 0, 4),
                'top_generators' => $top_generators ?? [],
                'timestamp' => time()
            ];
        } catch (Exception $e) {
            error_log("System analytics error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Helper: Get start time for period
     */
    private function getStartTime($period) {
        $now = time();
        
        switch ($period) {
            case 'today':
                return strtotime('today', $now);
            case 'week':
                return $now - (7 * 24 * 60 * 60);
            case 'month':
                return $now - (30 * 24 * 60 * 60);
            case 'quarter':
                return $now - (90 * 24 * 60 * 60);
            case 'year':
                return $now - (365 * 24 * 60 * 60);
            default:
                return $now - (30 * 24 * 60 * 60);
        }
    }
    
    /**
     * Helper: Empty stats template
     */
    private function emptyStats($period) {
        return [
            'period' => $period,
            'total_requests' => 0,
            'successful_requests' => 0,
            'failed_requests' => 0,
            'success_rate' => 0,
            'total_tokens_used' => 0,
            'total_cost' => 0,
            'avg_response_time_ms' => 0,
            'min_response_time_ms' => 0,
            'max_response_time_ms' => 0,
            'total_memory_used_mb' => 0,
            'generators_used' => 0,
            'timestamp' => time()
        ];
    }
}
?>
