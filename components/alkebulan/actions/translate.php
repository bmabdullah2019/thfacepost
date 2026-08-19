<?php
/**
 * Alkebulan AI - Translation API v3.0
 * Comprehensive REST API for advanced translation with full CRUD operations
 */

if(!ossn_isLoggedin()) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    return;
}

$user = ossn_loggedin_user();
$translator = new TranslationEngine($user->guid);
$action = array_shift(__OSSN_REQUESTED_ACTION__) ?: 'translate';

switch($action) {
    case 'translate':
        handleTranslation($translator, $user);
        break;
    case 'batch':
        handleBatchTranslation($translator, $user);
        break;
    case 'gallery':
        handleGetGallery($translator, $user);
        break;
    case 'search':
        handleSearchTranslations($translator, $user);
        break;
    case 'get':
        handleGetTranslation($translator, $user);
        break;
    case 'delete':
        handleDeleteTranslation($translator, $user);
        break;
    case 'update':
        handleUpdateTranslation($translator, $user);
        break;
    case 'rate':
        handleRateTranslation($translator, $user);
        break;
    case 'favorite':
        handleFavorite($translator, $user);
        break;
    case 'favorites':
        handleGetFavorites($translator, $user);
        break;
    case 'detect':
        handleLanguageDetection($translator, $user);
        break;
    case 'stats':
        handleGetStatistics($translator, $user);
        break;
    case 'trending':
        handleGetTrending($translator, $user);
        break;
    case 'analyze':
        handleAnalyzeTranslation($translator, $user);
        break;
    case 'export':
        handleExport($translator, $user);
        break;
    case 'bulk':
        handleBulkOperation($translator, $user);
        break;
    case 'languages':
        echo json_encode($translator->getSupportedLanguages());
        break;
    case 'help':
        handleGetHelp();
        break;
    case 'settings':
        handleSettings($translator, $user);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

// ===== TRANSLATION ENDPOINTS =====

function handleTranslation($translator, $user) {
    $content = sanitize($_REQUEST['content'] ?? '');
    $target_language = sanitize($_REQUEST['target_language'] ?? '');
    
    if(empty($content) || empty($target_language)) {
        echo json_encode(['status' => 'error', 'message' => 'Content and target language required']);
        return;
    }
    
    $options = [
        'source' => sanitize($_REQUEST['source_language'] ?? 'auto'),
        'formality' => sanitize($_REQUEST['formality'] ?? 'neutral'),
        'preserve_formatting' => (bool)($_REQUEST['preserve_formatting'] ?? true),
        'quality' => sanitize($_REQUEST['quality'] ?? 'high')
    ];
    
    $result = $translator->translate($content, $target_language, $options);
    echo json_encode($result);
}

function handleBatchTranslation($translator, $user) {
    $items = isset($_REQUEST['items']) ? (array)$_REQUEST['items'] : [];
    $target_language = sanitize($_REQUEST['target_language'] ?? '');
    
    if(empty($items) || empty($target_language)) {
        echo json_encode(['status' => 'error', 'message' => 'Items and target language required']);
        return;
    }
    
    $results = [];
    foreach($items as $item) {
        $results[] = $translator->translate(sanitize($item), $target_language, []);
    }
    
    echo json_encode(['status' => 'success', 'results' => $results, 'count' => count($results)]);
}

// ===== GALLERY & SEARCH =====

function handleGetGallery($translator, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    $offset = max(0, (int)($_REQUEST['offset'] ?? 0));
    $sort = sanitize($_REQUEST['sort'] ?? 'created_desc');
    
    $cache_key = "translation_gallery_{$user->guid}_{$offset}_{$limit}_{$sort}";
    $result = ossn_cache_get($cache_key, 'alkebulan');
    
    if(!$result) {
        $result = [
            'status' => 'success',
            'data' => $translator->getGallery($limit, $offset, $sort),
            'total' => $translator->getTranslationCount(),
            'limit' => $limit,
            'offset' => $offset
        ];
        ossn_cache_save($cache_key, $result, 3600, 'alkebulan');
    }
    echo json_encode($result);
}

function handleSearchTranslations($translator, $user) {
    $query = sanitize($_REQUEST['query'] ?? '');
    $language = sanitize($_REQUEST['language'] ?? '');
    $sort = sanitize($_REQUEST['sort'] ?? 'relevance');
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    
    if(empty($query)) {
        echo json_encode(['status' => 'error', 'message' => 'Search query required']);
        return;
    }
    
    $filters = ['language' => $language, 'sort' => $sort, 'limit' => $limit];
    
    $results = $translator->searchTranslations($query, $filters);
    echo json_encode([
        'status' => 'success',
        'query' => $query,
        'results' => $results,
        'count' => count($results)
    ]);
}

// ===== CRUD ENDPOINTS =====

function handleGetTranslation($translator, $user) {
    $translation_id = (int)($_REQUEST['translation_id'] ?? 0);
    if(empty($translation_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Translation ID required']);
        return;
    }
    
    $translation = $translator->getTranslationById($translation_id);
    if($translation && ($translation['user_id'] == $user->guid || $translation['is_public'])) {
        echo json_encode(['status' => 'success', 'translation' => $translation]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Not found or unauthorized']);
    }
}

function handleUpdateTranslation($translator, $user) {
    $translation_id = (int)($_REQUEST['translation_id'] ?? 0);
    $translated_text = sanitize($_REQUEST['translation'] ?? '');
    
    if(empty($translation_id) || empty($translated_text)) {
        echo json_encode(['status' => 'error', 'message' => 'Translation ID and text required']);
        return;
    }
    
    $success = $translator->updateTranslation($translation_id, $translated_text);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleDeleteTranslation($translator, $user) {
    $translation_id = (int)($_REQUEST['translation_id'] ?? 0);
    if(empty($translation_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Translation ID required']);
        return;
    }
    
    $success = $translator->deleteTranslation($translation_id);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

// ===== LANGUAGE DETECTION & ANALYSIS =====

function handleLanguageDetection($translator, $user) {
    $content = sanitize($_REQUEST['content'] ?? '');
    if(empty($content)) {
        echo json_encode(['status' => 'error', 'message' => 'Content required']);
        return;
    }
    
    $detected = $translator->detectLanguage($content);
    echo json_encode(['status' => 'success', 'detected' => $detected, 'content_length' => strlen($content)]);
}

function handleAnalyzeTranslation($translator, $user) {
    $translation_id = (int)($_REQUEST['translation_id'] ?? 0);
    if(empty($translation_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Translation ID required']);
        return;
    }
    
    $analysis = $translator->analyzeTranslation($translation_id);
    echo json_encode(['status' => 'success', 'analysis' => $analysis]);
}

// ===== RATING & FAVORITES =====

function handleRateTranslation($translator, $user) {
    $translation_id = (int)($_REQUEST['translation_id'] ?? 0);
    $rating = (int)($_REQUEST['rating'] ?? 0);
    
    if(empty($translation_id) || $rating < 1 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Valid translation ID and rating 1-5 required']);
        return;
    }
    
    $success = $translator->rateTranslation($translation_id, $rating);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleFavorite($translator, $user) {
    $translation_id = (int)($_REQUEST['translation_id'] ?? 0);
    if(empty($translation_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Translation ID required']);
        return;
    }
    
    $success = $translator->toggleFavorite($translation_id);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function handleGetFavorites($translator, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 20), 100);
    $offset = max(0, (int)($_REQUEST['offset'] ?? 0));
    
    $favorites = $translator->getFavorites($limit, $offset);
    echo json_encode([
        'status' => 'success',
        'favorites' => $favorites,
        'count' => count($favorites)
    ]);
}

// ===== ANALYTICS & TRENDING =====

function handleGetStatistics($translator, $user) {
    $timeframe = sanitize($_REQUEST['timeframe'] ?? '30');
    $stats = $translator->getStatistics($timeframe);
    echo json_encode(['status' => 'success', 'stats' => $stats]);
}

function handleGetTrending($translator, $user) {
    $limit = min((int)($_REQUEST['limit'] ?? 10), 50);
    $timeframe = sanitize($_REQUEST['timeframe'] ?? '7');
    
    $trending = $translator->getTrendingLanguagePairs($limit, $timeframe);
    echo json_encode(['status' => 'success', 'trending' => $trending]);
}

// ===== EXPORT & BULK =====

function handleExport($translator, $user) {
    $format = sanitize($_REQUEST['format'] ?? 'json');
    $ids = isset($_REQUEST['ids']) ? array_map('intval', (array)$_REQUEST['ids']) : [];
    
    if(empty($ids)) {
        $ids = $translator->getAllTranslationIds();
    }
    
    $export_data = $translator->exportTranslations($ids, $format);
    echo json_encode(['status' => 'success', 'data' => $export_data]);
}

function handleBulkOperation($translator, $user) {
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
                if($translator->deleteTranslation($id)) $result['processed']++;
                break;
            case 'make_public':
                if($translator->setTranslationPublic($id, true)) $result['processed']++;
                break;
            case 'make_private':
                if($translator->setTranslationPublic($id, false)) $result['processed']++;
                break;
        }
    }
    echo json_encode($result);
}

// ===== HELP & SETTINGS =====

function handleGetHelp() {
    $endpoints = [
        ['method' => 'POST', 'path' => '/translate/translate', 'description' => 'Translate content'],
        ['method' => 'POST', 'path' => '/translate/batch', 'description' => 'Batch translation'],
        ['method' => 'GET', 'path' => '/translate/gallery', 'description' => 'Get translation gallery'],
        ['method' => 'GET', 'path' => '/translate/search', 'description' => 'Search translations'],
        ['method' => 'GET', 'path' => '/translate/get', 'description' => 'Get translation by ID'],
        ['method' => 'POST', 'path' => '/translate/update', 'description' => 'Update translation'],
        ['method' => 'POST', 'path' => '/translate/delete', 'description' => 'Delete translation'],
        ['method' => 'POST', 'path' => '/translate/detect', 'description' => 'Detect language'],
        ['method' => 'GET', 'path' => '/translate/analyze', 'description' => 'Analyze translation quality'],
        ['method' => 'POST', 'path' => '/translate/rate', 'description' => 'Rate translation'],
        ['method' => 'POST', 'path' => '/translate/favorite', 'description' => 'Toggle favorite'],
        ['method' => 'GET', 'path' => '/translate/favorites', 'description' => 'Get favorite translations'],
        ['method' => 'GET', 'path' => '/translate/stats', 'description' => 'Get user statistics'],
        ['method' => 'GET', 'path' => '/translate/trending', 'description' => 'Get trending language pairs'],
        ['method' => 'POST', 'path' => '/translate/export', 'description' => 'Export translations'],
        ['method' => 'POST', 'path' => '/translate/bulk', 'description' => 'Bulk operations'],
        ['method' => 'GET', 'path' => '/translate/languages', 'description' => 'Supported languages'],
        ['method' => 'GET', 'path' => '/translate/settings', 'description' => 'User settings']
    ];
    echo json_encode(['status' => 'success', 'endpoints' => $endpoints]);
}

function handleSettings($translator, $user) {
    if($_REQUEST['method'] == 'get') {
        $settings = $translator->getUserSettings();
        echo json_encode(['status' => 'success', 'settings' => $settings]);
    } else if($_REQUEST['method'] == 'update') {
        $settings = [
            'default_formality' => sanitize($_REQUEST['formality'] ?? 'neutral'),
            'preserve_formatting' => (bool)($_REQUEST['preserve_formatting'] ?? true)
        ];
        $success = $translator->updateUserSettings($settings);
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>
