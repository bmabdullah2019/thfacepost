<?php
/**
 * Alkebulan AI Dashboard - Professional Profile-like Interface
 * Enhanced UI matching OSSN style with teal/purple gradient theme
 */
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        background: #f5f5f5;
    }
    
    .alkebulan-container {
        max-width: 100%;
        background: #f5f5f5;
        min-height: calc(100vh - 60px);
    }
    
    /* Cover Header */
    .ai-cover {
        background: linear-gradient(135deg, #1a9b8e 0%, #2d6a64 50%, #1a3a33 100%);
        height: 200px;
        position: relative;
        overflow: hidden;
    }
    
    .ai-cover::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.3;
        background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 200"><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="%23fff" stroke-width="0.5"/></pattern><rect width="1200" height="200" fill="url(%23grid)"/></svg>');
    }
    
    /* Profile Section */
    .ai-profile {
        background: white;
        padding: 30px 40px;
        display: grid;
        grid-template-columns: 200px 1fr auto;
        gap: 40px;
        align-items: start;
        border-bottom: 1px solid #ddd;
    }
    
    .ai-avatar {
        width: 180px;
        height: 180px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 80px;
        border: 4px solid #e0e0e0;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    }
    
    .ai-info h1 {
        font-size: 2em;
        color: #333;
        margin-bottom: 8px;
        font-weight: 700;
    }
    
    .ai-info p {
        color: #666;
        font-size: 1em;
        margin-bottom: 15px;
    }
    
    .ai-stats {
        display: flex;
        gap: 40px;
        margin-top: 20px;
    }
    
    .stat {
        text-align: left;
    }
    
    .stat-val {
        font-size: 1.6em;
        font-weight: 700;
        color: #667eea;
    }
    
    .stat-lbl {
        color: #999;
        font-size: 0.85em;
        margin-top: 5px;
    }
    
    .ai-btns {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        font-size: 0.9em;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
        background: #f0f0f0;
        color: #333;
        border: 1px solid #ddd;
    }
    
    .btn-secondary:hover {
        background: #e0e0e0;
    }
    
    /* Main Layout */
    .ai-body {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 20px;
        padding: 20px 40px;
    }
    
    /* Features Grid */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .feat-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 25px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }
    
    .feat-card:hover {
        transform: translateY(-8px);
        border-color: #667eea;
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.15);
        background: #fafafa;
    }
    
    .feat-icon {
        font-size: 2.5em;
        margin-bottom: 15px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .feat-card h3 {
        font-size: 1.1em;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }
    
    .feat-card p {
        color: #666;
        font-size: 0.9em;
        flex-grow: 1;
        margin-bottom: 15px;
    }
    
    .feat-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9em;
        display: inline-flex;
        gap: 5px;
    }
    
    .feat-link:hover {
        gap: 10px;
    }
    
    .section-title {
        font-size: 1.2em;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }
    
    .quick-btns {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 20px;
    }
    
    .quick-btn {
        padding: 15px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s;
        font-weight: 600;
        color: #333;
    }
    
    .quick-btn:hover {
        border-color: #667eea;
        background: #fafafa;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.15);
    }
    
    .quick-icon {
        font-size: 1.8em;
        margin-bottom: 8px;
    }
    
    /* Sidebar */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 20px;
    }
    
    .card h3 {
        font-size: 1em;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
        color: #667eea;
        font-weight: 700;
    }
    
    .card-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
        font-size: 0.9em;
    }
    
    .card-item:last-child {
        border-bottom: none;
    }
    
    .card-label {
        color: #666;
    }
    
    .card-value {
        font-weight: 600;
        color: #667eea;
    }
    
    .usage-item {
        margin-bottom: 15px;
    }
    
    .usage-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 0.9em;
    }
    
    .usage-name {
        color: #333;
        font-weight: 600;
    }
    
    .usage-pct {
        color: #667eea;
        font-weight: 600;
    }
    
    .bar {
        height: 8px;
        background: #f0f0f0;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }
    
    .activity-card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .activity-card h3 {
        font-size: 1em;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
        color: #667eea;
        font-weight: 700;
    }
    
    .activity-item {
        display: flex;
        gap: 12px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .activity-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .activity-icon {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9em;
        flex-shrink: 0;
    }
    
    .activity-content h4 {
        font-size: 0.9em;
        margin-bottom: 3px;
        color: #333;
        font-weight: 600;
    }
    
    .activity-content p {
        font-size: 0.85em;
        color: #666;
        margin: 0 0 5px 0;
    }
    
    .activity-time {
        font-size: 0.8em;
        color: #999;
    }
    
    @media (max-width: 768px) {
        .ai-profile {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .ai-body {
            grid-template-columns: 1fr;
            padding: 20px;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-btns {
            grid-template-columns: 1fr;
        }
        
        .ai-stats {
            gap: 20px;
        }
    }
</style>

<div class="alkebulan-container">
    <!-- Cover -->
    <div class="ai-cover"></div>
    
    <!-- Profile Section -->
    <div class="ai-profile">
        <!-- Avatar -->
        <div class="ai-avatar">🤖</div>
        
        <!-- Info -->
        <div class="ai-info">
            <h1>Alkebulan AI</h1>
            <p>Advanced AI-Powered Analytics & Insights</p>
            
            <div class="ai-stats">
                <div class="stat">
                    <div class="stat-val">42</div>
                    <div class="stat-lbl">Analyses</div>
                </div>
                <div class="stat">
                    <div class="stat-val">18</div>
                    <div class="stat-lbl">Recommendations</div>
                </div>
                <div class="stat">
                    <div class="stat-val">7</div>
                    <div class="stat-lbl">Sessions</div>
                </div>
                <div class="stat">
                    <div class="stat-val">240ms</div>
                    <div class="stat-lbl">Avg Response</div>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="ai-btns">
            <button class="btn btn-primary" onclick="location.href='<?php echo ossn_site_url('alkebulan/assistant'); ?>'">Start Chat</button>
            <button class="btn btn-secondary" onclick="location.href='<?php echo ossn_site_url('alkebulan/analytics'); ?>'">Analytics</button>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="ai-body">
        <div>
            <!-- Features -->
            <h2 class="section-title">🎯 AI Features</h2>
            <div class="features-grid">
                <div class="feat-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/assistant'); ?>'">
                    <div class="feat-icon">💬</div>
                    <h3>Chat Assistant</h3>
                    <p>Conversational AI for intelligent discussions</p>
                    <a href="<?php echo ossn_site_url('alkebulan/assistant'); ?>" class="feat-link">Learn more →</a>
                </div>
                
                <div class="feat-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/features'); ?>'">
                    <div class="feat-icon">📝</div>
                    <h3>Content Analysis</h3>
                    <p>Sentiment, keywords, categorization</p>
                    <a href="<?php echo ossn_site_url('alkebulan/features'); ?>" class="feat-link">Learn more →</a>
                </div>
                
                <div class="feat-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/features'); ?>'">
                    <div class="feat-icon">💡</div>
                    <h3>Recommendations</h3>
                    <p>Smart content & people suggestions</p>
                    <a href="<?php echo ossn_site_url('alkebulan/features'); ?>" class="feat-link">Learn more →</a>
                </div>
                
                <div class="feat-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/image-generator'); ?>'">
                    <div class="feat-icon">🎨</div>
                    <h3>Image Generator</h3>
                    <p>Create AI-generated images from text</p>
                    <a href="<?php echo ossn_site_url('alkebulan/image-generator'); ?>" class="feat-link">Learn more →</a>
                </div>
                
                <div class="feat-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/video-generator'); ?>'">
                    <div class="feat-icon">🎬</div>
                    <h3>Video Generator</h3>
                    <p>Create AI videos with cinematic quality</p>
                    <a href="<?php echo ossn_site_url('alkebulan/video-generator'); ?>" class="feat-link">Learn more →</a>
                </div>
                
                <div class="feat-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/audio-generator'); ?>'">
                    <div class="feat-icon">🎵</div>
                    <h3>Audio Generator</h3>
                    <p>Text-to-speech with natural voices</p>
                    <a href="<?php echo ossn_site_url('alkebulan/audio-generator'); ?>" class="feat-link">Learn more →</a>
                </div>
                
                <div class="feat-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/analyzer'); ?>'">
                    <div class="feat-icon">🔍</div>
                    <h3>Content Analyzer</h3>
                    <p>Sentiment, keywords, readability</p>
                    <a href="<?php echo ossn_site_url('alkebulan/analyzer'); ?>" class="feat-link">Learn more →</a>
                </div>
                
                <div class="feat-card" onclick="location.href='<?php echo ossn_site_url('alkebulan/insights'); ?>'">
                    <div class="feat-icon">💡</div>
                    <h3>AI Insights</h3>
                    <p>Personalized AI recommendations</p>
                    <a href="<?php echo ossn_site_url('alkebulan/insights'); ?>" class="feat-link">Learn more →</a>
                </div>
            </div>
            
            <!-- Quick Access -->
            <h2 class="section-title" style="margin-top: 40px;">⚡ Quick Access</h2>
            <div class="quick-btns">
                <button class="quick-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/assistant'); ?>'">
                    <div class="quick-icon">💬</div>Chat
                </button>
                <button class="quick-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/image-generator'); ?>'">
                    <div class="quick-icon">🎨</div>Images
                </button>
                <button class="quick-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/video-generator'); ?>'">
                    <div class="quick-icon">🎬</div>Videos
                </button>
                <button class="quick-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/audio-generator'); ?>'">
                    <div class="quick-icon">🎵</div>Audio
                </button>
                <button class="quick-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/analyzer'); ?>'">
                    <div class="quick-icon">🔍</div>Analyze
                </button>
                <button class="quick-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/insights'); ?>'">
                    <div class="quick-icon">💡</div>Insights
                </button>
                <button class="quick-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/features'); ?>'">
                    <div class="quick-icon">📝</div>Features
                </button>
                <button class="quick-btn" onclick="location.href='<?php echo ossn_site_url('alkebulan/analytics'); ?>'">
                    <div class="quick-icon">📊</div>Analytics
                </button>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Stats -->
            <div class="card">
                <h3>📊 This Month</h3>
                <div class="card-item">
                    <span class="card-label">Analyses</span>
                    <span class="card-value">42</span>
                </div>
                <div class="card-item">
                    <span class="card-label">Recommendations</span>
                    <span class="card-value">18</span>
                </div>
                <div class="card-item">
                    <span class="card-label">Images</span>
                    <span class="card-value">12</span>
                </div>
                <div class="card-item">
                    <span class="card-label">Videos</span>
                    <span class="card-value">8</span>
                </div>
                <div class="card-item">
                    <span class="card-label">Audio</span>
                    <span class="card-value">15</span>
                </div>
                <div class="card-item">
                    <span class="card-label">Sessions</span>
                    <span class="card-value">7</span>
                </div>
            </div>
            
            <!-- Usage -->
            <div class="card">
                <h3>⚙️ Usage</h3>
                <div class="usage-item">
                    <div class="usage-header">
                        <span class="usage-name">API Calls</span>
                        <span class="usage-pct">65%</span>
                    </div>
                    <div class="bar">
                        <div class="bar-fill" style="width: 65%;"></div>
                    </div>
                </div>
                <div class="usage-item">
                    <div class="usage-header">
                        <span class="usage-name">Tokens</span>
                        <span class="usage-pct">45%</span>
                    </div>
                    <div class="bar">
                        <div class="bar-fill" style="width: 45%;"></div>
                    </div>
                </div>
                <div class="usage-item">
                    <div class="usage-header">
                        <span class="usage-name">Storage</span>
                        <span class="usage-pct">30%</span>
                    </div>
                    <div class="bar">
                        <div class="bar-fill" style="width: 30%;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Activity -->
            <div class="activity-card">
                <h3>📈 Activity</h3>
                <div class="activity-item">
                    <div class="activity-icon">✓</div>
                    <div class="activity-content">
                        <h4>Analysis Complete</h4>
                        <p>5 posts analyzed</p>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">→</div>
                    <div class="activity-content">
                        <h4>Recommendations</h4>
                        <p>12 new suggestions</p>
                        <div class="activity-time">4 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">💬</div>
                    <div class="activity-content">
                        <h4>Chat Session</h4>
                        <p>15 messages</p>
                        <div class="activity-time">Yesterday</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
