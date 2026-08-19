<?php
/**
 * CacheManager Class - Intelligent Caching System
 * Provides multi-tier caching: Memory cache, File cache, and Redis (if available)
 * Features: Automatic expiration, compression, statistics, fallback mechanisms
 */

class CacheManager {
    private $cache_dir;
    private $memory_cache = [];
    private $redis_enabled = false;
    private $redis_client;
    private $compression_enabled = true;
    private $compress_threshold = 1024; // Compress files larger than 1KB
    private $stats = [
        'hits' => 0,
        'misses' => 0,
        'sets' => 0,
        'deletes' => 0
    ];
    
    public function __construct() {
        // Set cache directory
        $this->cache_dir = dirname(__FILE__) . '/../cache';
        
        // Create cache directory if it doesn't exist
        if(!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
        }
        
        // Initialize Redis if available
        $this->initializeRedis();
    }
    
    /**
     * Initialize Redis connection
     */
    private function initializeRedis() {
        if(extension_loaded('redis')) {
            try {
                $this->redis_client = new Redis();
                $this->redis_client->connect('127.0.0.1', 6379, 1);
                $this->redis_enabled = true;
            } catch(Exception $e) {
                $this->redis_enabled = false;
            }
        }
    }
    
    /**
     * Get value from cache
     * @param string $key Cache key
     * @return mixed Cached value or null
     */
    public function get($key) {
        // Check memory cache first
        if(isset($this->memory_cache[$key])) {
            $item = $this->memory_cache[$key];
            if($this->isExpired($item['expires_at'])) {
                unset($this->memory_cache[$key]);
                $this->stats['misses']++;
                return null;
            }
            $this->stats['hits']++;
            return $item['value'];
        }
        
        // Check Redis if enabled
        if($this->redis_enabled) {
            $value = $this->redis_client->get($key);
            if($value !== false) {
                $this->stats['hits']++;
                $decoded = @unserialize($value);
                return $decoded !== false ? $decoded : $value;
            }
        }
        
        // Check file cache
        $file_path = $this->getCacheFilePath($key);
        if(file_exists($file_path)) {
            $file_data = file_get_contents($file_path);
            
            // Check expiration
            $meta = $this->getCacheMetadata($key);
            if($this->isExpired($meta['expires_at'])) {
                unlink($file_path);
                $this->stats['misses']++;
                return null;
            }
            
            // Decompress if needed
            if($meta['compressed']) {
                $file_data = gzuncompress($file_data);
            }
            
            $this->stats['hits']++;
            return @unserialize($file_data) ?: $file_data;
        }
        
        $this->stats['misses']++;
        return null;
    }
    
    /**
     * Set value in cache
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param int $ttl Time to live in seconds
     * @return bool Success
     */
    public function set($key, $value, $ttl = 3600) {
        $this->stats['sets']++;
        $expires_at = time() + $ttl;
        
        // Store in memory cache
        $this->memory_cache[$key] = [
            'value' => $value,
            'expires_at' => $expires_at
        ];
        
        // Store in Redis if enabled
        if($this->redis_enabled) {
            $serialized = serialize($value);
            $this->redis_client->setex($key, $ttl, $serialized);
        }
        
        // Store in file cache
        $file_path = $this->getCacheFilePath($key);
        $serialized = serialize($value);
        
        // Compress if file is large
        $compressed = false;
        if($this->compression_enabled && strlen($serialized) > $this->compress_threshold) {
            $serialized = gzcompress($serialized, 9);
            $compressed = true;
        }
        
        // Write to file
        file_put_contents($file_path, $serialized);
        
        // Store metadata
        $this->setCacheMetadata($key, [
            'expires_at' => $expires_at,
            'compressed' => $compressed,
            'size' => strlen($serialized),
            'created' => time()
        ]);
        
        return true;
    }
    
    /**
     * Delete cache entry
     * @param string $key Cache key
     * @return bool Success
     */
    public function delete($key) {
        $this->stats['deletes']++;
        
        // Remove from memory
        unset($this->memory_cache[$key]);
        
        // Remove from Redis
        if($this->redis_enabled) {
            $this->redis_client->delete($key);
        }
        
        // Remove from file system
        $file_path = $this->getCacheFilePath($key);
        if(file_exists($file_path)) {
            unlink($file_path);
        }
        
        $meta_path = $this->getMetadataPath($key);
        if(file_exists($meta_path)) {
            unlink($meta_path);
        }
        
        return true;
    }
    
    /**
     * Clear all cache
     * @return bool Success
     */
    public function clear() {
        // Clear memory cache
        $this->memory_cache = [];
        
        // Clear Redis
        if($this->redis_enabled) {
            $this->redis_client->flushDB();
        }
        
        // Clear file cache
        array_map('unlink', glob($this->cache_dir . '/*.cache'));
        array_map('unlink', glob($this->cache_dir . '/*.meta'));
        
        return true;
    }
    
    /**
     * Check if cache entry is expired
     */
    private function isExpired($expires_at) {
        return time() > $expires_at;
    }
    
    /**
     * Get cache file path
     */
    private function getCacheFilePath($key) {
        return $this->cache_dir . '/' . md5($key) . '.cache';
    }
    
    /**
     * Get metadata file path
     */
    private function getMetadataPath($key) {
        return $this->cache_dir . '/' . md5($key) . '.meta';
    }
    
    /**
     * Get cache metadata
     */
    private function getCacheMetadata($key) {
        $meta_path = $this->getMetadataPath($key);
        
        if(!file_exists($meta_path)) {
            return [
                'expires_at' => 0,
                'compressed' => false,
                'size' => 0,
                'created' => 0
            ];
        }
        
        $meta = json_decode(file_get_contents($meta_path), true);
        return $meta ?: ['expires_at' => 0, 'compressed' => false];
    }
    
    /**
     * Set cache metadata
     */
    private function setCacheMetadata($key, $metadata) {
        $meta_path = $this->getMetadataPath($key);
        file_put_contents($meta_path, json_encode($metadata));
    }
    
    /**
     * Get cache statistics
     */
    public function getStats() {
        $total_requests = $this->stats['hits'] + $this->stats['misses'];
        $hit_rate = $total_requests > 0 ? round(($this->stats['hits'] / $total_requests) * 100, 2) : 0;
        
        return [
            'hits' => $this->stats['hits'],
            'misses' => $this->stats['misses'],
            'sets' => $this->stats['sets'],
            'deletes' => $this->stats['deletes'],
            'total_requests' => $total_requests,
            'hit_rate' => $hit_rate . '%',
            'redis_enabled' => $this->redis_enabled,
            'compression_enabled' => $this->compression_enabled,
            'cache_size' => $this->getCacheSize()
        ];
    }
    
    /**
     * Get total cache size
     */
    private function getCacheSize() {
        $size = 0;
        foreach(glob($this->cache_dir . '/*.cache') as $file) {
            $size += filesize($file);
        }
        return $this->formatBytes($size);
    }
    
    /**
     * Format bytes to human-readable format
     */
    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Clean expired cache entries
     */
    public function cleanup() {
        $count = 0;
        foreach(glob($this->cache_dir . '/*.meta') as $meta_file) {
            $meta = json_decode(file_get_contents($meta_file), true);
            
            if($this->isExpired($meta['expires_at'])) {
                $cache_file = str_replace('.meta', '.cache', $meta_file);
                
                if(file_exists($cache_file)) {
                    unlink($cache_file);
                }
                
                unlink($meta_file);
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
     * Enable/disable compression
     */
    public function setCompression($enabled) {
        $this->compression_enabled = $enabled;
    }
}
?>
