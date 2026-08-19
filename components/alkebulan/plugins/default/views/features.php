<?php
/**
 * Alkebulan AI Features Page - Showcase all AI features
 */
?>

<div class="alkebulan-features">
    <div class="features-hero">
        <h1>✨ Alkebulan AI Features</h1>
        <p>Powerful AI-driven capabilities for your social network</p>
    </div>

    <!-- Content Analysis -->
    <div class="feature-section">
        <div class="feature-content">
            <div class="feature-text">
                <h2>📝 Intelligent Content Analysis</h2>
                <p>Automatically analyze your content with advanced AI algorithms:</p>
                <ul class="feature-list">
                    <li>✓ <strong>Sentiment Analysis</strong> - Detect positive, negative, and neutral sentiments</li>
                    <li>✓ <strong>Emotion Recognition</strong> - Identify emotions like happiness, sadness, anger</li>
                    <li>✓ <strong>Entity Recognition</strong> - Extract persons, places, and organizations</li>
                    <li>✓ <strong>Keyword Extraction</strong> - Discover important keywords and topics</li>
                    <li>✓ <strong>Content Categorization</strong> - Auto-categorize into tech, business, health, etc.</li>
                </ul>
                <div class="demo-form">
                    <textarea placeholder="Enter text to analyze..." id="analyze-text"></textarea>
                    <button class="btn btn-primary" onclick="analyzeText()">Analyze Now</button>
                </div>
                <div id="analysis-result" class="analysis-result"></div>
            </div>
            <div class="feature-visual">
                <div class="visual-placeholder">
                    <div class="chart-bar">
                        <div class="bar positive" style="height: 70%;">Positive 70%</div>
                    </div>
                    <div class="chart-bar">
                        <div class="bar neutral" style="height: 20%;">Neutral 20%</div>
                    </div>
                    <div class="chart-bar">
                        <div class="bar negative" style="height: 10%;">Negative 10%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="feature-section alternate">
        <div class="feature-content">
            <div class="feature-visual">
                <div class="recommendations-preview">
                    <div class="rec-item">
                        <span class="rec-score">9.2</span>
                        <span class="rec-title">Recommended Post</span>
                    </div>
                    <div class="rec-item">
                        <span class="rec-score">8.7</span>
                        <span class="rec-title">Follow User</span>
                    </div>
                    <div class="rec-item">
                        <span class="rec-score">8.5</span>
                        <span class="rec-title">Join Group</span>
                    </div>
                </div>
            </div>
            <div class="feature-text">
                <h2>💡 Smart Recommendations</h2>
                <p>Get personalized recommendations based on your interests:</p>
                <ul class="feature-list">
                    <li>✓ <strong>Content Recommendations</strong> - Discover relevant posts and articles</li>
                    <li>✓ <strong>People Suggestions</strong> - Find interesting users to follow</li>
                    <li>✓ <strong>Community Discovery</strong> - Join groups that match your interests</li>
                    <li>✓ <strong>Trending Topics</strong> - Stay updated with what's trending</li>
                    <li>✓ <strong>Timeline Feed</strong> - Personalized content feed powered by AI</li>
                </ul>
                <button class="btn btn-primary" onclick="getRecommendations()">Get My Recommendations</button>
            </div>
        </div>
    </div>

    <!-- Chat Assistant -->
    <div class="feature-section">
        <div class="feature-content">
            <div class="feature-text">
                <h2>💬 AI Chat Assistant</h2>
                <p>Chat with our intelligent assistant for instant help:</p>
                <ul class="feature-list">
                    <li>✓ <strong>Conversational AI</strong> - Natural language conversations</li>
                    <li>✓ <strong>Intent Detection</strong> - Understanding what you're asking</li>
                    <li>✓ <strong>Context Awareness</strong> - Remembers conversation context</li>
                    <li>✓ <strong>Smart Suggestions</strong> - Get suggestions for next steps</li>
                    <li>✓ <strong>Session Summaries</strong> - Automatic conversation summaries</li>
                </ul>
                <button class="btn btn-primary" onclick="startChat()">Start Chat Now</button>
            </div>
            <div class="feature-visual">
                <div class="chat-preview">
                    <div class="message user">
                        <span>Hello! What can you help me with?</span>
                    </div>
                    <div class="message ai">
                        <span>I can help with content analysis, recommendations, and more!</span>
                    </div>
                    <div class="message user">
                        <span>That sounds great!</span>
                    </div>
                    <div class="message ai">
                        <span>Would you like to try analyzing some content?</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics -->
    <div class="feature-section alternate">
        <div class="feature-visual">
            <div class="analytics-preview">
                <div class="chart">
                    <div class="chart-bar" style="height: 60%;"></div>
                    <div class="chart-bar" style="height: 80%;"></div>
                    <div class="chart-bar" style="height: 40%;"></div>
                    <div class="chart-bar" style="height: 70%;"></div>
                </div>
                <p style="text-align: center; color: #666; margin-top: 10px;">Usage Trends</p>
            </div>
        </div>
        <div class="feature-text">
            <h2>📊 Advanced Analytics</h2>
            <p>Get detailed insights into your AI usage and performance:</p>
            <ul class="feature-list">
                <li>✓ <strong>Usage Statistics</strong> - Track API calls and token usage</li>
                <li>✓ <strong>Performance Metrics</strong> - Monitor response times and speed</li>
                <li>✓ <strong>Trending Topics</strong> - See what's trending in your content</li>
                <li>✓ <strong>Sentiment Trends</strong> - Track sentiment patterns over time</li>
                <li>✓ <strong>Report Generation</strong> - Create comprehensive reports</li>
            </ul>
            <button class="btn btn-primary" onclick="viewAnalytics()">View Analytics Dashboard</button>
        </div>
    </div>

    <!-- Feature Comparison Table -->
    <div class="features-comparison">
        <h2>Feature Comparison</h2>
        <div class="comparison-table">
            <div class="table-header">
                <div class="table-col">Feature</div>
                <div class="table-col">Basic</div>
                <div class="table-col">Pro</div>
                <div class="table-col">Enterprise</div>
            </div>
            <div class="table-row">
                <div class="table-col"><strong>Content Analysis</strong></div>
                <div class="table-col">✓</div>
                <div class="table-col">✓</div>
                <div class="table-col">✓</div>
            </div>
            <div class="table-row">
                <div class="table-col"><strong>Recommendations</strong></div>
                <div class="table-col">✓</div>
                <div class="table-col">✓</div>
                <div class="table-col">✓</div>
            </div>
            <div class="table-row">
                <div class="table-col"><strong>Chat Assistant</strong></div>
                <div class="table-col">Limited</div>
                <div class="table-col">✓</div>
                <div class="table-col">✓</div>
            </div>
            <div class="table-row">
                <div class="table-col"><strong>Analytics</strong></div>
                <div class="table-col">Basic</div>
                <div class="table-col">✓</div>
                <div class="table-col">✓</div>
            </div>
            <div class="table-row">
                <div class="table-col"><strong>API Access</strong></div>
                <div class="table-col">-</div>
                <div class="table-col">✓</div>
                <div class="table-col">✓</div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <h2>Ready to experience Alkebulan AI?</h2>
        <p>Start using AI-powered features today</p>
        <div class="cta-buttons">
            <button class="btn btn-primary btn-lg" onclick="startChat()">Start Chat</button>
            <button class="btn btn-secondary btn-lg" onclick="analyzeDemo()">Try Analysis</button>
            <button class="btn btn-secondary btn-lg" onclick="viewAnalytics()">View Analytics</button>
        </div>
    </div>
