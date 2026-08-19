<?php
/**
 * Alkebulan AI - Audio Analysis Action Handler
 * Handles all audio analysis API requests
 */

// Check user is logged in
if(!ossn_isLoggedin()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    return;
}

$user = ossn_loggedin_user();
$action = array_shift(__OSSN_REQUESTED_ACTION__);

switch($action) {
    case 'analyze':
        handleAudioAnalysis($user);
        break;
    case 'history':
        handleAnalysisHistory($user);
        break;
    case 'details':
        handleAnalysisDetails($user);
        break;
    case 'transcribe':
        handleTranscription($user);
        break;
    case 'delete':
        handleDeleteAnalysis($user);
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Unknown action: ' . $action
        ]);
}

/**
 * Handle audio analysis
 */
function handleAudioAnalysis($user) {
    if(!isset($_FILES['audio']) && !isset($_POST['audio_path'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Audio file or path is required'
        ]);
        return;
    }
    
    $audio_processor = new AudioProcessor($user->guid);
    
    $audio_path = $_POST['audio_path'] ?? null;
    
    // Handle file upload
    if(isset($_FILES['audio'])) {
        $upload_dir = dirname(__FILE__) . '/../uploads/audio/';
        
        // Create directory if needed
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['audio']['name']);
        $audio_path = $upload_dir . $file_name;
        
        if(!move_uploaded_file($_FILES['audio']['tmp_name'], $audio_path)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to upload audio'
            ]);
            return;
        }
    }
    
    // Get analysis options
    $options = [
        'detect_speech' => $_POST['detect_speech'] ?? true,
        'detect_music' => $_POST['detect_music'] ?? true,
        'analyze_emotions' => $_POST['analyze_emotions'] ?? true,
        'analyze_noise' => $_POST['analyze_noise'] ?? true,
        'transcribe' => $_POST['transcribe'] ?? false
    ];
    
    // Analyze audio
    $result = $audio_processor->analyzeAudio($audio_path, $options);
    
    echo json_encode($result);
}

/**
 * Handle analysis history
 */
function handleAnalysisHistory($user) {
    $audio_processor = new AudioProcessor($user->guid);
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    
    $history = $audio_processor->getAnalysisHistory($limit);
    
    echo json_encode([
        'status' => 'success',
        'history' => $history,
        'count' => count($history)
    ]);
}

/**
 * Handle analysis details
 */
function handleAnalysisDetails($user) {
    if(!isset($_GET['id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Analysis ID is required'
        ]);
        return;
    }
    
    $db = ossn_get_database();
    
    $analysis = $db->select('alkebulan_audio_analysis')
        ->where('id', (int)$_GET['id'])
        ->where('user_id', $user->guid)
        ->execute()
        ->fetch_object();
    
    if($analysis) {
        $analysis->analysis_data = json_decode($analysis->analysis_data, true);
        echo json_encode([
            'status' => 'success',
            'analysis' => $analysis
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Analysis not found'
        ]);
    }
}

/**
 * Handle transcription
 */
function handleTranscription($user) {
    if(!isset($_GET['id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Analysis ID is required'
        ]);
        return;
    }
    
    $db = ossn_get_database();
    
    $analysis = $db->select('alkebulan_audio_analysis')
        ->where('id', (int)$_GET['id'])
        ->where('user_id', $user->guid)
        ->execute()
        ->fetch_object();
    
    if($analysis) {
        $data = json_decode($analysis->analysis_data, true);
        echo json_encode([
            'status' => 'success',
            'transcription' => $data['transcription'] ?? null
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Analysis not found'
        ]);
    }
}

/**
 * Handle delete analysis
 */
function handleDeleteAnalysis($user) {
    if(!isset($_POST['id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Analysis ID is required'
        ]);
        return;
    }
    
    $db = ossn_get_database();
    
    $result = $db->delete('alkebulan_audio_analysis')
        ->where('id', (int)$_POST['id'])
        ->where('user_id', $user->guid)
        ->execute();
    
    if($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Analysis deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to delete analysis'
        ]);
    }
}
?>
