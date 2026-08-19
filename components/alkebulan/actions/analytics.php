<?php
/**
 * Analytics Action Handler - ENHANCED
 * Now tracks REAL usage data instead of mock data
 * Uses AIAnalyticsV2 for comprehensive analytics
 */

if(ossn_isLoggedin()) {
    $user_id = ossn_loggedin_user()->guid;
    $analytics_action = $_POST['report_type'] ?? $_GET['report_type'] ?? 'usage';
    $period = $_POST['period'] ?? $_GET['period'] ?? 'month';
    
    try {
        // Load V2 analytics if available, fallback to legacy
        if (class_exists('AIAnalyticsV2')) {
            require_once(__DIR__ . '/../classes/AIAnalyticsV2.php');
            $analytics = new AIAnalyticsV2($user_id);
            $use_v2 = true;
        } else {
            $analytics = new AIAnalytics($user_id);
            $use_v2 = false;
        }
        
        switch($analytics_action) {
            case 'usage':
                $result = $use_v2 ? $analytics->getRealUsageStats($period) : $analytics->getUsageStats($period);
                break;
                
            case 'generators':
                $result = $use_v2 ? ['generators' => $analytics->getGeneratorBreakdown($period)] : ['features' => $analytics->getFeatureUsage($period)];
                break;
                
            case 'performance':
                $result = $use_v2 ? ['trends' => $analytics->getPerformanceTrends(30)] : ['performance' => $analytics->getPerformanceMetrics()];
                break;
                
            case 'quality':
                $result = $use_v2 ? ['quality' => $analytics->getQualityAnalytics($period)] : ['sentiment' => $analytics->getSentimentTrends($period)];
                break;
                
            case 'cost':
                $result = $use_v2 ? $analytics->getCostTracking() : ['cost' => 'unavailable'];
                break;
                
            case 'insights':
                $result = $use_v2 ? ['insights' => $analytics->getInsightsAndRecommendations(10)] : ['recommendations' => $analytics->getRecommendationMetrics()];
                break;
                
            case 'full_report':
                $result = $use_v2 ? $analytics->generateAnalyticsReport($period) : $analytics->generateReport($period);
                break;
                
            case 'export':
                header('Content-Type: application/json');
                header('Content-Disposition: attachment; filename="alkebulan_analytics_' . date('Y-m-d') . '.json"');
                echo $use_v2 ? $analytics->exportReport($period, 'json') : $analytics->exportReport($period);
                exit;
                
            default:
                $result = $use_v2 ? $analytics->getRealUsageStats($period) : $analytics->getUsageStats($period);
        }
        
        echo json_encode([
            'success' => true,
            'data' => $result,
            'period' => $period,
            'report_type' => $analytics_action,
            'generated_at' => time()
        ]);
        
    } catch(Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['error' => 'Not logged in', 'success' => false]);
}
?>
