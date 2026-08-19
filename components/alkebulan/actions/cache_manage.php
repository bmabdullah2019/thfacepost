<?php
/**
 * Alkebulan AI - Cache Management Action Handler
 * Provides cache statistics, management, and monitoring
 */

// Check user is logged in or if it's an admin
if(!ossn_isLoggedin()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    return;
}

$user = ossn_loggedin_user();
$action = array_shift(__OSSN_REQUESTED_ACTION__);

// Initialize managers
$cache_manager = new CacheManager();
$query_optimizer = new QueryOptimizer();
$ai_analyzer = new AIAnalyzer($user->guid);

switch($action) {
    case 'stats':
        handleCacheStats();
        break;
    case 'clear':
        handleClearCache();
        break;
    case 'query-stats':
        handleQueryStats();
        break;
    case 'cleanup':
        handleCleanup();
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Unknown action: ' . $action
        ]);
}

/**
 * Get cache statistics
 */
function handleCacheStats() {
    $cache_manager = new CacheManager();
    
    $stats = $cache_manager->getStats();
    
    echo json_encode([
        'status' => 'success',
        'cache_stats' => $stats
    ]);
}

/**
 * Clear cache
 */
function handleClearCache() {
    $cache_manager = new CacheManager();
    
    $result = $cache_manager->clear();
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Cache cleared successfully',
        'cleared' => $result
    ]);
}

/**
 * Get query optimization statistics
 */
function handleQueryStats() {
    $query_optimizer = new QueryOptimizer();
    
    $stats = $query_optimizer->getPerformanceStats();
    
    echo json_encode([
        'status' => 'success',
        'query_stats' => $stats
    ]);
}

/**
 * Clean up expired cache
 */
function handleCleanup() {
    $cache_manager = new CacheManager();
    
    $count = $cache_manager->cleanup();
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Cache cleanup completed',
        'entries_removed' => $count
    ]);
}
?>
