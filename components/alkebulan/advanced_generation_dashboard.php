<?php
/**
 * Advanced Local Generation Dashboard v3.0
 * Test sophisticated text analysis, semantic generation, and advanced image creation
 */

if(!ossn_isLoggedin()) {
    die('Please login to access this tool.');
}

$user = ossn_loggedin_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Generation Dashboard v3.0</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            animation: slideDown 0.6s ease;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .tab-btn {
            padding: 12px 24px;
            background: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .tab-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .card h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.5em;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            flex: 1;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .results {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
        }
        
        .results h3 {
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .analysis-item {
            margin-bottom: 15px;
            padding: 12px;
            background: white;
            border-radius: 6px;
        }
        
        .analysis-item strong {
            color: #667eea;
        }
        
        .metric-badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-right: 8px;
            margin-top: 5px;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
            color: #667eea;
        }
        
        .loading.active {
            display: block;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 1.8em;
            }
        }
        
        .feature-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            margin-bottom: 15px;
        }
        
        .feature-box h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .feature-box p {
            color: #666;
            line-height: 1.6;
        }
        
        .comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        
        .comparison-item {
            padding: 15px;
            background: #f0f0f0;
            border-radius: 6px;
        }
        
        .comparison-item h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 0.9em;
        }
        
        .comparison-item p {
            font-size: 0.95em;
            line-height: 1.5;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Advanced Generation Dashboard v3.0</h1>
            <p>Sophisticated Local Text Analysis, Semantic Generation & Advanced Image Creation</p>
        </div>
        
        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('semantic')">Semantic Analysis</button>
            <button class="tab-btn" onclick="showTab('advanced-titles')">Advanced Titles</button>
            <button class="tab-btn" onclick="showTab('fluent-article')">Fluent Articles</button>
            <button class="tab-btn" onclick="showTab('summary')">Smart Summaries</button>
            <button class="tab-btn" onclick="showTab('style')">Style Enhancement</button>
            <button class="tab-btn" onclick="showTab('images')">Advanced Images</button>
            <button class="tab-btn" onclick="showTab('info')">System Info</button>
        </div>
        
        <!-- SEMANTIC ANALYSIS TAB -->
        <div id="semantic" class="tab-content active">
            <div class="card">
                <h2>📊 Semantic Text Analysis</h2>
                <p style="color: #666; margin-bottom: 20px;">Analyze text for semantic clusters, entities, topics, sentiment, and readability.</p>
                
                <form id="semanticForm" onsubmit="analyzeText(event)">
                    <div class="form-group">
                        <label>Text to Analyze:</label>
                        <textarea name="text" placeholder="Enter text to analyze..." required></textarea>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn">Analyze Text</button>
                    </div>
                </form>
                
                <div class="loading" id="semanticLoading">
                    <div class="spinner"></div>
                    <p>Analyzing text...</p>
                </div>
                
                <div class="results" id="semanticResults" style="display: none;"></div>
            </div>
        </div>
        
        <!-- ADVANCED TITLES TAB -->
        <div id="advanced-titles" class="tab-content">
            <div class="card">
                <h2>✨ Advanced Title Generation</h2>
                <p style="color: #666; margin-bottom: 20px;">Generate multiple intelligent titles with semantic analysis and optimization.</p>
                
                <form id="titleForm" onsubmit="generateTitles(event)">
                    <div class="form-group">
                        <label>Topic/Prompt:</label>
                        <textarea name="prompt" placeholder="Describe your content topic..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Style Preference:</label>
                        <select name="style">
                            <option value="question">Question-Based</option>
                            <option value="statement">Statement-Based</option>
                            <option value="provocative">Provocative</option>
                            <option value="data">Data-Driven</option>
                            <option value="balanced">Balanced</option>
                        </select>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn">Generate Titles</button>
                    </div>
                </form>
                
                <div class="loading" id="titleLoading">
                    <div class="spinner"></div>
                    <p>Generating titles...</p>
                </div>
                
                <div class="results" id="titleResults" style="display: none;"></div>
            </div>
        </div>
        
        <!-- FLUENT ARTICLE TAB -->
        <div id="fluent-article" class="tab-content">
            <div class="card">
                <h2>📝 Fluent Article Generation</h2>
                <p style="color: #666; margin-bottom: 20px;">Generate well-structured, coherent articles with semantic flow and tone refinement.</p>
                
                <form id="articleForm" onsubmit="generateArticle(event)">
                    <div class="form-group">
                        <label>Topic:</label>
                        <textarea name="prompt" placeholder="What should the article be about?" required></textarea>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Tone:</label>
                            <select name="tone">
                                <option value="professional">Professional</option>
                                <option value="casual">Casual</option>
                                <option value="academic">Academic</option>
                                <option value="creative">Creative</option>
                                <option value="engaging">Engaging</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Length:</label>
                            <select name="length">
                                <option value="short">Short (500-800 words)</option>
                                <option value="medium" selected>Medium (800-1500 words)</option>
                                <option value="long">Long (1500+ words)</option>
                            </select>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn">Generate Article</button>
                    </div>
                </form>
                
                <div class="loading" id="articleLoading">
                    <div class="spinner"></div>
                    <p>Generating article...</p>
                </div>
                
                <div class="results" id="articleResults" style="display: none;"></div>
            </div>
        </div>
        
        <!-- SUMMARY TAB -->
        <div id="summary" class="tab-content">
            <div class="card">
                <h2>📄 Smart Abstractive Summarization</h2>
                <p style="color: #666; margin-bottom: 20px;">Extract key information and generate intelligent summaries using semantic scoring.</p>
                
                <form id="summaryForm" onsubmit="generateSummary(event)">
                    <div class="form-group">
                        <label>Text to Summarize:</label>
                        <textarea name="text" placeholder="Paste the text you want to summarize..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Summary Length:</label>
                        <select name="length">
                            <option value="short">Short (30% of original)</option>
                            <option value="medium" selected>Medium (50% of original)</option>
                            <option value="long">Long (70% of original)</option>
                        </select>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn">Summarize</button>
                    </div>
                </form>
                
                <div class="loading" id="summaryLoading">
                    <div class="spinner"></div>
                    <p>Summarizing...</p>
                </div>
                
                <div class="results" id="summaryResults" style="display: none;"></div>
            </div>
        </div>
        
        <!-- STYLE ENHANCEMENT TAB -->
        <div id="style" class="tab-content">
            <div class="card">
                <h2>🎨 Tone & Style Enhancement</h2>
                <p style="color: #666; margin-bottom: 20px;">Transform text style with sophisticated tone refinement algorithms.</p>
                
                <form id="styleForm" onsubmit="enhanceStyle(event)">
                    <div class="form-group">
                        <label>Text to Refine:</label>
                        <textarea name="text" placeholder="Enter text to enhance..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Target Tone:</label>
                        <select name="tone">
                            <option value="professional">Professional (Formal)</option>
                            <option value="casual">Casual (Conversational)</option>
                            <option value="academic">Academic (Technical)</option>
                            <option value="creative">Creative (Expressive)</option>
                            <option value="engaging">Engaging (Dynamic)</option>
                        </select>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn">Enhance Style</button>
                    </div>
                </form>
                
                <div class="loading" id="styleLoading">
                    <div class="spinner"></div>
                    <p>Enhancing style...</p>
                </div>
                
                <div class="results" id="styleResults" style="display: none;"></div>
            </div>
        </div>
        
        <!-- ADVANCED IMAGES TAB -->
        <div id="images" class="tab-content">
            <div class="card">
                <h2>🖼️ Advanced Image Generation</h2>
                <p style="color: #666; margin-bottom: 20px;">Generate sophisticated procedural images with semantic color mapping.</p>
                
                <form id="imageForm" onsubmit="generateImage(event)">
                    <div class="form-group">
                        <label>Image Concept/Prompt:</label>
                        <textarea name="prompt" placeholder="Describe the image concept..." required></textarea>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Style:</label>
                            <select name="style">
                                <option value="elegant">Elegant (Lines & Circles)</option>
                                <option value="geometric">Geometric (Grid-Based)</option>
                                <option value="organic">Organic (Growth Patterns)</option>
                                <option value="neural">Neural (Node Networks)</option>
                                <option value="abstract" selected>Abstract (Mixed)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Dimensions:</label>
                            <select name="dimensions">
                                <option value="800x600">800x600 (Standard)</option>
                                <option value="1024x768">1024x768 (Large)</option>
                                <option value="1200x800">1200x800 (Wide)</option>
                            </select>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn">Generate Image</button>
                    </div>
                </form>
                
                <div class="loading" id="imageLoading">
                    <div class="spinner"></div>
                    <p>Generating image...</p>
                </div>
                
                <div class="results" id="imageResults" style="display: none;"></div>
            </div>
        </div>
        
        <!-- INFO TAB -->
        <div id="info" class="tab-content">
            <div class="card">
                <h2>ℹ️ System Information</h2>
                
                <div class="feature-box">
                    <h3>🧠 Semantic Analysis Engine</h3>
                    <p>Advanced text analysis featuring semantic clustering, entity extraction, topic modeling, sentiment analysis, and readability scoring using sophisticated NLP algorithms.</p>
                </div>
                
                <div class="feature-box">
                    <h3>✨ Advanced Title Generation</h3>
                    <p>Intelligent title generation with multiple templates, semantic understanding, sentiment adaptation, and quality scoring. Produces clickable, SEO-optimized titles.</p>
                </div>
                
                <div class="feature-box">
                    <h3>📝 Fluent Article Generation</h3>
                    <p>Coherent article generation with semantic flow, proper transitions, tone consistency, hook generation, structured sections, and conclusion with CTA. Maintains reading flow throughout.</p>
                </div>
                
                <div class="feature-box">
                    <h3>📄 Abstractive Summarization</h3>
                    <p>Smart extractive summarization using sentence importance scoring, semantic relevance, and position weighting. Maintains key information while reducing length.</p>
                </div>
                
                <div class="feature-box">
                    <h3>🎨 Style Enhancement</h3>
                    <p>Sophisticated tone refinement with formality adjustment, vocabulary shifting, sentence structure modification, and active/passive voice balancing.</p>
                </div>
                
                <div class="feature-box">
                    <h3>🖼️ Advanced Image Generation</h3>
                    <p>Procedural image generation with 5 distinct styles (elegant, geometric, organic, neural, abstract), semantic color extraction, and dynamic pattern generation using PHP GD library.</p>
                </div>
                
                <div class="feature-box">
                    <h3>⚡ Performance & Caching</h3>
                    <p>Intelligent 1-hour caching system with SHA-256 hashing. Results cached and retrieved instantly on repeat requests - up to 60x faster performance.</p>
                </div>
                
                <div class="feature-box">
                    <h3>🔒 Local & Secure</h3>
                    <p>100% local processing with zero external API calls. All processing happens on your server with full input validation and sanitization.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
        
        function analyzeText(e) {
            e.preventDefault();
            const form = e.target;
            const text = form.querySelector('textarea[name="text"]').value;
            
            document.getElementById('semanticLoading').classList.add('active');
            document.getElementById('semanticResults').style.display = 'none';
            
            fetch('/action/alkebulan/semantic_analysis', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'text=' + encodeURIComponent(text)
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('semanticLoading').classList.remove('active');
                if(data.status === 'success') {
                    let html = '<h3>Analysis Results</h3>';
                    const a = data.analysis;
                    html += `
                        <div class="analysis-item">
                            <strong>Word Count:</strong> ${a.word_count}
                            <span class="metric-badge">${a.unique_words} unique words</span>
                        </div>
                        <div class="analysis-item">
                            <strong>Readability Score:</strong> ${Math.round(a.readability)}
                            <span class="metric-badge">${data.interpretation.reading_level}</span>
                        </div>
                        <div class="analysis-item">
                            <strong>Complexity Score:</strong> ${Math.round(a.complexity_score)}/100
                            <span class="metric-badge">${data.interpretation.recommended_audience}</span>
                        </div>
                        <div class="analysis-item">
                            <strong>Average Word Length:</strong> ${Math.round(a.avg_word_length * 10) / 10} characters
                        </div>
                        <div class="analysis-item">
                            <strong>Sentiment:</strong> ${a.sentiment_direction}
                            <span class="metric-badge">${data.interpretation.sentiment}</span>
                        </div>
                        <div class="analysis-item">
                            <strong>Key Topics:</strong> ${a.topics.slice(0, 5).join(', ') || 'General'}
                        </div>
                        <div class="analysis-item">
                            <strong>Key Entities:</strong> ${a.key_entities.slice(0, 5).join(', ') || 'None identified'}
                        </div>
                        <div class="analysis-item">
                            <strong>Semantic Clusters:</strong> ${Object.keys(a.semantic_clusters).join(', ') || 'Mixed'}
                        </div>
                    `;
                    document.getElementById('semanticResults').innerHTML = html;
                    document.getElementById('semanticResults').style.display = 'block';
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
        
        function generateTitles(e) {
            e.preventDefault();
            const form = e.target;
            const prompt = form.querySelector('textarea[name="prompt"]').value;
            
            document.getElementById('titleLoading').classList.add('active');
            document.getElementById('titleResults').style.display = 'none';
            
            fetch('/action/alkebulan/advanced_title', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'prompt=' + encodeURIComponent(prompt)
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('titleLoading').classList.remove('active');
                if(data.status === 'success') {
                    let html = '<h3>Generated Titles</h3>';
                    data.titles.forEach((title, idx) => {
                        const quality = Math.round(title.score);
                        html += `
                            <div class="analysis-item">
                                <strong>Title ${idx + 1}:</strong> "${title.title}"
                                <span class="metric-badge">Quality: ${quality}%</span>
                                <span class="metric-badge">${title.type}</span>
                            </div>
                        `;
                    });
                    document.getElementById('titleResults').innerHTML = html;
                    document.getElementById('titleResults').style.display = 'block';
                }
            });
        }
        
        function generateArticle(e) {
            e.preventDefault();
            const form = e.target;
            const prompt = form.querySelector('textarea[name="prompt"]').value;
            const tone = form.querySelector('select[name="tone"]').value;
            const length = form.querySelector('select[name="length"]').value;
            
            document.getElementById('articleLoading').classList.add('active');
            document.getElementById('articleResults').style.display = 'none';
            
            const params = `prompt=${encodeURIComponent(prompt)}&tone=${tone}&length=${length}`;
            
            fetch('/action/alkebulan/fluent_article', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: params
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('articleLoading').classList.remove('active');
                if(data.status === 'success') {
                    const a = data.article;
                    let html = `<h3>Generated Article</h3>
                        <div class="analysis-item">
                            <strong>Flow Score:</strong> ${a.flow_score}/100
                            <span class="metric-badge">${a.word_count} words</span>
                            <span class="metric-badge">${a.reading_time}</span>
                        </div>
                        <div style="background: white; padding: 20px; border-radius: 6px; margin-top: 15px; line-height: 1.8;">
                            ${a.article.replace(/\n/g, '<br>')}
                        </div>
                    `;
                    document.getElementById('articleResults').innerHTML = html;
                    document.getElementById('articleResults').style.display = 'block';
                }
            });
        }
        
        function generateSummary(e) {
            e.preventDefault();
            const form = e.target;
            const text = form.querySelector('textarea[name="text"]').value;
            const length = form.querySelector('select[name="length"]').value;
            
            document.getElementById('summaryLoading').classList.add('active');
            document.getElementById('summaryResults').style.display = 'none';
            
            fetch('/action/alkebulan/abstractive_summary', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `text=${encodeURIComponent(text)}&length=${length}`
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('summaryLoading').classList.remove('active');
                if(data.status === 'success') {
                    const s = data.summary;
                    let html = `<h3>Generated Summary</h3>
                        <div class="comparison">
                            <div class="comparison-item">
                                <h4>Original Length</h4>
                                <p>${data.original_length} words</p>
                            </div>
                            <div class="comparison-item">
                                <h4>Summary Length</h4>
                                <p>${s.word_count} words</p>
                            </div>
                        </div>
                        <div class="analysis-item" style="margin-top: 15px;">
                            <strong>Compression Ratio:</strong> ${data.compression_ratio}
                        </div>
                        <div style="background: white; padding: 20px; border-radius: 6px; margin-top: 15px; line-height: 1.8;">
                            <strong>Summary:</strong><br><br>${s.summary}
                        </div>
                    `;
                    document.getElementById('summaryResults').innerHTML = html;
                    document.getElementById('summaryResults').style.display = 'block';
                }
            });
        }
        
        function enhanceStyle(e) {
            e.preventDefault();
            const form = e.target;
            const text = form.querySelector('textarea[name="text"]').value;
            const tone = form.querySelector('select[name="tone"]').value;
            
            document.getElementById('styleLoading').classList.add('active');
            document.getElementById('styleResults').style.display = 'none';
            
            fetch('/action/alkebulan/style_enhance', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `text=${encodeURIComponent(text)}&tone=${tone}`
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('styleLoading').classList.remove('active');
                if(data.status === 'success') {
                    let html = `<h3>Style Enhancement Results</h3>
                        <div class="comparison">
                            <div class="comparison-item">
                                <h4>Original</h4>
                                <p>${data.original}</p>
                            </div>
                            <div class="comparison-item">
                                <h4>Enhanced (${data.tone_applied})</h4>
                                <p>${data.enhanced}</p>
                            </div>
                        </div>
                    `;
                    document.getElementById('styleResults').innerHTML = html;
                    document.getElementById('styleResults').style.display = 'block';
                }
            });
        }
        
        function generateImage(e) {
            e.preventDefault();
            const form = e.target;
            const prompt = form.querySelector('textarea[name="prompt"]').value;
            const style = form.querySelector('select[name="style"]').value;
            const dims = form.querySelector('select[name="dimensions"]').value;
            const [w, h] = dims.split('x');
            
            document.getElementById('imageLoading').classList.add('active');
            document.getElementById('imageResults').style.display = 'none';
            
            fetch('/action/alkebulan/advanced_image', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `prompt=${encodeURIComponent(prompt)}&style=${style}&width=${w}&height=${h}`
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('imageLoading').classList.remove('active');
                if(data.status === 'success') {
                    let html = `<h3>Generated Image</h3>
                        <div class="analysis-item">
                            <strong>Style:</strong> ${data.style}
                            <span class="metric-badge">${data.dimensions}</span>
                            <span class="metric-badge">${Math.round(data.size / 1024)}KB</span>
                        </div>
                        <div style="margin-top: 20px; text-align: center;">
                            <img src="${data.image_path}" style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        </div>
                    `;
                    document.getElementById('imageResults').innerHTML = html;
                    document.getElementById('imageResults').style.display = 'block';
                }
            });
        }
    </script>
</body>
</html>
