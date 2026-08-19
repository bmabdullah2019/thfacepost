<?php
/**
 * Alkebulan AI - Chat Assistant Page
 * Interactive AI chat interface for engaging with Alkebulan AI assistant
 */

// Check user is logged in
if(!ossn_loggedin_user()) {
    echo "Please log in to use the chat assistant.";
    return;
}

$current_user = ossn_loggedin_user();
$username = $current_user->username;

?>
<div class="alkebulan-page alkebulan-chat-assistant">
    <style>
        * {
            box-sizing: border-box;
        }

        .chat-container {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            margin: 20px 0;
            min-height: calc(100vh - 300px);
        }

        .chat-main {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .chat-header h1 {
            margin: 0;
            font-size: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .chat-header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 13px;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f9f9f9;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .chat-messages::-webkit-scrollbar {
            width: 8px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 4px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #ccc;
        }

        .message {
            display: flex;
            gap: 12px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.user {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.4;
            word-break: break-word;
        }

        .message.user .message-content {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message.ai .message-content {
            background: white;
            color: #333;
            border: 1px solid #e0e0e0;
            border-bottom-left-radius: 4px;
        }

        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .message.user .message-avatar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            order: 2;
        }

        .message.ai .message-avatar {
            background: #e0e0e0;
            color: #667eea;
        }

        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 12px 16px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            border-bottom-left-radius: 4px;
            width: fit-content;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #999;
            animation: typing 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.5;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        .chat-input-area {
            padding: 15px;
            border-top: 1px solid #e0e0e0;
            background: white;
        }

        .input-group {
            display: flex;
            gap: 10px;
        }

        .chat-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            transition: border-color 0.3s;
        }

        .chat-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-send {
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            font-size: 14px;
        }

        .btn-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-send:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .suggestions-panel {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .suggestions-title {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .suggestion-btn {
            padding: 12px;
            background: #f5f5f5;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            text-align: left;
            transition: all 0.3s;
            color: #333;
            font-weight: 500;
            line-height: 1.3;
        }

        .suggestion-btn:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            transform: translateX(4px);
        }

        .stats-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px;
            margin-top: 20px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 12px;
            color: #666;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-value {
            font-weight: 600;
            color: #667eea;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
            padding: 40px;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        @media (max-width: 1024px) {
            .chat-container {
                grid-template-columns: 1fr;
            }

            .message-content {
                max-width: 100%;
            }

            .suggestions-panel {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .suggestion-btn {
                padding: 10px;
                font-size: 11px;
            }
        }

        @media (max-width: 600px) {
            .chat-container {
                min-height: auto;
            }

            .message-content {
                max-width: 90%;
            }

            .suggestions-panel {
                grid-template-columns: 1fr;
            }

            .chat-header h1 {
                font-size: 20px;
            }
        }
    </style>

    <div class="chat-header">
        <h1>💬 Alkebulan AI Assistant</h1>
        <p>Powered by Advanced AI Analytics & Intelligence</p>
    </div>

    <div class="chat-container">
        <!-- Main Chat Area -->
        <div class="chat-main">
            <div class="chat-messages" id="chatMessages">
                <div class="empty-state">
                    <div class="empty-state-icon">🤖</div>
                    <div>Start a conversation to begin</div>
                </div>
            </div>

            <div class="chat-input-area">
                <div class="input-group">
                    <input 
                        type="text" 
                        id="chatInput" 
                        class="chat-input" 
                        placeholder="Ask me anything about your analytics, trends, content tips..."
                        autocomplete="off"
                    >
                    <button class="btn-send" id="sendBtn" onclick="sendMessage()">Send</button>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div>
            <!-- Suggestions Panel -->
            <div class="suggestions-panel">
                <div class="suggestions-title">Quick Prompts</div>
                <button class="suggestion-btn" onclick="useSuggestion('Analyze my account sentiment')">
                    📊 Analyze my account sentiment
                </button>
                <button class="suggestion-btn" onclick="useSuggestion('What are trending topics in my niche?')">
                    🔥 Trending topics
                </button>
                <button class="suggestion-btn" onclick="useSuggestion('Give me content creation tips')">
                    ✍️ Content tips
                </button>
                <button class="suggestion-btn" onclick="useSuggestion('Recommend accounts to follow')">
                    👥 Recommendations
                </button>
                <button class="suggestion-btn" onclick="useSuggestion('How can I increase engagement?')">
                    📈 Boost engagement
                </button>
                <button class="suggestion-btn" onclick="useSuggestion('Clear chat history')">
                    🗑️ Clear history
                </button>
            </div>

            <!-- Stats Panel -->
            <div class="stats-container">
                <div style="font-weight: 600; margin-bottom: 10px; color: #333; font-size: 13px;">Session Stats</div>
                <div class="stat-item">
                    <span>Messages</span>
                    <span class="stat-value" id="messageCount">0</span>
                </div>
                <div class="stat-item">
                    <span>AI Responses</span>
                    <span class="stat-value" id="responseCount">0</span>
                </div>
                <div class="stat-item">
                    <span>Duration</span>
                    <span class="stat-value" id="duration">0m</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        const messageCount = document.getElementById('messageCount');
        const responseCount = document.getElementById('responseCount');
        const durationEl = document.getElementById('duration');

        let messages = [];
        let stats = {
            messageCount: 0,
            responseCount: 0,
            startTime: Date.now()
        };

        const aiResponses = {
            'analyze my account sentiment': [
                'Your account sentiment analysis shows 85% positive engagement! Your followers respond well to motivational content and behind-the-scenes posts. Keep up the authentic approach.',
                '📊 Sentiment Breakdown:\n- Positive: 85%\n- Neutral: 12%\n- Negative: 3%\n\nYour recent wellness posts are performing exceptionally well with high engagement rates.'
            ],
            'trending topics': [
                '🔥 Top Trending Topics in Your Niche:\n1. Digital wellness (#3.2M posts)\n2. Work-life balance (#2.8M posts)\n3. Productivity hacks (#2.1M posts)\n4. Mindfulness (#1.9M posts)\n5. Mental health awareness (#1.7M posts)',
                'The wellness industry is trending upward 34% this month. Content about sustainability and eco-friendly living is gaining traction. Consider incorporating these themes!'
            ],
            'content tips': [
                '✍️ Content Creation Tips:\n\n1. Post consistently (3-5x per week)\n2. Use power words: "discover", "transform", "unlock"\n3. Include a CTA in every post\n4. Video content gets 3x more engagement\n5. Post when your audience is most active (8 PM - 10 PM)\n6. Engage with similar content daily',
                'Your best performing content:\n- Educational carousel posts (avg 2.3k likes)\n- Behind-the-scenes videos (avg 1.8k likes)\n- User testimonials (avg 1.5k likes)\n\nFocus on these formats!'
            ],
            'recommend': [
                '👥 Account Recommendations:\n\nBased on your interests:\n1. @digital_wellness_expert (45K followers)\n2. @mindfulness_coach (82K followers)\n3. @productivity_guru (156K followers)\n4. @wellness_lifestyle (203K followers)\n\nThese accounts align with your content and would make great collaborators.',
                'Top collaborators in your space:\n- @content_creator_hub\n- @growth_strategist\n- @wellness_influencer\n\nFollow them for insights and collaboration opportunities!'
            ],
            'engagement': [
                '📈 Boost Engagement Strategy:\n\n1. Reply to ALL comments in first hour\n2. Ask questions in captions\n3. Use interactive stickers (polls, quizzes)\n4. Host monthly Q&A sessions\n5. Create carousel posts with 7-10 slides\n6. Share user-generated content\n7. Cross-post to Reels/TikTok\n\nThese tactics typically boost engagement by 40-60%!',
                'Quick wins to increase engagement:\n- Post Stories daily (shows you\'re active)\n- Use 25-30 relevant hashtags\n- Tag complementary accounts\n- Respond to DMs within 2 hours\n- Go live weekly (Live videos get 3x views)\n\nImplement 3-4 of these this week and measure results!'
            ]
        };

        function displayEmptyState() {
            if (messages.length === 0) {
                chatMessages.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">🤖</div>
                        <div>Start a conversation to begin</div>
                    </div>
                `;
            }
        }

        function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            // Add user message
            addMessage(message, 'user');
            stats.messageCount++;
            messageCount.textContent = stats.messageCount;

            // Clear input
            chatInput.value = '';
            chatInput.focus();

            // Show typing indicator
            showTypingIndicator();

            // Simulate AI response delay
            setTimeout(() => {
                removeTypingIndicator();
                generateAIResponse(message);
            }, 1500 + Math.random() * 1000);
        }

        function useSuggestion(suggestion) {
            chatInput.value = suggestion;
            chatInput.focus();
            
            if (suggestion === 'Clear chat history') {
                messages = [];
                stats.messageCount = 0;
                stats.responseCount = 0;
                messageCount.textContent = '0';
                responseCount.textContent = '0';
                displayEmptyState();
                return;
            }
            
            sendMessage();
        }

        function addMessage(text, sender) {
            if (messages.length === 0) {
                chatMessages.innerHTML = '';
            }

            const messageEl = document.createElement('div');
            messageEl.className = `message ${sender}`;
            
            const avatar = sender === 'user' ? '👤' : '🤖';
            
            messageEl.innerHTML = `
                <div class="message-avatar">${avatar}</div>
                <div class="message-content">${escapeHtml(text)}</div>
            `;
            
            chatMessages.appendChild(messageEl);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            messages.push({
                text: text,
                sender: sender,
                timestamp: new Date()
            });
        }

        function showTypingIndicator() {
            const typing = document.createElement('div');
            typing.className = 'message ai';
            typing.id = 'typingIndicator';
            typing.innerHTML = `
                <div class="message-avatar">🤖</div>
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            `;
            chatMessages.appendChild(typing);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function removeTypingIndicator() {
            const typing = document.getElementById('typingIndicator');
            if (typing) typing.remove();
        }

        function generateAIResponse(userMessage) {
            let response = "I'm here to help! Ask me about your account analytics, content strategy, trending topics, or how to boost engagement.";
            
            const lowerMessage = userMessage.toLowerCase();
            
            for (const [key, responses] of Object.entries(aiResponses)) {
                if (lowerMessage.includes(key) || key.includes(lowerMessage.substring(0, 5))) {
                    response = responses[Math.floor(Math.random() * responses.length)];
                    break;
                }
            }
            
            addMessage(response, 'ai');
            stats.responseCount++;
            responseCount.textContent = stats.responseCount;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Update duration every second
        setInterval(() => {
            const elapsed = Math.floor((Date.now() - stats.startTime) / 60000);
            durationEl.textContent = elapsed + 'm';
        }, 60000);

        // Send on Enter key
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Initial display
        displayEmptyState();
        chatInput.focus();
    </script>
</div>
