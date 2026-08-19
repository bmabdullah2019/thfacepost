# Alkebulan AI v2.2 - Quick Start Guide

## ⚡ Installation & Setup

### Prerequisites
- OSSN 7.6 or higher
- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx)

### Installation Steps

#### 1. Component File Placement
Copy the Alkebulan component folder to OSSN plugins:
```
xampp/htdocs/live stream/components/Alkebulan/
```

#### 2. File Structure
```
Alkebulan/
├── views/
│   ├── dashboard.php
│   ├── assistant.php
│   ├── image-generator.php
│   ├── video-generator.php
│   ├── audio-generator.php
│   ├── analyzer.php
│   ├── insights.php
│   ├── features.php
│   ├── analytics.php
│   └── settings.php
├── actions/
│   ├── image.php
│   ├── video.php
│   ├── audio.php
│   ├── chat.php
│   ├── analyzer.php
│   ├── insights.php
│   └── assistant.php
├── languages/
│   └── en.php
├── includes/
│   ├── config.php
│   └── functions.php
├── static/
│   └── css/
│       └── alkebulan.css
│   └── js/
│       └── alkebulan.js
└── ossn_com.php (main component file)
```

#### 3. Database Setup
The component automatically creates tables on first access:
- alkebulan_images
- alkebulan_videos
- alkebulan_analysis
- alkebulan_recommendations
- alkebulan_chat_sessions
- alkebulan_usage_log
- alkebulan_config
- alkebulan_user_settings

#### 4. Activate Component
1. Log in to OSSN Admin Panel
2. Go to Settings → Components
3. Find "Alkebulan AI"
4. Click "Activate"

#### 5. Verify Installation
Visit: `http://localhost/live%20stream/alkebulan/dashboard/`

---

## 🎯 First Time User Guide

### Step 1: Access Dashboard
```
URL: /alkebulan/dashboard/
```
You'll see:
- Welcome message
- 8 feature cards
- Quick access buttons
- Usage statistics
- Recent activity

### Step 2: Try Chat Assistant
```
URL: /alkebulan/assistant/
```
1. Click in the message input box
2. Type a message or use suggestion buttons
3. See AI responses
4. View chat history

### Step 3: Generate Image
```
URL: /alkebulan/image-generator/
```
1. Describe the image: "A sunset over mountains"
2. Select style: "Colorful"
3. Set size: 512×512
4. Click "Generate Image"
5. Download or view gallery

### Step 4: Create Video
```
URL: /alkebulan/video-generator/
```
1. Describe the video: "A spaceship flying through stars"
2. Choose style: "Sci-Fi"
3. Set duration: 20 seconds
4. Select quality: "1080p"
5. Enable music
6. Click "Generate Video"

### Step 5: Generate Audio
```
URL: /alkebulan/audio-generator/
```
1. Enter text: "Hello, welcome to Alkebulan AI"
2. Choose voice: "Nova" (warm tone)
3. Select language: "en-US"
4. Adjust speed: 1.0x
5. Click "Generate Audio"
6. Listen or download as MP3

### Step 6: Analyze Content
```
URL: /alkebulan/analyzer/
```
1. Paste or type content
2. Click "Analyze"
3. View results:
   - **Sentiment**: Positive/Negative/Neutral
   - **Keywords**: Top terms and frequency
   - **Readability**: Grade level and metrics

### Step 7: View Insights
```
URL: /alkebulan/insights/
```
1. See AI-generated insights:
   - Content Performance
   - Top Topics
   - Audience Growth
   - Recommendations
   - Content Trends
   - Competitor Analysis
2. Check trending section
3. Review recommendations

---

## ⚙️ Configuration

### Basic Settings
Edit: `components/Alkebulan/includes/config.php`

```php
// API Configuration
define('ALKEBULAN_IMAGE_MAX_SIZE', 1024);
define('ALKEBULAN_VIDEO_MAX_DURATION', 60);
define('ALKEBULAN_AUDIO_MAX_LENGTH', 5000);

// Feature Toggles
define('ALKEBULAN_ENABLE_IMAGES', true);
define('ALKEBULAN_ENABLE_VIDEOS', true);
define('ALKEBULAN_ENABLE_AUDIO', true);
define('ALKEBULAN_ENABLE_ANALYSIS', true);

// Storage Paths
define('ALKEBULAN_STORAGE_PATH', 'cache/alkebulan/');
define('ALKEBULAN_UPLOAD_PATH', 'media/alkebulan/');

// API Limits
define('ALKEBULAN_RATE_LIMIT', 100);
define('ALKEBULAN_RATE_LIMIT_WINDOW', 3600);
```

### User Settings
Access: `/alkebulan/settings/`

Available Options:
- Language preference
- AI response tone
- Notification settings
- Privacy settings
- Email notifications

---

## 🔐 Security Setup

### 1. Enable HTTPS
- Ensure SSL certificate installed
- Update all URLs to https://
- Set secure cookies

### 2. CORS Configuration
```php
// In ossn_com.php
header('Access-Control-Allow-Origin: ' . ossn_site_url());
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
```

### 3. Rate Limiting
Automatic rate limiting enabled:
- 100 requests per hour per user
- API calls tracked
- Exceeded limits blocked

### 4. Input Validation
All inputs validated:
```php
$text = ossn_secure_input($_POST['text']);
$style = preg_match('/^[a-z]+$/', $_POST['style']) ? $_POST['style'] : 'default';
```

---

## 🧪 Testing Features

### Test Image Generation
```
Text: "A futuristic city with neon lights"
Style: "Gradient"
Size: 512×512
```

### Test Video Creation
```
Text: "A journey through space"
Style: "Abstract"
Duration: 10 seconds
Quality: 1080p
FPS: 30
```

