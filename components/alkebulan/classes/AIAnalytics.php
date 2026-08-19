<?php
/**
 * AIAnalytics Class - Advanced Analytics and Insights
 * Tracks usage, performance, trends, and generates insights
 */

class AIAnalytics {
    private $db;
    private $user_id;
    
    public function __construct($user_id = null) {
        $this->db = ossn_get_database();
        $this->user_id = $user_id;
    }
    
    /**
     * Log feature usage
     */
    public function logUsage($feature, $tokens_used = 0, $response_time = 0, $status = 'success') {
        $cost = $tokens_used * 0.00015; // Example pricing
        
        $data = [
            'user_id' => $this->user_id,
            'feature_used' => $feature,
            'tokens_used' => $tokens_used,
            'cost' => $cost,
            'status' => $status,
            'response_time' => $response_time,
            'created' => time()
        ];
        
        return $this->db->insert('alkebulan_usage_log', $data);
    }
    
    /**
     * Get usage statistics
     */
    public function getUsageStats($period = 'today') {
        $start_date = $this->getStartDate($period);
        
        $query = "SELECT 
                    COUNT(*) as total_requests,
                    SUM(tokens_used) as total_tokens,
                    SUM(cost) as total_cost,
                    AVG(response_time) as avg_response_time,
                    MAX(response_time) as max_response_time
                  FROM alkebulan_usage_log
                  WHERE created >= ? AND user_id = ?";
        
        $result = $this->db->query($query, [$start_date, $this->user_id])->fetch_object();
        
        return [
            'period' => $period,
            'total_requests' => $result->total_requests ?? 0,
            'total_tokens' => $result->total_tokens ?? 0,
            'total_cost' => $result->total_cost ?? 0,
            'avg_response_time' => round($result->avg_response_time ?? 0, 2) . 'ms',
            'max_response_time' => round($result->max_response_time ?? 0, 2) . 'ms'
        ];
    }
    
    /**
     * Get feature usage breakdown
     */
    public function getFeatureUsage($period = 'week') {
        $start_date = $this->getStartDate($period);
        
        $query = "SELECT 
                    feature_used,
                    COUNT(*) as usage_count,
                    SUM(tokens_used) as tokens,
                    SUM(cost) as cost,
                    AVG(response_time) as avg_time,
                    SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful,
                    SUM(CASE WHEN status = 'error' THEN 1 ELSE 0 END) as errors
                  FROM alkebulan_usage_log
                  WHERE created >= ? AND user_id = ?
                  GROUP BY feature_used
                  ORDER BY usage_count DESC";
        
        return $this->db->query($query, [$start_date, $this->user_id])->fetch();
    }
    
    /**
     * Get trending topics
     */
    public function getTrendingTopics($limit = 10) {
        $query = "SELECT 
                    JSON_EXTRACT(output_data, '$.keywords[0]') as keyword,
                    COUNT(*) as frequency
                  FROM alkebulan_analysis
                  WHERE created >= ? AND status = 'completed'
                  GROUP BY keyword
                  ORDER BY frequency DESC
                  LIMIT ?";
        
        $week_ago = time() - (7 * 24 * 60 * 60);
        return $this->db->query($query, [$week_ago, $limit])->fetch();
    }
    
    /**
     * Get recommendation effectiveness
     */
    public function getRecommendationMetrics() {
        if(!$this->user_id) return null;
        
        $query = "SELECT 
                    COUNT(*) as total_recommendations,
                    SUM(CASE WHEN viewed = 1 THEN 1 ELSE 0 END) as viewed,
                    SUM(CASE WHEN acted_upon = 1 THEN 1 ELSE 0 END) as acted_upon,
                    AVG(CAST(relevance_score * 100 as FLOAT)) as avg_relevance
                  FROM alkebulan_recommendations
                  WHERE user_id = ?";
        
        $result = $this->db->query($query, [$this->user_id])->fetch_object();
        
        $total = $result->total_recommendations ?? 0;
        
        return [
            'total' => $total,
            'viewed' => $result->viewed ?? 0,
            'acted_upon' => $result->acted_upon ?? 0,
            'view_rate' => $total > 0 ? round(($result->viewed / $total) * 100, 2) : 0,
            'action_rate' => $total > 0 ? round(($result->acted_upon / $total) * 100, 2) : 0,
            'avg_relevance' => round($result->avg_relevance ?? 0, 2)
        ];
    }
    
