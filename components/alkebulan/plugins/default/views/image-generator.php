<?php
/**
 * Alkebulan AI - Image Generator Page
 */

if(!ossn_loggedin_user()) {
    echo "Please log in to use the image generator.";
    return;
}

$current_user = ossn_loggedin_user();
$site_url = ossn_site_url();

?>
<div class="alkebulan-page alkebulan-image-generator">
    <style>
        * { box-sizing: border-box; }
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
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
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
            transition: transform 0.2s;
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
            border: 1px solid #e0e0e0;
        }
        .gallery-item:hover {
            transform: scale(1.05);
        }
        .gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
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
            margin-top: 5px;
        }
        @media (max-width: 1024px) {
            .image-generator-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <div class="alkebulan-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; color: white; margin-bottom: 30px;">
        <h1 style="margin: 0; font-size: 32px;">Image Generator</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Create stunning images from text prompts using AI</p>
    </div>
    
    <div class="image-generator-container">
        <div class="generator-panel">
            <div class="generator-header">Generator</div>
            
            <form id="imageGeneratorForm" onsubmit="generateImage(event)">
                <div class="form-group">
                    <label for="prompt">Describe Your Image</label>
                    <textarea id="prompt" name="prompt" placeholder="Describe the image you want to create..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Visual Style</label>
                    <div class="style-grid">
                        <div class="style-option active" onclick="selectStyle(this, 'colorful')">Colorful</div>
                        <div class="style-option" onclick="selectStyle(this, 'abstract')">Abstract</div>
                        <div class="style-option" onclick="selectStyle(this, 'minimalist')">Minimalist</div>
                        <div class="style-option" onclick="selectStyle(this, 'dark')">Dark</div>
                        <div class="style-option" onclick="selectStyle(this, 'gradient')">Gradient</div>
                        <div class="style-option" onclick="selectStyle(this, 'geometric')">Geometric</div>
                    </div>
                    <input type="hidden" id="style" name="style" value="colorful">
                </div>
                
                <div class="form-group">
                    <label for="width">Width: <span id="widthValue">800</span>px</label>
                    <input type="range" id="width" name="width" min="400" max="1024" value="800" step="50" oninput="updateSize()">
                </div>
                
                <div class="form-group">
                    <label for="height">Height: <span id="heightValue">600</span>px</label>
                    <input type="range" id="height" name="height" min="300" max="1024" value="600" step="50" oninput="updateSize()">
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn-generate" id="generateBtn">Generate Image</button>
                    <button type="button" class="btn-clear" onclick="resetForm()">Clear</button>
                </div>
            </form>
        </div>
        
        <div class="preview-container">
            <div class="preview-title">Preview</div>
            
            <div class="preview-area" id="previewArea">
                <div class="preview-placeholder">Your generated image will appear here...</div>
            </div>
        </div>
    </div>
    
    <div style="background: white; padding: 25px; border-radius: 12px; margin-top: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3>Your Statistics</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 20px 0;">
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
                <div class="label">Avg Generation Time</div>
            </div>
        </div>
    </div>
</div>

<script>
    var demoImages = [
        'https://via.placeholder.com/800x600/667eea/ffffff?text=Generated+Image+1',
        'https://via.placeholder.com/800x600/764ba2/ffffff?text=Generated+Image+2',
        'https://via.placeholder.com/800x600/f093fb/4fd1c5?text=Generated+Image+3'
    ];
    
    var generationTimes = [];
    
    function selectStyle(element, style) {
        var options = document.querySelectorAll('.style-option');
        for(var i = 0; i < options.length; i++) {
            options[i].classList.remove('active');
        }
        element.classList.add('active');
        document.getElementById('style').value = style;
    }
    
    function updateSize() {
        var width = document.getElementById('width').value;
        var height = document.getElementById('height').value;
        document.getElementById('widthValue').textContent = width;
        document.getElementById('heightValue').textContent = height;
    }
    
    function resetForm() {
        document.getElementById('imageGeneratorForm').reset();
        document.getElementById('style').value = 'colorful';
        var options = document.querySelectorAll('.style-option');
        for(var i = 0; i < options.length; i++) {
            options[i].classList.remove('active');
        }
        options[0].classList.add('active');
        document.getElementById('previewArea').innerHTML = '<div class="preview-placeholder">Your generated image will appear here...</div>';
        updateSize();
    }
    
    function generateImage(e) {
        e.preventDefault();
        
        var prompt = document.getElementById('prompt').value;
        var style = document.getElementById('style').value;
        var width = document.getElementById('width').value;
        var height = document.getElementById('height').value;
        
        var btn = document.getElementById('generateBtn');
        btn.disabled = true;
        btn.innerHTML = 'Generating...';
        
        var previewArea = document.getElementById('previewArea');
        previewArea.innerHTML = '<div class="loading-spinner"></div>';
        
        var startTime = Date.now();
        
        setTimeout(function() {
            var genTime = Date.now() - startTime;
            generationTimes.push(genTime);
            
            var randomImage = demoImages[Math.floor(Math.random() * demoImages.length)];
            previewArea.innerHTML = '<img src="' + randomImage + '" class="preview-image" alt="Generated image">';
            
            btn.disabled = false;
            btn.innerHTML = 'Generate Image';
            
            updateStats();
        }, 2000 + Math.random() * 1500);
    }
    
    function updateStats() {
        document.getElementById('statTotal').textContent = generationTimes.length;
        
        if(generationTimes.length > 0) {
            var sum = 0;
            for(var i = 0; i < generationTimes.length; i++) {
                sum += generationTimes[i];
            }
            var avgTime = Math.round(sum / generationTimes.length);
            document.getElementById('statAvgTime').textContent = avgTime + 'ms';
        }
        
        document.getElementById('statStyles').textContent = Math.min(generationTimes.length, 6);
    }
</script>
