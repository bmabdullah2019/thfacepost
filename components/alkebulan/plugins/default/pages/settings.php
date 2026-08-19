<?php
/**
 * Alkebulan AI Settings - User preferences and configuration
 */
?>

<div class="alkebulan-settings">
    <div class="settings-container">
        <div class="settings-header">
            <h1>⚙️ Alkebulan AI Settings</h1>
            <p>Configure your AI preferences and manage your account</p>
        </div>

        <div class="settings-layout">
            <!-- Settings Sidebar -->
            <div class="settings-sidebar">
                <nav class="settings-nav">
                    <a class="nav-item active" onclick="switchSettings('general')">
                        <span class="nav-icon">⚙️</span>
                        <span>General Settings</span>
                    </a>
                    <a class="nav-item" onclick="switchSettings('preferences')">
                        <span class="nav-icon">🎯</span>
                        <span>Preferences</span>
                    </a>
                    <a class="nav-item" onclick="switchSettings('privacy')">
                        <span class="nav-icon">🔒</span>
                        <span>Privacy & Security</span>
                    </a>
                    <a class="nav-item" onclick="switchSettings('api')">
                        <span class="nav-icon">🔌</span>
                        <span>API Configuration</span>
                    </a>
                    <a class="nav-item" onclick="switchSettings('notifications')">
                        <span class="nav-icon">🔔</span>
                        <span>Notifications</span>
                    </a>
                    <a class="nav-item" onclick="switchSettings('about')">
                        <span class="nav-icon">ℹ️</span>
                        <span>About</span>
                    </a>
                </nav>
            </div>

            <!-- Settings Content -->
            <div class="settings-content">
                <!-- General Settings -->
                <div id="general" class="settings-section active">
                    <h2>General Settings</h2>
                    
                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Component Status</h3>
                            <p>Current state and information</p>
                        </div>
                        <div class="setting-value">
                            <div class="status-badge active">✓ Active</div>
                            <div class="info-row">
                                <span>Version</span>
                                <span>1.0 (Stable)</span>
                            </div>
                            <div class="info-row">
                                <span>Framework</span>
                                <span>OSSN 7.6+</span>
                            </div>
                            <div class="info-row">
                                <span>Database Tables</span>
                                <span>8 tables</span>
                            </div>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Language</h3>
                            <p>Choose your preferred language</p>
                        </div>
                        <div class="setting-value">
                            <select class="form-input">
                                <option selected>English</option>
                                <option>Spanish</option>
                                <option>French</option>
                                <option>German</option>
                            </select>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Theme</h3>
                            <p>Choose your preferred theme</p>
                        </div>
                        <div class="setting-value">
                            <div class="theme-selector">
                                <div class="theme-option active" onclick="selectTheme(this)">
                                    <div class="theme-preview" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                    <span>Dark Purple</span>
                                </div>
                                <div class="theme-option" onclick="selectTheme(this)">
                                    <div class="theme-preview" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"></div>
                                    <span>Green</span>
                                </div>
                                <div class="theme-option" onclick="selectTheme(this)">
                                    <div class="theme-preview" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);"></div>
                                    <span>Pink/Yellow</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Auto-save Settings</h3>
                            <p>Automatically save your preferences</p>
                        </div>
                        <div class="setting-value">
                            <label class="toggle">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div id="preferences" class="settings-section">
                    <h2>AI Preferences</h2>
                    
                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Default Analysis Type</h3>
                            <p>What analysis to perform by default</p>
                        </div>
                        <div class="setting-value">
                            <select class="form-input">
                                <option selected>Full Analysis (Sentiment + Entities + Keywords)</option>
                                <option>Sentiment Analysis Only</option>
                                <option>Quick Analysis</option>
                            </select>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Recommendation Sensitivity</h3>
                            <p>How strict recommendations should be</p>
                        </div>
                        <div class="setting-value">
                            <input type="range" min="0" max="100" value="50" class="slider">
                            <div class="slider-labels">
                                <span>Strict</span>
                                <span>Moderate</span>
                                <span>Loose</span>
                            </div>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Chat Response Style</h3>
                            <p>How detailed should chat responses be</p>
                        </div>
                        <div class="setting-value">
                            <div class="radio-group">
                                <label class="radio">
                                    <input type="radio" name="chat-style" value="brief">
                                    <span>Brief</span>
                                </label>
                                <label class="radio">
                                    <input type="radio" name="chat-style" value="moderate" checked>
                                    <span>Moderate</span>
                                </label>
                                <label class="radio">
                                    <input type="radio" name="chat-style" value="detailed">
                                    <span>Detailed</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Enable Analytics Tracking</h3>
                            <p>Allow tracking of your AI usage</p>
                        </div>
                        <div class="setting-value">
                            <label class="toggle">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Batch Processing</h3>
                            <p>Enable batch analysis for multiple items</p>
                        </div>
                        <div class="setting-value">
                            <label class="toggle">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Privacy & Security -->
                <div id="privacy" class="settings-section">
                    <h2>Privacy & Security</h2>
                    
                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Data Privacy</h3>
                            <p>Control how your data is used</p>
                        </div>
                        <div class="setting-value">
                            <div class="privacy-options">
                                <div class="privacy-option">
                                    <label class="radio">
                                        <input type="radio" name="privacy" value="full" checked>
                                        <span><strong>Full Privacy</strong> - Data not used for model training</span>
                                    </label>
                                </div>
                                <div class="privacy-option">
                                    <label class="radio">
                                        <input type="radio" name="privacy" value="partial">
                                        <span><strong>Partial Privacy</strong> - Anonymized data may be used</span>
                                    </label>
                                </div>
                                <div class="privacy-option">
                                    <label class="radio">
                                        <input type="radio" name="privacy" value="standard">
                                        <span><strong>Standard</strong> - Data may be used for improvement</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Chat History Storage</h3>
                            <p>How long to keep chat messages</p>
                        </div>
                        <div class="setting-value">
                            <select class="form-input">
                                <option>30 Days</option>
                                <option>60 Days</option>
                                <option selected>90 Days</option>
                                <option>1 Year</option>
                                <option>Forever</option>
                            </select>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Analysis Data Encryption</h3>
                            <p>Encrypt stored analysis results</p>
                        </div>
                        <div class="setting-value">
                            <label class="toggle">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Export Personal Data</h3>
                            <p>Download all your data in JSON format</p>
                        </div>
                        <div class="setting-value">
                            <button class="btn btn-secondary" onclick="exportPersonalData()">Export Data</button>
                        </div>
                    </div>

                    <div class="setting-group danger">
                        <div class="setting-label">
                            <h3>Delete All Data</h3>
                            <p>Permanently delete all your AI data</p>
                        </div>
                        <div class="setting-value">
                            <button class="btn btn-danger" onclick="confirmDelete()">Delete All</button>
                        </div>
                    </div>
                </div>

                <!-- API Configuration -->
                <div id="api" class="settings-section">
                    <h2>API Configuration</h2>
                    
                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>API Key</h3>
                            <p>Your personal API key</p>
                        </div>
                        <div class="setting-value">
                            <div class="api-key-box">
                                <input type="password" value="sk-1234567890abcdefghij" class="api-key-input" id="api-key">
                                <button class="btn btn-sm btn-secondary" onclick="toggleApiKey()">Show</button>
                                <button class="btn btn-sm btn-secondary" onclick="copyApiKey()">Copy</button>
                            </div>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>API Rate Limit</h3>
                            <p>Maximum requests per minute</p>
                        </div>
                        <div class="setting-value">
                            <input type="number" min="1" max="1000" value="100" class="form-input" style="width: 100px;">
                            <span class="hint-text">Requests per minute</span>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Daily API Quota</h3>
                            <p>Maximum API calls per day</p>
                        </div>
                        <div class="setting-value">
                            <input type="number" min="100" max="10000" value="1000" class="form-input" style="width: 120px;">
                            <span class="hint-text">Calls per day</span>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Allowed Domains</h3>
                            <p>Domains that can access your API</p>
                        </div>
                        <div class="setting-value">
                            <textarea class="form-textarea" placeholder="One domain per line&#10;Example:&#10;localhost&#10;example.com&#10;api.example.com">localhost
