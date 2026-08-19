# Image Generation API v3.0 - Integration Guide

**Status**: ✅ COMPLETE & READY
**Version**: 3.0
**Date**: January 23, 2026

---

## 🔌 Integration Overview

This guide helps developers integrate the Image Generation API into their applications.

---

## 1️⃣ Installation & Setup

### Step 1: Copy Files
```
Copy these files to alkebulan directory:
├── classes/ImageGenerator.php
├── actions/image_generate.php
├── actions/setup_image_database.php
```

### Step 2: Initialize Database
```bash
# Option 1: Via HTTP
GET http://localhost/alkebulan/action/setup_image_database

# Option 2: Direct PHP
php alkebulan/actions/setup_image_database.php
```

**Response**:
```json
{
  "status": "success",
  "message": "All database tables created successfully",
  "tables_created": [13 table names],
  "total_tables": 13
}
```

### Step 3: Verify Installation
```bash
# Check if API responds
GET /alkebulan/action/image_generate/styles

# Should return list of styles
```

---

## 2️⃣ Basic Integration

### PHP Usage (Direct Class)

```php
<?php

// Create instance
$generator = new ImageGenerator($user_id);

// Generate image
$result = $generator->generateImage(
    'A beautiful sunset over mountains',
    [
        'style' => 'photorealistic',
        'quality' => 'hd',
        'width' => 1024,
        'height' => 1024,
        'format' => 'png'
    ]
);

// Check result
if($result['status'] === 'success') {
    echo "Image generated: " . $result['image_id'];
    echo "URL: " . $result['image_url'];
} else {
    echo "Error: " . $result['message'];
}

?>
```

### REST API Usage (HTTP)

```bash
# Generate image via HTTP
curl -X POST http://localhost/alkebulan/action/image_generate/generate \
  -H "Content-Type: application/json" \
  -d '{
    "prompt": "A beautiful sunset over mountains",
    "style": "photorealistic",
    "quality": "hd",
    "width": 1024,
    "height": 1024
  }'
```

### JavaScript/Frontend Integration

```javascript
// Generate image from frontend
async function generateImage(prompt) {
    const response = await fetch('/alkebulan/action/image_generate/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            prompt: prompt,
            style: 'photorealistic',
            quality: 'hd'
        })
    });
    
    const result = await response.json();
    
    if(result.status === 'success') {
        console.log('Image ID:', result.image_id);
        console.log('Image URL:', result.image_url);
        console.log('Processing time:', result.processing_time_ms, 'ms');
    } else {
        console.error('Error:', result.message);
    }
    
    return result;
}

// Usage
generateImage('A futuristic city skyline');
```

---

## 3️⃣ Advanced Integration

### Gallery Display

```php
<?php

class ImageGallery {
    private $generator;
    
    public function __construct($user_id) {
        $this->generator = new ImageGenerator($user_id);
    }
    
    public function displayGallery($page = 1, $limit = 20) {
        // Get images
        $result = $this->generator->getGallery($limit, ($page - 1) * $limit);
        
        if($result['status'] !== 'success') {
            return ['error' => $result['message']];
        }
        
        return [
            'images' => $result['images'],
            'total' => $result['total'],
            'pages' => ceil($result['total'] / $limit),
            'current_page' => $page
        ];
    }
    
    public function searchGallery($query, $filters = []) {
        return $this->generator->searchImages($query, $filters);
    }
    
    public function deleteImage($image_id) {
        return $this->generator->deleteImage($image_id);
    }
}

// Usage
$gallery = new ImageGallery($user_id);
$images = $gallery->displayGallery(1, 20);

?>
```

### Batch Generation

```php
<?php

class BatchGenerator {
    private $generator;
    
    public function __construct($user_id) {
        $this->generator = new ImageGenerator($user_id);
    }
    
    public function generateBatch($prompts, $options = []) {
        $results = [];
        
        foreach($prompts as $prompt) {
            $result = $this->generator->generateImage($prompt, $options);
            $results[] = $result;
            
            // Log each generation
            if($result['status'] === 'success') {
                echo "✓ Generated: {$prompt}\n";
            } else {
                echo "✗ Failed: {$prompt} - {$result['message']}\n";
            }
        }
        
        return $results;
    }
}

// Usage
$batch = new BatchGenerator($user_id);
$results = $batch->generateBatch([
    'A sunset over ocean',
    'A forest at dawn',
    'A city at night'
], ['style' => 'photorealistic']);

?>
```

