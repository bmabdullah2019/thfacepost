<?php
/**
 * Alkebulan AI - Video Analysis Action Handler
 * Handles all video analysis API requests
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
        handleVideoAnalysis($user);
        break;
    case 'history':
        handleAnalysisHistory($user);
        break;
    case 'details':
        handleAnalysisDetails($user);
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
 * Handle video analysis
 */
function handleVideoAnalysis($user) {
    if(!isset($_FILES['video']) && !isset($_POST['video_path'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Video file or path is required'
        ]);
        return;
    }
    
    $video_analyzer = new VideoAnalyzer($user->guid);
    
    $video_path = $_POST['video_path'] ?? null;
    
    // Handle file upload
    if(isset($_FILES['video'])) {
        $upload_dir = dirname(__FILE__) . '/../uploads/videos/';
        
        // Create directory if needed
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['video']['name']);
        $video_path = $upload_dir . $file_name;
        
        if(!move_uploaded_file($_FILES['video']['tmp_name'], $video_path)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to upload video'
            ]);
            return;
        }
    }
    
    // Get analysis options
    $options = [
        'extract_scenes' => $_POST['extract_scenes'] ?? true,
        'detect_objects' => $_POST['detect_objects'] ?? true,
        'count_faces' => $_POST['count_faces'] ?? true,
        'extract_text' => $_POST['extract_text'] ?? true
    ];
    
    // Analyze video
    $result = $video_analyzer->analyzeVideo($video_path, $options);
    
    echo json_encode($result);
}

/**
 * Handle analysis history
 */
function handleAnalysisHistory($user) {
    $video_analyzer = new VideoAnalyzer($user->guid);
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    
    $history = $video_analyzer->getAnalysisHistory($limit);
    
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
    
    $analysis = $db->select('alkebulan_video_analysis')
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
    
    $result = $db->delete('alkebulan_video_analysis')
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
