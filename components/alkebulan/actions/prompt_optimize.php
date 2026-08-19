<?php
/**
 * Alkebulan AI - Prompt Optimization API v3.0
 * Comprehensive REST API for advanced prompt optimization with full CRUD operations
 */

if(!ossn_isLoggedin()) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    return;
}

$user = ossn_loggedin_user();
$optimizer = new PromptOptimizer($user->guid);
$action = array_shift(__OSSN_REQUESTED_ACTION__) ?: 'optimize';

switch($action) {
    case 'optimize':
        handlePromptOptimization($optimizer, $user);
        break;
    case 'batch':
        handleBatchOptimization($optimizer, $user);
        break;
    case 'gallery':
        handleGetGallery($optimizer, $user);
        break;
    case 'search':
        handleSearchPrompts($optimizer, $user);
        break;
    case 'get':
        handleGetPrompt($optimizer, $user);
        break;
    case 'delete':
        handleDeletePrompt($optimizer, $user);
        break;
    case 'update':
        handleUpdatePrompt($optimizer, $user);
        break;
    case 'rate':
        handleRatePrompt($optimizer, $user);
        break;
    case 'favorite':
        handleFavorite($optimizer, $user);
        break;
    case 'favorites':
        handleGetFavorites($optimizer, $user);
        break;
    case 'compare':
        handleCompare($optimizer, $user);
        break;
    case 'stats':
        handleGetStatistics($optimizer, $user);
        break;
    case 'trending':
        handleGetTrending($optimizer, $user);
        break;
    case 'analyze':
        handleAnalyzePrompt($optimizer, $user);
        break;
    case 'export':
        handleExport($optimizer, $user);
        break;
    case 'bulk':
        handleBulkOperation($optimizer, $user);
        break;
    case 'techniques':
        echo json_encode($optimizer->getAvailableTechniques());
        break;
    case 'help':
        handleGetHelp();
        break;
    case 'settings':
        handleSettings($optimizer, $user);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

// ===== OPTIMIZATION ENDPOINTS =====

function handlePromptOptimization($optimizer, $user) {
    $prompt = sanitize($_REQUEST['prompt'] ?? '');
    if(empty($prompt)) {
        echo json_encode(['status' => 'error', 'message' => 'Prompt required']);
        return;
    }
    
    $techniques = isset($_REQUEST['techniques']) ? (array)$_REQUEST['techniques'] : ['all'];
    
    $options = [
        'level' => sanitize($_REQUEST['level'] ?? 'standard'),
        'techniques' => $techniques,
        'quality' => (int)($_REQUEST['quality'] ?? 5)
    ];
    
    $result = $optimizer->optimizePrompt($prompt, $options);
    echo json_encode($result);
}

function handleBatchOptimization($optimizer, $user) {
    $prompts = isset($_REQUEST['prompts']) ? (array)$_REQUEST['prompts'] : [];
    
    if(empty($prompts)) {
        echo json_encode(['status' => 'error', 'message' => 'Prompts array required']);
        return;
    }
    
    $results = [];
    foreach($prompts as $prompt) {
        $results[] = $optimizer->optimizePrompt(sanitize($prompt), []);
    }
    
    echo json_encode(['status' => 'success', 'results' => $results, 'count' => count($results)]);
}

// ===== GALLERY & SEARCH =====

function handleGetGallery($optimizer, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    $offset = max(0, (int)($_REQUEST['offset'] ?? 0));
    $sort = sanitize($_REQUEST['sort'] ?? 'created_desc');
    
    $cache_key = "prompt_gallery_{$user->guid}_{$offset}_{$limit}_{$sort}";
    $result = ossn_cache_get($cache_key, 'alkebulan');
    
    if(!$result) {
        $result = [
            'status' => 'success',
            'data' => $optimizer->getGallery($limit, $offset, $sort),
            'total' => $optimizer->getPromptCount(),
            'limit' => $limit,
            'offset' => $offset
        ];
        ossn_cache_save($cache_key, $result, 3600, 'alkebulan');
    }
    echo json_encode($result);
}

function handleSearchPrompts($optimizer, $user) {
    $query = sanitize($_REQUEST['query'] ?? '');
    $sort = sanitize($_REQUEST['sort'] ?? 'relevance');
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    
    if(empty($query)) {
        echo json_encode(['status' => 'error', 'message' => 'Search query required']);
        return;
    }
    
    $filters = ['sort' => $sort, 'limit' => $limit];
    
    $results = $optimizer->searchPrompts($query, $filters);
    echo json_encode([
        'status' => 'success',
        'query' => $query,
        'results' => $results,
        'count' => count($results)
    ]);
}

// ===== CRUD ENDPOINTS =====

function handleGetPrompt($optimizer, $user) {
    $prompt_id = (int)($_REQUEST['prompt_id'] ?? 0);
    if(empty($prompt_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Prompt ID required']);
        return;
    }
    
    $prompt = $optimizer->getPromptById($prompt_id);
    if($prompt && ($prompt['user_id'] == $user->guid || $prompt['is_public'])) {
        echo json_encode(['status' => 'success', 'prompt' => $prompt]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Not found or unauthorized']);
    }
}

function handleUpdatePrompt($optimizer, $user) {
    $prompt_id = (int)($_REQUEST['prompt_id'] ?? 0);
    $optimized_text = sanitize($_REQUEST['prompt'] ?? '');
    
    if(empty($prompt_id) || empty($optimized_text)) {
        echo json_encode(['status' => 'error', 'message' => 'Prompt ID and text required']);
        return;
    }
    
    $success = $optimizer->updatePrompt($prompt_id, $optimized_text);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleDeletePrompt($optimizer, $user) {
    $prompt_id = (int)($_REQUEST['prompt_id'] ?? 0);
    if(empty($prompt_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Prompt ID required']);
        return;
    }
    
    $success = $optimizer->deletePrompt($prompt_id);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

// ===== COMPARE & ANALYSIS =====

function handleCompare($optimizer, $user) {
    $original = sanitize($_REQUEST['original'] ?? '');
    $optimized = sanitize($_REQUEST['optimized'] ?? '');
    
    if(empty($original) || empty($optimized)) {
        echo json_encode(['status' => 'error', 'message' => 'Both prompts required']);
        return;
    }
    
    $comparison = $optimizer->comparePrompts($original, $optimized);
    echo json_encode(['status' => 'success', 'comparison' => $comparison]);
}

function handleAnalyzePrompt($optimizer, $user) {
    $prompt_id = (int)($_REQUEST['prompt_id'] ?? 0);
    if(empty($prompt_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Prompt ID required']);
        return;
    }
    
    $analysis = $optimizer->analyzePrompt($prompt_id);
    echo json_encode(['status' => 'success', 'analysis' => $analysis]);
}

// ===== RATING & FAVORITES =====

function handleRatePrompt($optimizer, $user) {
    $prompt_id = (int)($_REQUEST['prompt_id'] ?? 0);
    $rating = (int)($_REQUEST['rating'] ?? 0);
    
    if(empty($prompt_id) || $rating < 1 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Valid prompt ID and rating 1-5 required']);
        return;
    }
    
    $success = $optimizer->ratePrompt($prompt_id, $rating);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleFavorite($optimizer, $user) {
    $prompt_id = (int)($_REQUEST['prompt_id'] ?? 0);
    if(empty($prompt_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Prompt ID required']);
        return;
    }
    
    $success = $optimizer->toggleFavorite($prompt_id);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleGetFavorites($optimizer, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    $offset = max(0, (int)($_REQUEST['offset'] ?? 0));
    
    $favorites = $optimizer->getFavorites($limit, $offset);
    echo json_encode([
        'status' => 'success',
        'favorites' => $favorites,
        'count' => count($favorites)
    ]);
}

// ===== ANALYTICS & TRENDING =====

function handleGetStatistics($optimizer, $user) {
    $timeframe = sanitize($_REQUEST['timeframe'] ?? '30');
    $stats = $optimizer->getStatistics($timeframe);
    echo json_encode(['status' => 'success', 'stats' => $stats]);
}

function handleGetTrending($optimizer, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 10), 50);
    $timeframe = sanitize($_REQUEST['timeframe'] ?? '7');
    
    $trending = $optimizer->getTrendingPrompts($limit, $timeframe);
    echo json_encode(['status' => 'success', 'trending' => $trending]);
}

// ===== EXPORT & BULK =====

function handleExport($optimizer, $user) {
    $format = sanitize($_REQUEST['format'] ?? 'json');
    $ids = isset($_REQUEST['ids']) ? array_map('intval', (array)$_REQUEST['ids']) : [];
    
    if(empty($ids)) {
        $ids = $optimizer->getAllPromptIds();
    }
    
    $export_data = $optimizer->exportPrompts($ids, $format);
    echo json_encode(['status' => 'success', 'data' => $export_data]);
}

function handleBulkOperation($optimizer, $user) {
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
                if($optimizer->deletePrompt($id)) $result['processed']++;
                break;
            case 'make_public':
                if($optimizer->setPromptPublic($id, true)) $result['processed']++;
                break;
            case 'make_private':
                if($optimizer->setPromptPublic($id, false)) $result['processed']++;
                break;
        }
    }
    echo json_encode($result);
}

// ===== HELP & SETTINGS =====

function handleGetHelp() {
    $endpoints = [
        ['method' => 'POST', 'path' => '/prompt_optimize/optimize', 'description' => 'Optimize single prompt'],
        ['method' => 'POST', 'path' => '/prompt_optimize/batch', 'description' => 'Optimize multiple prompts'],
        ['method' => 'GET', 'path' => '/prompt_optimize/gallery', 'description' => 'Get optimization gallery'],
        ['method' => 'GET', 'path' => '/prompt_optimize/search', 'description' => 'Search optimizations'],
        ['method' => 'GET', 'path' => '/prompt_optimize/get', 'description' => 'Get optimization by ID'],
        ['method' => 'POST', 'path' => '/prompt_optimize/update', 'description' => 'Update optimization'],
        ['method' => 'POST', 'path' => '/prompt_optimize/delete', 'description' => 'Delete optimization'],
        ['method' => 'POST', 'path' => '/prompt_optimize/compare', 'description' => 'Compare prompts'],
        ['method' => 'GET', 'path' => '/prompt_optimize/analyze', 'description' => 'Analyze prompt quality'],
        ['method' => 'POST', 'path' => '/prompt_optimize/rate', 'description' => 'Rate optimization'],
        ['method' => 'POST', 'path' => '/prompt_optimize/favorite', 'description' => 'Toggle favorite'],
        ['method' => 'GET', 'path' => '/prompt_optimize/favorites', 'description' => 'Get favorite prompts'],
        ['method' => 'GET', 'path' => '/prompt_optimize/stats', 'description' => 'Get user statistics'],
        ['method' => 'GET', 'path' => '/prompt_optimize/trending', 'description' => 'Get trending techniques'],
        ['method' => 'POST', 'path' => '/prompt_optimize/export', 'description' => 'Export optimizations'],
        ['method' => 'POST', 'path' => '/prompt_optimize/bulk', 'description' => 'Bulk operations'],
        ['method' => 'GET', 'path' => '/prompt_optimize/techniques', 'description' => 'Available techniques'],
        ['method' => 'GET', 'path' => '/prompt_optimize/settings', 'description' => 'User settings']
    ];
    echo json_encode(['status' => 'success', 'endpoints' => $endpoints]);
}

function handleSettings($optimizer, $user) {
    if($_REQUEST['method'] == 'get') {
        $settings = $optimizer->getUserSettings();
        echo json_encode(['status' => 'success', 'settings' => $settings]);
    } else if($_REQUEST['method'] == 'update') {
        $settings = [
            'default_level' => sanitize($_REQUEST['level'] ?? 'standard'),
            'auto_compare' => (bool)($_REQUEST['auto_compare'] ?? false)
        ];
        $success = $optimizer->updateUserSettings($settings);
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>
