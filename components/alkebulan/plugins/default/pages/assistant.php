<?php
/**
 * Alkebulan AI Chat Assistant - Interactive Chat Interface
 */
?>

<div class="alkebulan-chat">
    <div class="chat-container">
        <div class="chat-header">
            <div class="chat-header-content">
                <h1>💬 AI Chat Assistant</h1>
                <p>Chat with Alkebulan AI for insights and assistance</p>
            </div>
            <div class="chat-header-actions">
                <button class="btn btn-sm btn-secondary" onclick="clearChat()">Clear Chat</button>
                <button class="btn btn-sm btn-secondary" onclick="exportChat()">Export</button>
            </div>
        </div>

        <div class="chat-main">
            <div class="chat-sidebar">
                <div class="sessions-header">
                    <h3>Sessions</h3>
                    <button class="btn-icon" onclick="newSession()">+</button>
                </div>
                <div class="sessions-list">
                    <div class="session-item active" onclick="loadSession(this, 1)">
                        <span class="session-title">Current Session</span>
                        <span class="session-time">Now</span>
                    </div>
                    <div class="session-item" onclick="loadSession(this, 2)">
                        <span class="session-title">Content Discussion</span>
                        <span class="session-time">Yesterday</span>
                    </div>
                    <div class="session-item" onclick="loadSession(this, 3)">
                        <span class="session-title">Analysis Tips</span>
                        <span class="session-time">2 days ago</span>
                    </div>
                </div>

                <div class="chat-shortcuts">
                    <h4>Quick Prompts</h4>
                    <button class="shortcut-btn" onclick="sendPrompt('Analyze this post for sentiment')">
                        📝 Analyze Sentiment
                    </button>
                    <button class="shortcut-btn" onclick="sendPrompt('What are trending topics?')">
                        📊 Trending Topics
                    </button>
                    <button class="shortcut-btn" onclick="sendPrompt('Help me write better content')">
                        ✍️ Content Tips
                    </button>
                    <button class="shortcut-btn" onclick="sendPrompt('Show recommendation insights')">
                        💡 Recommendations
                    </button>
                </div>
            </div>

            <div class="chat-body">
                <div class="messages-container" id="messages">
                    <div class="message ai">
                        <div class="message-avatar">🤖</div>
                        <div class="message-content">
                            <div class="message-text">
                                Hello! I'm Alkebulan AI, your intelligent assistant. How can I help you today?
                            </div>
                            <div class="message-time">Now</div>
                        </div>
                    </div>
                </div>

                <div class="chat-input-area">
                    <div class="input-wrapper">
                        <input type="text" id="message-input" class="message-input" placeholder="Ask me anything..." onkeypress="handleKeypress(event)">
                        <button class="btn-send" onclick="sendMessage()">
                            <span>➤</span>
                        </button>
                    </div>
                    <div class="input-hints">
                        <span class="hint">💡 Tip: Ask about content analysis, recommendations, or AI features</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="chat-sidebar-right">
        <div class="session-info">
            <h3>Session Summary</h3>
            <div class="summary-item">
                <span class="summary-label">Duration</span>
                <span class="summary-value" id="session-duration">0m</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Messages</span>
                <span class="summary-value" id="message-count">1</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Topics</span>
                <span class="summary-value" id="topics-discussed">AI, Help</span>
            </div>
        </div>

        <div class="conversation-context">
            <h3>Context</h3>
            <div class="context-tags">
                <span class="tag">General Help</span>
            </div>
        </div>

        <div class="chat-suggestions">
            <h3>Suggestions</h3>
            <div class="suggestion-item" onclick="sendPrompt(this.textContent)">
                <span>📈</span> Check analytics dashboard
            </div>
            <div class="suggestion-item" onclick="sendPrompt(this.textContent)">
                <span>🎯</span> Get content recommendations
            </div>
            <div class="suggestion-item" onclick="sendPrompt(this.textContent)">
                <span>⚙️</span> Configure AI settings
            </div>
        </div>
    </div>
</div>

<style>
.alkebulan-chat {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
    display: flex;
    gap: 20px;
}

.chat-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    overflow: hidden;
    max-height: calc(100vh - 40px);
}

.chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid rgba(255,255,255,0.1);
}

.chat-header-content h1 {
    margin: 0;
    font-size: 1.8em;
}

.chat-header-content p {
    margin: 5px 0 0 0;
    opacity: 0.9;
}

.chat-header-actions {
    display: flex;
    gap: 10px;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.9em;
}

.chat-main {
    display: grid;
    grid-template-columns: 250px 1fr;
    flex: 1;
    gap: 0;
}

.chat-sidebar {
    background: #f8f9fa;
    border-right: 1px solid #e9ecef;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.sessions-header {
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e9ecef;
}

.sessions-header h3 {
    margin: 0;
    color: #333;
    font-size: 1em;
}

.btn-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    font-weight: bold;
    transition: transform 0.2s;
}

.btn-icon:hover {
    transform: scale(1.1);
}

