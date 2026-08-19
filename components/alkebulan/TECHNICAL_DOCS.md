# Alkebulan AI v2.2 - Technical Documentation

## 📋 Architecture Overview

```
Alkebulan AI Component
├── Views (Page Components)
│   ├── dashboard.php        (596 lines) - Main dashboard
│   ├── assistant.php        (610 lines) - Chat interface
│   ├── image-generator.php  (550 lines) - Image creation
│   ├── video-generator.php  (780 lines) - Video creation
│   ├── audio-generator.php  (390 lines) - Audio/TTS
│   ├── analyzer.php         (460 lines) - Content analysis
│   ├── insights.php         (370 lines) - AI insights
│   ├── features.php         (623 lines) - Feature showcase
│   ├── analytics.php        - Analytics dashboard
│   └── settings.php         - User settings
├── Actions (API Handlers)
│   ├── image.php       - Image generation endpoints
│   ├── video.php       - Video generation endpoints
│   ├── audio.php       - Audio generation endpoints
│   ├── chat.php        - Chat endpoints
│   ├── analyzer.php    - Analysis endpoints
│   ├── insights.php    - Insights endpoints
│   └── assistant.php   - Assistant endpoints
├── Styling
│   └── alkebulan.css   (800+ lines)
│       ├── Layout framework
│       ├── Component styles
│       ├── Animations (4 keyframes)
│       ├── Dark mode support
│       └── Responsive grid
├── JavaScript
│   └── alkebulan.js    (300+ lines)
│       ├── Component initialization
│       ├── API utilities
│       ├── Navigation helpers
│       └── Notification system
└── Database
    ├── alkebulan_analysis
    ├── alkebulan_recommendations
    ├── alkebulan_chat_sessions
    ├── alkebulan_images
    ├── alkebulan_videos
    ├── alkebulan_audios (optional)
    ├── alkebulan_usage_log
    ├── alkebulan_config
    └── alkebulan_user_settings
```

---

## 📄 File Structure

### View Files (PHP)

#### dashboard.php (596 lines)
```php
// Key Components:
- Header section (200px teal gradient)
- Profile card (stats and action buttons)
- Feature cards grid (8 features)
- Quick access buttons (8 buttons)
- Sidebar statistics
- Activity timeline
- Responsive layout
```

**Includes:**
- ossn_plugin_view() calls for other pages
- Direct template rendering
- CSS styling (inline)
- JavaScript functionality

---

#### assistant.php (610 lines)
```php
// Features:
- Chat interface with message display
- Typing indicator animation
- 6 suggestion buttons
- Message input/output
- Full responsive design
- JavaScript message handling
```

**Key Functions:**
- Display messages
- Handle user input
- Generate demo responses
- Update chat history

---

#### image-generator.php (550 lines)
```php
// Components:
- Text prompt textarea
- Style selector (6 options)
- Width/height sliders (400-1024px)
- Generate button
- Preview area
- Gallery section
- Statistics display

// Styles Available:
- Colorful
- Abstract
- Minimalist
- Dark
- Gradient
- Geometric
```

**JavaScript Features:**
- Real-time slider updates
- Form validation
- Demo image generation
- Gallery interaction

---

#### video-generator.php (780 lines)
```php
// Components:
- Text input field
- Style dropdown (6 cinematic styles)
- Duration slider (5-60 seconds)
- Frame rate selector (24/30/60)
- Quality selector (720p/1080p/4K)
- Music toggle
- Progress indicator
- Gallery display

// Styles Available:
- Cinematic
- Anime
- Documentary
- Abstract
- Sci-Fi
- Nature
```

**Advanced Features:**
- Progress tracking
- Quality-based sizing
- Video gallery
- Download functionality
- Statistics calculation

---

