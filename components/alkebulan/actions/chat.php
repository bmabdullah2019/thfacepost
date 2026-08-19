<?php
/**
 * Chat Action Handler
 * Handles chat assistant requests
 */

if(ossn_isLoggedin()) {
    $user_id = ossn_loggedin_user()->guid;
    $action = $_POST['chat_action'] ?? 'message';
    $session_id = $_POST['session_id'] ?? null;
    $message = $_POST['message'] ?? '';
    
    try {
        $analytics = new AIAnalytics($user_id);
        $start_time = microtime(true);
        
        switch($action) {
            case 'create_session':
                $context = isset($_POST['context']) ? json_decode($_POST['context'], true) : [];
                $assistant = new ChatAssistant($user_id);
                $result = $assistant->createSession($context);
                $feature = 'chat:create_session';
                break;
                
            case 'message':
                if(!$session_id || !$message) {
                    throw new Exception('Session ID and message required');
                }
                $assistant = new ChatAssistant($user_id, $session_id);
                $result = $assistant->sendMessage($message);
                $feature = 'chat:message';
                break;
                
            case 'get_history':
                if(!$session_id) {
                    throw new Exception('Session ID required');
                }
                $assistant = new ChatAssistant($user_id, $session_id);
                $result = [
                    'history' => $assistant->getConversationHistory(),
                    'session_id' => $session_id
                ];
                $feature = 'chat:get_history';
                break;
                
            case 'get_summary':
                if(!$session_id) {
                    throw new Exception('Session ID required');
                }
                $assistant = new ChatAssistant($user_id, $session_id);
                $result = $assistant->getSessionSummary();
                $feature = 'chat:summary';
                break;
                
            case 'get_suggestions':
                if(!$session_id) {
                    throw new Exception('Session ID required');
                }
                $assistant = new ChatAssistant($user_id, $session_id);
                $result = [
                    'suggestions' => $assistant->getSuggestions(),
                    'session_id' => $session_id
                ];
                $feature = 'chat:suggestions';
                break;
                
            case 'end_session':
                if(!$session_id) {
                    throw new Exception('Session ID required');
                }
                $assistant = new ChatAssistant($user_id, $session_id);
                $assistant->endSession();
                $result = ['status' => 'closed', 'session_id' => $session_id];
                $feature = 'chat:end_session';
                break;
                
            default:
                throw new Exception('Unknown chat action');
        }
        
        $response_time = round((microtime(true) - $start_time) * 1000);
        $tokens = !empty($message) ? ceil(strlen($message) / 4) : 10;
        $analytics->logUsage($feature, $tokens, $response_time);
        
        echo json_encode([
            'success' => true,
            'data' => $result,
            'response_time' => $response_time . 'ms',
            'action' => $action
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
