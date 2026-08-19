<?php
/**
 * Recommend Action Handler
 * Handles recommendation requests
 */

if(ossn_isLoggedin()) {
    $user_id = ossn_loggedin_user()->guid;
    $rec_type = $_POST['type'] ?? 'content';
    
    try {
        $recommender = new AIRecommender($user_id);
        $analytics = new AIAnalytics($user_id);
        
        $start_time = microtime(true);
        
        switch($rec_type) {
            case 'content':
                $recommendations = $recommender->getContentRecommendations(10);
                break;
            case 'people':
                $recommendations = $recommender->getPeopleRecommendations(10);
                break;
            case 'groups':
                $recommendations = $recommender->getGroupRecommendations(5);
                break;
            case 'timeline':
                $recommendations = $recommender->getTimelineRecommendations(15);
                break;
            case 'trending':
                $recommendations = $recommender->getTrendingContent(10);
                break;
            default:
                $recommendations = $recommender->getContentRecommendations(10);
        }
        
        $response_time = round((microtime(true) - $start_time) * 1000);
        $analytics->logUsage('recommendation:' . $rec_type, count($recommendations), $response_time);
        
        echo json_encode([
            'success' => true,
            'data' => $recommendations,
            'type' => $rec_type,
            'count' => count($recommendations),
            'response_time' => $response_time . 'ms'
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