#### audio-generator.php (390 lines)
```php
// Core Components:
- Text input textarea (5000 char limit)
- Voice selector dropdown (6 voices)
- Language selector (9 languages)
- Voice characteristics (6 options)
- Speed slider (0.5x - 2.0x)
- Pitch slider (-50% to +50%)
- Audio normalization checkbox
- Download format selector (MP3/WAV)
- Audio player
- Generate button
- Statistics display

// Voice Options:
1. Alloy - Neutral tone
2. Echo - Professional
3. Fable - Storyteller
4. Onyx - Deep voice
5. Nova - Warm tone
6. Shimmer - Bright tone

// Language Support:
- en-US (English US)
- en-GB (English UK)
- es (Spanish)
- fr (French)
- de (German)
- it (Italian)
- ja (Japanese)
- pt (Portuguese)
- zh (Chinese)

// Voice Characteristics:
- Natural
- Energetic
- Calm
- Robotic
- Whisper
- Theatrical
```

**JavaScript Functions:**
- Real-time slider updates
- Voice selector change handling
- Language selection
- Audio generation simulation
- Duration estimation
- Download handler

---

#### analyzer.php (460 lines)
```php
// Main Components:
- Text input textarea
- Tab navigation (All/Sentiment/Keywords/Readability)
- Analysis request button
- Results display area

// Sentiment Analysis Tab:
- Overall sentiment classification
- Confidence percentage
- Sentiment breakdown:
  - Positive %
  - Neutral %
  - Negative %
- Visual sentiment bar
- Classification details

// Keyword Extraction Tab:
- Top keywords list
- Frequency counts
- Entity recognition
- Named entities display
- Tag-based layout

// Readability Tab:
- Reading level classification
- Flesch Reading Index (0-100)
  - 0-30: College Graduate level
  - 30-60: High School level
  - 60-100: Elementary level
- Word count
- Average word length
- Sentence count
- Average sentence length
- Character count
```

**JavaScript Features:**
- Sentiment analysis calculation
- Keyword extraction algorithm
- Readability metrics computation
- Tab switching functionality
- Result display handling

**Sentiment Calculation:**
```javascript
- Positive words: happy, great, excellent, love, perfect...
- Negative words: hate, terrible, bad, awful, worst...
- Confidence: word count / total words
```

---

#### insights.php (370 lines)
```php
// Components:

// Insight Cards (6 total):
1. Content Performance
   - Engagement rate: 78%
   - Peak engagement time: 8-10 PM
   - Reach: 12,450 users
   - Impressions: 45,230

2. Top Topics
   - Current trending topics
   - Tag cloud display
   - Total topics: 24
   - Growing topics badge

3. Audience Growth
   - New followers: +145/month
   - Growth rate: 12% monthly
   - Follower trend: 📈 upward
   - Projected: 3.2K next month

4. AI Recommendations
   - 5 actionable recommendations
   - Implementation difficulty
   - Expected success rate: 85%
   - Time to implement

5. Content Trends
   - Best format: Video (65% engagement)
   - Optimal length: 5-10 minutes
   - Best posting time: Wed 8-10 PM
   - Best day: Wednesday

6. Competitor Analysis
   - Your rank: #42
   - Average followers: 12.5K
   - Engagement difference: +15%
   - Market position analysis

// Trending Section:
- Top 5 hashtags/topics
- Post counts
- Growth percentages
- Interactive badges
```

**Styling Features:**
- Card hover elevation
- Badge animations
- Responsive grid layout
- Color-coded metrics
- Icon integration

---

### Action Handler Files

#### audio.php (30 lines)
```php
// Endpoints:
POST /action/alkebulan/audio/generate
- Input: text, voice, language, speed, pitch
- Output: audio_id, duration, file_url
- Storage: alkebulan_audios table

POST /action/alkebulan/audio/download
- Input: audio_id, format (mp3/wav)
- Output: file download
- Headers: Content-Type, Content-Disposition
```

---

