<?php
/**
 * Alkebulan AI - Image Generator Page
 * Interactive interface for generating images from text prompts
 */

// Check user is logged in
if(!ossn_loggedin_user()) {
    echo "Please log in to use the image generator.";
    return;
}

?>
<div class="alkebulan-page alkebulan-image-generator">
    <style>
        .image-generator-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 20px 0;
        }
        
        .generator-panel {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .generator-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .generator-header::before {
            content: "🎨";
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 18px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
        }
        
        .form-group textarea,
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            transition: border-color 0.3s;
        }
        
        .form-group textarea:focus,
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
            font-family: Arial, sans-serif;
        }
        
        .form-group input[type="range"] {
            width: 100%;
            height: 6px;
            cursor: pointer;
        }
        
        .size-display {
            display: flex;
            gap: 15px;
            margin-top: 8px;
            font-size: 13px;
            color: #666;
        }
        
        .style-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 8px;
        }
        
        .style-option {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            background: white;
        }
        
        .style-option:hover {
            border-color: #667eea;
            background: #f5f7ff;
        }
        
        .style-option.active {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-generate {
            flex: 1;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            font-size: 14px;
        }
        
        .btn-generate:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-generate:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .btn-clear {
            padding: 12px 20px;
            background: #f5f5f5;
            color: #333;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        .btn-clear:hover {
            background: #e0e0e0;
        }
        
        .preview-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .preview-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .preview-title::before {
            content: "👁️";
            font-size: 28px;
        }
        
        .preview-area {
            background: #f9f9f9;
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 40px 20px;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            position: relative;
        }
        
        .preview-placeholder {
            color: #999;
            font-size: 14px;
        }
        
        .preview-image {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .generation-time {
            color: #999;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .prompt-display {
            background: #f5f5f5;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 13px;
            color: #666;
            word-break: break-word;
        }
        
        .prompt-display strong {
            color: #333;
        }
        
        .download-section {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-download {
            flex: 1;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            font-size: 13px;
        }
        
        .btn-download:hover {
            background: #45a049;
        }
        
        .gallery-section {
            margin-top: 40px;
        }
        
        .gallery-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .gallery-item {
            background: #f5f5f5;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s;
            position: relative;
        }
        
        .gallery-item:hover {
            transform: scale(1.05);
        }
        
        .gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        
        .gallery-item-info {
            padding: 8px;
            font-size: 11px;
            color: #666;
            background: white;
        }
        
        .gallery-item-delete {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255,0,0,0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
        }
        
        .gallery-item:hover .gallery-item-delete {
            opacity: 1;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
        }
        
        .stat-card .label {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .error-message {
            background: #fee;
            color: #c00;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            display: none;
        }
        
        .error-message.show {
            display: block;
        }
        
        .success-message {
            background: #efe;
            color: #060;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            display: none;
        }
        
        .success-message.show {
            display: block;
        }
        
        @media (max-width: 1024px) {
            .image-generator-container {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .style-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
    
    <div class="alkebulan-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; color: white; margin-bottom: 30px;">
        <h1 style="margin: 0; font-size: 32px;">🎨 Image Generator</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Create stunning images from text prompts using AI</p>
    </div>
    
    <div id="errorMessage" class="error-message"></div>
    <div id="successMessage" class="success-message"></div>
    
    <div class="image-generator-container">
        <!-- Left Panel: Generator Form -->
        <div class="generator-panel">
            <div class="generator-header">Generator</div>
            
            <form id="imageGeneratorForm" onsubmit="generateImage(event)">
                <!-- Prompt Input -->
                <div class="form-group">
                    <label for="prompt">Describe Your Image *</label>
                    <textarea id="prompt" name="prompt" placeholder="e.g., A serene mountain landscape with snow-capped peaks..." required></textarea>
                    <small style="color: #999;">Max 500 characters</small>
                </div>
                
                <!-- Style Selection -->
                <div class="form-group">
                    <label>Visual Style</label>
                    <div class="style-grid">
                        <div class="style-option active" onclick="selectStyle(this, 'colorful')">🌈 Colorful</div>
                        <div class="style-option" onclick="selectStyle(this, 'abstract')">✨ Abstract</div>
                        <div class="style-option" onclick="selectStyle(this, 'minimalist')">■ Minimalist</div>
                        <div class="style-option" onclick="selectStyle(this, 'dark')">🌙 Dark</div>
                        <div class="style-option" onclick="selectStyle(this, 'gradient')">🎯 Gradient</div>
                        <div class="style-option" onclick="selectStyle(this, 'geometric')">◼ Geometric</div>
                    </div>
                    <input type="hidden" id="style" name="style" value="colorful">
                </div>
                
                <!-- Size Settings -->
                <div class="form-group">
                    <label for="width">Width: <span id="widthValue">800</span>px</label>
                    <input type="range" id="width" name="width" min="400" max="1024" value="800" step="50" oninput="updateSize()">
                </div>
                
                <div class="form-group">
                    <label for="height">Height: <span id="heightValue">600</span>px</label>
                    <input type="range" id="height" name="height" min="300" max="1024" value="600" step="50" oninput="updateSize()">
                </div>
                
                <div class="size-display">
                    <span>Ratio: <strong id="ratio">4:3</strong></span>
                    <span>Size: <strong id="sizeDisplay">800×600</strong></span>
                </div>
                
                <!-- Public Option -->
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_public" value="1" checked>
                        Make image public (visible in community)
                    </label>
                </div>
                
                <!-- Action Buttons -->
                <div class="button-group">
                    <button type="submit" class="btn-generate" id="generateBtn">Generate Image</button>
                    <button type="button" class="btn-clear" onclick="resetForm()">Clear</button>
                </div>
            </form>
        </div>
        
        <!-- Right Panel: Preview -->
        <div class="preview-container">
            <div class="preview-title">Preview</div>
            
            <div class="preview-area" id="previewArea">
                <div class="preview-placeholder">Your generated image will appear here...</div>
            </div>
            
            <div id="generationTime" class="generation-time" style="display: none;"></div>
            
            <div id="promptDisplay" class="prompt-display" style="display: none;">
                <strong>Prompt:</strong> <span id="promptText"></span>
            </div>
            
            <div id="downloadSection" class="download-section" style="display: none;">
                <button class="btn-download" onclick="downloadImage()">⬇ Download</button>
                <button class="btn-download" style="background: #666;" onclick="shareImage()">↗ Share</button>
            </div>
        </div>
    </div>
    
    <!-- Statistics Section -->
    <div style="background: white; padding: 25px; border-radius: 12px; margin-top: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div class="gallery-title">📊 Your Statistics</div>
        <div id="statsCards" class="stats-cards">
            <div class="stat-card">
                <div class="value" id="statTotal">0</div>
                <div class="label">Images Generated</div>
            </div>
            <div class="stat-card">
                <div class="value" id="statStyles">0</div>
                <div class="label">Styles Used</div>
            </div>
            <div class="stat-card">
                <div class="value" id="statAvgTime">0ms</div>
                <div class="label">Avg Generation</div>
            </div>
        </div>
    </div>
    
    <!-- Gallery Section -->
    <div style="background: white; padding: 25px; border-radius: 12px; margin-top: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div class="gallery-title">🖼️ Your Gallery</div>
        <div id="galleryGrid" class="gallery-grid">
            <div style="grid-column: 1/-1; text-align: center; color: #999; padding: 40px;">
                Loading gallery...
            </div>
        </div>
    </div>
</div>

<script>
    let currentImageData = null;
    const sitePath = '<?php echo ossn_site_url(); ?>';
    
    function selectStyle(element, style) {
        document.querySelectorAll('.style-option').forEach(opt => opt.classList.remove('active'));
        element.classList.add('active');
        document.getElementById('style').value = style;
    }
    
    function updateSize() {
        const width = document.getElementById('width').value;
        const height = document.getElementById('height').value;
        
        document.getElementById('widthValue').textContent = width;
        document.getElementById('heightValue').textContent = height;
        document.getElementById('sizeDisplay').textContent = width + '×' + height;
        
        // Calculate ratio
        const gcd = (a, b) => b === 0 ? a : gcd(b, a % b);
        const divisor = gcd(width, height);
        const ratio = (width/divisor) + ':' + (height/divisor);
        document.getElementById('ratio').textContent = ratio;
    }
    
    function resetForm() {
        document.getElementById('imageGeneratorForm').reset();
        document.getElementById('style').value = 'colorful';
        document.querySelectorAll('.style-option').forEach(opt => opt.classList.remove('active'));
        document.querySelector('[onclick="selectStyle(this, \'colorful\')"]').classList.add('active');
        document.getElementById('previewArea').innerHTML = '<div class="preview-placeholder">Your generated image will appear here...</div>';
        document.getElementById('generationTime').style.display = 'none';
        document.getElementById('promptDisplay').style.display = 'none';
        document.getElementById('downloadSection').style.display = 'none';
        updateSize();
    }
    
    function showError(message) {
        const err = document.getElementById('errorMessage');
        err.textContent = message;
        err.classList.add('show');
        setTimeout(() => err.classList.remove('show'), 5000);
    }
    
    function showSuccess(message) {
        const msg = document.getElementById('successMessage');
        msg.textContent = message;
        msg.classList.add('show');
        setTimeout(() => msg.classList.remove('show'), 5000);
    }
    
    async function generateImage(e) {
        e.preventDefault();
        
        const prompt = document.getElementById('prompt').value;
        const style = document.getElementById('style').value;
        const width = document.getElementById('width').value;
        const height = document.getElementById('height').value;
        const isPublic = document.querySelector('input[name="is_public"]').checked ? 1 : 0;
        
        const btn = document.getElementById('generateBtn');
        btn.disabled = true;
        btn.innerHTML = '<div style="display: inline-block; margin-right: 8px;"><div class="loading-spinner" style="width: 20px; height: 20px; border-width: 2px;"></div></div>Generating...';
        
        const previewArea = document.getElementById('previewArea');
        previewArea.innerHTML = '<div class="loading-spinner"></div>';
        
        try {
            const response = await fetch(sitePath + 'action/alkebulan/image/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    prompt: prompt,
                    style: style,
                    width: width,
                    height: height,
                    is_public: isPublic
                })
            });
            
            const data = await response.json();
            
            if(data.status === 'success') {
                currentImageData = data;
                
                previewArea.innerHTML = '<img src="' + data.preview_url + '?t=' + Date.now() + '" class="preview-image" alt="Generated image">';
                
                document.getElementById('generationTime').innerHTML = '⏱️ Generated in ' + data.generation_time;
                document.getElementById('generationTime').style.display = 'block';
                
                document.getElementById('promptText').textContent = data.prompt;
                document.getElementById('promptDisplay').style.display = 'block';
                
                document.getElementById('downloadSection').style.display = 'flex';
                
                showSuccess('Image generated successfully!');
                loadGallery();
                loadStats();
            } else {
                showError(data.message || 'Failed to generate image');
                previewArea.innerHTML = '<div class="preview-placeholder">Failed to generate image</div>';
            }
        } catch(error) {
            showError('Error: ' + error.message);
            previewArea.innerHTML = '<div class="preview-placeholder">Error generating image</div>';
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Generate Image';
        }
    }
    
    function downloadImage() {
        if(!currentImageData) return;
        
        window.location.href = sitePath + 'action/alkebulan/image/download?image_id=' + currentImageData.image_id;
    }
    
    function shareImage() {
        if(!currentImageData) return;
        
        const text = 'Check out this AI-generated image from Alkebulan AI: "' + currentImageData.prompt + '"';
        
        if(navigator.share) {
            navigator.share({
                title: 'Alkebulan AI - Generated Image',
                text: text
            });
        } else {
            alert(text);
        }
    }
    
    async function loadGallery() {
        try {
            const response = await fetch(sitePath + 'action/alkebulan/image/gallery?limit=12');
            const data = await response.json();
            
            const gallery = document.getElementById('galleryGrid');
            
            if(data.images && data.images.length > 0) {
                gallery.innerHTML = data.images.map(img => `
                    <div class="gallery-item">
                        <img src="${img.preview_url || sitePath + 'cache/alkebulan_images/' + img.filename}" alt="Gallery item">
                        <div class="gallery-item-info">${img.prompt.substring(0, 20)}...</div>
                        <button class="gallery-item-delete" onclick="deleteImage(${img.id})">✕</button>
                    </div>
                `).join('');
            } else {
                gallery.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: #999; padding: 40px;">No images yet. Create your first one!</div>';
            }
        } catch(error) {
            console.error('Error loading gallery:', error);
        }
    }
    
    async function deleteImage(imageId) {
        if(!confirm('Delete this image?')) return;
        
        try {
            const response = await fetch(sitePath + 'action/alkebulan/image/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'image_id=' + imageId
            });
            
            const data = await response.json();
            
            if(data.status === 'success') {
                showSuccess('Image deleted');
                loadGallery();
            } else {
                showError(data.message || 'Failed to delete image');
            }
        } catch(error) {
            showError('Error: ' + error.message);
        }
    }
    
    async function loadStats() {
        try {
            const response = await fetch(sitePath + 'action/alkebulan/image/stats');
            const data = await response.json();
            
            if(data.stats) {
                document.getElementById('statTotal').textContent = data.stats.total_generated;
                document.getElementById('statStyles').textContent = data.stats.styles_used;
                document.getElementById('statAvgTime').textContent = Math.round(data.stats.avg_gen_time) + 'ms';
            }
        } catch(error) {
            console.error('Error loading stats:', error);
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSize();
        loadGallery();
        loadStats();
    });
</script>
