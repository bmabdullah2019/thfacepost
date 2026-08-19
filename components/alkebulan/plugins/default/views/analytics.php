<?php
/**
 * Alkebulan AI Analytics Dashboard - Advanced Analytics and Reporting
 */
?>

<div class="alkebulan-analytics">
    <div class="analytics-header">
        <h1>📊 Alkebulan AI Analytics</h1>
        <p>Track your AI usage, performance, and insights</p>
        
        <div class="analytics-controls">
            <div class="period-selector">
                <button class="period-btn active" onclick="selectPeriod(this, 'today')">Today</button>
                <button class="period-btn" onclick="selectPeriod(this, 'week')">Week</button>
                <button class="period-btn" onclick="selectPeriod(this, 'month')">Month</button>
                <button class="period-btn" onclick="selectPeriod(this, 'year')">Year</button>
            </div>
            <div class="analytics-actions">
                <button class="btn btn-primary" onclick="generateReport()">Generate Report</button>
                <button class="btn btn-secondary" onclick="exportData()">Export CSV</button>
            </div>
        </div>
    </div>

    <div class="analytics-grid">
        <!-- Key Metrics -->
        <div class="analytics-card">
            <h3>📈 Key Metrics</h3>
            <div class="metrics-grid">
                <div class="metric-box">
                    <div class="metric-label">Total API Calls</div>
                    <div class="metric-value">1,234</div>
                    <div class="metric-change positive">↑ 12% from last period</div>
                </div>
                <div class="metric-box">
                    <div class="metric-label">Tokens Used</div>
                    <div class="metric-value">45.2K</div>
                    <div class="metric-change positive">↑ 8% from last period</div>
                </div>
                <div class="metric-box">
                    <div class="metric-label">Avg Response Time</div>
                    <div class="metric-value">245ms</div>
                    <div class="metric-change positive">↓ 5% faster</div>
                </div>
                <div class="metric-box">
                    <div class="metric-label">Success Rate</div>
                    <div class="metric-value">99.8%</div>
                    <div class="metric-change positive">↑ 0.2% improvement</div>
                </div>
            </div>
        </div>

        <!-- Usage by Feature -->
        <div class="analytics-card">
            <h3>🎯 Usage by Feature</h3>
            <div class="feature-usage">
                <div class="usage-item">
                    <div class="usage-label">
                        <span>Content Analysis</span>
                        <span>45%</span>
                    </div>
                    <div class="usage-bar">
                        <div class="usage-fill" style="width: 45%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"></div>
                    </div>
                </div>
                <div class="usage-item">
                    <div class="usage-label">
                        <span>Recommendations</span>
                        <span>30%</span>
                    </div>
                    <div class="usage-bar">
                        <div class="usage-fill" style="width: 30%; background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%);"></div>
                    </div>
                </div>
                <div class="usage-item">
                    <div class="usage-label">
                        <span>Chat Assistant</span>
                        <span>20%</span>
                    </div>
                    <div class="usage-bar">
                        <div class="usage-fill" style="width: 20%; background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);"></div>
                    </div>
                </div>
                <div class="usage-item">
                    <div class="usage-label">
                        <span>Analytics Query</span>
                        <span>5%</span>
                    </div>
                    <div class="usage-bar">
                        <div class="usage-fill" style="width: 5%; background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sentiment Trends -->
        <div class="analytics-card">
            <h3>😊 Sentiment Trends</h3>
            <div class="sentiment-chart">
                <div class="chart-bar-group">
                    <div class="chart-bar">
                        <div class="bar-value positive" style="height: 65%;"></div>
                        <div class="bar-label">Positive</div>
                    </div>
                    <div class="chart-bar">
                        <div class="bar-value neutral" style="height: 25%;"></div>
                        <div class="bar-label">Neutral</div>
                    </div>
                    <div class="chart-bar">
                        <div class="bar-value negative" style="height: 10%;"></div>
                        <div class="bar-label">Negative</div>
                    </div>
                </div>
                <div class="sentiment-legend">
                    <div class="legend-item">
                        <span class="legend-color positive"></span>
                        <span>Positive: 65%</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color neutral"></span>
                        <span>Neutral: 25%</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color negative"></span>
                        <span>Negative: 10%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="analytics-card">
            <h3>⚡ Performance Metrics</h3>
            <div class="performance-table">
                <div class="table-row header">
                    <div class="col">Metric</div>
                    <div class="col">Min</div>
                    <div class="col">Avg</div>
                    <div class="col">Max</div>
                </div>
                <div class="table-row">
                    <div class="col">Response Time</div>
                    <div class="col">120ms</div>
                    <div class="col">245ms</div>
                    <div class="col">890ms</div>
                </div>
                <div class="table-row">
                    <div class="col">Token Usage</div>
                    <div class="col">50</div>
                    <div class="col">180</div>
                    <div class="col">520</div>
                </div>
                <div class="table-row">
                    <div class="col">API Calls/Hour</div>
                    <div class="col">45</div>
                    <div class="col">102</div>
                    <div class="col">234</div>
                </div>
            </div>
        </div>

        <!-- Trending Topics -->
        <div class="analytics-card">
            <h3>🔥 Trending Topics</h3>
            <div class="trending-list">
                <div class="trending-item">
                    <span class="trending-rank">1</span>
                    <span class="trending-topic">Artificial Intelligence</span>
                    <span class="trending-count">234 mentions</span>
                </div>
                <div class="trending-item">
                    <span class="trending-rank">2</span>
                    <span class="trending-topic">Machine Learning</span>
                    <span class="trending-count">189 mentions</span>
                </div>
                <div class="trending-item">
                    <span class="trending-rank">3</span>
                    <span class="trending-topic">Data Analysis</span>
                    <span class="trending-count">156 mentions</span>
                </div>
                <div class="trending-item">
                    <span class="trending-rank">4</span>
                    <span class="trending-topic">Natural Language Processing</span>
                    <span class="trending-count">143 mentions</span>
                </div>
                <div class="trending-item">
                    <span class="trending-rank">5</span>
                    <span class="trending-topic">Deep Learning</span>
                    <span class="trending-count">128 mentions</span>
                </div>
            </div>
        </div>

        <!-- Time Series Chart -->
        <div class="analytics-card full-width">
            <h3>📅 Usage Timeline</h3>
            <div class="timeline-chart">
                <div class="timeline-bar" style="height: 40%;"></div>
                <div class="timeline-bar" style="height: 60%;"></div>
                <div class="timeline-bar" style="height: 45%;"></div>
                <div class="timeline-bar" style="height: 75%;"></div>
                <div class="timeline-bar" style="height: 55%;"></div>
                <div class="timeline-bar" style="height: 70%;"></div>
                <div class="timeline-bar" style="height: 85%;"></div>
            </div>
            <div class="timeline-labels">
                <span>Mon</span>
                <span>Tue</span>
                <span>Wed</span>
                <span>Thu</span>
                <span>Fri</span>
                <span>Sat</span>
                <span>Sun</span>
            </div>
        </div>

        <!-- Top Entities -->
        <div class="analytics-card">
            <h3>🏢 Top Entities Mentioned</h3>
            <div class="entities-list">
                <div class="entity-item">
                    <div class="entity-name">TechCorp</div>
                    <div class="entity-bar">
                        <div class="entity-fill" style="width: 85%;"></div>
                    </div>
                    <div class="entity-count">125</div>
                </div>
                <div class="entity-item">
                    <div class="entity-name">AI Initiative</div>
                    <div class="entity-bar">
                        <div class="entity-fill" style="width: 72%;"></div>
                    </div>
                    <div class="entity-count">108</div>
                </div>
                <div class="entity-item">
                    <div class="entity-name">DataHub</div>
                    <div class="entity-bar">
                        <div class="entity-fill" style="width: 65%;"></div>
                    </div>
                    <div class="entity-count">96</div>
                </div>
            </div>
        </div>

        <!-- Cost Analysis -->
        <div class="analytics-card">
            <h3>💰 Cost Analysis</h3>
            <div class="cost-summary">
                <div class="cost-item">
                    <span class="cost-label">Daily Average</span>
                    <span class="cost-value">$2.45</span>
                </div>
                <div class="cost-item">
                    <span class="cost-label">This Month</span>
                    <span class="cost-value">$73.50</span>
                </div>
                <div class="cost-item">
                    <span class="cost-label">Quota</span>
                    <span class="cost-value">$300/mo</span>
                </div>
                <div class="cost-item">
                    <span class="cost-label">Remaining</span>
                    <span class="cost-value">$226.50</span>
                </div>
            </div>
            <div class="cost-chart">
                <div class="cost-bar">
                    <div class="cost-used" style="width: 24.5%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Report Section -->
    <div class="analytics-section">
        <h2>📋 Detailed Report</h2>
        <div class="report-tabs">
            <button class="tab-btn active" onclick="switchTab(this, 'summary')">Summary</button>
            <button class="tab-btn" onclick="switchTab(this, 'details')">Details</button>
            <button class="tab-btn" onclick="switchTab(this, 'comparison')">Comparison</button>
        </div>
        <div id="summary" class="tab-content active">
            <div class="report-content">
                <h4>Report Summary</h4>
                <p>This period shows strong growth in AI feature usage with a 12% increase in API calls. The performance metrics remain stable with an average response time of 245ms. Content analysis continues to be the most utilized feature at 45% of total usage.</p>
                <p>Sentiment analysis results indicate a predominantly positive tone (65% positive) across analyzed content, with trending topics centered around AI and machine learning technologies.</p>
            </div>
        </div>
        <div id="details" class="tab-content">
            <div class="report-content">
                <h4>Detailed Breakdown</h4>
                <p>Feature-by-feature analysis shows steady performance across all modules. The recommendation engine processed 456 requests with a 92% relevance score. Chat assistant engaged with 234 unique sessions totaling 1,245 messages.</p>
            </div>
        </div>
        <div id="comparison" class="tab-content">
            <div class="report-content">
                <h4>Period Comparison</h4>
                <p>Comparing this period with the previous one shows a 12% growth in API calls, 8% increase in token usage, and 5% improvement in response times. Cost per request has decreased by 3%.</p>
            </div>
        </div>
    </div>
