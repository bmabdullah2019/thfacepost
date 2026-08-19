<?php
/**
 * Enhanced Component Generation System - Test & Demo
 * Demonstrates all new features with examples
 */

// Access this file: http://localhost/alkebulan/component_generation_test.php

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Component Generation - Test & Demo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        h1 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
        }
        h2 {
            color: #764ba2;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .feature-section {
            background: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .endpoint {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #333;
            margin: 10px 0;
        }
        .code-block {
            background: #282c34;
            color: #abb2bf;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            line-height: 1.5;
        }
        .method-tag {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 0.85em;
            margin-right: 8px;
        }
        .feature-list {
            list-style-position: inside;
            margin: 15px 0;
        }
        .feature-list li {
            margin: 8px 0;
            color: #333;
        }
        .parameter-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .parameter-table th,
        .parameter-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .parameter-table th {
            background: #667eea;
            color: white;
            font-weight: bold;
        }
        .parameter-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .test-btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin: 10px 5px 10px 0;
            transition: all 0.3s;
        }
        .test-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .response-box {
            background: #f0f8ff;
            border: 1px solid #667eea;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            display: none;
        }
        .response-box.show {
            display: block;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .summary-table th {
            background: #667eea;
            color: white;
        }
        .badge {
            display: inline-block;
            background: #764ba2;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.85em;
            margin: 2px;
        }
        .tip {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .tip strong {
            color: #1976D2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Enhanced Component Generation System - Test & Documentation</h1>

        <!-- OVERVIEW -->
        <h2>📋 System Overview</h2>
        <div class="feature-section">
            <p><strong>Version:</strong> 2.0</p>
            <p><strong>Status:</strong> Active & Ready</p>
            <p><strong>Total Features:</strong> 20+</p>
            <p><strong>Base Path:</strong> /action/alkebulan/component_generate/</p>
            
            <h3 style="color: #667eea; margin-top: 15px;">Feature Categories:</h3>
            <ul class="feature-list">
                <li><span class="badge">TEXT</span> 7 advanced text generation features</li>
                <li><span class="badge">IMAGE</span> 4 advanced image generation features</li>
                <li><span class="badge">AUDIO</span> 2 advanced audio features</li>
                <li><span class="badge">VIDEO</span> 2 advanced video features</li>
                <li><span class="badge">ADVANCED</span> 5 advanced utility features</li>
            </ul>
        </div>

        <!-- TEXT GENERATION -->
        <h2>📝 Text Generation Features</h2>
        
        <div class="feature-section">
            <h3>1. Content Bundle Generation</h3>
            <p>Generate complete content packages from a single prompt.</p>
            <span class="method-tag">POST</span>
            <div class="endpoint">/action/alkebulan/component_generate/generate_content_bundle</div>
            
            <table class="parameter-table">
                <tr>
                    <th>Parameter</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>prompt</td>
                    <td>string</td>
                    <td>Yes</td>
                    <td>Main topic or content idea</td>
                </tr>
                <tr>
                    <td>include_article</td>
                    <td>boolean</td>
                    <td>No</td>
                    <td>Generate full article (default: true)</td>
                </tr>
                <tr>
                    <td>include_outline</td>
                    <td>boolean</td>
                    <td>No</td>
                    <td>Generate outline (default: true)</td>
                </tr>
                <tr>
                    <td>include_summary</td>
                    <td>boolean</td>
                    <td>No</td>
                    <td>Generate summary (default: true)</td>
                </tr>
                <tr>
                    <td>include_title</td>
                    <td>boolean</td>
                    <td>No</td>
                    <td>Generate title (default: true)</td>
                </tr>
                <tr>
                    <td>include_meta</td>
                    <td>boolean</td>
                    <td>No</td>
                    <td>Generate meta description</td>
                </tr>
                <tr>
                    <td>include_hashtags</td>
                    <td>boolean</td>
                    <td>No</td>
                    <td>Generate hashtags</td>
                </tr>
                <tr>
                    <td>include_social</td>
                    <td>boolean</td>
                    <td>No</td>
                    <td>Generate social media posts</td>
                </tr>
                <tr>
                    <td>include_email</td>
                    <td>boolean</td>
                    <td>No</td>
                    <td>Generate email version</td>
                </tr>
            </table>

            <p><strong>Example Request:</strong></p>
            <div class="code-block">{
  "prompt": "Artificial Intelligence in Healthcare",
  "include_article": true,
  "include_outline": true,
  "include_summary": true,
  "include_title": true,
  "include_meta": true,
  "include_hashtags": true,
  "include_social": true,
  "include_email": true
}</div>

            <button class="test-btn" onclick="testEndpoint('generate_content_bundle')">Test This Feature</button>
            <div id="response_generate_content_bundle" class="response-box"></div>
        </div>

        <!-- Feature Summary Table -->
        <h2>📊 Complete Feature Matrix</h2>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Feature Name</th>
                    <th>Endpoint</th>
                    <th>Use Case</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge">TEXT</span></td>
                    <td>Content Bundle</td>
                    <td>generate_content_bundle</td>
                    <td>Generate 8+ content types from single prompt</td>
                </tr>
                <tr>
                    <td><span class="badge">TEXT</span></td>
                    <td>From Outline</td>
                    <td>generate_from_outline</td>
                    <td>Create full articles from outline</td>
                </tr>
                <tr>
                    <td><span class="badge">TEXT</span></td>
                    <td>Batch Generation</td>
                    <td>batch_generate</td>
                    <td>Generate multiple articles at once</td>
                </tr>
                <tr>
                    <td><span class="badge">TEXT</span></td>
                    <td>Quality Enhancement</td>
                    <td>quality_enhance</td>
                    <td>Improve grammar, clarity, engagement</td>
                </tr>
                <tr>
                    <td><span class="badge">TEXT</span></td>
                    <td>Plagiarism Check</td>
                    <td>plagiarism_check</td>
                    <td>Verify content uniqueness</td>
                </tr>
                <tr>
                    <td><span class="badge">TEXT</span></td>
                    <td>SEO Optimization</td>
                    <td>seo_optimize</td>
                    <td>Optimize for search engines</td>
                </tr>
                <tr>
                    <td><span class="badge">TEXT</span></td>
                    <td>Paraphrase Content</td>
                    <td>paraphrase_content</td>
                    <td>Create content variations</td>
                </tr>
                <tr>
                    <td><span class="badge">IMAGE</span></td>
                    <td>Style Transfer</td>
                    <td>style_transfer</td>
                    <td>Apply artistic styles to images</td>
                </tr>
                <tr>
                    <td><span class="badge">IMAGE</span></td>
                    <td>Image Upscaling</td>
                    <td>image_upscale</td>
                    <td>Enhance image resolution (2x/4x)</td>
                </tr>
                <tr>
                    <td><span class="badge">IMAGE</span></td>
                    <td>Image Editing</td>
                    <td>image_edit</td>
                    <td>Brightness, contrast, effects</td>
                </tr>
                <tr>
                    <td><span class="badge">IMAGE</span></td>
                    <td>Batch Generation</td>
                    <td>batch_image_generate</td>
                    <td>Generate multiple images</td>
                </tr>
                <tr>
                    <td><span class="badge">AUDIO</span></td>
                    <td>Batch TTS</td>
                    <td>text_to_speech_batch</td>
                    <td>Convert multiple texts to speech</td>
                </tr>
                <tr>
                    <td><span class="badge">AUDIO</span></td>
                    <td>Voice Cloning</td>
                    <td>voice_clone</td>
                    <td>Clone voice from samples</td>
                </tr>
                <tr>
                    <td><span class="badge">VIDEO</span></td>
                    <td>Video Editing</td>
                    <td>video_edit</td>
                    <td>Edit, trim, enhance videos</td>
                </tr>
                <tr>
                    <td><span class="badge">VIDEO</span></td>
                    <td>Voiceover Generation</td>
                    <td>generate_with_voiceover</td>
                    <td>Create videos with narration</td>
                </tr>
                <tr>
                    <td><span class="badge">ADVANCED</span></td>
                    <td>Smart Suggestions</td>
                    <td>smart_suggestion</td>
                    <td>AI-powered content ideas</td>
                </tr>
                <tr>
                    <td><span class="badge">ADVANCED</span></td>
                    <td>Content Calendar</td>
                    <td>content_calendar</td>
                    <td>Plan content schedule</td>
                </tr>
                <tr>
                    <td><span class="badge">ADVANCED</span></td>
                    <td>AI Collaboration</td>
                    <td>ai_collaboration</td>
                    <td>Share & collaborate on content</td>
                </tr>
                <tr>
                    <td><span class="badge">ADVANCED</span></td>
                    <td>Performance Metrics</td>
                    <td>performance_metrics</td>
                    <td>Track generation statistics</td>
                </tr>
                <tr>
                    <td><span class="badge">ADVANCED</span></td>
                    <td>Export Content</td>
                    <td>export_content</td>
                    <td>Export to PDF, DOCX, etc.</td>
                </tr>
            </tbody>
        </table>

        <!-- Quick Links -->
        <h2>🔗 Quick Access Links</h2>
        <div class="feature-section">
            <p><strong>Dashboard UI:</strong></p>
            <div class="endpoint">http://localhost/alkebulan/pages/enhanced_generation.html</div>
            
            <p style="margin-top: 15px;"><strong>API Base Path:</strong></p>
            <div class="endpoint">/action/alkebulan/component_generate/[feature_name]</div>
            
            <p style="margin-top: 15px;"><strong>System Information:</strong></p>
            <div class="endpoint">/action/alkebulan/component_generate/info</div>
        </div>

        <!-- Best Practices -->
        <h2>💡 Best Practices</h2>
        <div class="tip">
            <strong>✓ Use Content Bundles</strong> when you need multiple content types from single idea
        </div>
        <div class="tip">
            <strong>✓ Batch Operations</strong> for generating 5+ items at once (saves time)
        </div>
        <div class="tip">
            <strong>✓ SEO Optimization</strong> before publishing important content
        </div>
        <div class="tip">
            <strong>✓ Quality Enhancement</strong> on rough drafts to improve quality
        </div>
        <div class="tip">
            <strong>✓ Content Calendar</strong> for planning 2-4 weeks ahead
        </div>
        <div class="tip">
            <strong>✓ Batch Voiceovers</strong> for creating audiobooks or podcasts
        </div>

        <!-- Integration Examples -->
        <h2>🔌 Integration Examples</h2>
        <div class="feature-section">
            <h3>JavaScript Example</h3>
            <div class="code-block">async function generateContentBundle(topic) {
  const response = await fetch(
    '/action/alkebulan/component_generate/generate_content_bundle',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        prompt: topic,
        include_article: true,
        include_summary: true,
        include_social: true
      })
    }
  );
  return await response.json();
}