</div>

<style>
.alkebulan-features {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 20px;
    min-height: 100vh;
}

.features-hero {
    text-align: center;
    color: white;
    margin-bottom: 60px;
    animation: slideDown 0.6s ease;
}

.features-hero h1 {
    font-size: 3em;
    margin: 0 0 10px 0;
}

.features-hero p {
    font-size: 1.2em;
    opacity: 0.9;
    margin: 0;
}

.feature-section {
    background: white;
    border-radius: 12px;
    padding: 40px;
    margin-bottom: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.feature-section.alternate {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.feature-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: center;
}

.feature-text h2 {
    font-size: 2em;
    color: #333;
    margin: 0 0 20px 0;
}

.feature-text p {
    color: #666;
    font-size: 1.1em;
    margin: 0 0 20px 0;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0 0 20px 0;
}

.feature-list li {
    padding: 10px 0;
    color: #444;
    font-size: 1em;
}

.demo-form {
    margin-top: 20px;
}

.demo-form textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 1em;
    font-family: Arial, sans-serif;
    resize: vertical;
    min-height: 80px;
    margin-bottom: 10px;
}

.demo-form textarea:focus {
    outline: none;
    border-color: #667eea;
}

.analysis-result {
    margin-top: 15px;
    padding: 15px;
    background: #f0f0f0;
    border-radius: 8px;
    display: none;
}

