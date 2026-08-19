<?php
/**
 * Analyze Action Handler
 * Handles content analysis requests
 */

if(ossn_isLoggedin()) {
    $user_id = ossn_loggedin_user()->guid;
    $action = $_POST['action_type'] ?? 'sentiment';
    $text = $_POST['text'] ?? '';
    
    if(empty($text)) {
        echo json_encode(['error' => 'No text provided', 'success' => false]);
        exit;
    }
    
    try {
        $analyzer = new AIAnalyzer($user_id);
        $analytics = new AIAnalytics($user_id);
        
        $start_time = microtime(true);
        
        switch($action) {
            case 'sentiment':
                $result = $analyzer->analyzeSentiment($text);
                break;
            case 'categorize':
                $result = [
                    'categories' => $analyzer->categorizeContent($text),
                    'text' => substr($text, 0, 100)
                ];
                break;
            case 'entities':
                $result = $analyzer->recognizeEntities($text);
                break;
            case 'keywords':
                $analyzer->analyzeSentiment($text); // Store for keyword extraction
                $result = ['status' => 'analyzed'];
                break;
            default:
                $result = $analyzer->analyzeSentiment($text);
        }
        
        $response_time = round((microtime(true) - $start_time) * 1000);
        $tokens = ceil(strlen($text) / 4); // Approximate token count
        
        $analytics->logUsage($action, $tokens, $response_time);
        
        echo json_encode([
            'success' => true,
            'data' => $result,
            'response_time' => $response_time . 'ms',
            'tokens_used' => $tokens
        ]);
        
    } catch(Exception $e) {
        $analytics->logUsage($action, 0, 0, 'error');
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['error' => 'Not logged in', 'success' => false]);
}
?>