127.0.0.1
example.com</textarea>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Regenerate API Key</h3>
                            <p>Generate a new API key (old one will be deactivated)</p>
                        </div>
                        <div class="setting-value">
                            <button class="btn btn-danger" onclick="confirmRegenerate()">Regenerate Key</button>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div id="notifications" class="settings-section">
                    <h2>Notifications</h2>
                    
                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Usage Alerts</h3>
                            <p>Alert when reaching API quota limits</p>
                        </div>
                        <div class="setting-value">
                            <label class="toggle">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>System Updates</h3>
                            <p>Notify about system updates and features</p>
                        </div>
                        <div class="setting-value">
                            <label class="toggle">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Weekly Reports</h3>
                            <p>Receive weekly usage reports</p>
                        </div>
                        <div class="setting-value">
                            <label class="toggle">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Email Notifications</h3>
                            <p>Receive notifications via email</p>
                        </div>
                        <div class="setting-value">
                            <label class="toggle">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="setting-label">
                            <h3>Notification Email</h3>
                            <p>Where to send notifications</p>
                        </div>
                        <div class="setting-value">
                            <input type="email" value="user@example.com" class="form-input">
                        </div>
                    </div>
                </div>

                <!-- About -->
                <div id="about" class="settings-section">
                    <h2>About Alkebulan AI</h2>
                    
                    <div class="about-content">
                        <div class="about-section">
                            <h3>Component Information</h3>
                            <div class="about-item">
                                <span class="label">Name:</span>
                                <span>Alkebulan AI</span>
                            </div>
                            <div class="about-item">
                                <span class="label">Version:</span>
                                <span>1.0 (Stable Release)</span>
                            </div>
                            <div class="about-item">
                                <span class="label">Release Date:</span>
                                <span><?php echo date('F j, Y'); ?></span>
                            </div>
                            <div class="about-item">
                                <span class="label">Framework:</span>
                                <span>OSSN 7.6+</span>
                            </div>
                        </div>

                        <div class="about-section">
                            <h3>Features</h3>
                            <ul class="feature-list">
                                <li>✓ Advanced Sentiment Analysis</li>
                                <li>✓ Intelligent Recommendations Engine</li>
                                <li>✓ AI Chat Assistant</li>
                                <li>✓ Comprehensive Analytics</li>
                                <li>✓ Entity Recognition</li>
                                <li>✓ Keyword Extraction</li>
                                <li>✓ Performance Metrics</li>
                                <li>✓ Report Generation</li>
                            </ul>
                        </div>

                        <div class="about-section">
                            <h3>Requirements</h3>
                            <ul class="requirements-list">
                                <li>PHP 7.0+</li>
                                <li>MySQL 5.6+</li>
                                <li>OSSN Framework 7.6+</li>
                                <li>Modern Web Browser</li>
                            </ul>
                        </div>

                        <div class="about-section">
                            <h3>Documentation</h3>
                            <div class="docs-links">
                                <a href="#" class="doc-link">📖 User Guide</a>
                                <a href="#" class="doc-link">🔧 Developer Documentation</a>
                                <a href="#" class="doc-link">📚 API Reference</a>
                                <a href="#" class="doc-link">❓ FAQ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="settings-actions">
            <button class="btn btn-primary" onclick="saveSettings()">💾 Save Changes</button>
            <button class="btn btn-secondary" onclick="resetSettings()">↻ Reset to Defaults</button>
        </div>
    </div>