### Test Audio Generation
```
Text: "Good morning, this is a test of the audio system"
Voice: "Echo"
Language: "en-US"
Speed: 1.0x
```

### Test Content Analysis
```
Text: "This amazing product is absolutely fantastic! I love it."
Expected Result: Positive sentiment (95% confidence)
```

---

## 📊 Monitoring & Analytics

### Dashboard Statistics
- Monitor total generations
- Track API usage
- View user activity
- Check performance metrics

### Database Queries
```sql
-- Check image generation count
SELECT COUNT(*) FROM alkebulan_images WHERE DATE(created_at) = CURDATE();

-- Most used voice
SELECT voice, COUNT(*) as count FROM alkebulan_audios GROUP BY voice ORDER BY count DESC;

-- User activity
SELECT user_id, COUNT(*) as actions FROM alkebulan_usage_log GROUP BY user_id;
```

---

## 🐛 Troubleshooting

### Issue: Pages show blank
**Solution:**
1. Check OSSN is properly installed
2. Verify database tables exist
3. Check file permissions (755 for folders, 644 for files)
4. Clear browser cache

### Issue: Images not generating
**Solution:**
1. Verify storage path exists: `cache/alkebulan/`
2. Check folder permissions
3. Verify PHP GD library installed
4. Check error logs

### Issue: Audio not playing
**Solution:**
1. Check browser audio settings
2. Verify audio file format
3. Check media server serving files correctly
4. Test in different browser

### Issue: API endpoints returning errors
**Solution:**
1. Check user authentication
2. Verify POST method used
3. Check JSON data format
4. Review error logs

### Enable Debug Mode
```php
// Add to ossn_com.php
define('ALKEBULAN_DEBUG', true);
```

Check logs at: `/logs/alkebulan.log`

---

## 🚀 Performance Optimization

### 1. Enable Caching
```php
// In config.php
define('ALKEBULAN_CACHE_ENABLED', true);
define('ALKEBULAN_CACHE_TTL', 3600);
```

### 2. Optimize Database
```sql
-- Add indexes
CREATE INDEX idx_user_created ON alkebulan_images(user_id, created_at);
CREATE INDEX idx_user_created ON alkebulan_videos(user_id, created_at);

-- Optimize tables
OPTIMIZE TABLE alkebulan_images;
OPTIMIZE TABLE alkebulan_videos;
```

### 3. CDN Setup
Serve static assets from CDN:
- CSS from CDN
- JavaScript from CDN
- Images from CDN

### 4. Lazy Loading
Images and videos load on scroll in galleries.

---

## 📱 Mobile Optimization

All pages are fully responsive:
- Tested on iPhone, iPad, Android
- Touch-friendly buttons
- Optimized layout for small screens
- Readable text sizes

### Mobile-Specific Features:
- Swipe to navigate
- Touch-optimized sliders
- Full-width forms
- Large tap targets

---

## 🔄 Backup & Restore

### Backup Database
```bash
# Using mysqldump
mysqldump -u root -p ossn_database > alkebulan_backup.sql
```

### Backup Files
```bash
# Using tar
tar -czf alkebulan_backup.tar.gz components/Alkebulan/
```

### Restore Database
```bash
mysql -u root -p ossn_database < alkebulan_backup.sql
```

---

## 📚 API Documentation

### Generate Image
```bash
curl -X POST http://localhost/live%20stream/action/alkebulan/image/generate \
  -d "text=A beautiful sunset" \
  -d "style=colorful"
```

### Generate Video
```bash
curl -X POST http://localhost/live%20stream/action/alkebulan/video/generate \
  -d "text=A spacecraft flying" \
  -d "style=sci-fi" \
  -d "duration=20"
```

### Generate Audio
```bash
curl -X POST http://localhost/live%20stream/action/alkebulan/audio/generate \
  -d "text=Hello world" \
  -d "voice=nova" \
  -d "language=en-US"
```

### Analyze Content
```bash
curl -X POST http://localhost/live%20stream/action/alkebulan/analyzer/analyze \
  -d "text=This is amazing content"
```

---

## 📞 Support Resources

### Documentation Files:
- `FEATURE_GUIDE.md` - User guide
- `TECHNICAL_DOCS.md` - Developer documentation
- `README.md` - Component overview

### Logs & Debugging:
- OSSN Logs: `/logs/`
- Component Logs: `/logs/alkebulan.log`
- Database Logs: MySQL error log

### Community:
- OSSN Forum: https://www.ossn.org/
- Issue Tracker: Check component folder
- Documentation Wiki: See included docs

---

## ✅ Verification Checklist

- [ ] Component installed in correct folder
- [ ] Database tables created
- [ ] Component activated in admin panel
- [ ] All 8 pages loading correctly
- [ ] Images generating successfully
- [ ] Videos creating properly
- [ ] Audio generating with correct voice
- [ ] Content analysis working
- [ ] Insights displaying data
- [ ] No JavaScript console errors
- [ ] Dark mode working
- [ ] Mobile responsive
- [ ] API endpoints responding
- [ ] Statistics tracking actions
- [ ] Security checks passed

---

## 🎉 You're Ready!

Your Alkebulan AI component is now ready to use. Start exploring all the amazing AI-powered features!

### Quick Links:
- Dashboard: `/alkebulan/dashboard/`
- Chat: `/alkebulan/assistant/`
- Create Images: `/alkebulan/image-generator/`
- Create Videos: `/alkebulan/video-generator/`
- Generate Audio: `/alkebulan/audio-generator/`
- Analyze Content: `/alkebulan/analyzer/`
- View Insights: `/alkebulan/insights/`

**Version**: 2.2  
**Status**: Production Ready  
**Last Updated**: January 2038