.analysis-result.show {
    display: block;
}

.feature-visual {
    display: flex;
    align-items: center;
    justify-content: center;
}

.visual-placeholder {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    width: 100%;
}

.chart-bar {
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    height: 150px;
    background: #f0f0f0;
    border-radius: 8px;
    position: relative;
}

.bar {
    border-radius: 8px 8px 0 0;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.9em;
    padding-bottom: 5px;
}

.bar.positive {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.bar.neutral {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.bar.negative {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.recommendations-preview {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.rec-item {
    background: white;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.rec-score {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2em;
}

.rec-title {
    flex: 1;
    color: #333;
    font-weight: 600;
}

.chat-preview {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    height: 300px;
    overflow-y: auto;
}

.message {
    margin-bottom: 15px;
    display: flex;
    animation: fadeIn 0.3s ease;
}

.message.user {
    justify-content: flex-end;
}

.message span {
    max-width: 80%;
    padding: 12px 15px;
    border-radius: 12px;
    word-wrap: break-word;
}

.message.user span {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.message.ai span {
    background: #e9ecef;
    color: #333;
}

.analytics-preview {
    background: white;
    padding: 20px;
    border-radius: 8px;
    height: 200px;
    display: flex;
    align-items: flex-end;
    gap: 10px;
}

.analytics-preview .chart {
    display: flex;
    align-items: flex-end;
    gap: 10px;
    flex: 1;
    height: 100%;
}

.analytics-preview .chart-bar {
    flex: 1;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px 8px 0 0;
}

.features-comparison {
    max-width: 1000px;
    margin: 60px auto;
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.features-comparison h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

.comparison-table {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.table-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 20px;
    padding: 15px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0;
    font-weight: bold;
}

.table-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 20px;
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    align-items: center;
    text-align: center;
}

.table-row .table-col:first-child {
    text-align: left;
}

.table-header .table-col,
.table-row .table-col {
    padding: 10px;
}

.cta-section {
    text-align: center;
    color: white;
    margin: 60px auto;
    max-width: 800px;
}

.cta-section h2 {
    font-size: 2.5em;
    margin: 0 0 10px 0;
}

.cta-section p {
    font-size: 1.2em;
    margin: 0 0 30px 0;
    opacity: 0.9;
}

.cta-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1em;
    font-weight: 600;
    transition: transform 0.3s, box-shadow 0.3s;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-secondary {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-lg {
    padding: 15px 30px;
    font-size: 1.1em;
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
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .feature-content {
        grid-template-columns: 1fr;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 1fr;
    }
    
    .cta-buttons {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function analyzeText() {
    const text = document.getElementById('analyze-text').value;
    if (!text.trim()) {
        alert('Please enter some text to analyze');
        return;
    }
    
    // Simulate analysis
    const result = document.getElementById('analysis-result');
    result.innerHTML = `
        <div style="padding: 15px;">
            <h4 style="margin: 0 0 10px 0;">Analysis Results:</h4>
            <p><strong>Sentiment:</strong> Positive (85% confidence)</p>
            <p><strong>Emotion:</strong> Happy, Excited</p>
            <p><strong>Keywords:</strong> AI, awesome, features, great</p>
            <p><strong>Category:</strong> Technology</p>
        </div>
    `;
    result.classList.add('show');
}

function startChat() {
    window.location.href = '<?php echo ossn_site_url('alkebulan:assistant'); ?>';
}

function getRecommendations() {
    alert('Loading personalized recommendations...');
}

function viewAnalytics() {
    window.location.href = '<?php echo ossn_site_url('alkebulan:analytics'); ?>';
}

function analyzeDemo() {
    document.getElementById('analyze-text').value = 'I absolutely love this amazing feature! It is incredible and works perfectly. Highly recommended!';
    analyzeText();
}
</script>
