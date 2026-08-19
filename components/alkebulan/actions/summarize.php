<?php
/**
 * Alkebulan AI - Summarization API v3.0
 * Comprehensive REST API for advanced content summarization with full CRUD operations
 */

if(!ossn_isLoggedin()) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    return;
}

$user = ossn_loggedin_user();
$generator = new SummaryGenerator($user->guid);
$action = array_shift(__OSSN_REQUESTED_ACTION__) ?: 'summarize';

switch($action) {
    case 'summarize':
        handleSummarization($generator, $user);
        break;
    case 'by_type':
        handleSummarizeByType($generator, $user);
        break;
    case 'gallery':
        handleGetGallery($generator, $user);
        break;
    case 'search':
        handleSearchSummaries($generator, $user);
        break;
    case 'get':
        handleGetSummary($generator, $user);
        break;
    case 'delete':
        handleDeleteSummary($generator, $user);
        break;
    case 'update':
        handleUpdateSummary($generator, $user);
        break;
    case 'rate':
        handleRateSummary($generator, $user);
        break;
    case 'favorite':
        handleFavorite($generator, $user);
        break;
    case 'favorites':
        handleGetFavorites($generator, $user);
        break;
    case 'stats':
        handleGetStatistics($generator, $user);
        break;
    case 'trending':
        handleGetTrending($generator, $user);
        break;
    case 'analyze':
        handleAnalyzeSummary($generator, $user);
        break;
    case 'export':
        handleExport($generator, $user);
        break;
    case 'bulk':
        handleBulkOperation($generator, $user);
        break;
    case 'types':
        echo json_encode($generator->getSummaryTypes());
        break;
    case 'help':
        handleGetHelp();
        break;
    case 'settings':
        handleSettings($generator, $user);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

// ===== SUMMARIZATION ENDPOINTS =====

function handleSummarization($generator, $user) {
    $content = sanitize($_REQUEST['content'] ?? '');
    
    if(empty($content) && isset($_FILES['file'])) {
        $content = file_get_contents($_FILES['file']['tmp_name']);
    }
    
    if(empty($content)) {
        echo json_encode(['status' => 'error', 'message' => 'Content required']);
        return;
    }
    
    $options = [
        'type' => sanitize($_REQUEST['type'] ?? 'extractive'),
        'ratio' => (float)($_REQUEST['ratio'] ?? 0.3),
        'language' => sanitize($_REQUEST['language'] ?? 'en'),
        'quality' => sanitize($_REQUEST['quality'] ?? 'high')
    ];
    
    $result = $generator->generateSummary($content, $options);
    echo json_encode($result);
}

function handleSummarizeByType($generator, $user) {
    $content = sanitize($_REQUEST['content'] ?? '');
    $type = sanitize($_REQUEST['type'] ?? 'extractive');
    
    if(empty($content)) {
        echo json_encode(['status' => 'error', 'message' => 'Content required']);
        return;
    }
    
    $result = $generator->generateByType($content, $type);
    echo json_encode($result);
}

// ===== GALLERY & SEARCH =====

function handleGetGallery($generator, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    $offset = max(0, (int)($_REQUEST['offset'] ?? 0));
    $sort = sanitize($_REQUEST['sort'] ?? 'created_desc');
    
    $cache_key = "summary_gallery_{$user->guid}_{$offset}_{$limit}_{$sort}";
    $result = ossn_cache_get($cache_key, 'alkebulan');
    
    if(!$result) {
        $result = [
            'status' => 'success',
            'data' => $generator->getGallery($limit, $offset, $sort),
            'total' => $generator->getSummaryCount(),
            'limit' => $limit,
            'offset' => $offset
        ];
        ossn_cache_save($cache_key, $result, 3600, 'alkebulan');
    }
    echo json_encode($result);
}

function handleSearchSummaries($generator, $user) {
    $query = sanitize($_REQUEST['query'] ?? '');
    $type = sanitize($_REQUEST['type'] ?? '');
    $sort = sanitize($_REQUEST['sort'] ?? 'relevance');
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    
    if(empty($query)) {
        echo json_encode(['status' => 'error', 'message' => 'Search query required']);
        return;
    }
    
    $filters = [
        'type' => $type,
        'sort' => $sort,
        'limit' => $limit
    ];
    
    $results = $generator->searchSummaries($query, $filters);
    echo json_encode([
        'status' => 'success',
        'query' => $query,
        'results' => $results,
        'count' => count($results)
    ]);
}

// ===== CRUD ENDPOINTS =====

function handleGetSummary($generator, $user) {
    $summary_id = (int)($_REQUEST['summary_id'] ?? 0);
    if(empty($summary_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Summary ID required']);
        return;
    }
    
    $summary = $generator->getSummaryById($summary_id);
    if($summary && ($summary['user_id'] == $user->guid || $summary['is_public'])) {
        echo json_encode(['status' => 'success', 'summary' => $summary]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Not found or unauthorized']);
    }
}

function handleUpdateSummary($generator, $user) {
    $summary_id = (int)($_REQUEST['summary_id'] ?? 0);
    $summary_text = sanitize($_REQUEST['summary'] ?? '');
    
    if(empty($summary_id) || empty($summary_text)) {
        echo json_encode(['status' => 'error', 'message' => 'Summary ID and text required']);
        return;
    }
    
    $success = $generator->updateSummary($summary_id, $summary_text);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleDeleteSummary($generator, $user) {
    $summary_id = (int)($_REQUEST['summary_id'] ?? 0);
    if(empty($summary_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Summary ID required']);
        return;
    }
    
    $success = $generator->deleteSummary($summary_id);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

// ===== RATING & FAVORITES =====

function handleRateSummary($generator, $user) {
    $summary_id = (int)($_REQUEST['summary_id'] ?? 0);
    $rating = (int)($_REQUEST['rating'] ?? 0);
    
    if(empty($summary_id) || $rating < 1 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Valid summary ID and rating 1-5 required']);
        return;
    }
    
    $success = $generator->rateSummary($summary_id, $rating);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleFavorite($generator, $user) {
    $summary_id = (int)($_REQUEST['summary_id'] ?? 0);
    if(empty($summary_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Summary ID required']);
        return;
    }
    
    $success = $generator->toggleFavorite($summary_id);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleGetFavorites($generator, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    $offset = max(0, (int)($_REQUEST['offset'] ?? 0));
    
    $favorites = $generator->getFavorites($limit, $offset);
    echo json_encode([
        'status' => 'success',
        'favorites' => $favorites,
        'count' => count($favorites)
    ]);
}

// ===== ANALYTICS & TRENDING =====

function handleGetStatistics($generator, $user) {
    $timeframe = sanitize($_REQUEST['timeframe'] ?? '30');
    $stats = $generator->getStatistics($timeframe);
    echo json_encode(['status' => 'success', 'stats' => $stats]);
}

function handleGetTrending($generator, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 10), 50);
    $timeframe = sanitize($_REQUEST['timeframe'] ?? '7');
    
    $trending = $generator->getTrendingTopics($limit, $timeframe);
    echo json_encode(['status' => 'success', 'trending' => $trending]);
}

function handleAnalyzeSummary($generator, $user) {
    $summary_id = (int)($_REQUEST['summary_id'] ?? 0);
    if(empty($summary_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Summary ID required']);
        return;
    }
    
    $analysis = $generator->analyzeSummary($summary_id);
    echo json_encode(['status' => 'success', 'analysis' => $analysis]);
}

// ===== EXPORT & BULK =====

function handleExport($generator, $user) {
    $format = sanitize($_REQUEST['format'] ?? 'json');
    $ids = isset($_REQUEST['ids']) ? array_map('intval', (array)$_REQUEST['ids']) : [];
    
    if(empty($ids)) {
        $ids = $generator->getAllSummaryIds();
    }
    
    $export_data = $generator->exportSummaries($ids, $format);
    echo json_encode(['status' => 'success', 'data' => $export_data]);
}

function handleBulkOperation($generator, $user) {
    $operation = sanitize($_REQUEST['operation'] ?? '');
    $ids = isset($_REQUEST['ids']) ? array_map('intval', (array)$_REQUEST['ids']) : [];
    
    if(empty($operation) || empty($ids)) {
        echo json_encode(['status' => 'error', 'message' => 'Operation and IDs required']);
        return;
    }
    
    $result = ['status' => 'success', 'processed' => 0];
    foreach($ids as $id) {
        switch($operation) {
            case 'delete':
                if($generator->deleteSummary($id)) $result['processed']++;
                break;
            case 'make_public':
                if($generator->setSummaryPublic($id, true)) $result['processed']++;
                break;
            case 'make_private':
                if($generator->setSummaryPublic($id, false)) $result['processed']++;
                break;
        }
    }
    echo json_encode($result);
}

// ===== HELP & SETTINGS =====

function handleGetHelp() {
    $endpoints = [
        ['method' => 'POST', 'path' => '/summarize/summarize', 'description' => 'Create summary from content'],
        ['method' => 'POST', 'path' => '/summarize/by_type', 'description' => 'Summarize by type'],
        ['method' => 'GET', 'path' => '/summarize/gallery', 'description' => 'Get summaries gallery'],
        ['method' => 'GET', 'path' => '/summarize/search', 'description' => 'Search summaries'],
        ['method' => 'GET', 'path' => '/summarize/get', 'description' => 'Get summary by ID'],
        ['method' => 'POST', 'path' => '/summarize/update', 'description' => 'Update summary'],
        ['method' => 'POST', 'path' => '/summarize/delete', 'description' => 'Delete summary'],
        ['method' => 'POST', 'path' => '/summarize/rate', 'description' => 'Rate summary quality'],
        ['method' => 'POST', 'path' => '/summarize/favorite', 'description' => 'Toggle favorite'],
        ['method' => 'GET', 'path' => '/summarize/favorites', 'description' => 'Get favorite summaries'],
        ['method' => 'GET', 'path' => '/summarize/stats', 'description' => 'Get user statistics'],
        ['method' => 'GET', 'path' => '/summarize/trending', 'description' => 'Get trending topics'],
        ['method' => 'GET', 'path' => '/summarize/analyze', 'description' => 'Analyze summary quality'],
        ['method' => 'POST', 'path' => '/summarize/export', 'description' => 'Export summaries'],
        ['method' => 'POST', 'path' => '/summarize/bulk', 'description' => 'Bulk operations'],
        ['method' => 'GET', 'path' => '/summarize/types', 'description' => 'Summary types'],
        ['method' => 'GET', 'path' => '/summarize/settings', 'description' => 'User settings']
    ];
    echo json_encode(['status' => 'success', 'endpoints' => $endpoints]);
}

function handleSettings($generator, $user) {
    if($_REQUEST['method'] == 'get') {
        $settings = $generator->getUserSettings();
        echo json_encode(['status' => 'success', 'settings' => $settings]);
    } else if($_REQUEST['method'] == 'update') {
        $settings = [
            'default_type' => sanitize($_REQUEST['type'] ?? 'extractive'),
            'default_ratio' => (float)($_REQUEST['ratio'] ?? 0.3),
            'language' => sanitize($_REQUEST['language'] ?? 'en')
        ];
        $success = $generator->updateUserSettings($settings);
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>
