<?php
/**
 * Alkebulan AI - Content Analyzer
 * Text analysis, sentiment detection, keyword extraction
 */

if(!ossn_loggedin_user()) {
    echo "Please log in to use the content analyzer.";
    return;
}

$current_user = ossn_loggedin_user();
$site_url = ossn_site_url();

?>
<div class="alkebulan-page alkebulan-analyzer">
    <style>
        * { box-sizing: border-box; }
        .analyzer-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 20px 0;
        }
        .analyzer-panel {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .panel-header {
            font-size: 20px;
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
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            resize: vertical;
            min-height: 120px;
            transition: border-color 0.3s;
        }
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        .analysis-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .tab-btn {
            padding: 8px 16px;
            background: #f5f5f5;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s;
        }
        .tab-btn:hover {
            background: #e0e0e0;
        }
        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-analyze {
            width: 100%;
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
        .btn-analyze:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-analyze:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .results-panel {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .result-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
        }
        .result-card.show {
            display: block;
        }
        .result-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 12px;
        }
        .metric {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .metric-item {
            background: rgba(255,255,255,0.1);
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 13px;
        }
        .metric-label {
            opacity: 0.9;
            font-size: 12px;
        }
        .metric-value {
            font-weight: bold;
            font-size: 16px;
        }
        .sentiment-bar {
            width: 100%;
            height: 8px;
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
        }
        .sentiment-fill {
            height: 100%;
            background: #4CAF50;
            width: 0%;
            transition: width 0.5s;
        }
        .keywords-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        .keyword-tag {
            background: rgba(255,255,255,0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            border: 1px solid rgba(255,255,255,0.4);
        }
        .loading-spinner {
            width: 40px;
            height: 40px;
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
        .stat-badge {
            display: inline-block;
            background: rgba(0,0,0,0.2);
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin: 2px;
        }
        @media (max-width: 1024px) {
            .analyzer-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <div class="alkebulan-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; color: white; margin-bottom: 30px;">
        <h1 style="margin: 0; font-size: 32px;">Content Analyzer</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Analyze text for sentiment, keywords, readability, and more</p>
    </div>
    
    <div class="analyzer-container">
        <div class="analyzer-panel">
            <div class="panel-header">Input Content</div>
            
            <div class="analysis-tabs">
                <button class="tab-btn active" onclick="selectAnalysis(this, 'all')">All</button>
                <button class="tab-btn" onclick="selectAnalysis(this, 'sentiment')">Sentiment</button>
                <button class="tab-btn" onclick="selectAnalysis(this, 'keywords')">Keywords</button>
                <button class="tab-btn" onclick="selectAnalysis(this, 'readability')">Readability</button>
            </div>
            
            <div class="form-group">
                <label for="content">Paste your text here</label>
                <textarea id="content" placeholder="Enter the text you want to analyze..."></textarea>
                <small style="color: #999;">Max 10000 characters</small>
            </div>
            
            <button class="btn-analyze" onclick="analyzeContent()" id="analyzeBtn">Analyze</button>
        </div>
        
        <div class="results-panel">
            <div class="panel-header">Analysis Results</div>
            
            <div id="loadingSpinner" class="loading-spinner" style="display: none;"></div>
            
            <div id="sentimentResult" class="result-card">
                <div class="result-title">Sentiment Analysis</div>
                <div class="metric">
                    <div class="metric-item">
                        <div class="metric-label">Overall Sentiment</div>
                        <div class="metric-value" id="sentimentType">Positive</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Confidence</div>
                        <div class="metric-value" id="sentimentScore">85%</div>
                    </div>
                </div>
                <div class="sentiment-bar">
                    <div class="sentiment-fill" id="sentimentBar"></div>
                </div>
                <div style="margin-top: 12px; font-size: 13px;">
                    <strong>Breakdown:</strong><br>
                    Positive: <span class="stat-badge" id="posScore">50%</span>
                    Neutral: <span class="stat-badge" id="neuScore">35%</span>
                    Negative: <span class="stat-badge" id="negScore">15%</span>
                </div>
            </div>
            
            <div id="keywordsResult" class="result-card">
                <div class="result-title">Key Insights</div>
                <div class="metric">
                    <div class="metric-item">
                        <div class="metric-label">Top Keywords</div>
                        <div id="topKeywords" class="keywords-list"></div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Entities</div>
                        <div id="topEntities" class="keywords-list"></div>
                    </div>
                </div>
            </div>
            
            <div id="readabilityResult" class="result-card">
                <div class="result-title">Readability Metrics</div>
                <div class="metric">
                    <div class="metric-item">
                        <div class="metric-label">Reading Level</div>
                        <div class="metric-value" id="readingLevel">High School</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Flesch Index</div>
                        <div class="metric-value" id="fleschIndex">65</div>
                    </div>
                </div>
                <div class="metric">
                    <div class="metric-item">
                        <div class="metric-label">Word Count</div>
                        <div class="metric-value" id="wordCount">0</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Avg Word Length</div>
                        <div class="metric-value" id="avgWordLength">0</div>
                    </div>
                </div>
                <div class="metric">
                    <div class="metric-item">
                        <div class="metric-label">Sentence Count</div>
                        <div class="metric-value" id="sentenceCount">0</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Avg Sentence Length</div>
                        <div class="metric-value" id="avgSentenceLength">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var analysisType = 'all';
    
    function selectAnalysis(element, type) {
        var tabs = document.querySelectorAll('.tab-btn');
        for(var i = 0; i < tabs.length; i++) {
            tabs[i].classList.remove('active');
        }
        element.classList.add('active');
        analysisType = type;
    }
    
    function analyzeContent() {
        var content = document.getElementById('content').value.trim();
        
        if(content.length === 0) {
            alert('Please enter some text to analyze');
            return;
        }
        
        var btn = document.getElementById('analyzeBtn');
        btn.disabled = true;
        btn.innerHTML = 'Analyzing...';
        
        document.getElementById('loadingSpinner').style.display = 'block';
        
        setTimeout(function() {
            document.getElementById('loadingSpinner').style.display = 'none';
            
            // Sentiment Analysis
            var words = content.toLowerCase().split(/\s+/);
            var positiveWords = ['good', 'great', 'excellent', 'amazing', 'wonderful', 'fantastic', 'awesome', 'love', 'best'];
            var negativeWords = ['bad', 'terrible', 'awful', 'horrible', 'worst', 'hate', 'poor', 'disappointing'];
            
            var posCount = 0, negCount = 0;
            for(var i = 0; i < words.length; i++) {
                if(positiveWords.includes(words[i])) posCount++;
                if(negativeWords.includes(words[i])) negCount++;
            }
            
            var sentiment = posCount > negCount ? 'Positive' : (negCount > posCount ? 'Negative' : 'Neutral');
            var sentimentScore = Math.min(100, Math.max(0, 50 + (posCount - negCount) * 5));
            
            document.getElementById('sentimentType').textContent = sentiment;
            document.getElementById('sentimentScore').textContent = Math.round(sentimentScore) + '%';
            document.getElementById('sentimentBar').style.width = sentimentScore + '%';
            document.getElementById('sentimentBar').style.background = sentiment === 'Positive' ? '#4CAF50' : (sentiment === 'Negative' ? '#FF6B6B' : '#FFC107');
            
            document.getElementById('posScore').textContent = Math.round((posCount / words.length) * 100) + '%';
            document.getElementById('negScore').textContent = Math.round((negCount / words.length) * 100) + '%';
            document.getElementById('neuScore').textContent = Math.round(100 - ((posCount + negCount) / words.length) * 100) + '%';
            
            // Keywords
            var keywordMap = {};
            for(var i = 0; i < words.length; i++) {
                var word = words[i].replace(/[^a-z0-9]/g, '');
                if(word.length > 3) {
                    keywordMap[word] = (keywordMap[word] || 0) + 1;
                }
            }
            
            var sortedKeywords = Object.keys(keywordMap).sort(function(a, b) {
                return keywordMap[b] - keywordMap[a];
            }).slice(0, 5);
            
            var keywordHtml = '';
            for(var i = 0; i < sortedKeywords.length; i++) {
                keywordHtml += '<span class="keyword-tag">' + sortedKeywords[i] + ' (' + keywordMap[sortedKeywords[i]] + ')</span>';
            }
            document.getElementById('topKeywords').innerHTML = keywordHtml;
            document.getElementById('topEntities').innerHTML = '<span class="keyword-tag">Entity 1</span><span class="keyword-tag">Entity 2</span><span class="keyword-tag">Entity 3</span>';
            
            // Readability
            var wordCount = words.length;
            var sentenceCount = content.split(/[.!?]+/).filter(function(s) { return s.trim().length > 0; }).length;
            var avgWordLength = content.length / wordCount;
            var avgSentenceLength = wordCount / sentenceCount;
            var fleschIndex = Math.max(0, Math.min(100, 206.835 - 1.015 * avgSentenceLength - 84.6 * (avgWordLength / 5)));
            
            document.getElementById('wordCount').textContent = wordCount;
            document.getElementById('sentenceCount').textContent = sentenceCount;
            document.getElementById('avgWordLength').textContent = avgWordLength.toFixed(1);
            document.getElementById('avgSentenceLength').textContent = avgSentenceLength.toFixed(1);
            document.getElementById('fleschIndex').textContent = Math.round(fleschIndex);
            
            var readingLevel = fleschIndex > 60 ? 'High School' : (fleschIndex > 30 ? 'College' : 'Graduate');
            document.getElementById('readingLevel').textContent = readingLevel;
            
            // Show results
            if(analysisType === 'all' || analysisType === 'sentiment') {
                document.getElementById('sentimentResult').classList.add('show');
            }
            if(analysisType === 'all' || analysisType === 'keywords') {
                document.getElementById('keywordsResult').classList.add('show');
            }
            if(analysisType === 'all' || analysisType === 'readability') {
                document.getElementById('readabilityResult').classList.add('show');
            }
            
            btn.disabled = false;
            btn.innerHTML = 'Analyze';
        }, 1500);
    }
</script>
