<?php
/**
 * FINAL TEST - Generate images and verify system is working
 */

// Set headers
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎨 Image Generation - Final Test</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }
        .container { max-width: 1400px; margin: 0 auto; }
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 40px;
            font-size: 40px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .test-section {
            margin-bottom: 40px;
        }
        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        input[type="text"], select {
            flex: 1;
            min-width: 200px;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
        }
        input[type="text"]:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            padding: 12px 32px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        button:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }
        .quick-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .quick-buttons button {
            padding: 10px 20px;
            font-size: 13px;
            background: #f0f0f0;
            color: #333;
        }
        .quick-buttons button:hover {
            background: #667eea;
            color: white;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .image-card {
            background: #f9f9f9;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .image-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .image-container {
            width: 100%;
            padding-bottom: 100%;
            position: relative;
            background: #000;
        }
        .image-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-info {
            padding: 15px;
        }
        .image-method {
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        .image-size {
            font-size: 12px;
            color: #999;
        }
        .loading {
            text-align: center;
            padding: 40px;
            display: none;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }
        .message.success {
            background: #d4edda;
            border: 1px solid #28a745;
            color: #155724;
            display: block;
        }
        .message.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            display: block;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎨 Image Generation System</h1>
        
        <div class="content">
            <!-- Generation Section -->
            <div class="test-section">
                <div class="section-title">Generate Images</div>
                
                <div class="input-group">
                    <input type="text" id="prompt" placeholder="Enter a prompt..." value="sunset">
                    <select id="size">
                        <option value="256">256×256</option>
                        <option value="512" selected>512×512</option>
                        <option value="768">768×768</option>
                    </select>
                    <button onclick="generateImages()">Generate</button>
                </div>
                
                <div class="quick-buttons">
                    <button onclick="setPrompt('car')">🚗 Car</button>
                    <button onclick="setPrompt('sunset')">🌅 Sunset</button>
                    <button onclick="setPrompt('ocean')">🌊 Ocean</button>
                    <button onclick="setPrompt('forest')">🌲 Forest</button>
                    <button onclick="setPrompt('space')">🌌 Space</button>
                    <button onclick="setPrompt('fire')">🔥 Fire</button>
                </div>
                
                <div id="message" class="message"></div>
                <div id="loading" class="loading">
                    <div class="spinner"></div>
                    <p>Generating images...</p>
                </div>
                
                <div id="stats" class="stats" style="display:none;">
                    <div class="stat-box">
                        <div class="stat-value" id="img-count">0</div>
                        <div class="stat-label">Images</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value" id="gen-time">0ms</div>
                        <div class="stat-label">Time</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value" id="total-size">0KB</div>
                        <div class="stat-label">Size</div>
                    </div>
                </div>
                
                <div id="gallery" class="gallery"></div>
            </div>
        </div>
    </div>

    <script>
        function setPrompt(p) {
            document.getElementById('prompt').value = p;
            generateImages();
        }

        async function generateImages() {
            const prompt = document.getElementById('prompt').value;
            const size = document.getElementById('size').value;
            
            if (!prompt.trim()) {
                showMessage('Please enter a prompt', 'error');
                return;
            }
            
            document.getElementById('loading').style.display = 'block';
            document.getElementById('stats').style.display = 'none';
            document.getElementById('gallery').innerHTML = '';
            document.getElementById('message').style.display = 'none';
            
            const startTime = performance.now();
            
            try {
                const response = await fetch('/alkebulan/actions/generate_image.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `prompt=${encodeURIComponent(prompt)}&width=${size}&height=${size}`
                });
                
                const data = await response.json();
                const endTime = performance.now();
                
                document.getElementById('loading').style.display = 'none';
                
                if (data.status === 'success' && data.images) {
                    displayImages(data.images);
                    
                    const totalSize = data.images.reduce((sum, img) => sum + img.size, 0);
                    const genTime = Math.round(endTime - startTime);
                    
                    document.getElementById('img-count').textContent = data.images.length;
                    document.getElementById('gen-time').textContent = genTime + 'ms';
                    document.getElementById('total-size').textContent = (totalSize / 1024).toFixed(1) + 'KB';
                    document.getElementById('stats').style.display = 'grid';
                    
                    showMessage(`✅ Generated ${data.images.length} images in ${genTime}ms`, 'success');
                } else {
                    showMessage('❌ ' + (data.message || 'Failed to generate images'), 'error');
                }
            } catch (error) {
                document.getElementById('loading').style.display = 'none';
                showMessage('❌ Error: ' + error.message, 'error');
            }
        }

        function displayImages(images) {
            const gallery = document.getElementById('gallery');
            gallery.innerHTML = '';
            
            images.forEach(img => {
                const card = document.createElement('div');
                card.className = 'image-card';
                card.innerHTML = `
                    <div class="image-container">
                        <img src="${img.url}?t=${Date.now()}" alt="${img.method}" loading="lazy">
                    </div>
                    <div class="image-info">
                        <div class="image-method">${img.method}</div>
                        <div class="image-size">${(img.size / 1024).toFixed(1)} KB</div>
                    </div>
                `;
                gallery.appendChild(card);
            });
        }

        function showMessage(msg, type) {
            const el = document.getElementById('message');
            el.textContent = msg;
            el.className = 'message ' + type;
        }

        // Generate on load
        window.addEventListener('load', () => {
            setTimeout(generateImages, 500);
        });
    </script>
</body>
</html>
