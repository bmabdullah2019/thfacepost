<?php
/**
 * Alkebulan AI - Text Generation API Handler v3.0
 * Complete REST API for text generation with full CRUD operations
 */

if(!ossn_isLoggedin()) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    return;
}

$user = ossn_loggedin_user();
$generator = new TextGenerator($user->guid);
$action = array_shift(__OSSN_REQUESTED_ACTION__) ?: 'generate';

switch($action) {
    case 'generate':
        handleTextGeneration($generator, $user);
        break;
    case 'variations':
        handleTextVariations($generator, $user);
        break;
    case 'by_type':
        handleGenerateByType($generator, $user);
        break;
    case 'apply_tone':
        handleApplyTone($generator, $user);
        break;
    case 'gallery':
        handleGetGallery($generator, $user);
        break;
    case 'search':
        handleSearchTexts($generator, $user);
        break;
    case 'get':
        handleGetText($generator, $user);
        break;
    case 'delete':
        handleDeleteText($generator, $user);
        break;
    case 'rate':
        handleRateText($generator, $user);
        break;
    case 'stats':
        handleGetStatistics($generator, $user);
        break;
    case 'trending':
        handleGetTrending($generator, $user);
        break;
    case 'types':
        echo json_encode($generator->getContentTypes());
        break;
    case 'tones':
        echo json_encode($generator->getSupportedTones());
        break;
    case 'help':
        handleGetHelp();
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid action: ' . htmlspecialchars($action),
            'available_actions' => ['generate', 'variations', 'by_type', 'apply_tone', 'gallery', 'search', 'get', 'delete', 'rate', 'stats', 'trending', 'types', 'tones', 'help']
        ]);
}

function handleTextGeneration($generator, $user) {
    $prompt = sanitize($_REQUEST['prompt'] ?? '');
    if(empty($prompt)) {
        echo json_encode(['status' => 'error', 'message' => 'Prompt required']);
        return;
    }
    
    $result = $generator->generateText($prompt, [
        'tone' => sanitize($_REQUEST['tone'] ?? 'neutral'),
        'type' => sanitize($_REQUEST['type'] ?? 'article'),
        'length' => (int)($_REQUEST['length'] ?? 500),
        'language' => sanitize($_REQUEST['language'] ?? 'en')
    ]);
    echo json_encode($result);
}

function handleTextVariations($generator, $user) {
    $text_id = (int)($_REQUEST['text_id'] ?? 0);
    $count = min(max(1, (int)($_REQUEST['count'] ?? 3)), 10);
    
    if(empty($text_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Text ID required']);
        return;
    }
    
    $text = $generator->getTextById($text_id);
    if(!$text) {
        echo json_encode(['status' => 'error', 'message' => 'Text not found']);
        return;
    }
    
    $variations = [];
    for($i = 0; $i < $count; $i++) {
        $result = $generator->generateText($text['content'], [
            'tone' => $text['tone'],
            'type' => $text['type'],
            'length' => $text['word_count']
        ]);
        if($result['status'] === 'success') {
            $variations[] = $result;
        }
    }
    
    echo json_encode(['status' => 'success', 'original_id' => $text_id, 'variations_count' => count($variations), 'variations' => $variations]);
}

function handleGenerateByType($generator, $user) {
    $type = sanitize($_REQUEST['type'] ?? 'article');
    $title = sanitize($_REQUEST['title'] ?? '');
    $length = (int)($_REQUEST['length'] ?? 500);
    
    if(empty($title)) {
        echo json_encode(['status' => 'error', 'message' => 'Title required']);
        return;
    }
    
    $result = $generator->generateByType($type, $title, $length);
    echo json_encode($result);
}

function handleApplyTone($generator, $user) {
    $text_id = (int)($_REQUEST['text_id'] ?? 0);
    $tone = sanitize($_REQUEST['tone'] ?? 'neutral');
    
    if(empty($text_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Text ID required']);
        return;
    }
    
    $text = $generator->getTextById($text_id);
    if(!$text) {
        echo json_encode(['status' => 'error', 'message' => 'Text not found']);
        return;
    }
    
    $result = $generator->applyTone($text['content'], $tone);
    echo json_encode($result);
}

function handleGetGallery($generator, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    $offset = max(0, (int)($_REQUEST['offset'] ?? 0));
    
    $result = $generator->getGallery($limit, $offset);
    echo json_encode($result);
}

function handleSearchTexts($generator, $user) {
    $query = sanitize($_REQUEST['query'] ?? '');
    $type = sanitize($_REQUEST['type'] ?? '');
    $tone = sanitize($_REQUEST['tone'] ?? '');
    
    $result = $generator->searchTexts($query, $type, $tone);
    echo json_encode($result);
}

function handleGetText($generator, $user) {
    $text_id = (int)($_REQUEST['text_id'] ?? 0);
    if(empty($text_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Text ID required']);
        return;
    }
    
    $text = $generator->getTextById($text_id);
    echo json_encode($text ? ['status' => 'success', 'text' => $text] : ['status' => 'error', 'message' => 'Not found']);
}

function handleDeleteText($generator, $user) {
    $text_id = (int)($_REQUEST['text_id'] ?? 0);
    if(empty($text_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Text ID required']);
        return;
    }
    
    $success = $generator->deleteText($text_id);
    echo json_encode(['status' => $success ? 'success' : 'error', 'message' => $success ? 'Deleted' : 'Failed']);
}

function handleRateText($generator, $user) {
    $text_id = (int)($_REQUEST['text_id'] ?? 0);
    $rating = (int)($_REQUEST['rating'] ?? 0);
    
    if(empty($text_id) || $rating < 1 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Valid text ID and rating 1-5 required']);
        return;
    }
    
    $success = $generator->rateText($text_id, $rating);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleGetStatistics($generator, $user) {
    echo json_encode($generator->getStatistics());
}

function handleGetTrending($generator, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 10), 50);
    echo json_encode($generator->getTrendingPrompts($limit));
}

function handleGetHelp() {
    echo json_encode(['status' => 'success', 'endpoints' => [
        ['method' => 'POST', 'path' => '/text_generate/generate', 'description' => 'Generate text'],
        ['method' => 'POST', 'path' => '/text_generate/variations', 'description' => 'Create variations'],
        ['method' => 'POST', 'path' => '/text_generate/by_type', 'description' => 'Generate by type'],
        ['method' => 'POST', 'path' => '/text_generate/apply_tone', 'description' => 'Apply tone'],
        ['method' => 'GET', 'path' => '/text_generate/gallery', 'description' => 'Get gallery'],
        ['method' => 'GET', 'path' => '/text_generate/search', 'description' => 'Search texts'],
        ['method' => 'GET', 'path' => '/text_generate/stats', 'description' => 'Get statistics'],
        ['method' => 'GET', 'path' => '/text_generate/types', 'description' => 'Content types'],
        ['method' => 'GET', 'path' => '/text_generate/tones', 'description' => 'Tone types']
    ]]);
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>
