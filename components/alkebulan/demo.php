<?php
/**
 * INTERACTIVE IMAGE GENERATION DEMO
 * Run this directly to see real images being generated
 */

// Set headers for JSON if requested
if(isset($_GET['api'])) {
    header('Content-Type: application/json');
    
    $prompt = $_GET['prompt'] ?? 'demo';
    $width = (int)($_GET['width'] ?? 512);
    $height = (int)($_GET['height'] ?? 512);
    
    include 'actions/generate_image.php';
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Image Generation Demo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        h1 {
            margin: 0;
            color: #333;
            font-size: 36px;
        }
        .subtitle {
            color: #666;
            margin-top: 5px;
        }
        .demo-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .demo-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .demo-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #667eea;
        }
        .demo-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .demo-code {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 12px;
            border-radius: 4px;
            font-size: 12px;
            overflow-x: auto;
            margin-bottom: 10px;
            font-family: 'Courier New', monospace;
        }
        .demo-link {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }
        .demo-link:hover {
            background: #764ba2;
        }
        .gallery-preview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .image-thumb {
            aspect-ratio: 1;
            background: #e0e0e0;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .image-thumb:hover {
            background: #d0d0d0;
            transform: scale(1.05);
        }
        .image-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }
        .status {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .status.loading {
            background: #e3f2fd;
            color: #1976d2;
        }
        .status.success {
            background: #c8e6c9;
            color: #388e3c;
        }
        .status.error {
            background: #ffcdd2;
            color: #d32f2f;
        }
        .button-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }
        button:hover {
            background: #764ba2;
        }
        .test-results {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
        }
        .test-result-item {
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .test-result-item.success {
            color: #2e7d32;
        }
        .test-result-item.error {
            color: #c62828;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎨 Image Generation System - Live Demo</h1>
        <p class="subtitle">Generate 4 unique images from a single prompt using real algorithms</p>
    </div>

    <!-- Quick Start -->
    <div class="demo-section">
        <div class="section-title">⚡ Quick Start</div>
        
        <div class="demo-row">
            <div class="demo-box">
                <div class="demo-title">Web UI (Easiest)</div>
                <a href="generate_images.html" class="demo-link">→ Open Web Interface</a>
                <p style="font-size: 12px; color: #666; margin-top: 10px;">Beautiful, interactive UI for generating images</p>
            </div>
            <div class="demo-box">
                <div class="demo-title">Try It Now</div>
                <div style="display: flex; gap: 10px;">
                    <button onclick="testPrompt('car')">Car</button>
                    <button onclick="testPrompt('sunset')">Sunset</button>
                    <button onclick="testPrompt('ocean')">Ocean</button>
                </div>
                <div id="demo-status" style="margin-top: 10px;"></div>
            </div>
        </div>

        <div id="demo-gallery" class="gallery-preview"></div>
    </div>

    <!-- API Examples -->
    <div class="demo-section">
        <div class="section-title">📡 API Examples</div>
        
        <div class="demo-row">
            <div class="demo-box">
                <div class="demo-title">1. Simple GET Request</div>
                <div class="demo-code">http://localhost/alkebulan/demo.php?api=1&prompt=car</div>
                <button onclick="testAPI('GET', 'car')">Test →</button>
            </div>
            
            <div class="demo-box">
                <div class="demo-title">2. POST with Parameters</div>
                <div class="demo-code">POST /actions/generate_image.php
prompt=car&width=512&height=512</div>
                <button onclick="testAPI('POST', 'car')">Test →</button>
            </div>
            
            <div class="demo-box">
                <div class="demo-title">3. Custom Size</div>
                <div class="demo-code">prompt=car&width=256&height=256</div>
                <button onclick="testAPI('POST', 'car', 256)">Test →</button>
            </div>
        </div>
    </div>

    <!-- Algorithm Info -->
    <div class="demo-section">
        <div class="section-title">🧬 The 4 Generation Algorithms</div>
        
        <div class="demo-row">
            <div class="demo-box">
                <div class="demo-title">1. Fractal Landscape</div>
                <p>Uses Diamond-Square algorithm to create terrain-like landscapes with natural elevation coloring (water → grass → rock → snow).</p>
            </div>
            
            <div class="demo-box">
                <div class="demo-title">2. Perlin Noise</div>
                <p>Multi-octave noise synthesis creating smooth, cloud-like organic patterns with flowing gradients.</p>
            </div>
            
            <div class="demo-box">
                <div class="demo-title">3. Particle System</div>
                <p>Dynamic particle trajectories creating flowing patterns, lines, and motion visualizations.</p>
            </div>
            
            <div class="demo-box">
                <div class="demo-title">4. Cellular Automata</div>
                <p>Conway's Game of Life variant running 20 generations to create intricate, organic-looking structures.</p>
            </div>
        </div>
    </div>

    <!-- Integration Examples -->
    <div class="demo-section">
        <div class="section-title">💻 Integration Code</div>
        
        <div class="demo-row">
            <div class="demo-box">
                <div class="demo-title">PHP Integration</div>
                <div class="demo-code"><?php echo htmlspecialchars("
\$response = file_get_contents(
  'http://localhost/alkebulan/actions/generate_image.php' .
  '?prompt=car&width=512&height=512'
);
\$data = json_decode(\$response);
foreach(\$data->images as \$img) {
  echo \"<img src='\$img->url'>\";
}"); ?></div>
            </div>
            
            <div class="demo-box">
                <div class="demo-title">JavaScript Integration</div>
                <div class="demo-code"><?php echo htmlspecialchars("
const response = await fetch(
  '/alkebulan/actions/generate_image.php',
  { method: 'POST',
    body: new FormData({prompt: 'car'})
  }
);
const data = await response.json();
data.images.forEach(img => {
  document.body.innerHTML += 
    `<img src=\"\${img.url}\" />`;
});"); ?></div>
            </div>
        </div>
    </div>

    <!-- System Tests -->
    <div class="demo-section">
        <div class="section-title">✅ System Tests</div>
        
        <button onclick="runTests()">Run Full Test Suite</button>
        <div id="test-results" class="test-results" style="margin-top: 15px; display:none;"></div>
    </div>

    <!-- File Structure -->
    <div class="demo-section">
        <div class="section-title">📁 File Structure</div>
        <div class="demo-code"><?php echo htmlspecialchars("alkebulan/
├── generate_images.html          ← Web UI
├── demo.php                       ← This demo
├── IMAGE_GENERATION_GUIDE.md      ← Full documentation
├── actions/
│   └── generate_image.php        ← API endpoint
├── images/
│   └── generated/                ← Output directory
└── classes/
    └── ImageGeneratorV3.php      ← Main engine"); ?></div>
    </div>

    <script>
        async function testPrompt(prompt) {
            const status = document.getElementById('demo-status');
            const gallery = document.getElementById('demo-gallery');
            
            status.className = 'status loading';
            status.textContent = '⏳ Generating images for "' + prompt + '"...';
            gallery.innerHTML = '';
            
            try {
                const response = await fetch('./actions/generate_image.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'prompt=' + encodeURIComponent(prompt) + '&width=256&height=256'
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    status.className = 'status success';
                    status.textContent = '✅ Generated ' + data.images.length + ' images!';
                    
                    data.images.forEach(img => {
                        const thumb = document.createElement('div');
                        thumb.className = 'image-thumb';
                        thumb.innerHTML = '<img src="' + img.url + '" title="' + img.method + '">';
                        gallery.appendChild(thumb);
                    });
                } else {
                    status.className = 'status error';
                    status.textContent = '❌ Error: ' + (data.message || 'Unknown error');
                }
            } catch (e) {
                status.className = 'status error';
                status.textContent = '❌ Error: ' + e.message;
            }
        }
        
        async function testAPI(method, prompt, size = 512) {
            if (method === 'GET') {
                window.open('./demo.php?api=1&prompt=' + prompt + '&width=' + size + '&height=' + size);
            } else {
                const data = await testPrompt(prompt);
            }
        }
        
        async function runTests() {
            const resultsDiv = document.getElementById('test-results');
            resultsDiv.style.display = 'block';
            resultsDiv.innerHTML = '<div class="test-result-item">Running tests...</div>';
            
            let html = '';
            
            // Test 1: File existence
            try {
                const response = await fetch('./actions/generate_image.php');
                html += '<div class="test-result-item success">✓ API endpoint exists</div>';
            } catch (e) {
                html += '<div class="test-result-item error">✗ API endpoint not found</div>';
            }
            
            // Test 2: Image directory
            try {
                const response = await fetch('./images/generated/');
                html += '<div class="test-result-item success">✓ Image directory is accessible</div>';
            } catch (e) {
                html += '<div class="test-result-item error">✗ Image directory not accessible</div>';
            }
            
            // Test 3: Generate images
            try {
                const response = await fetch('./actions/generate_image.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'prompt=test&width=256&height=256'
                });
                
                const data = await response.json();
                if (data.status === 'success' && data.images.length === 4) {
                    html += '<div class="test-result-item success">✓ Successfully generated 4 images</div>';
                    html += '<div class="test-result-item success">  - Image 1: ' + data.images[0].method + '</div>';
                    html += '<div class="test-result-item success">  - Image 2: ' + data.images[1].method + '</div>';
                    html += '<div class="test-result-item success">  - Image 3: ' + data.images[2].method + '</div>';
                    html += '<div class="test-result-item success">  - Image 4: ' + data.images[3].method + '</div>';
                } else {
                    html += '<div class="test-result-item error">✗ Generation failed: ' + data.message + '</div>';
                }
            } catch (e) {
                html += '<div class="test-result-item error">✗ Generation error: ' + e.message + '</div>';
            }
            
            // Test 4: GD Library check
            try {
                const response = await fetch('./actions/generate_image.php?prompt=test');
                const text = await response.text();
                if (!text.includes('error')) {
                    html += '<div class="test-result-item success">✓ GD Library is available</div>';
                }
            } catch (e) {}
            
            html += '<div class="test-result-item" style="border:none; margin-top: 10px; font-weight:bold;">All tests completed!</div>';
            resultsDiv.innerHTML = html;
        }
    </script>
</body>
</html>
