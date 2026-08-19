/**
 * Alkebulan AI - Main JavaScript File
 * Handles interactions for all Alkebulan AI pages
 */

(function() {
    'use strict';

    // Alkebulan namespace
    window.Alkebulan = window.Alkebulan || {};

    /**
     * Initialize Alkebulan AI when DOM is ready
     */
    Alkebulan.init = function() {
        console.log('Alkebulan AI initialized');
        
        // Load dashboard data
        if (document.querySelector('.alkebulan-dashboard')) {
            Alkebulan.loadDashboardData();
        }
        
        // Load analytics data
        if (document.querySelector('.alkebulan-analytics')) {
            Alkebulan.loadAnalyticsData();
        }
        
        // Load image generator
        if (document.querySelector('.alkebulan-image-generator')) {
            Alkebulan.initImageGenerator();
        }
        
        // Initialize chat if present
        if (document.querySelector('.alkebulan-chat')) {
            Alkebulan.initChat();
        }
    };

    /**
     * Load dashboard statistics
     */
    Alkebulan.loadDashboardData = function() {
        console.log('Loading dashboard data...');
        // Data will be loaded via AJAX from action endpoints
    };

    /**
     * Load analytics data
     */
    Alkebulan.loadAnalyticsData = function() {
        console.log('Loading analytics data...');
        // Data will be loaded via AJAX
    };

    /**
     * Initialize image generator
     */
    Alkebulan.initImageGenerator = function() {
        console.log('Initializing image generator...');
        
        var generateBtn = document.querySelector('.btn-generate-image');
        if (generateBtn) {
            generateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                Alkebulan.generateImage();
            });
        }
    };

    /**
     * Generate image from prompt
     */
    Alkebulan.generateImage = function() {
        var prompt = document.querySelector('input[name="prompt"]');
        var style = document.querySelector('select[name="style"]');
        
        if (!prompt || !prompt.value.trim()) {
            alert('Please enter a prompt');
            return;
        }
        
        console.log('Generating image with prompt: ' + prompt.value);
        // Image generation will be handled via AJAX
    };

    /**
     * Initialize chat interface
     */
    Alkebulan.initChat = function() {
        console.log('Initializing chat...');
        
        var sendBtn = document.querySelector('.btn-send-message');
        if (sendBtn) {
            sendBtn.addEventListener('click', function(e) {
                e.preventDefault();
                Alkebulan.sendMessage();
            });
        }
    };

    /**
     * Send chat message
     */
    Alkebulan.sendMessage = function() {
        var input = document.querySelector('input[name="message"]');
        if (input && input.value.trim()) {
            console.log('Sending message: ' + input.value);
            // Message will be sent via AJAX
        }
    };

    /**
     * Make AJAX request to Alkebulan endpoint
     */
    Alkebulan.apiCall = function(action, data, callback) {
        var formData = new FormData();
        formData.append('__elgg_action', action);
        
        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                formData.append(key, data[key]);
            }
        }
        
        fetch('/action/' + action, {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (callback) {
                callback(data);
            }
        })
        .catch(function(error) {
            console.error('API Error:', error);
        });
    };

    /**
     * Navigate to page
     */
    window.navigateTo = function(page) {
        var baseUrl = '<?php echo ossn_site_url('alkebulan'); ?>/';
        switch(page) {
            case 'analyze':
                window.location.href = baseUrl + 'features/';
                break;
            case 'recommend':
                window.location.href = baseUrl + 'features/';
                break;
            default:
                window.location.href = baseUrl;
        }
    };

    /**
     * Start chat
     */
    window.startChat = function() {
        window.location.href = '<?php echo ossn_site_url('alkebulan/assistant'); ?>';
    };

    /**
     * Get recommendations
     */
    window.getRecommendations = function() {
        window.location.href = '<?php echo ossn_site_url('alkebulan/features'); ?>';
    };

    /**
     * View analytics
     */
    window.viewAnalytics = function() {
        window.location.href = '<?php echo ossn_site_url('alkebulan/analytics'); ?>';
    };

    /**
     * Show analyze modal/form
     */
    window.showAnalyzeModal = function() {
        window.location.href = '<?php echo ossn_site_url('alkebulan/features'); ?>';
    };

    /**
     * Start new chat
     */
    window.startNewChat = function() {
        window.location.href = '<?php echo ossn_site_url('alkebulan/assistant'); ?>';
    };

    /**
     * Analyze text (for features page)
     */
    window.analyzeText = function() {
        var textArea = document.getElementById('analyze-text');
        if (!textArea) return;
        
        var text = textArea.value;
        if (!text.trim()) {
            alert('Please enter some text to analyze');
            return;
        }
        
        // Simulate analysis with demo results
        var result = document.getElementById('analysis-result');
        if (result) {
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
    };

    /**
     * Demo analysis
     */
    window.analyzeDemo = function() {
        var textArea = document.getElementById('analyze-text');
        if (textArea) {
            textArea.value = 'I absolutely love this amazing feature! It is incredible and works perfectly. Highly recommended!';
            window.analyzeText();
        }
    };

    /**
     * Navigation Helper
     */
    Alkebulan.navigate = function(path) {
        window.location.href = Alkebulan.getSiteUrl('alkebulan/' + path);
    };

    /**
     * Get site URL
     */
    Alkebulan.getSiteUrl = function(path) {
        var baseUrl = document.body.getAttribute('data-site-url') || '/live%20stream/';
        return baseUrl + path + '/';
    };

    /**
     * Show notification
     */
    Alkebulan.notify = function(message, type) {
        type = type || 'info';
        var notification = document.createElement('div');
        notification.className = 'alkebulan-notification notification-' + type;
        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 15px 20px; background: white; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); animation: slideInUp 0.3s ease-out;';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(function() {
            notification.style.animation = 'fadeOut 0.3s ease-out forwards';
            setTimeout(function() {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    };

    /**
     * API Call Helper
     */
    Alkebulan.apiCall = function(action, data, callback) {
        var formData = new FormData();
        
        if (typeof data === 'object') {
            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    formData.append(key, data[key]);
                }
            }
        }
        
        fetch(ossn_site_url('action/alkebulan/' + action), {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (callback) {
                callback(data);
            }
        })
        .catch(function(error) {
            console.error('API Error:', error);
            Alkebulan.notify('Error: ' + error.message, 'error');
        });
    };

    /**
        Alkebulan.init();
    }

})();
