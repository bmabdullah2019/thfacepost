<?php
/**
 * ChatAssistant Class - AI-Powered Conversational Assistant
 * Provides intelligent chat, context awareness, and natural conversation
 */

class ChatAssistant {
    private $db;
    private $user_id;
    private $session_id;
    private $conversation_history;
    private $context;
    
    public function __construct($user_id, $session_id = null) {
        $this->db = ossn_get_database();
        $this->user_id = $user_id;
        $this->session_id = $session_id;
        $this->conversation_history = [];
        $this->context = [];
        
        if($session_id) {
            $this->loadSession($session_id);
        }
    }
    
    /**
     * Create new chat session
     */
    public function createSession($context = []) {
        $session_key = bin2hex(random_bytes(16));
        
        $session_data = [
            'user_id' => $this->user_id,
            'session_key' => $session_key,
            'context' => json_encode($context),
            'status' => 'active',
            'created' => time(),
            'last_active' => time()
        ];
        
        $this->session_id = $this->db->insert('alkebulan_chat_sessions', $session_data);
        $this->context = $context;
        
        return [
            'session_id' => $this->session_id,
            'session_key' => $session_key,
            'status' => 'created'
        ];
    }
    
    /**
     * Load existing session
     */
    private function loadSession($session_id) {
        $query = $this->db->select('alkebulan_chat_sessions')
            ->where('id', $session_id)
            ->where('user_id', $this->user_id)
            ->execute();
        
        $session = $query->fetch_object();
        
        if($session) {
            $this->session_id = $session->id;
            $this->context = json_decode($session->context, true);
            $this->loadConversationHistory($session_id);
            
            // Update last active
            $this->db->update('alkebulan_chat_sessions', ['last_active' => time()])
                ->where('id', $session_id)
                ->execute();
        }
    }
    
    /**
     * Load conversation history
     */
    private function loadConversationHistory($session_id) {
        $query = $this->db->select('alkebulan_chat_messages')
            ->where('session_id', $session_id)
            ->order_by('created', 'ASC')
            ->execute();
        
        $messages = $query->fetch();
        if($messages) {
            foreach($messages as $msg) {
                $this->conversation_history[] = [
                    'sender' => $msg->sender_type,
                    'message' => $msg->message_text,
                    'timestamp' => $msg->created
                ];
            }
        }
    }
    
    /**
     * Send message and get AI response
     */
    public function sendMessage($message) {
        if(!$this->session_id) {
            return ['error' => 'No active session'];
        }
        
        // Store user message
        $user_msg_id = $this->storeMessage('user', $message);
        
        // Generate AI response
        $response = $this->generateResponse($message);
        
        // Store AI message
        $ai_msg_id = $this->storeMessage('assistant', $response, ['auto_generated' => true]);
        
        // Update conversation history
        $this->conversation_history[] = ['sender' => 'user', 'message' => $message];
        $this->conversation_history[] = ['sender' => 'assistant', 'message' => $response];
        
        // Update session message count
        $this->db->query("UPDATE alkebulan_chat_sessions SET total_messages = total_messages + 2 WHERE id = ?", [$this->session_id]);
        
        return [
            'user_message_id' => $user_msg_id,
            'assistant_message_id' => $ai_msg_id,
            'response' => $response,
            'timestamp' => time()
        ];
    }
    
    /**
     * Generate AI response based on context and conversation
     */
    private function generateResponse($message) {
        $response_templates = [
            'greeting' => "Hello! I'm Alkebulan AI. How can I assist you today?",
            'help' => "I can help you with content analysis, recommendations, insights, and more. What would you like to know?",
            'analysis' => "I'd be happy to analyze that for you. Let me break down the key points.",
            'default' => "That's interesting! Based on the context, here's my analysis.",
            'question' => "Great question! Let me provide you with some insights.",
            'closing' => "Is there anything else I can help you with?"
        ];
        
        $intent = $this->detectIntent($message);
        
        // Build response based on intent
        $response = $response_templates[$intent] ?? $response_templates['default'];
        
        // Add context-specific information
        if(!empty($this->context)) {
            $response .= "\n\nBased on your profile, I can also provide recommendations.";
        }
        
        return $response;
    }
    
