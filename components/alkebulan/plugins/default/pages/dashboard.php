<?php
/**
 * Alkebulan AI Dashboard - Main Dashboard Page
 */
?>

<style>
    .alkebulan-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background: #f8f9fa;
        min-height: calc(100vh - 200px);
    }
    
    .alkebulan-dashboard {
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
    }
    
    .dashboard-header h1 {
        font-size: 2.5em;
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .dashboard-header .subtitle {
        font-size: 1.1em;
        opacity: 0.95;
    }
    
    .header-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    .stat-card {
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        background: rgba(255,255,255,0.15);
    }
    
    .stat-icon {
        font-size: 2em;
    }
    
    .stat-info h3 {
        font-size: 0.9em;
        opacity: 0.9;
        margin-bottom: 5px;
    }
    
    .stat-value {
        font-size: 1.5em;
        font-weight: 700;
    }
    
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        padding: 40px 30px;
    }
    
    .dashboard-section {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: box-shadow 0.3s;
    }
    
    .dashboard-section:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .section-header h2 {
        font-size: 1.5em;
        color: #333;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .feature-card {
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        border-left: 4px solid #667eea;
    }
    
    .feature-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-left-color: #fff;
    }
    
    .feature-icon {
        font-size: 2em;
        margin-bottom: 10px;
    }
    
    .feature-card h3 {
        margin: 10px 0 5px 0;
        font-size: 1.1em;
    }
    
    .feature-card p {
        opacity: 0.8;
        font-size: 0.9em;
    }
    
    .badge {
        display: inline-block;
        padding: 4px 8px;
        background: #11998e;
        color: white;
        border-radius: 20px;
        font-size: 0.75em;
        margin-top: 10px;
        font-weight: 600;
    }
    
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .activity-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #667eea;
    }
    
    .activity-icon {
        font-size: 1.5em;
        min-width: 30px;
        text-align: center;
    }
    
    .activity-content h4 {
        margin-bottom: 5px;
        color: #333;
    }
    
    .activity-content p {
        margin-bottom: 5px;
        color: #666;
        font-size: 0.9em;
    }
    
    .activity-time {
        font-size: 0.8em;
        color: #999;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }
    
    .action-btn {
        padding: 20px;
        border: 2px solid #e9ecef;
        background: white;
        border-radius: 10px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s;
        font-weight: 600;
    }
    
    .action-btn:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
    }
    
    .action-icon {
        font-size: 2em;
        margin-bottom: 10px;
    }
    
    .usage-stats {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .stat {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .stat-name {
        font-weight: 600;
        color: #333;
    }
    
    .stat-bar {
        height: 30px;
        background: #f0f0f0;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .stat-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        transition: width 0.3s;
    }
    
    .stat-value {
        font-size: 0.9em;
        color: #666;
    }
</style>

<div class="alkebulan-container">
    <div class="alkebulan-dashboard">
        <div class="dashboard-header">
            <h1>🤖 Alkebulan AI Dashboard</h1>
            <p class="subtitle">Advanced AI-Powered Analytics & Insights</p>
            <div class="header-stats">
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-info">
                        <h3>Total Analyses</h3>
                        <div class="stat-value">42</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💡</div>
                    <div class="stat-info">
                        <h3>Recommendations</h3>
                        <div class="stat-value">18</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💬</div>
                    <div class="stat-info">
                        <h3>Chat Sessions</h3>
                        <div class="stat-value">7</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⚡</div>
                    <div class="stat-info">
                        <h3>Avg Response</h3>
                        <div class="stat-value">240ms</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- AI Features Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>🎯 AI Features</h2>
                </div>
                <div class="features-grid">
                    <div class="feature-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/assistant'); ?>'">
                        <div class="feature-icon">💬</div>
                        <h3>Chat Assistant</h3>
                        <p>Conversational AI for intelligent conversations</p>
                        <span class="badge">Smart</span>
                    </div>
                    <div class="feature-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/features'); ?>'">
                        <div class="feature-icon">📝</div>
                        <h3>Content Analysis</h3>
                        <p>Sentiment, keywords, and categorization</p>
                    </div>
                    <div class="feature-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/features'); ?>'">
                        <div class="feature-icon">💡</div>
                        <h3>Recommendations</h3>
                        <p>Personalized content, people, and groups</p>
                    </div>
                    <div class="feature-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/image-generator'); ?>'">
                        <div class="feature-icon">🎨</div>
                        <h3>Image Generator</h3>
                        <p>Generate stunning images from text</p>
                        <span class="badge">New</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>📈 Recent Activity</h2>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">✓</div>
                        <div class="activity-content">
                            <h4>Content Analysis</h4>
                            <p>Analyzed 5 posts - 1 positive, 2 neutral</p>
                            <span class="activity-time">2 hours ago</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">→</div>
                        <div class="activity-content">
                            <h4>Recommendations</h4>
                            <p>12 new personalized suggestions</p>
                            <span class="activity-time">4 hours ago</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">💬</div>
                        <div class="activity-content">
                            <h4>Chat Session</h4>
                            <p>Engaged with AI Assistant - 15 messages</p>
                            <span class="activity-time">Yesterday</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>⚡ Quick Access</h2>
                </div>
                <div class="quick-actions">
                    <button class="action-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/features'); ?>'">
                        <div class="action-icon">📝</div>
                        <span>Features</span>
                    </button>
                    <button class="action-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/assistant'); ?>'">
                        <div class="action-icon">💬</div>
                        <span>Chat</span>
                    </button>
                    <button class="action-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/analytics'); ?>'">
                        <div class="action-icon">📊</div>
                        <span>Analytics</span>
                    </button>
                    <button class="action-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/image-generator'); ?>'">
                        <div class="action-icon">🎨</div>
                        <span>Images</span>
                    </button>
                </div>
            </div>

            <!-- Usage Stats -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>📊 Usage This Month</h2>
                </div>
                <div class="usage-stats">
                    <div class="stat">
                        <div class="stat-name">API Calls</div>
                        <div class="stat-bar">
                            <div class="stat-fill" style="width: 65%;"></div>
                        </div>
                        <div class="stat-value">650 / 1000 calls</div>
                    </div>
                    <div class="stat">
                        <div class="stat-name">Tokens Used</div>
                        <div class="stat-bar">
                            <div class="stat-fill" style="width: 45%;"></div>
                        </div>
                        <div class="stat-value">45,000 / 100,000 tokens</div>
                    </div>
                    <div class="stat">
                        <div class="stat-name">Storage Used</div>
                        <div class="stat-bar">
                            <div class="stat-fill" style="width: 30%;"></div>
                        </div>
                        <div class="stat-value">150 MB / 500 MB</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                <div class="stat">
                    <div class="stat-name">API Calls</div>
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: 65%;"></div>
                    </div>
                    <div class="stat-value">650 / 1000</div>
                </div>
                <div class="stat">
                    <div class="stat-name">Tokens Used</div>
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: 45%;"></div>
                    </div>
                    <div class="stat-value">45K / 100K</div>
                </div>
                <div class="stat">
                    <div class="stat-name">Storage Used</div>
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: 30%;"></div>
                    </div>
                    <div class="stat-value">3 GB / 10 GB</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.alkebulan-dashboard {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.dashboard-header {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.header-content h1 {
    font-size: 2.5em;
    color: #333;
    margin: 0 0 10px 0;
}

.header-content .subtitle {
    font-size: 1.1em;
    color: #666;
    margin: 0;
}

.header-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    font-size: 2em;
}

.stat-label {
    font-size: 0.9em;
    opacity: 0.9;
}

.stat-value {
    font-size: 1.8em;
    font-weight: bold;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 30px;
}

.dashboard-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 15px;
}

.section-header h2 {
    margin: 0;
    color: #333;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.feature-card {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.feature-icon {
    font-size: 2.5em;
    margin-bottom: 10px;
}

.feature-card h3 {
    margin: 10px 0 5px 0;
    color: #333;
}

.feature-card p {
    margin: 0;
    font-size: 0.9em;
    color: #666;
}

.badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ff6b6b;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8em;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.activity-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.activity-icon {
    font-size: 1.5em;
    flex-shrink: 0;
}

.activity-content h4 {
    margin: 0 0 5px 0;
    color: #333;
}

.activity-content p {
    margin: 0 0 5px 0;
    color: #666;
    font-size: 0.9em;
}

.activity-time {
    color: #999;
    font-size: 0.85em;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.action-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: transform 0.3s;
    font-weight: 600;
}

.action-btn:hover {
    transform: translateY(-3px);
}

.action-icon {
    font-size: 1.5em;
    margin-bottom: 10px;
    display: block;
}

.usage-stats {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.stat {
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-name {
    flex-shrink: 0;
    width: 120px;
    font-weight: 600;
}

.stat-bar {
    flex: 1;
    height: 20px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.stat-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s;
}

.stat-value {
    flex-shrink: 0;
    width: 120px;
    text-align: right;
    font-weight: 600;
    color: #667eea;
}

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function showAnalyzeModal() {
    alert('Content Analysis Feature');
}

function startNewChat() {
    window.location.href = '<?php echo ossn_site_url('alkebulan:assistant'); ?>';
}

function getRecommendations() {
    alert('Loading Recommendations...');
}

function viewAnalytics() {
    window.location.href = '<?php echo ossn_site_url('alkebulan:analytics'); ?>';
}

function navigateTo(page) {
    alert('Navigating to ' + page);
}
</script>