</div>

<style>
.alkebulan-analytics {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 20px;
    min-height: 100vh;
}

.analytics-header {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.analytics-header h1 {
    margin: 0 0 10px 0;
    font-size: 2.5em;
    color: #333;
}

.analytics-header p {
    margin: 0 0 20px 0;
    color: #666;
    font-size: 1.1em;
}

.analytics-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.period-selector {
    display: flex;
    gap: 10px;
}

.period-btn {
    padding: 10px 20px;
    border: 2px solid #ddd;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.period-btn:hover {
    border-color: #667eea;
    color: #667eea;
}

.period-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
}

.analytics-actions {
    display: flex;
    gap: 10px;
}

.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 30px;
}

.analytics-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.analytics-card.full-width {
    grid-column: 1 / -1;
}

.analytics-card h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 1.2em;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.metric-box {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
    border-radius: 8px;
}

.metric-label {
    color: #666;
    font-size: 0.9em;
    margin-bottom: 10px;
}

.metric-value {
    font-size: 2em;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

.metric-change {
    font-size: 0.85em;
    color: #666;
}

.metric-change.positive {
    color: #11998e;
}

.feature-usage {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.usage-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.usage-label {
    display: flex;
    justify-content: space-between;
    font-size: 0.95em;
    font-weight: 600;
    color: #333;
}

.usage-bar {
    height: 25px;
    background: #f0f0f0;
    border-radius: 8px;
    overflow: hidden;
}

.usage-fill {
    height: 100%;
    transition: width 0.3s ease;
}

.sentiment-chart {
    display: flex;
    justify-content: space-between;
    gap: 30px;
}

.chart-bar-group {
    display: flex;
    gap: 20px;
    flex: 1;
    align-items: flex-end;
    justify-content: space-around;
    height: 200px;
}

.chart-bar {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.bar-value {
    width: 60px;
    border-radius: 8px 8px 0 0;
}

.bar-value.positive {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.bar-value.neutral {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.bar-value.negative {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.bar-label {
    font-size: 0.85em;
    color: #666;
    font-weight: 600;
}

.sentiment-legend {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 4px;
}

.legend-color.positive {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.legend-color.neutral {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.legend-color.negative {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.performance-table {
    display: flex;
    flex-direction: column;
}

.table-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.table-row.header {
    font-weight: bold;
    color: #333;
    border-bottom: 2px solid #667eea;
    margin-bottom: 10px;
}

.col {
    text-align: center;
    color: #666;
}

.table-row.header .col {
    color: #333;
}

.trending-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.trending-item {
    display: grid;
    grid-template-columns: 30px 1fr auto;
    gap: 15px;
    align-items: center;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.trending-rank {
    font-weight: bold;
    color: #667eea;
    font-size: 1.1em;
}

.trending-topic {
    color: #333;
    font-weight: 600;
}

.trending-count {
    color: #999;
    font-size: 0.9em;
}

.timeline-chart {
    display: flex;
    align-items: flex-end;
    justify-content: space-around;
    height: 150px;
    gap: 10px;
    margin-bottom: 20px;
}

.timeline-bar {
    flex: 1;
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px 8px 0 0;
    transition: opacity 0.3s;
}

.timeline-bar:hover {
    opacity: 0.8;
}

.timeline-labels {
    display: flex;
    justify-content: space-around;
    color: #666;
    font-weight: 600;
    text-align: center;
}

.entities-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.entity-item {
    display: grid;
    grid-template-columns: 80px 1fr 50px;
    gap: 15px;
    align-items: center;
}

.entity-name {
    font-weight: 600;
    color: #333;
}

.entity-bar {
    height: 25px;
    background: #f0f0f0;
    border-radius: 8px;
    overflow: hidden;
}

.entity-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.entity-count {
    text-align: right;
    color: #667eea;
    font-weight: bold;
}

.cost-summary {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.cost-item {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cost-label {
    color: #666;
}

.cost-value {
    font-weight: bold;
    color: #667eea;
    font-size: 1.2em;
}

.cost-chart {
    height: 20px;
    background: #f0f0f0;
    border-radius: 10px;
    overflow: hidden;
}

.cost-used {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.analytics-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    max-width: 1200px;
    margin: 0 auto;
}

.analytics-section h2 {
    margin: 0 0 20px 0;
    color: #333;
}

.report-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.tab-btn {
    padding: 12px 20px;
    background: transparent;
    border: none;
    cursor: pointer;
    color: #666;
    font-weight: 600;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
}

.tab-btn:hover {
    color: #667eea;
}

.tab-btn.active {
    color: #667eea;
    border-bottom-color: #667eea;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.report-content {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.report-content h4 {
    margin: 0 0 15px 0;
    color: #333;
}

.report-content p {
    margin: 0 0 15px 0;
    color: #666;
    line-height: 1.6;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
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

@media (max-width: 768px) {
    .analytics-grid {
        grid-template-columns: 1fr;
    }
    
    .analytics-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .cost-summary {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function selectPeriod(element, period) {
    document.querySelectorAll('.period-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    console.log('Loading data for period:', period);
}

function generateReport() {
    alert('Report generated successfully!');
}

function exportData() {
    alert('Data exported as CSV');
}

function switchTab(element, tabId) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    
    element.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}
</script>