    /**
     * Get sentiment trends
     */
    public function getSentimentTrends($period = 'month') {
        $start_date = $this->getStartDate($period);
        $trends = [
            'positive' => 0,
            'negative' => 0,
            'neutral' => 0
        ];
        
        $query = "SELECT output_data FROM alkebulan_analysis 
                  WHERE created >= ? AND user_id = ? AND status = 'completed'";
        
        $results = $this->db->query($query, [$start_date, $this->user_id])->fetch();
        
        if($results) {
            foreach($results as $result) {
                $data = json_decode($result->output_data, true);
                $sentiment = $data['sentiment'] ?? 'neutral';
                $trends[$sentiment]++;
            }
        }
        
        $total = array_sum($trends);
        
        if($total > 0) {
            foreach($trends as $key => $value) {
                $trends[$key] = round(($value / $total) * 100, 2);
            }
        }
        
        return $trends;
    }
    
    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics() {
        $query = "SELECT 
                    COUNT(*) as total_requests,
                    SUM(CASE WHEN response_time < 100 THEN 1 ELSE 0 END) as fast,
                    SUM(CASE WHEN response_time BETWEEN 100 AND 500 THEN 1 ELSE 0 END) as medium,
                    SUM(CASE WHEN response_time > 500 THEN 1 ELSE 0 END) as slow,
                    MIN(response_time) as min_time,
                    MAX(response_time) as max_time,
                    AVG(response_time) as avg_time
                  FROM alkebulan_usage_log
                  WHERE user_id = ?";
        
        $result = $this->db->query($query, [$this->user_id])->fetch_object();
        $total = $result->total_requests ?? 0;
        
        return [
            'total_requests' => $total,
            'speed_distribution' => [
                'fast' => $total > 0 ? round(($result->fast / $total) * 100, 2) : 0,
                'medium' => $total > 0 ? round(($result->medium / $total) * 100, 2) : 0,
                'slow' => $total > 0 ? round(($result->slow / $total) * 100, 2) : 0
            ],
            'response_times' => [
                'min' => round($result->min_time ?? 0, 2) . 'ms',
                'max' => round($result->max_time ?? 0, 2) . 'ms',
                'avg' => round($result->avg_time ?? 0, 2) . 'ms'
            ]
        ];
    }
    
    /**
     * Generate comprehensive report
     */
    public function generateReport($period = 'month') {
        return [
            'period' => $period,
            'usage_stats' => $this->getUsageStats($period),
            'feature_usage' => $this->getFeatureUsage($period),
            'sentiment_trends' => $this->getSentimentTrends($period),
            'recommendation_metrics' => $this->getRecommendationMetrics(),
            'performance_metrics' => $this->getPerformanceMetrics(),
            'trending_topics' => $this->getTrendingTopics(5),
            'generated_at' => time()
        ];
    }
    
    /**
     * Export report as JSON
     */
    public function exportReport($period = 'month') {
        $report = $this->generateReport($period);
        return json_encode($report, JSON_PRETTY_PRINT);
    }
    
    /**
     * Get start date based on period
     */
    private function getStartDate($period) {
        $now = time();
        
        switch($period) {
            case 'today':
                return strtotime('today');
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
     * Get system-wide analytics (admin)
     */
    public function getSystemAnalytics() {
        return [
            'total_users_active' => $this->getTotalActiveUsers(),
            'total_analyses' => $this->getTotalAnalyses(),
            'total_recommendations' => $this->getTotalRecommendations(),
            'system_performance' => $this->getSystemPerformance(),
            'api_health' => $this->getAPIHealth(),
            'popular_features' => $this->getPopularFeatures()
        ];
    }
    
    /**
     * Get total active users
     */
    private function getTotalActiveUsers() {
        $query = "SELECT COUNT(DISTINCT user_id) as count FROM alkebulan_usage_log 
                  WHERE created >= ? LIMIT 1";
        
        $week_ago = time() - (7 * 24 * 60 * 60);
        $result = $this->db->query($query, [$week_ago])->fetch_object();
        
        return $result->count ?? 0;
    }
    
    /**
     * Get total analyses
     */
    private function getTotalAnalyses() {
        $query = "SELECT COUNT(*) as count FROM alkebulan_analysis WHERE status = 'completed'";
        $result = $this->db->query($query)->fetch_object();
        
        return $result->count ?? 0;
    }
    
    /**
     * Get total recommendations
     */
    private function getTotalRecommendations() {
        $query = "SELECT COUNT(*) as count FROM alkebulan_recommendations";
        $result = $this->db->query($query)->fetch_object();
        
        return $result->count ?? 0;
    }
    
    /**
     * Get system performance
     */
    private function getSystemPerformance() {
        $metrics = $this->getPerformanceMetrics();
        return $metrics['response_times'] ?? [];
    }
    
    /**
     * Get API health
     */
    private function getAPIHealth() {
        $query = "SELECT 
                    SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as success,
                    SUM(CASE WHEN status = 'error' THEN 1 ELSE 0 END) as errors,
                    COUNT(*) as total
                  FROM alkebulan_usage_log
                  WHERE created >= ?";
        
        $hour_ago = time() - 3600;
        $result = $this->db->query($query, [$hour_ago])->fetch_object();
        $total = $result->total ?? 0;
        
        return [
            'uptime' => $total > 0 ? round(($result->success / $total) * 100, 2) : 100,
            'errors' => $result->errors ?? 0
        ];
    }
    
    /**
     * Get popular features
     */
    private function getPopularFeatures() {
        $query = "SELECT feature_used, COUNT(*) as count 
                  FROM alkebulan_usage_log 
                  GROUP BY feature_used 
                  ORDER BY count DESC 
                  LIMIT 5";
        
        return $this->db->query($query)->fetch();
    }
}
?>
