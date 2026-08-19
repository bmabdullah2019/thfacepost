<?php
/**
 * Alkebulan AI - Video Generation Action Handler
 * Handles all video-related API requests
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
    case 'generate':
        handleVideoGeneration($user);
        break;
    case 'gallery':
        handleVideoGallery($user);
        break;
    case 'stats':
        handleVideoStats($user);
        break;
    case 'download':
        handleVideoDownload($user);
        break;
    case 'delete':
        handleVideoDelete($user);
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Unknown action'
        ]);
}

function handleVideoGeneration($user) {
    $prompt = isset($_POST['prompt']) ? trim($_POST['prompt']) : '';
    $style = isset($_POST['style']) ? trim($_POST['style']) : 'cinematic';
    $duration = isset($_POST['duration']) ? intval($_POST['duration']) : 10;
    $fps = isset($_POST['fps']) ? intval($_POST['fps']) : 30;
    $quality = isset($_POST['quality']) ? trim($_POST['quality']) : '1080p';
    $with_music = isset($_POST['with_music']) ? intval($_POST['with_music']) : 1;
    
    if(empty($prompt)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Video prompt is required'
        ]);
        return;
    }
    
    // Store video generation record
    $db = ossn_get_database();
    $timestamp = time();
    
    $sql = "INSERT INTO alkebulan_videos 
            (user_id, prompt, style, duration, fps, quality, with_music, status, created) 
            VALUES 
            ({$user->guid}, '{$db->sanitize($prompt)}', '{$db->sanitize($style)}', 
             {$duration}, {$fps}, '{$db->sanitize($quality)}', {$with_music}, 'processing', {$timestamp})";
    
    if($db->query($sql)) {
        $video_id = $db->lastInsertId();
        echo json_encode([
            'status' => 'success',
            'message' => 'Video generation started',
            'video_id' => $video_id,
            'prompt' => $prompt,
            'style' => $style,
            'duration' => $duration,
            'quality' => $quality
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to create video record'
        ]);
    }
}

function handleVideoGallery($user) {
    $db = ossn_get_database();
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    
    $sql = "SELECT id, prompt, style, duration, quality, status, created 
            FROM alkebulan_videos 
            WHERE user_id = {$user->guid} 
            ORDER BY created DESC 
            LIMIT {$limit} OFFSET {$offset}";
    
    $videos = $db->fetch($sql);
    
    echo json_encode([
        'status' => 'success',
        'videos' => $videos ? $videos : [],
        'count' => count($videos ? $videos : [])
    ]);
}

function handleVideoStats($user) {
    $db = ossn_get_database();
    
    $sql = "SELECT 
            COUNT(*) as total_videos,
            SUM(duration) as total_duration,
            AVG(duration) as avg_duration,
            COUNT(DISTINCT style) as unique_styles
            FROM alkebulan_videos 
            WHERE user_id = {$user->guid}";
    
    $result = $db->fetch($sql);
    $stats = $result ? $result[0] : [];
    
    echo json_encode([
        'status' => 'success',
        'stats' => $stats
    ]);
}

function handleVideoDownload($user) {
    $video_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if(!$video_id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Video ID is required'
        ]);
        return;
    }
    
    $db = ossn_get_database();
    $sql = "SELECT id, prompt FROM alkebulan_videos 
            WHERE id = {$video_id} AND user_id = {$user->guid}";
    
    $video = $db->fetch($sql);
    
    if($video) {
        // Update download count
        $db->query("UPDATE alkebulan_videos SET downloads = downloads + 1 WHERE id = {$video_id}");
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Video download initiated',
            'video_id' => $video_id
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Video not found'
        ]);
    }
}

function handleVideoDelete($user) {
    $video_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if(!$video_id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Video ID is required'
        ]);
        return;
    }
    
    $db = ossn_get_database();
    $sql = "DELETE FROM alkebulan_videos 
            WHERE id = {$video_id} AND user_id = {$user->guid}";
    
    if($db->query($sql)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Video deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to delete video'
        ]);
    }
}

?>