### Analytics Integration

```php
<?php

class ImageAnalytics {
    private $generator;
    
    public function __construct($user_id) {
        $this->generator = new ImageGenerator($user_id);
    }
    
    public function displayDashboard() {
        // Get statistics
        $stats = $this->generator->getStatistics();
        $trending = $this->generator->getTrendingPrompts(10);
        
        return [
            'statistics' => $stats,
            'trending_prompts' => $trending,
            'summary' => [
                'total_images' => $stats['total_images'],
                'storage_used' => $stats['total_storage_mb'] . ' MB',
                'avg_processing_time' => $stats['average_processing_time_ms'] . ' ms',
                'unique_styles' => $stats['unique_styles']
            ]
        ];
    }
}

// Usage
$analytics = new ImageAnalytics($user_id);
$dashboard = $analytics->displayDashboard();

?>
```

---

## 4️⃣ Frontend Integration Example

### React Component

```jsx
import React, { useState } from 'react';

export function ImageGenerator() {
    const [prompt, setPrompt] = useState('');
    const [style, setStyle] = useState('abstract');
    const [quality, setQuality] = useState('standard');
    const [loading, setLoading] = useState(false);
    const [image, setImage] = useState(null);
    
    const handleGenerate = async () => {
        setLoading(true);
        
        try {
            const response = await fetch('/alkebulan/action/image_generate/generate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ prompt, style, quality })
            });
            
            const result = await response.json();
            
            if(result.status === 'success') {
                setImage(result);
            } else {
                alert('Error: ' + result.message);
            }
        } finally {
            setLoading(false);
        }
    };
    
    return (
        <div className="image-generator">
            <h2>Image Generator</h2>
            
            <textarea
                value={prompt}
                onChange={(e) => setPrompt(e.target.value)}
                placeholder="Describe the image you want to generate..."
            />
            
            <select value={style} onChange={(e) => setStyle(e.target.value)}>
                <option value="abstract">Abstract</option>
                <option value="photorealistic">Photorealistic</option>
                <option value="cyberpunk">Cyberpunk</option>
                {/* ... more styles */}
            </select>
            
            <select value={quality} onChange={(e) => setQuality(e.target.value)}>
                <option value="draft">Draft (5-10s)</option>
                <option value="standard">Standard (15-30s)</option>
                <option value="hd">HD (30-60s)</option>
                <option value="ultra">Ultra (60-120s)</option>
            </select>
            
            <button onClick={handleGenerate} disabled={loading}>
                {loading ? 'Generating...' : 'Generate'}
            </button>
            
            {image && (
                <div className="result">
                    <img src={image.image_url} alt="Generated" />
                    <p>Processing time: {image.processing_time_ms}ms</p>
                </div>
            )}
        </div>
    );
}
```

### HTML Form

```html
<form id="imageGeneratorForm">
    <div>
        <label>Prompt:</label>
        <textarea id="prompt" required></textarea>
    </div>
    
    <div>
        <label>Style:</label>
        <select id="style">
            <option value="photorealistic">Photorealistic</option>
            <option value="cyberpunk">Cyberpunk</option>
            <option value="anime">Anime</option>
        </select>
    </div>
    
    <div>
        <label>Quality:</label>
        <select id="quality">
            <option value="standard">Standard</option>
            <option value="hd">HD</option>
            <option value="ultra">Ultra</option>
        </select>
    </div>
    
    <button type="submit">Generate Image</button>
</form>

<div id="result"></div>

<script>
document.getElementById('imageGeneratorForm').onsubmit = async (e) => {
    e.preventDefault();
    
    const data = {
        prompt: document.getElementById('prompt').value,
        style: document.getElementById('style').value,
        quality: document.getElementById('quality').value
    };
    
    const response = await fetch('/alkebulan/action/image_generate/generate', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
    });
    
    const result = await response.json();
    
    if(result.status === 'success') {
        document.getElementById('result').innerHTML = 
            `<img src="${result.image_url}" alt="Generated">`;
    }
};
</script>
```

---

## 5️⃣ Database Integration

### Querying Images Directly

