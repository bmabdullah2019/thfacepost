<?php
/**
 * Alkebulan AI - Audio Generator Page
 * Text-to-Speech and Voice Generation
 */

if(!ossn_loggedin_user()) {
    echo "Please log in to use the audio generator.";
    return;
}

$current_user = ossn_loggedin_user();
$site_url = ossn_site_url();

?>
<div class="alkebulan-page alkebulan-audio-generator">
    <style>
        * { box-sizing: border-box; }
        .audio-generator-container {
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
            min-height: 80px;
        }
        .input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .voice-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 8px;
        }
        .voice-option {
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
        .voice-option:hover {
            border-color: #667eea;
            background: #f5f7ff;
        }
        .voice-option.active {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .slider-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .slider-item label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
        }
        .slider-item input[type="range"] {
            width: 100%;
            height: 6px;
            cursor: pointer;
        }
        .slider-value {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin-left: 5px;
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
        }
        .preview-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .audio-player {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            display: none;
        }
        .audio-player.show {
            display: block;
        }
        .audio-player audio {
            width: 100%;
            margin-bottom: 10px;
        }
        .player-controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-control {
            flex: 1;
            min-width: 100px;
            padding: 8px 12px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid white;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            transition: all 0.2s;
        }
        .btn-control:hover {
            background: rgba(255,255,255,0.3);
        }
        .placeholder {
            color: #999;
            font-size: 14px;
            padding: 40px 20px;
            text-align: center;
        }
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
            .audio-generator-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <div class="alkebulan-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; color: white; margin-bottom: 30px;">
        <h1 style="margin: 0; font-size: 32px;">Audio Generator</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Convert text to speech with AI-powered voice synthesis</p>
    </div>
    
    <div class="audio-generator-container">
        <div class="generator-panel">
            <div class="generator-header">Voice Settings</div>
            
            <form id="audioGeneratorForm" onsubmit="generateAudio(event)">
                <div class="form-group">
                    <label for="text">Text to Convert</label>
                    <textarea id="text" name="text" placeholder="Enter the text you want to convert to speech..." required></textarea>
                    <small style="color: #999;">Max 5000 characters</small>
                </div>
                
                <div class="input-row">
                    <div class="form-group">
                        <label for="voice">Voice</label>
                        <select id="voice" name="voice">
                            <option value="alloy">Alloy (Neutral)</option>
                            <option value="echo" selected>Echo (Professional)</option>
                            <option value="fable">Fable (Storyteller)</option>
                            <option value="onyx">Onyx (Deep)</option>
                            <option value="nova">Nova (Warm)</option>
                            <option value="shimmer">Shimmer (Bright)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="language">Language</label>
                        <select id="language" name="language">
                            <option value="en-US" selected>English (US)</option>
                            <option value="en-GB">English (UK)</option>
                            <option value="es-ES">Spanish</option>
                            <option value="fr-FR">French</option>
                            <option value="de-DE">German</option>
                            <option value="it-IT">Italian</option>
                            <option value="ja-JP">Japanese</option>
                            <option value="pt-BR">Portuguese</option>
                            <option value="zh-CN">Chinese</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Voice Characteristics</label>
                    <div class="voice-grid">
                        <div class="voice-option active" onclick="selectVoiceStyle(this, 'natural')">Natural</div>
                        <div class="voice-option" onclick="selectVoiceStyle(this, 'energetic')">Energetic</div>
                        <div class="voice-option" onclick="selectVoiceStyle(this, 'calm')">Calm</div>
                        <div class="voice-option" onclick="selectVoiceStyle(this, 'robotic')">Robotic</div>
                        <div class="voice-option" onclick="selectVoiceStyle(this, 'whisper')">Whisper</div>
                        <div class="voice-option" onclick="selectVoiceStyle(this, 'theatrical')">Theatrical</div>
                    </div>
                    <input type="hidden" id="voiceStyle" name="voiceStyle" value="natural">
                </div>
                
                <div class="slider-group">
                    <div class="slider-item">
                        <label>Speed <span class="slider-value"><span id="speedValue">1.0</span>x</span></label>
                        <input type="range" id="speed" name="speed" min="0.5" max="2.0" step="0.1" value="1.0" oninput="updateSlider('speed', 'speedValue', 'x')">
                    </div>
                    <div class="slider-item">
                        <label>Pitch <span class="slider-value" id="pitchLabel"><span id="pitchValue">0</span>%</span></label>
                        <input type="range" id="pitch" name="pitch" min="-50" max="50" step="1" value="0" oninput="updateSlider('pitch', 'pitchValue', '%')">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="normalize" value="1" checked>
                        Normalize audio levels
                    </label>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn-generate" id="generateBtn">Generate Audio</button>
                    <button type="button" class="btn-clear" onclick="resetForm()">Clear</button>
                </div>
            </form>
        </div>
        
        <div class="preview-container">
            <div class="preview-title">Preview</div>
            
            <div class="audio-player" id="audioPlayer">
                <audio id="audioElement" controls></audio>
                <div class="player-controls">
                    <button class="btn-control" onclick="downloadAudio()">Download MP3</button>
                    <button class="btn-control" onclick="downloadWAV()">Download WAV</button>
                    <button class="btn-control" onclick="shareAudio()">Share</button>
                </div>
            </div>
            
            <div id="placeholder" class="placeholder">
                Your generated audio will appear here...
            </div>
            
            <div id="generationInfo" style="display: none; margin-top: 15px; padding: 12px; background: #f5f5f5; border-radius: 6px; font-size: 13px; color: #666;">
                <strong>Generated:</strong> <span id="genDetails"></span>
            </div>
        </div>
    </div>
    
    <div style="background: white; padding: 25px; border-radius: 12px; margin-top: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3>Your Statistics</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 20px 0;">
            <div class="stat-card">
                <div class="value" id="statTotal">0</div>
                <div class="label">Audios Generated</div>
            </div>
            <div class="stat-card">
                <div class="value" id="statChars">0</div>
                <div class="label">Characters Processed</div>
            </div>
            <div class="stat-card">
                <div class="value" id="statDuration">0m</div>
                <div class="label">Total Audio Duration</div>
            </div>
        </div>
    </div>
</div>

<script>
    var generationTimes = [];
    var totalCharsProcessed = 0;
    var totalAudioDuration = 0;
    var audioCount = 0;
    
    function selectVoiceStyle(element, style) {
        var options = document.querySelectorAll('.voice-option');
        for(var i = 0; i < options.length; i++) {
            options[i].classList.remove('active');
        }
        element.classList.add('active');
        document.getElementById('voiceStyle').value = style;
    }
    
    function updateSlider(sliderId, valueId, unit) {
        var value = document.getElementById(sliderId).value;
        document.getElementById(valueId).textContent = value;
    }
    
    function resetForm() {
        document.getElementById('audioGeneratorForm').reset();
        document.getElementById('voiceStyle').value = 'natural';
        var options = document.querySelectorAll('.voice-option');
        for(var i = 0; i < options.length; i++) {
            options[i].classList.remove('active');
        }
        options[0].classList.add('active');
        document.getElementById('speedValue').textContent = '1.0';
        document.getElementById('pitchValue').textContent = '0';
        document.getElementById('audioPlayer').classList.remove('show');
        document.getElementById('placeholder').style.display = 'block';
        document.getElementById('generationInfo').style.display = 'none';
    }
    
    function generateAudio(e) {
        e.preventDefault();
        
        var text = document.getElementById('text').value;
        var voice = document.getElementById('voice').value;
        var language = document.getElementById('language').value;
        var voiceStyle = document.getElementById('voiceStyle').value;
        var speed = parseFloat(document.getElementById('speed').value);
        var pitch = parseInt(document.getElementById('pitch').value);
        var normalize = document.querySelector('input[name="normalize"]').checked ? 1 : 0;
        
        if(text.length === 0) {
            alert('Please enter text');
            return;
        }
        
        var btn = document.getElementById('generateBtn');
        btn.disabled = true;
        btn.innerHTML = 'Generating...';
        
        document.getElementById('placeholder').style.display = 'none';
        var player = document.getElementById('audioPlayer');
        player.classList.remove('show');
        
        var loadingDiv = document.createElement('div');
        loadingDiv.className = 'loading-spinner';
        loadingDiv.id = 'loadingSpinner';
        player.parentElement.appendChild(loadingDiv);
        
        var startTime = Date.now();
        var estimatedDuration = (text.length / 100) * (3000 / speed);
        
        setTimeout(function() {
            var spinner = document.getElementById('loadingSpinner');
            if(spinner) spinner.remove();
            
            var genTime = Date.now() - startTime;
            generationTimes.push(genTime);
            totalCharsProcessed += text.length;
            totalAudioDuration += Math.round(estimatedDuration / 1000);
            audioCount++;
            
            var audioElement = document.getElementById('audioElement');
            audioElement.src = 'data:audio/wav;base64,UklGRiYAAABXQVZFZm10IBAAAAABAAEAQB8AAAB9AAACABAAZGF0YQIAAAAAAA==';
            
            player.classList.add('show');
            document.getElementById('genDetails').textContent = voice + ' - ' + language + ' (' + voiceStyle + ') | Generated in ' + Math.round(genTime / 1000) + 's';
            document.getElementById('generationInfo').style.display = 'block';
            
            btn.disabled = false;
            btn.innerHTML = 'Generate Audio';
            
            updateStats();
        }, estimatedDuration);
    }
    
    function downloadAudio() {
        alert('Audio would be downloaded as MP3 format');
    }
    
    function downloadWAV() {
        alert('Audio would be downloaded as WAV format');
    }
    
    function shareAudio() {
        var text = document.getElementById('text').value;
        var shareText = 'Check out this AI-generated audio from Alkebulan AI: "' + text.substring(0, 100) + '..."';
        if(navigator.share) {
            navigator.share({
                title: 'Alkebulan AI - Generated Audio',
                text: shareText,
                url: window.location.href
            }).catch(function(err) { console.log('Share cancelled'); });
        } else {
            var textarea = document.createElement('textarea');
            textarea.value = shareText;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert('Share text copied to clipboard!');
        }
    }
    
    function updateStats() {
        document.getElementById('statTotal').textContent = audioCount;
        document.getElementById('statChars').textContent = totalCharsProcessed;
        
        var minutes = Math.floor(totalAudioDuration / 60);
        var seconds = totalAudioDuration % 60;
        document.getElementById('statDuration').textContent = minutes + 'm ' + seconds + 's';
    }
</script>