</div>

<style>
.alkebulan-settings {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 20px;
    min-height: 100vh;
}

.settings-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    max-width: 1200px;
    margin: 0 auto;
    overflow: hidden;
}

.settings-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    text-align: center;
}

.settings-header h1 {
    margin: 0 0 10px 0;
    font-size: 2.5em;
}

.settings-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 1.1em;
}

.settings-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    min-height: 500px;
}

.settings-sidebar {
    background: #f8f9fa;
    border-right: 1px solid #e9ecef;
    overflow-y: auto;
}

.settings-nav {
    display: flex;
    flex-direction: column;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 20px;
    cursor: pointer;
    border-left: 3px solid transparent;
    transition: all 0.3s;
    color: #666;
}

.nav-item:hover {
    background: #e9ecef;
    color: #333;
}

.nav-item.active {
    background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
    border-left-color: #667eea;
    color: #667eea;
    font-weight: 600;
}

.nav-icon {
    font-size: 1.3em;
}

.settings-content {
    padding: 40px;
    overflow-y: auto;
    max-height: calc(100vh - 200px);
}

.settings-section {
    display: none;
}

.settings-section.active {
    display: block;
}

.settings-section h2 {
    margin: 0 0 30px 0;
    color: #333;
    font-size: 1.8em;
}

.setting-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    padding: 30px 0;
    border-bottom: 1px solid #e9ecef;
}

.setting-group:last-child {
    border-bottom: none;
}

.setting-group.danger {
    background: #fff5f5;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #feb2b2;
}

.setting-label h3 {
    margin: 0;
    color: #333;
}