```php
<?php

class ImageRepository {
    private $db;
    
    public function __construct() {
        $this->db = ossn_get_database();
    }
    
    public function getPopularImages($limit = 20) {
        return $this->db->query(
            "SELECT * FROM alkebulan_images 
             WHERE is_public = 1 
             ORDER BY rating DESC, views DESC 
             LIMIT :limit",
            [':limit' => $limit]
        )->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRecentImages($days = 7) {
        $timestamp = time() - ($days * 86400);
        
        return $this->db->query(
            "SELECT * FROM alkebulan_images 
             WHERE created > :timestamp 
             ORDER BY created DESC",
            [':timestamp' => $timestamp]
        )->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getImagesByStyle($style, $limit = 20) {
        return $this->db->query(
            "SELECT * FROM alkebulan_images 
             WHERE style = :style 
             AND is_public = 1
             ORDER BY rating DESC 
             LIMIT :limit",
            [':style' => $style, ':limit' => $limit]
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
```

---

## 6️⃣ Error Handling

### Comprehensive Error Handling

```php
<?php

class SafeImageGenerator {
    private $generator;
    private $logger;
    
    public function __construct($user_id) {
        $this->generator = new ImageGenerator($user_id);
        $this->logger = new Logger();
    }
    
    public function generateWithErrorHandling($prompt, $options = []) {
        try {
            // Validate input
            if(empty($prompt)) {
                throw new Exception('Prompt is required');
            }
            
            if(strlen($prompt) > 2000) {
                throw new Exception('Prompt too long (max 2000 characters)');
            }
            
            // Generate
            $result = $this->generator->generateImage($prompt, $options);
            
            // Check result
            if($result['status'] !== 'success') {
                $this->logger->error('Generation failed: ' . $result['message']);
                throw new Exception($result['message']);
            }
            
            return $result;
            
        } catch(Exception $e) {
            // Log error
            $this->logger->error('Image generation error: ' . $e->getMessage());
            
            // Return safe response
            return [
                'status' => 'error',
                'message' => 'Failed to generate image',
                'error_code' => 'GEN_ERROR'
            ];
        }
    }
}

?>
```

### Validation Helper

```php
<?php

class ImageValidator {
    public static function validatePrompt($prompt) {
        if(empty($prompt)) {
            return ['valid' => false, 'error' => 'Prompt is required'];
        }
        
        if(strlen($prompt) > 2000) {
            return ['valid' => false, 'error' => 'Prompt too long'];
        }
        
        return ['valid' => true];
    }
    
    public static function validateDimensions($width, $height) {
        $min = 256;
        $max = 1536;
        
        if($width < $min || $width > $max || $height < $min || $height > $max) {
            return ['valid' => false, 'error' => "Dimensions must be $min-$max"];
        }
        
        return ['valid' => true];
    }
    
    public static function validateQuality($quality) {
        $valid = ['draft', 'standard', 'hd', 'ultra'];
        
        if(!in_array($quality, $valid)) {
            return ['valid' => false, 'error' => 'Invalid quality preset'];
        }
        
        return ['valid' => true];
    }
}

// Usage
$validation = ImageValidator::validatePrompt($prompt);
if(!$validation['valid']) {
    echo "Error: " . $validation['error'];
}

?>
```

---

## 7️⃣ Caching Strategy

### Intelligent Caching

```php
<?php

class CachedImageGenerator {
    private $generator;
    private $cache;
    
    public function __construct($user_id) {
        $this->generator = new ImageGenerator($user_id);
        $this->cache = new CacheManager();
    }
    
    public function generateWithCache($prompt, $options = []) {
        // Create cache key
        $cacheKey = 'image_' . md5($prompt . json_encode($options));
        
        // Check cache first
        $cached = $this->cache->get($cacheKey);
        if($cached) {
            $cached['from_cache'] = true;
            return $cached;
        }
        
        // Generate if not cached
        $result = $this->generator->generateImage($prompt, $options);
        
        // Cache result for 24 hours
        if($result['status'] === 'success') {
            $this->cache->set($cacheKey, $result, 86400);
        }
        
        $result['from_cache'] = false;
        return $result;
    }
}

?>
```

---

## 8️⃣ Performance Optimization

### Batch Operations