.sessions-list {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.session-item {
    padding: 12px;
    margin-bottom: 8px;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    border-left: 3px solid transparent;
}

.session-item:hover {
    background: #e9ecef;
}

.session-item.active {
    background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
    border-left-color: #667eea;
}

.session-title {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.session-time {
    display: block;
    font-size: 0.8em;
    color: #999;
}

.chat-shortcuts {
    padding: 15px;
    border-top: 1px solid #e9ecef;
}

.chat-shortcuts h4 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 0.9em;
}

.shortcut-btn {
    display: block;
    width: 100%;
    padding: 10px;
    margin-bottom: 8px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    cursor: pointer;
    text-align: left;
    font-size: 0.9em;
    color: #333;
    transition: all 0.3s;
}

.shortcut-btn:hover {
    background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
    border-color: #667eea;
}

.chat-body {
    display: flex;
    flex-direction: column;
    background: white;
}

.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.message {
    display: flex;
    gap: 12px;
    animation: slideUp 0.3s ease;
}

.message.user {
    justify-content: flex-end;
}

.message-avatar {
    font-size: 1.5em;
    flex-shrink: 0;
}

.message.user .message-avatar {
    display: none;
}

.message-content {
    max-width: 70%;
}

.message.user .message-content {
    text-align: right;
}

.message-text {
    padding: 12px 16px;
    border-radius: 12px;
    word-wrap: break-word;
    line-height: 1.5;
}

.message.ai .message-text {
    background: #f0f0f0;
    color: #333;
    border-radius: 12px 12px 12px 0;
}

.message.user .message-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 12px;
}

.message-time {
    font-size: 0.8em;
    color: #999;
    margin-top: 5px;
    padding: 0 5px;
}

.chat-input-area {
    padding: 15px;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
}

.input-wrapper {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.message-input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1em;
    font-family: Arial, sans-serif;
    transition: border-color 0.3s;
}

.message-input:focus {
    outline: none;
    border-color: #667eea;
}

.btn-send {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.2em;
    transition: transform 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-send:hover {
    transform: scale(1.05);
}

.input-hints {
    font-size: 0.85em;
    color: #999;
}

.hint {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.chat-sidebar-right {
    width: 280px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.session-info,
.conversation-context,
.chat-suggestions {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.session-info h3,
.conversation-context h3,
.chat-suggestions h3 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1em;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    color: #666;
    font-size: 0.9em;
}

.summary-value {
    color: #667eea;
    font-weight: 600;
}

.context-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag {
    background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
    color: #667eea;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85em;
}

.suggestion-item {
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 10px;
    transition: all 0.3s;
    display: flex;
    gap: 8px;
    align-items: center;
}

.suggestion-item:hover {
    background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 1200px) {
    .alkebulan-chat {
        flex-direction: column;
    }
    
    .chat-sidebar-right {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .chat-main {
        grid-template-columns: 1fr;
    }
    
    .chat-sidebar {
        display: none;
    }
    
    .message-content {
        max-width: 90%;
    }
}
</style>

<script>
let messageCount = 1;

function sendMessage() {
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message
    addMessage(message, 'user');
    input.value = '';
    
    // Simulate AI response
    setTimeout(() => {
        const responses = [
            'That\'s a great question! I can help you with that.',
            'Let me analyze that for you. Based on your input, I\'d recommend...',
            'Interesting! This relates to our AI features. Would you like to know more?',
            'I understand. Let me provide you with detailed insights on this topic.',
            'That\'s exactly what my analytics can help with! Here\'s what I found...'
        ];
        const response = responses[Math.floor(Math.random() * responses.length)];
        addMessage(response, 'ai');
    }, 500);
    
    messageCount++;
    updateSessionInfo();
}

function addMessage(text, sender) {
    const container = document.getElementById('messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}`;
    
    const now = new Date();
    const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    
    messageDiv.innerHTML = `
        <div class="message-avatar">${sender === 'ai' ? '🤖' : ''}</div>
        <div class="message-content">
            <div class="message-text">${text}</div>
            <div class="message-time">${time}</div>
        </div>
    `;
    
    container.appendChild(messageDiv);
    container.scrollTop = container.scrollHeight;
}

function sendPrompt(prompt) {
    document.getElementById('message-input').value = prompt;
    sendMessage();
}

function handleKeypress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

function clearChat() {
    const container = document.getElementById('messages');
    container.innerHTML = `
        <div class="message ai">
            <div class="message-avatar">🤖</div>
            <div class="message-content">
                <div class="message-text">
                    Chat cleared. How can I help you now?
                </div>
            </div>
        </div>
    `;
    messageCount = 1;
    updateSessionInfo();
}

function newSession() {
    alert('New session created');
}

function loadSession(element, id) {
    document.querySelectorAll('.session-item').forEach(el => el.classList.remove('active'));
    element.classList.add('active');
}

function exportChat() {
    alert('Chat exported successfully');
}

function updateSessionInfo() {
    document.getElementById('message-count').textContent = messageCount;
    
    const now = new Date();
    const duration = Math.floor(Math.random() * 60);
    document.getElementById('session-duration').textContent = duration + 'm';
}
</script>