.setting-label p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 0.9em;
}

.status-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    margin-bottom: 15px;
}

.status-badge.active {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row span:first-child {
    color: #666;
}

.info-row span:last-child {
    font-weight: 600;
    color: #333;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1em;
    font-family: Arial, sans-serif;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.theme-selector {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.theme-option {
    text-align: center;
    cursor: pointer;
    padding: 10px;
    border-radius: 8px;
    border: 2px solid transparent;
    transition: all 0.3s;
}

.theme-option:hover {
    border-color: #ddd;
}

.theme-option.active {
    border-color: #667eea;
}

.theme-preview {
    width: 100%;
    height: 60px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.theme-option span {
    display: block;
    color: #333;
    font-weight: 600;
}

.toggle {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 30px;
    cursor: pointer;
}

.toggle input {
    display: none;
}

.toggle-slider {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ccc;
    border-radius: 15px;
    transition: background 0.3s;
}

.toggle-slider:before {
    position: absolute;
    content: '';
    height: 24px;
    width: 24px;
    left: 3px;
    bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s;
}

.toggle input:checked + .toggle-slider {
    background: #667eea;
}

.toggle input:checked + .toggle-slider:before {
    transform: translateX(20px);
}

.slider {
    width: 100%;
    height: 8px;
    border-radius: 5px;
    background: #ddd;
    outline: none;
    -webkit-appearance: none;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    cursor: pointer;
}

.slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    cursor: pointer;
    border: none;
}

.slider-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 0.85em;
    color: #666;
}

.radio-group {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.radio {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.radio input {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #667eea;
}

.privacy-options {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.privacy-option {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.api-key-box {
    display: flex;
    gap: 10px;
}

.api-key-input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: monospace;
    letter-spacing: 2px;
}

.hint-text {
    display: block;
    margin-top: 5px;
    font-size: 0.85em;
    color: #666;
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.about-content {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.about-section {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.about-section h3 {
    margin: 0 0 15px 0;
    color: #333;
}

.about-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.about-item .label {
    font-weight: 600;
    color: #666;
}

.feature-list,
.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-list li,
.requirements-list li {
    padding: 8px 0;
    color: #666;
}

.docs-links {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.doc-link {
    display: inline-block;
    padding: 10px 15px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    color: #667eea;
    text-decoration: none;
    transition: all 0.3s;
}

.doc-link:hover {
    border-color: #667eea;
    background: #f8f9fa;
}

.settings-actions {
    background: #f8f9fa;
    padding: 20px;
    display: flex;
    justify-content: center;
    gap: 15px;
    border-top: 1px solid #e9ecef;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    font-size: 1em;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-secondary:hover {
    background: #f8f9fa;
}

.btn-danger {
    background: #ff6b6b;
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
}

.btn-sm {
    padding: 8px 15px;
    font-size: 0.9em;
}

@media (max-width: 768px) {
    .settings-layout {
        grid-template-columns: 1fr;
    }
    
    .settings-sidebar {
        display: none;
    }
    
    .setting-group {
        grid-template-columns: 1fr;
    }
    
    .api-key-box {
        flex-direction: column;
    }
    
    .theme-selector {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function switchSettings(section) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(sec => {
        sec.classList.remove('active');
    });
    
    // Remove active class from nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById(section).classList.add('active');
    
    // Add active class to clicked nav item
    event.target.closest('.nav-item').classList.add('active');
}

function selectTheme(element) {
    document.querySelectorAll('.theme-option').forEach(opt => {
        opt.classList.remove('active');
    });
    element.classList.add('active');
}

function toggleApiKey() {
    const input = document.getElementById('api-key');
    input.type = input.type === 'password' ? 'text' : 'password';
}

function copyApiKey() {
    const input = document.getElementById('api-key');
    input.select();
    document.execCommand('copy');
    alert('API Key copied to clipboard!');
}

function exportPersonalData() {
    alert('Your personal data is being prepared for download...');
}

function confirmDelete() {
    if (confirm('Are you sure? This action cannot be undone!')) {
        alert('All data has been deleted.');
    }
}

function confirmRegenerate() {
    if (confirm('Your old API key will be deactivated. Continue?')) {
        alert('New API key generated successfully.');
    }
}

function saveSettings() {
    alert('Settings saved successfully!');
}

function resetSettings() {
    if (confirm('Reset all settings to defaults?')) {
        alert('Settings have been reset.');
        location.reload();
    }
}
</script>