```php
<?php

class OptimizedBatchGenerator {
    private $generator;
    
    public function __construct($user_id) {
        $this->generator = new ImageGenerator($user_id);
    }
    
    public function generateBatchOptimized($prompts, $options = []) {
        $results = [];
        $count = 0;
        $total = count($prompts);
        
        foreach($prompts as $prompt) {
            // Progress tracking
            $count++;
            echo "Processing $count/$total: $prompt\n";
            
            // Generate with caching
            $result = $this->generator->generateImage($prompt, $options);
            $results[] = $result;
            
            // Memory management
            if($count % 10 === 0) {
                gc_collect_cycles();
            }
            
            // Rate limiting (optional)
            sleep(1);
        }
        
        return $results;
    }
}

?>
```

---

## 9️⃣ Monitoring & Logging

### Usage Tracking

```php
<?php

class ImageUsageTracker {
    private $db;
    
    public function __construct() {
        $this->db = ossn_get_database();
    }
    
    public function trackGeneration($user_id, $image_id, $processing_time) {
        $this->db->query(
            "INSERT INTO alkebulan_image_history 
             (user_id, image_id, processing_time, created) 
             VALUES (:user, :image, :time, :created)",
            [
                ':user' => $user_id,
                ':image' => $image_id,
                ':time' => $processing_time,
                ':created' => time()
            ]
        );
    }
    
    public function getUserStats($user_id) {
        return $this->db->query(
            "SELECT COUNT(*) as total_generated,
                    AVG(processing_time) as avg_time,
                    SUM(processing_time) as total_time
             FROM alkebulan_image_history 
             WHERE user_id = :user",
            [':user' => $user_id]
        )->fetch(PDO::FETCH_ASSOC);
    }
}

?>
```

---

## 🔟 Testing

### Unit Tests

```php
<?php

class ImageGeneratorTest {
    private $generator;
    
    public function setUp() {
        $this->generator = new ImageGenerator(1);
    }
    
    public function testSimpleGeneration() {
        $result = $this->generator->generateImage(
            'A beautiful sunset',
            ['style' => 'photorealistic']
        );
        
        assert($result['status'] === 'success');
        assert(!empty($result['image_id']));
        assert(!empty($result['image_url']));
    }
    
    public function testVariationGeneration() {
        // First generate an image
        $result = $this->generator->generateImage('A test image');
        $image_id = $result['image_id'];
        
        // Generate variations
        $variations = $this->generator->generateVariations($image_id, 3);
        
        assert($variations['status'] === 'success');
        assert($variations['variations_count'] == 3);
    }
    
    public function testGalleryRetrieval() {
        $gallery = $this->generator->getGallery(10, 0);
        
        assert($gallery['status'] === 'success');
        assert(isset($gallery['total']));
        assert(is_array($gallery['images']));
    }
}

?>
```

---

## 📋 Checklist

Before deploying:

- [ ] Database setup script executed
- [ ] All tables created and indexed
- [ ] ImageGenerator class accessible
- [ ] image_generate.php API handler in place
- [ ] File permissions correct
- [ ] Cache directory writable
- [ ] Image storage directory created
- [ ] CacheManager dependency available
- [ ] Database connection tested
- [ ] API endpoints tested
- [ ] Error handling verified
- [ ] Security features validated
- [ ] Performance benchmarks met
- [ ] Documentation reviewed
- [ ] Team training completed
- [ ] Monitoring configured
- [ ] Backup strategy in place

---

## 🆘 Troubleshooting

### Common Issues

**Issue**: "Class ImageGenerator not found"
```php
// Solution: Make sure class is properly loaded
require_once 'alkebulan/classes/ImageGenerator.php';
```

**Issue**: Database tables not created
```bash
# Solution: Run setup script
curl http://localhost/alkebulan/action/setup_image_database
```

**Issue**: Images not saving
```php
// Check file permissions
chmod 755 alkebulan/cache/images
chmod 644 alkebulan/cache/images/*
```

**Issue**: Slow generation
```php
// Check cache
$generator->getStatistics(); // Look at cache hit rate
// Enable caching if disabled
```

---

## 📚 Additional Resources

- Full API Docs: `IMAGE_GENERATION_API_V3.md`
- Quick Reference: `IMAGE_GENERATION_QUICK_REFERENCE.md`
- Implementation Summary: `IMAGE_GENERATION_IMPLEMENTATION_SUMMARY.md`
- API Help Endpoint: `/alkebulan/action/image_generate/help`

---

**Image Generation API v3.0 - Integration Complete!** ✅🎉
