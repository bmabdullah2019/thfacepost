<?php
/**
 * Alkebulan AI - Insights & Recommendations
 */

if(!ossn_loggedin_user()) {
    echo "Please log in to view insights.";
    return;
}

$current_user = ossn_loggedin_user();
$site_url = ossn_site_url();

?>
<div class="alkebulan-page alkebulan-insights">
    <style>
        * { box-sizing: border-box; }
        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .insight-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .insight-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .card-icon {
            font-size: 24px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .card-description {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        .metric-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        .metric-row:last-child {
            border-bottom: none;
        }
        .metric-label {
            color: #666;
        }
        .metric-value {
            font-weight: bold;
            color: #333;
        }
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            margin: 10px 0;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            width: 0%;
        }
        .tag {
            display: inline-block;
            background: #f0f0f0;
            color: #333;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin: 2px;
            margin-bottom: 10px;
        }
        .btn-explore {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: transform 0.2s;
        }
        .btn-explore:hover {
            transform: scale(1.02);
        }
        .trending-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .trending-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .trending-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .trending-item:last-child {
            border-bottom: none;
        }
        .trending-rank {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        .trending-name {
            flex: 1;
        }
        .trending-metric {
            font-size: 12px;
            color: #667eea;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .insights-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <div class="alkebulan-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; color: white; margin-bottom: 30px;">
        <h1 style="margin: 0; font-size: 32px;">AI Insights & Recommendations</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Personalized insights powered by AI analysis</p>
    </div>
    
    <div class="insights-grid">
        <div class="insight-card">
            <div class="card-header">
                <span class="card-icon">📊</span>
                <span class="card-title">Content Performance</span>
            </div>
            <div class="card-description">Your content is performing well. High engagement rate detected.</div>
            <div class="metric-row">
                <span class="metric-label">Engagement Rate</span>
                <span class="metric-value">78%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 78%;"></div>
            </div>
            <div class="metric-row">
                <span class="metric-label">Peak Time</span>
                <span class="metric-value">8-10 PM</span>
            </div>
            <button class="btn-explore">View Details</button>
        </div>
        
        <div class="insight-card">
            <div class="card-header">
                <span class="card-icon">🎯</span>
                <span class="card-title">Top Topics</span>
            </div>
            <div class="card-description">Most discussed topics in your network.</div>
            <div style="margin-bottom: 10px;">
                <span class="tag">AI Technology</span>
                <span class="tag">Web Development</span>
                <span class="tag">Data Science</span>
                <span class="tag">Cloud Computing</span>
            </div>
            <div class="metric-row">
                <span class="metric-label">Total Topics</span>
                <span class="metric-value">24</span>
            </div>
            <button class="btn-explore">Explore Topics</button>
        </div>
        
        <div class="insight-card">
            <div class="card-header">
                <span class="card-icon">👥</span>
                <span class="card-title">Audience Growth</span>
            </div>
            <div class="card-description">Your audience is growing steadily.</div>
            <div class="metric-row">
                <span class="metric-label">New Followers</span>
                <span class="metric-value">+145</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 62%;"></div>
            </div>
            <div class="metric-row">
                <span class="metric-label">Growth Rate</span>
                <span class="metric-value">12% /mo</span>
            </div>
            <button class="btn-explore">View Growth</button>
        </div>
        
        <div class="insight-card">
            <div class="card-header">
                <span class="card-icon">💡</span>
                <span class="card-title">Recommendations</span>
            </div>
            <div class="card-description">AI-generated suggestions for your next post.</div>
            <div style="margin-bottom: 10px;">
                <span class="tag">Post in evening</span>
                <span class="tag">Use video format</span>
                <span class="tag">Include hashtags</span>
            </div>
            <div class="metric-row">
                <span class="metric-label">Success Rate</span>
                <span class="metric-value">85%</span>
            </div>
            <button class="btn-explore">Get Suggestions</button>
        </div>
        
        <div class="insight-card">
            <div class="card-header">
                <span class="card-icon">⚡</span>
                <span class="card-title">Content Trends</span>
            </div>
            <div class="card-description">What type of content works best.</div>
            <div class="metric-row">
                <span class="metric-label">Best Format</span>
                <span class="metric-value">Video</span>
            </div>
            <div class="metric-row">
                <span class="metric-label">Best Length</span>
                <span class="metric-value">5-10 min</span>
            </div>
            <div class="metric-row">
                <span class="metric-label">Best Day</span>
                <span class="metric-value">Wednesday</span>
            </div>
            <button class="btn-explore">View Trends</button>
        </div>
        
        <div class="insight-card">
            <div class="card-header">
                <span class="card-icon">🔍</span>
                <span class="card-title">Competitor Analysis</span>
            </div>
            <div class="card-description">How you compare to similar creators.</div>
            <div class="metric-row">
                <span class="metric-label">Your Rank</span>
                <span class="metric-value">#42</span>
            </div>
            <div class="metric-row">
                <span class="metric-label">Average Followers</span>
                <span class="metric-value">12.5K</span>
            </div>
            <div class="metric-row">
                <span class="metric-label">Engagement vs Avg</span>
                <span class="metric-value">+15%</span>
            </div>
            <button class="btn-explore">Compare</button>
        </div>
    </div>
    
    <div class="trending-section">
        <h2 style="margin-top: 0; color: #333;">Trending Now</h2>
        <ul class="trending-list">
            <li class="trending-item">
                <div style="display: flex; align-items: center; flex: 1;">
                    <div class="trending-rank">1</div>
                    <div class="trending-name">
                        <strong>#ArtificialIntelligence</strong><br>
                        <small style="color: #999;">15.2K posts today</small>
                    </div>
                </div>
                <div class="trending-metric">📈 +45%</div>
            </li>
            <li class="trending-item">
                <div style="display: flex; align-items: center; flex: 1;">
                    <div class="trending-rank">2</div>
                    <div class="trending-name">
                        <strong>#WebDevelopment</strong><br>
                        <small style="color: #999;">8.9K posts today</small>
                    </div>
                </div>
                <div class="trending-metric">📈 +32%</div>
            </li>
            <li class="trending-item">
                <div style="display: flex; align-items: center; flex: 1;">
                    <div class="trending-rank">3</div>
                    <div class="trending-name">
                        <strong>#DataScience</strong><br>
                        <small style="color: #999;">7.3K posts today</small>
                    </div>
                </div>
                <div class="trending-metric">📈 +28%</div>
            </li>
            <li class="trending-item">
                <div style="display: flex; align-items: center; flex: 1;">
                    <div class="trending-rank">4</div>
                    <div class="trending-name">
                        <strong>#CloudComputing</strong><br>
                        <small style="color: #999;">6.1K posts today</small>
                    </div>
                </div>
                <div class="trending-metric">📈 +18%</div>
            </li>
            <li class="trending-item">
                <div style="display: flex; align-items: center; flex: 1;">
                    <div class="trending-rank">5</div>
                    <div class="trending-name">
                        <strong>#TechNews</strong><br>
                        <small style="color: #999;">5.8K posts today</small>
                    </div>
                </div>
                <div class="trending-metric">📈 +12%</div>
            </li>
        </ul>
    </div>
</div>
