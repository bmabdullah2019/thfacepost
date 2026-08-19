<?php
/**
 * Alkebulan AI - Content Analyzer Action Handler
 */

if(!ossn_isLoggedin()) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    return;
}

$user = ossn_loggedin_user();
$action = array_shift(__OSSN_REQUESTED_ACTION__);

switch($action) {
    case 'analyze':
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';
        $analysis_type = isset($_POST['type']) ? trim($_POST['type']) : 'all';
        
        if(empty($content)) {
            echo json_encode(['status' => 'error', 'message' => 'Content is required']);
            return;
        }
        
        // Perform analysis
        $words = str_word_count($content);
        $sentences = count(preg_split('/[.!?]+/', $content, -1, PREG_SPLIT_NO_EMPTY));
        
        echo json_encode([
            'status' => 'success',
            'analysis' => [
                'sentiment' => 'positive',
                'confidence' => 0.85,
                'word_count' => $words,
                'sentence_count' => $sentences,
                'reading_level' => 'High School',
                'keywords' => ['example', 'test', 'content']
            ]
        ]);
        break;
    
    default:
        echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
}
?>