#### analyzer.php (35 lines)
```php
// Endpoints:
POST /action/alkebulan/analyzer/analyze
- Input: text, analysis_type (all/sentiment/keywords/readability)
- Output: results JSON with sentiment, keywords, metrics
- Storage: alkebulan_analysis table
```

---

#### insights.php (30 lines)
```php
// Endpoints:
POST /action/alkebulan/insights/get
- Input: user_id, date_range
- Output: 6 insight cards data
- Calculation: Aggregated from analytics

POST /action/alkebulan/insights/trending
- Input: limit (default: 5)
- Output: trending hashtags/topics with metrics
- Source: alkebulan_trending_topics table
```

---

## 🎨 CSS Architecture

### File: alkebulan.css (800+ lines)

#### Animations (4 keyframes):
```css
@keyframes slideInUp {
  from: transform translateY(20px), opacity 0
  to: transform translateY(0), opacity 1
}

@keyframes fadeIn {
  from: opacity 0
  to: opacity 1
}

@keyframes pulse {
  animation loop: opacity 0.7 → 1 → 0.7
}

@keyframes glow {
  0%: box-shadow color 0 0 0
  50%: box-shadow color 0 0 20px
  100%: box-shadow color 0 0 0
}
```

#### Component Classes:
```css
.alkebulan-container      - Main wrapper
.alkebulan-header         - Header section
.alkebulan-card           - Card component
.alkebulan-btn            - Button styles
.alkebulan-input          - Input fields
.alkebulan-grid           - Grid layout
.alkebulan-badge          - Badge styles
.alkebulan-alert          - Alert component
.alkebulan-spinner        - Loading spinner
```

#### Button Variants:
```css
.btn-primary              - Primary action button
.btn-secondary            - Secondary action
.btn-success              - Success action
.btn-danger               - Danger action
.btn-warning              - Warning action
.btn-info                 - Information action
```

#### Color Scheme:
```css
Primary: #667eea → #764ba2 (gradient)
Header: #1a9b8e → #2d6a64 (teal gradient)
Success: #10b981
Warning: #f59e0b
Danger: #ef4444
Info: #3b82f6
Text: #1f2937 (dark)
Background: #f9fafb (light)
```

#### Responsive Breakpoints:
```css
Mobile: < 640px
Tablet: 640px - 1024px
Desktop: > 1024px
```

#### Dark Mode:
```css
@media (prefers-color-scheme: dark) {
  Background: #111827
  Text: #f3f4f6
  Cards: #1f2937
  Borders: #374151
}
```

---

## 💻 JavaScript Architecture

### File: alkebulan.js (300+ lines)

#### Global Object:
```javascript
window.Alkebulan = {
  // Core properties
  siteUrl: string,
  userId: number,
  
  // Navigation
  navigate(page) → void
  
  // Utilities
  getSiteUrl(path) → string
  apiCall(endpoint, method, data) → Promise
  
  // UI
  notify(message, type) → void
  showLoading() → void
  hideLoading() → void
  
  // Analytics
  trackEvent(event, data) → void
  trackPageView(page) → void
}
```

#### Helper Functions:
```javascript
// Navigation
Alkebulan.navigate('dashboard')   // Go to page
Alkebulan.navigate('image-generator')

// URL Building
Alkebulan.getSiteUrl('/alkebulan/dashboard/')
Alkebulan.getSiteUrl('/action/alkebulan/image/generate')

// API Calls
Alkebulan.apiCall(
  '/action/alkebulan/image/generate',
  'POST',
  { text: 'description', style: 'colorful' }
).then(response => { ... })

// Notifications
Alkebulan.notify('Image generated!', 'success')
Alkebulan.notify('Error occurred', 'danger')
Alkebulan.notify('Please wait...', 'info')
```

#### Feature-Specific Scripts:

