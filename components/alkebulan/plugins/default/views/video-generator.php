<?php
/**
 * Alkebulan AI - Video Generator Page
 */

if(!ossn_loggedin_user()) {
    echo "Please log in to use the video generator.";
    return;
}

$current_user = ossn_loggedin_user();
$site_url = ossn_site_url();

?>
<div class="alkebulan-page alkebulan-video-generator">
    <style>
        * { box-sizing: border-box; }
        .video-generator-container {
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
        .input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
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
        .quality-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 8px;
        }
        .quality-option {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            font-size: 12px;
            transition: all 0.3s;
            background: white;
        }
        .quality-option:hover {
            border-color: #667eea;
            background: #f5f7ff;
        }
        .quality-option.active {
            border-color: #667eea;
            background: #667eea;
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
            background: #1a1a1a;
            border: 2px solid #333;
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
            color: #666;
            font-size: 14px;
        }
        .preview-video {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
            background: black;
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
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            margin-top: 10px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            width: 0%;
            animation: none;
        }
        .generation-info {
            background: #f5f5f5;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 13px;
            color: #666;
            display: none;
        }
        .generation-info.show {
            display: block;
        }
        .video-controls {
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
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
        }
        .gallery-item {
            background: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s;
            border: 1px solid #333;
        }
        .gallery-item:hover {
            transform: scale(1.05);
        }
        .gallery-item-thumb {
            width: 100%;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        .gallery-item-info {
            padding: 8px;
            font-size: 11px;
            color: #999;
            background: #222;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
            .video-generator-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <div class="alkebulan-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; color: white; margin-bottom: 30px;">
        <h1 style="margin: 0; font-size: 32px;">Video Generator</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Create stunning videos from text prompts using AI</p>
    </div>
    
    <div class="video-generator-container">
        <div class="generator-panel">
            <div class="generator-header">Video Settings</div>
            
            <form id="videoGeneratorForm" onsubmit="generateVideo(event)">
                <div class="form-group">
                    <label for="prompt">Video Description</label>
                    <textarea id="prompt" name="prompt" placeholder="Describe the video you want to create..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Video Style</label>
                    <div class="style-grid">
                        <div class="style-option active" onclick="selectStyle(this, 'cinematic')">Cinematic</div>
                        <div class="style-option" onclick="selectStyle(this, 'anime')">Anime</div>
                        <div class="style-option" onclick="selectStyle(this, 'documentary')">Documentary</div>
                        <div class="style-option" onclick="selectStyle(this, 'abstract')">Abstract</div>
                        <div class="style-option" onclick="selectStyle(this, 'scifi')">Sci-Fi</div>
                        <div class="style-option" onclick="selectStyle(this, 'nature')">Nature</div>
                    </div>
                    <input type="hidden" id="style" name="style" value="cinematic">
                </div>
                
                <div class="input-row">
                    <div class="form-group">
                        <label for="duration">Duration (seconds)</label>
                        <select id="duration" name="duration">
                            <option value="5">5 seconds</option>
                            <option value="10" selected>10 seconds</option>
                            <option value="15">15 seconds</option>
                            <option value="30">30 seconds</option>
                            <option value="60">60 seconds (1 minute)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fps">Frame Rate</label>
                        <select id="fps" name="fps">
                            <option value="24">24 FPS</option>
                            <option value="30" selected>30 FPS</option>
                            <option value="60">60 FPS</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Quality</label>
                    <div class="quality-options">
                        <div class="quality-option" onclick="selectQuality(this, '720p')">720p</div>
                        <div class="quality-option active" onclick="selectQuality(this, '1080p')">1080p</div>
                        <div class="quality-option" onclick="selectQuality(this, '4k')">4K</div>
                    </div>
                    <input type="hidden" id="quality" name="quality" value="1080p">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="with_music" value="1" checked>
                        Include background music
                    </label>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn-generate" id="generateBtn">Generate Video</button>
                    <button type="button" class="btn-clear" onclick="resetForm()">Clear</button>
                </div>
            </form>
        </div>
        
        <div class="preview-container">
            <div class="preview-title">Preview</div>
            
            <div class="preview-area" id="previewArea">
                <div class="preview-placeholder">Your generated video will appear here...</div>
            </div>
            
            <div id="generationInfo" class="generation-info"></div>
            
            <div id="videoControls" class="video-controls" style="display: none;">
                <button class="btn-download" onclick="downloadVideo()">Download MP4</button>
                <button class="btn-download" style="background: #666;" onclick="shareVideo()">Share</button>
            </div>
        </div>
    </div>
    
    <div style="background: white; padding: 25px; border-radius: 12px; margin-top: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3>Your Statistics</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 20px 0;">
            <div class="stat-card">
                <div class="value" id="statTotal">0</div>
                <div class="label">Videos Generated</div>
            </div>
            <div class="stat-card">
                <div class="value" id="statDuration">0s</div>
                <div class="label">Total Duration</div>
            </div>
            <div class="stat-card">
                <div class="value" id="statAvgTime">0m</div>
                <div class="label">Avg Generation Time</div>
            </div>
        </div>
    </div>
    
    <div style="background: white; padding: 25px; border-radius: 12px; margin-top: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h3>Your Gallery</h3>
        <div id="galleryGrid" class="gallery-grid">
            <div style="grid-column: 1/-1; text-align: center; color: #999; padding: 40px;">
                No videos yet. Create your first AI-generated video!
            </div>
        </div>
    </div>
</div>

<script>
    var currentVideoData = null;
    var generationTimes = [];
    var videoDurations = [];
    var sitePath = '<?php echo $site_url; ?>';
    
    var demoVideos = [
        { id: 1, title: 'Mountain Landscape', duration: 10, style: 'cinematic', thumb: 'Mountain' },
        { id: 2, title: 'Digital Art', duration: 15, style: 'abstract', thumb: 'Digital' },
        { id: 3, title: 'Ocean Waves', duration: 10, style: 'nature', thumb: 'Ocean' },
        { id: 4, title: 'Space Journey', duration: 30, style: 'scifi', thumb: 'Space' },
        { id: 5, title: 'Aurora', duration: 20, style: 'nature', thumb: 'Aurora' }
    ];
    
    function selectStyle(element, style) {
        var options = document.querySelectorAll('.style-option');
        for(var i = 0; i < options.length; i++) {
            options[i].classList.remove('active');
        }
        element.classList.add('active');
        document.getElementById('style').value = style;
    }
    
    function selectQuality(element, quality) {
        var options = document.querySelectorAll('.quality-option');
        for(var i = 0; i < options.length; i++) {
            options[i].classList.remove('active');
        }
        element.classList.add('active');
        document.getElementById('quality').value = quality;
    }
    
    function resetForm() {
        document.getElementById('videoGeneratorForm').reset();
        document.getElementById('style').value = 'cinematic';
        document.getElementById('quality').value = '1080p';
        var options = document.querySelectorAll('.style-option');
        for(var i = 0; i < options.length; i++) {
            options[i].classList.remove('active');
        }
        options[0].classList.add('active');
        var qualityOptions = document.querySelectorAll('.quality-option');
        for(var i = 0; i < qualityOptions.length; i++) {
            qualityOptions[i].classList.remove('active');
        }
        qualityOptions[1].classList.add('active');
        document.getElementById('previewArea').innerHTML = '<div class="preview-placeholder">Your generated video will appear here...</div>';
        document.getElementById('generationInfo').classList.remove('show');
        document.getElementById('videoControls').style.display = 'none';
    }
    
    function generateVideo(e) {
        e.preventDefault();
        
        var prompt = document.getElementById('prompt').value;
        var style = document.getElementById('style').value;
        var duration = parseInt(document.getElementById('duration').value);
        var fps = parseInt(document.getElementById('fps').value);
        var quality = document.getElementById('quality').value;
        var withMusic = document.querySelector('input[name="with_music"]').checked ? 1 : 0;
        
        var btn = document.getElementById('generateBtn');
        btn.disabled = true;
        btn.innerHTML = 'Generating...';
        
        var previewArea = document.getElementById('previewArea');
        previewArea.innerHTML = '<div class="loading-spinner"></div>';
        
        var startTime = Date.now();
        
        var frames = duration * fps;
        var estimatedTime = Math.floor((frames / 30) * 1000) + Math.random() * 2000;
        
        var progressInterval = setInterval(function() {
            var elapsed = Date.now() - startTime;
            var percent = Math.min((elapsed / estimatedTime) * 100, 95);
            var progressBar = document.querySelector('.progress-fill');
            if(progressBar) {
                progressBar.style.width = percent + '%';
            }
        }, 100);
        
        setTimeout(function() {
            clearInterval(progressInterval);
            
            var genTime = Date.now() - startTime;
            generationTimes.push(genTime);
            videoDurations.push(duration);
            
            currentVideoData = {
                id: Math.floor(Math.random() * 10000),
                prompt: prompt,
                style: style,
                duration: duration,
                fps: fps,
                quality: quality,
                with_music: withMusic,
                generation_time: genTime,
                created_at: new Date().toLocaleTimeString()
            };
            
            previewArea.innerHTML = '<div style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; padding: 40px; text-align: center; color: white;"><div style="font-size: 48px; margin-bottom: 10px;">Play Video</div><p>Generated ' + quality + ' video (' + duration + 's)</p></div>';
            
            var genInfo = document.getElementById('generationInfo');
            genInfo.innerHTML = '<strong>Generated:</strong> ' + duration + ' seconds at ' + fps + ' FPS (' + quality + ') | Time: ' + Math.round(genTime / 1000) + 's';
            genInfo.classList.add('show');
            
            document.getElementById('videoControls').style.display = 'flex';
            
            btn.disabled = false;
            btn.innerHTML = 'Generate Video';
            
            updateStats();
            loadGallery();
        }, estimatedTime);
    }
    
    function downloadVideo() {
        if(!currentVideoData) return;
        alert('Video download would start: ' + currentVideoData.prompt + ' (' + currentVideoData.quality + ' - ' + currentVideoData.duration + 's)');
    }
    
    function shareVideo() {
        if(!currentVideoData) return;
        var text = 'Check out this AI-generated video from Alkebulan AI: "' + currentVideoData.prompt + '" (' + currentVideoData.duration + 's)';
        if(navigator.share) {
            navigator.share({
                title: 'Alkebulan AI - Generated Video',
                text: text,
                url: window.location.href
            }).catch(function(err) { console.log('Share cancelled'); });
        } else {
            var textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert('Share text copied to clipboard!');
        }
    }
    
    function loadGallery() {
        try {
            var gallery = document.getElementById('galleryGrid');
            
            if(demoVideos && demoVideos.length > 0) {
                var html = '';
                for(var i = 0; i < demoVideos.length; i++) {
                    var video = demoVideos[i];
                    html += '<div class="gallery-item" onclick="loadVideoToPreview(' + video.id + ')">';
                    html += '<div class="gallery-item-thumb">' + video.thumb + ' Video</div>';
                    html += '<div class="gallery-item-info" title="' + video.title + '">' + video.title + ' (' + video.duration + 's)</div>';
                    html += '</div>';
                }
                gallery.innerHTML = html;
            } else {
                gallery.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: #999; padding: 40px;">No videos yet. Create your first AI-generated video!</div>';
            }
        } catch(error) {
            console.error('Error loading gallery:', error);
        }
    }
    
    function loadVideoToPreview(id) {
        var gallery = document.getElementById('galleryGrid');
        var item = null;
        for(var i = 0; i < demoVideos.length; i++) {
            if(demoVideos[i].id === id) {
                item = demoVideos[i];
                break;
            }
        }
        if(item) {
            document.getElementById('previewArea').innerHTML = '<div style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; padding: 40px; text-align: center; color: white;"><div style="font-size: 48px; margin-bottom: 10px;">' + item.thumb + '</div><p>' + item.title + ' (' + item.duration + 's)</p></div>';
            document.getElementById('generationInfo').classList.remove('show');
            document.getElementById('videoControls').style.display = 'flex';
        }
    }
    
    function updateStats() {
        document.getElementById('statTotal').textContent = generationTimes.length;
        
        var totalDuration = 0;
        for(var i = 0; i < videoDurations.length; i++) {
            totalDuration += videoDurations[i];
        }
        document.getElementById('statDuration').textContent = totalDuration + 's';
        
        if(generationTimes.length > 0) {
            var sum = 0;
            for(var i = 0; i < generationTimes.length; i++) {
                sum += generationTimes[i];
            }
            var avgTime = Math.round(sum / generationTimes.length / 1000);
            document.getElementById('statAvgTime').textContent = avgTime + 'm';
        }
    }
    
    window.addEventListener('DOMContentLoaded', function() {
        loadGallery();
        updateStats();
    });
</script>