    /**
     * Detect user intent
     */
    private function detectIntent($message) {
        $message_lower = strtolower($message);
        
        if(preg_match('/hello|hi|hey|greet/i', $message_lower)) {
            return 'greeting';
        }
        if(preg_match('/help|assist|support|can you/i', $message_lower)) {
            return 'help';
        }
        if(preg_match('/analyze|analyze|break down/i', $message_lower)) {
            return 'analysis';
        }
        if(preg_match('/\?/', $message)) {
            return 'question';
        }
        if(preg_match('/bye|goodbye|thanks|thank you|exit/i', $message_lower)) {
            return 'closing';
        }
        
        return 'default';
    }
    
    /**
     * Store message in database
     */
    private function storeMessage($sender_type, $message_text, $metadata = []) {
        $msg_data = [
            'session_id' => $this->session_id,
            'sender_type' => $sender_type,
            'message_text' => $message_text,
            'message_type' => 'text',
            'metadata' => json_encode($metadata),
            'created' => time()
        ];
        
        return $this->db->insert('alkebulan_chat_messages', $msg_data);
    }
    
    /**
     * Get conversation history
     */
    public function getConversationHistory($limit = 50) {
        return array_slice($this->conversation_history, -$limit);
    }
    
    /**
     * End chat session
     */
    public function endSession() {
        if($this->session_id) {
            return $this->db->update('alkebulan_chat_sessions', ['status' => 'closed'])
                ->where('id', $this->session_id)
                ->execute();
        }
        return false;
    }
    
    /**
     * Add context to session
     */
    public function addContext($context_data) {
        $this->context = array_merge($this->context, $context_data);
        
        return $this->db->update('alkebulan_chat_sessions', 
            ['context' => json_encode($this->context)])
            ->where('id', $this->session_id)
            ->execute();
    }
    
    /**
     * Get session summary
     */
    public function getSessionSummary() {
        $key_topics = $this->extractTopics();
        $sentiment = $this->analyzeConversationSentiment();
        
        return [
            'session_id' => $this->session_id,
            'message_count' => count($this->conversation_history),
            'key_topics' => $key_topics,
            'overall_sentiment' => $sentiment,
            'duration' => round((time() - $this->context['session_start'] ?? 0) / 60),
            'last_message_time' => end($this->conversation_history)['timestamp'] ?? null
        ];
    }
    
    /**
     * Extract key topics from conversation
     */
    private function extractTopics() {
        $topics = [];
        foreach($this->conversation_history as $msg) {
            $words = str_word_count($msg['message'], 1);
            $topics = array_merge($topics, $words);
        }
        
        $frequency = array_count_values($topics);
        arsort($frequency);
        
        return array_keys(array_slice($frequency, 0, 5));
    }
    
    /**
     * Analyze conversation sentiment
     */
    private function analyzeConversationSentiment() {
        $positive = 0;
        $negative = 0;
        $neutral = 0;
        
        $analyzer = new AIAnalyzer($this->user_id);
        
        foreach($this->conversation_history as $msg) {
            $sentiment = $analyzer->analyzeSentiment($msg['message']);
            
            if($sentiment['sentiment'] === 'positive') {
                $positive++;
            } elseif($sentiment['sentiment'] === 'negative') {
                $negative++;
            } else {
                $neutral++;
            }
        }
        
        $total = $positive + $negative + $neutral;
        
        return [
            'positive' => round(($positive / $total) * 100, 2),
            'negative' => round(($negative / $total) * 100, 2),
            'neutral' => round(($neutral / $total) * 100, 2)
        ];
    }
    
    /**
     * Get active sessions for user
     */
    public function getActiveSessions() {
        $query = $this->db->select('alkebulan_chat_sessions')
            ->where('user_id', $this->user_id)
            ->where('status', 'active')
            ->order_by('last_active', 'DESC')
            ->execute();
        
        return $query->fetch();
    }
    
    /**
     * Smart suggestions based on conversation
     */
    public function getSuggestions() {
        $suggestions = [];
        
        $current_topics = $this->extractTopics();
        
        foreach($current_topics as $topic) {
            $suggestions[] = "Tell me more about " . $topic;
            $suggestions[] = "How does " . $topic . " relate to your needs?";
        }
        
        return array_slice($suggestions, 0, 3);
    }
}
?>