**Image Generator:**
```javascript
function generateImage(event) {
  const text = document.getElementById('prompt').value
  const style = document.getElementById('style').value
  const width = parseInt(document.getElementById('width').value)
  const height = parseInt(document.getElementById('height').value)
  
  Alkebulan.apiCall('/action/alkebulan/image/generate', 'POST', {
    text, style, width, height
  }).then(data => {
    displayImage(data.image_url)
  })
}
```

**Video Generator:**
```javascript
function generateVideo(event) {
  const text = document.getElementById('description').value
  const style = document.getElementById('style').value
  const duration = parseInt(document.getElementById('duration').value)
  const fps = parseInt(document.getElementById('fps').value)
  const quality = document.getElementById('quality').value
  const music = document.getElementById('music').checked
  
  Alkebulan.apiCall('/action/alkebulan/video/generate', 'POST', {
    text, style, duration, fps, quality, music
  }).then(data => {
    updateProgress(data.progress)
  })
}
```

**Audio Generator:**
```javascript
function generateAudio(event) {
  const text = document.getElementById('text').value
  const voice = document.getElementById('voice').value
  const language = document.getElementById('language').value
  const speed = parseFloat(document.getElementById('speed').value)
  const pitch = parseInt(document.getElementById('pitch').value)
  
  Alkebulan.apiCall('/action/alkebulan/audio/generate', 'POST', {
    text, voice, language, speed, pitch
  }).then(data => {
    playAudio(data.audio_url)
  })
}
```

**Content Analyzer:**
```javascript
function analyzeContent(event) {
  const text = document.getElementById('content').value
  const analysisType = document.querySelector('.tab-active')?.dataset.type
  
  Alkebulan.apiCall('/action/alkebulan/analyzer/analyze', 'POST', {
    text, type: analysisType
  }).then(data => {
    displayAnalysisResults(data)
  })
}
```

---

## 🗄️ Database Schema

### Table: alkebulan_images
```sql
CREATE TABLE alkebulan_images (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  prompt TEXT NOT NULL,
  style VARCHAR(50),
  width INT,
  height INT,
  image_url VARCHAR(255),
  file_size INT,
  generation_time FLOAT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  KEY idx_user_id (user_id),
  KEY idx_created_at (created_at)
)
```

### Table: alkebulan_videos
```sql
CREATE TABLE alkebulan_videos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  description TEXT,
  style VARCHAR(50),
  duration INT,
  fps INT,
  quality VARCHAR(20),
  has_music BOOLEAN,
  video_url VARCHAR(255),
  file_size INT,
  generation_time FLOAT,
  created_at TIMESTAMP,
  KEY idx_user_id (user_id),
  KEY idx_created_at (created_at)
)
```

### Table: alkebulan_audios (Optional)
```sql
CREATE TABLE alkebulan_audios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  text TEXT,
  voice VARCHAR(50),
  language VARCHAR(10),
  speed FLOAT,
  pitch INT,
  audio_url VARCHAR(255),
  file_size INT,
  duration FLOAT,
  format VARCHAR(10),
  generation_time FLOAT,
  created_at TIMESTAMP,
  KEY idx_user_id (user_id)
)
```

### Table: alkebulan_analysis
```sql
CREATE TABLE alkebulan_analysis (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  text TEXT,
  sentiment VARCHAR(20),
  confidence FLOAT,
  keywords JSON,
  entities JSON,
  readability_score INT,
  word_count INT,
  sentence_count INT,
  created_at TIMESTAMP,
  KEY idx_user_id (user_id)
)
```

### Table: alkebulan_usage_log
```sql
CREATE TABLE alkebulan_usage_log (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  action VARCHAR(100),
  resource_type VARCHAR(50),
  duration FLOAT,
  status VARCHAR(20),
  ip_address VARCHAR(50),
  created_at TIMESTAMP,
  KEY idx_user_id (user_id),
  KEY idx_action (action)
)
```

---

## 🔌 API Endpoints

### Image Generation
```
POST /action/alkebulan/image/generate
POST /action/alkebulan/image/download
POST /action/alkebulan/image/delete
POST /action/alkebulan/image/gallery
```

