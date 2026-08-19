<?php
/**
 * Alkebulan AI - Code Generation API v3.0
 * Comprehensive REST API for advanced code generation with full CRUD operations
 */

if(!ossn_isLoggedin()) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    return;
}

$user = ossn_loggedin_user();
$generator = new CodeGenerator($user->guid);
$action = array_shift(__OSSN_REQUESTED_ACTION__) ?: 'generate';

switch($action) {
    case 'generate':
        handleCodeGeneration($generator, $user);
        break;
    case 'by_type':
        handleGenerateByType($generator, $user);
        break;
    case 'gallery':
        handleGetGallery($generator, $user);
        break;
    case 'search':
        handleSearchCode($generator, $user);
        break;
    case 'get':
        handleGetCode($generator, $user);
        break;
    case 'delete':
        handleDeleteCode($generator, $user);
        break;
    case 'update':
        handleUpdateCode($generator, $user);
        break;
    case 'rate':
        handleRateCode($generator, $user);
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
        handleAnalyzeCode($generator, $user);
        break;
    case 'export':
        handleExport($generator, $user);
        break;
    case 'bulk':
        handleBulkOperation($generator, $user);
        break;
    case 'languages':
        echo json_encode($generator->getSupportedLanguages());
        break;
    case 'types':
        echo json_encode($generator->getCodeTypes());
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

// ===== GENERATION ENDPOINTS =====

function handleCodeGeneration($generator, $user) {
    $description = sanitize($_REQUEST['description'] ?? '');
    if(empty($description)) {
        echo json_encode(['status' => 'error', 'message' => 'Description required']);
        return;
    }
    
    $options = [
        'language' => sanitize($_REQUEST['language'] ?? 'php'),
        'type' => sanitize($_REQUEST['type'] ?? 'function'),
        'docs' => (bool)($_REQUEST['docs'] ?? true),
        'tests' => (bool)($_REQUEST['tests'] ?? false),
        'optimize' => (bool)($_REQUEST['optimize'] ?? true),
        'quality' => sanitize($_REQUEST['quality'] ?? 'high')
    ];
    
    $result = $generator->generateCode($description, $options);
    echo json_encode($result);
}

function handleGenerateByType($generator, $user) {
    $language = sanitize($_REQUEST['language'] ?? 'php');
    $type = sanitize($_REQUEST['type'] ?? 'function');
    $description = sanitize($_REQUEST['description'] ?? '');
    
    if(empty($description)) {
        echo json_encode(['status' => 'error', 'message' => 'Description required']);
        return;
    }
    
    $result = $generator->generateByType($language, $type, $description);
    echo json_encode($result);
}

// ===== GALLERY & SEARCH ENDPOINTS =====

function handleGetGallery($generator, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    $offset = max(0, (int)($_REQUEST['offset'] ?? 0));
    $sort = sanitize($_REQUEST['sort'] ?? 'created_desc');
    
    $cache_key = "code_gallery_{$user->guid}_{$offset}_{$limit}_{$sort}";
    $result = ossn_cache_get($cache_key, 'alkebulan');
    
    if(!$result) {
        $result = [
            'status' => 'success',
            'data' => $generator->getGallery($limit, $offset, $sort),
            'total' => $generator->getCodeCount(),
            'limit' => $limit,
            'offset' => $offset
        ];
        ossn_cache_save($cache_key, $result, 3600, 'alkebulan');
    }
    echo json_encode($result);
}

function handleSearchCode($generator, $user) {
    $query = sanitize($_REQUEST['query'] ?? '');
    $language = sanitize($_REQUEST['language'] ?? '');
    $type = sanitize($_REQUEST['type'] ?? '');
    $sort = sanitize($_REQUEST['sort'] ?? 'relevance');
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    
    if(empty($query)) {
        echo json_encode(['status' => 'error', 'message' => 'Search query required']);
        return;
    }
    
    $filters = [
        'language' => $language,
        'type' => $type,
        'sort' => $sort,
        'limit' => $limit
    ];
    
    $results = $generator->searchCode($query, $filters);
    echo json_encode([
        'status' => 'success',
        'query' => $query,
        'results' => $results,
        'count' => count($results)
    ]);
}

// ===== CRUD ENDPOINTS =====

function handleGetCode($generator, $user) {
    $code_id = (int)($_REQUEST['code_id'] ?? 0);
    if(empty($code_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Code ID required']);
        return;
    }
    
    $code = $generator->getCodeById($code_id);
    if($code && ($code['user_id'] == $user->guid || $code['is_public'])) {
        echo json_encode(['status' => 'success', 'code' => $code]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Not found or unauthorized']);
    }
}

function handleUpdateCode($generator, $user) {
    $code_id = (int)($_REQUEST['code_id'] ?? 0);
    $generated_code = sanitize($_REQUEST['code'] ?? '');
    
    if(empty($code_id) || empty($generated_code)) {
        echo json_encode(['status' => 'error', 'message' => 'Code ID and code content required']);
        return;
    }
    
    $success = $generator->updateCode($code_id, $generated_code);
    echo json_encode(['status' => $success ? 'success' : 'error', 'message' => $success ? 'Updated' : 'Failed']);
}

function handleDeleteCode($generator, $user) {
    $code_id = (int)($_REQUEST['code_id'] ?? 0);
    if(empty($code_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Code ID required']);
        return;
    }
    
    $success = $generator->deleteCode($code_id);
    echo json_encode(['status' => $success ? 'success' : 'error', 'message' => $success ? 'Deleted' : 'Failed']);
}

// ===== RATING & FAVORITES =====

function handleRateCode($generator, $user) {
    $code_id = (int)($_REQUEST['code_id'] ?? 0);
    $rating = (int)($_REQUEST['rating'] ?? 0);
    $comment = sanitize($_REQUEST['comment'] ?? '');
    
    if(empty($code_id) || $rating < 1 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Valid code ID and rating 1-5 required']);
        return;
    }
    
    $success = $generator->rateCode($code_id, $rating, $comment);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleFavorite($generator, $user) {
    $code_id = (int)($_REQUEST['code_id'] ?? 0);
    if(empty($code_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Code ID required']);
        return;
    }
    
    $success = $generator->toggleFavorite($code_id);
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
    
    $trending = $generator->getTrendingPrompts($limit, $timeframe);
    echo json_encode(['status' => 'success', 'trending' => $trending]);
}

function handleAnalyzeCode($generator, $user) {
    $code_id = (int)($_REQUEST['code_id'] ?? 0);
    if(empty($code_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Code ID required']);
        return;
    }
    
    $analysis = $generator->analyzeCode($code_id);
    echo json_encode(['status' => 'success', 'analysis' => $analysis]);
}

// ===== EXPORT & BULK OPERATIONS =====

function handleExport($generator, $user) {
    $format = sanitize($_REQUEST['format'] ?? 'json');
    $ids = isset($_REQUEST['ids']) ? array_map('intval', (array)$_REQUEST['ids']) : [];
    
    if(empty($ids)) {
        $ids = $generator->getAllCodeIds();
    }
    
    $export_data = $generator->exportCodes($ids, $format);
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
                if($generator->deleteCode($id)) $result['processed']++;
                break;
            case 'make_public':
                if($generator->setCodePublic($id, true)) $result['processed']++;
                break;
            case 'make_private':
                if($generator->setCodePublic($id, false)) $result['processed']++;
                break;
        }
    }
    echo json_encode($result);
}

// ===== HELP & SETTINGS =====

function handleGetHelp() {
    $endpoints = [
        ['method' => 'POST', 'path' => '/code_generate/generate', 'description' => 'Generate code from description'],
        ['method' => 'POST', 'path' => '/code_generate/by_type', 'description' => 'Generate by language and type'],
        ['method' => 'GET', 'path' => '/code_generate/gallery', 'description' => 'Get code gallery (paginated)'],
        ['method' => 'GET', 'path' => '/code_generate/search', 'description' => 'Search generated code'],
        ['method' => 'GET', 'path' => '/code_generate/get', 'description' => 'Get specific code by ID'],
        ['method' => 'POST', 'path' => '/code_generate/update', 'description' => 'Update code content'],
        ['method' => 'POST', 'path' => '/code_generate/delete', 'description' => 'Delete code generation'],
        ['method' => 'POST', 'path' => '/code_generate/rate', 'description' => 'Rate code quality'],
        ['method' => 'POST', 'path' => '/code_generate/favorite', 'description' => 'Toggle favorite'],
        ['method' => 'GET', 'path' => '/code_generate/favorites', 'description' => 'Get favorited code'],
        ['method' => 'GET', 'path' => '/code_generate/stats', 'description' => 'Get user statistics'],
        ['method' => 'GET', 'path' => '/code_generate/trending', 'description' => 'Get trending prompts'],
        ['method' => 'GET', 'path' => '/code_generate/analyze', 'description' => 'Analyze code quality'],
        ['method' => 'POST', 'path' => '/code_generate/export', 'description' => 'Export generated code'],
        ['method' => 'POST', 'path' => '/code_generate/bulk', 'description' => 'Bulk operations'],
        ['method' => 'GET', 'path' => '/code_generate/languages', 'description' => 'Supported languages'],
        ['method' => 'GET', 'path' => '/code_generate/types', 'description' => 'Code types'],
        ['method' => 'GET', 'path' => '/code_generate/settings', 'description' => 'User settings']
    ];
    echo json_encode(['status' => 'success', 'endpoints' => $endpoints]);
}

function handleSettings($generator, $user) {
    if($_REQUEST['method'] == 'get') {
        $settings = $generator->getUserSettings();
        echo json_encode(['status' => 'success', 'settings' => $settings]);
    } else if($_REQUEST['method'] == 'update') {
        $settings = [
            'default_language' => sanitize($_REQUEST['language'] ?? 'php'),
            'default_type' => sanitize($_REQUEST['type'] ?? 'function'),
            'auto_docs' => (bool)($_REQUEST['auto_docs'] ?? true),
            'auto_tests' => (bool)($_REQUEST['auto_tests'] ?? false)
        ];
        $success = $generator->updateUserSettings($settings);
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>