// Usage
const result = await generateContentBundle('My Topic');
console.log(result);
            </div>

            <h3>cURL Example</h3>
            <div class="code-block">curl -X POST \
  http://localhost/action/alkebulan/component_generate/generate_content_bundle \
  -H 'Content-Type: application/json' \
  -d '{
    "prompt": "Artificial Intelligence",
    "include_article": true,
    "include_summary": true,
    "include_social": true
  }'
            </div>
        </div>

        <!-- Support -->
        <h2>📞 Support & Help</h2>
        <div class="feature-section">
            <p><strong>Documentation:</strong> /alkebulan/ENHANCED_COMPONENT_GENERATION_DOCS.md</p>
            <p><strong>Latest Features:</strong> This file (component_generation_test.php)</p>
            <p><strong>User Interface:</strong> /alkebulan/pages/enhanced_generation.html</p>
            <p><strong>System Status:</strong> All 20+ features are active and ready</p>
            
            <h3 style="color: #667eea; margin-top: 15px;">API Response Format:</h3>
            <div class="code-block">{
  "status": "success|error",
  "message": "Description",
  "data": { /* Feature-specific data */ },
  "timestamp": "2024-01-15 14:30:00",
  "generation_time": "2345ms"
}
            </div>
        </div>
    </div>

    <script>
        async function testEndpoint(endpoint) {
            const box = document.getElementById('response_' + endpoint);
            
            if (!box) return;
            
            box.innerHTML = '<p>Testing...</p>';
            box.classList.add('show');

            try {
                const response = await fetch(
                    '/action/alkebulan/component_generate/' + endpoint,
                    {
                        method: endpoint === 'info' ? 'GET' : 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: endpoint === 'info' ? undefined : JSON.stringify({
                            prompt: 'Test Topic',
                            include_article: true,
                            include_outline: true,
                            include_summary: true
                        })
                    }
                );

                const data = await response.json();
                
                box.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                box.innerHTML = '<p style="color: red;">Error: ' + error.message + '</p>';
            }
        }
    </script>
</body>
</html>