### Video Generation
```
POST /action/alkebulan/video/generate
POST /action/alkebulan/video/download
POST /action/alkebulan/video/delete
POST /action/alkebulan/video/stats
```

### Audio Generation
```
POST /action/alkebulan/audio/generate
POST /action/alkebulan/audio/download
```

### Content Analysis
```
POST /action/alkebulan/analyzer/analyze
```

### Chat & Insights
```
POST /action/alkebulan/chat
POST /action/alkebulan/insights/get
POST /action/alkebulan/insights/trending
```

---

## 🔐 Security Implementation

### Authentication:
- Requires valid OSSN session
- User ID validation on all endpoints
- Session token verification

### Authorization:
- User can only access their own data
- Admin functions restricted
- Rate limiting implemented

### Input Validation:
```php
// All inputs sanitized
$text = ossn_secure_input($_POST['text']);
$style = ossn_secure_input($_POST['style']);

// Length validation
if (strlen($text) > 5000) { return false; }

// Type validation
$width = intval($_POST['width']);
if ($width < 400 || $width > 1024) { return false; }
```

### Output Encoding:
```php
// HTML escaping
echo htmlspecialchars($user_input, ENT_QUOTES);

// JSON encoding
echo json_encode($data, JSON_UNESCAPED_UNICODE);
```

---

## 📈 Performance Optimization

### Caching:
- Page caching (1 hour)
- API response caching (5 minutes)
- Database query caching

### Asset Optimization:
- CSS minification
- JavaScript minification
- Image compression
- Lazy loading for galleries

### Database Optimization:
- Indexed frequently queried columns
- Optimized queries with proper JOINs
- Connection pooling

---

## 🐛 Error Handling

### Frontend:
```javascript
try {
  const response = await Alkebulan.apiCall(endpoint, method, data)
  displayResults(response)
} catch (error) {
  console.error('API Error:', error)
  Alkebulan.notify('An error occurred', 'danger')
}
```

### Backend:
```php
try {
  // Process request
  $result = processAction()
  echo json_encode(['status' => 'success', 'data' => $result])
} catch (Exception $e) {
  http_response_code(500)
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()])
}
```

---

## 📝 Development Guidelines

### Adding New Features:

1. **Create View File** (views/newfeature.php)
   - Follow existing structure
   - Include responsive grid
   - Add CSS styling
   - Include JavaScript functionality

2. **Create Action Handler** (actions/newfeature.php)
   - Validate user authentication
   - Sanitize inputs
   - Process request
   - Return JSON response

3. **Update ossn_com.php**
   - Register page handler
   - Register action handler
   - Create database table if needed

4. **Update Dashboard**
   - Add feature card
   - Add quick button
   - Update stats

5. **Test Thoroughly**
   - Test on desktop/mobile
   - Test error cases
   - Test dark mode
   - Check console for errors

---

## 🚀 Deployment Checklist

- [ ] All pages tested in browser
- [ ] No JavaScript console errors
- [ ] CSS styling responsive
- [ ] Dark mode working
- [ ] Database tables created
- [ ] API endpoints responding
- [ ] Authentication working
- [ ] Error handling implemented
- [ ] Documentation complete
- [ ] Performance optimized

---

## 📞 Support & Maintenance

### Logs Location:
- OSSN Logs: `/logs/`
- Error logs: `/logs/error.log`
- Access logs: `/logs/access.log`

### Database Maintenance:
```sql
-- Check table status
CHECK TABLE alkebulan_images;
CHECK TABLE alkebulan_videos;

-- Optimize tables
OPTIMIZE TABLE alkebulan_images;
OPTIMIZE TABLE alkebulan_videos;
```

### Cache Clearing:
```php
ossn_cache_delete_all();
```

---

**Last Updated**: January 2038  
**Version**: 2.2  
**Status**: Production Ready
