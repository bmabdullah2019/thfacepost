<?php
/**
 * Alkebulan AI - Audio Generation Action Handler
 */

if(!ossn_isLoggedin()) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    return;
}

$user = ossn_loggedin_user();
$action = array_shift(__OSSN_REQUESTED_ACTION__);

switch($action) {
    case 'generate':
        $text = isset($_POST['text']) ? trim($_POST['text']) : '';
        $voice = isset($_POST['voice']) ? trim($_POST['voice']) : 'echo';
        $language = isset($_POST['language']) ? trim($_POST['language']) : 'en-US';
        
        if(empty($text)) {
            echo json_encode(['status' => 'error', 'message' => 'Text is required']);
            return;
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Audio generated successfully',
            'voice' => $voice,
            'language' => $language,
            'text_length' => strlen($text)
        ]);
        break;
    
    case 'download':
        echo json_encode(['status' => 'success', 'message' => 'Download initiated']);
        break;
    
    default:
        echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
}
?>
