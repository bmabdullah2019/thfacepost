# 🚀 Advanced Local Generation v3.0 - Deployment & Setup Guide

**Version:** 3.0  
**Release Date:** January 24, 2026  
**Status:** ✅ Ready for Production

---

## Quick Start (2 Minutes)

### Step 1: Verify Files
```bash
cd /alkebulan
ls -la actions/component_generate_enhanced.php
ls -la advanced_generation_dashboard.php
```

### Step 2: Check Permissions
```bash
chmod 755 actions/component_generate_enhanced.php
chmod 755 advanced_generation_dashboard.php
chmod 755 generated/
chmod 755 generated/cache/
chmod 755 generated/images/
```

### Step 3: Access Dashboard
```
http://your-site.com/alkebulan/advanced_generation_dashboard.php
```

### Step 4: Test Generation
- Click "Semantic Analysis" tab
- Paste sample text
- Click "Analyze Text"
- See results in 150-200ms

**Done!** System is ready to use.

---

## System Requirements

### Minimum Requirements
- PHP 7.0+
- 100 MB free disk space
- 128 MB RAM (minimum)
- Apache/Nginx web server
- OSSN 7.6+ installed

### Recommended Requirements
- PHP 8.0+
- 500 MB free disk space
- 512 MB+ RAM
- SSD storage
- 50 Mbps+ internet (for updates)

### Optional Enhancements
- GD library (for images) - `sudo apt-get install php-gd`
- Imagick (advanced images) - `sudo apt-get install php-imagick`
- FFmpeg (video) - `sudo apt-get install ffmpeg`

---

## Installation Steps

### Step 1: Upload Files
```bash
# Create directory if not exists
mkdir -p /var/www/html/alkebulan/actions
mkdir -p /var/www/html/alkebulan/generated/{text,images,audio,cache,analysis}

# Copy main engine
cp component_generate_enhanced.php /var/www/html/alkebulan/actions/

# Copy dashboard
cp advanced_generation_dashboard.php /var/www/html/alkebulan/

# Copy documentation
cp ADVANCED_GENERATION_GUIDE.md /var/www/html/alkebulan/
cp ADVANCED_QUICK_REFERENCE.md /var/www/html/alkebulan/
cp V3_ENHANCEMENT_SUMMARY.md /var/www/html/alkebulan/
```

### Step 2: Set Permissions
```bash
# Allow PHP execution
chmod 755 /var/www/html/alkebulan/actions/component_generate_enhanced.php
chmod 755 /var/www/html/alkebulan/advanced_generation_dashboard.php

# Allow write access for cache
chmod 755 /var/www/html/alkebulan/generated
chmod 755 /var/www/html/alkebulan/generated/cache
chmod 755 /var/www/html/alkebulan/generated/images
chmod 755 /var/www/html/alkebulan/generated/text
chmod 755 /var/www/html/alkebulan/generated/audio

# Ensure ownership
chown www-data:www-data /var/www/html/alkebulan/generated/*
```

### Step 3: Verify Installation
```bash
# Check PHP version
php -v  # Should be 7.0+

# Check GD
php -m | grep GD  # Optional

# Test file readability
cat /var/www/html/alkebulan/actions/component_generate_enhanced.php | head -5
```

### Step 4: Test in Browser
```
1. Navigate to: http://your-site.com/alkebulan/advanced_generation_dashboard.php
2. Login with your OSSN account
3. Try "Semantic Analysis" tab
4. Paste test text and click "Analyze Text"
5. Should see results in ~200ms
```

---

## Configuration

### Cache Settings
Edit `component_generate_enhanced.php`, line ~50:

```php
private $cacheExpiry = 3600;  // 1 hour cache

// Options:
// 1800  = 30 minutes
// 3600  = 1 hour (default)
// 7200  = 2 hours
// 86400 = 1 day
```

### Tone Profiles
Edit `component_generate_enhanced.php`, line ~70:

```php
private $toneProfiles = [
    'professional' => [
        'formality' => 0.95,        // 0.0-1.0
        'complexity' => 0.85,       // 0.0-1.0
        'sentence_variety' => 0.75, // 0.0-1.0
        ...
    ],
    ...
];
```

Adjust values to change tone behavior:
- Higher = more formal/complex
- Lower = more casual/simple

### Image Dimensions
Edit `component_generate_enhanced.php`, line ~1500:

```php
// Default: 800x600
$width = (int)sanitize($_REQUEST['width'] ?? 800);
$height = (int)sanitize($_REQUEST['height'] ?? 600);

// Change to your preferred defaults
```

### Cache Location
Default: `/alkebulan/generated/cache/advanced_cache.json`

To change, edit line ~50:
```php
$cacheFile = $this->baseDir . 'cache/advanced_cache.json';
// Or:
$cacheFile = '/var/www/custom/path/cache.json';
```

---

## Verification Checklist

### Pre-Deployment
- [ ] PHP 7.0+ installed
- [ ] OSSN 7.6+ running
- [ ] Files uploaded to correct location
- [ ] Permissions set (755)
- [ ] Ownership correct (www-data)
- [ ] Cache directory writable
- [ ] Generated directories exist

### Post-Deployment
- [ ] Dashboard loads at `/alkebulan/advanced_generation_dashboard.php`
- [ ] Can login with OSSN credentials
- [ ] "Semantic Analysis" tab works
- [ ] Sample text analysis completes in <300ms
- [ ] Cache file created (`generated/cache/advanced_cache.json`)
- [ ] No PHP errors in logs
- [ ] GD library available (optional)

### Performance
- [ ] First request: ~200-500ms
- [ ] Cached request: ~3-5ms
- [ ] Cache hit rate: >70%
- [ ] Memory usage: <50MB per request
- [ ] CPU usage: <5% average

---

## Troubleshooting

### Issue: "User not authenticated"
**Solution:** Must be logged into OSSN
```php
// Dashboard requires OSSN login
if(!ossn_isLoggedin()) {
    die('Please login to access this tool.');
}
```
**Action:** Login to your OSSN site first, then access dashboard

---

### Issue: "Cannot write to cache directory"
**Solution:** Fix permissions
```bash
# Check directory
ls -la /alkebulan/generated/cache/

# Fix ownership
sudo chown www-data:www-data /alkebulan/generated/cache

# Fix permissions
sudo chmod 755 /alkebulan/generated/cache

# Test write
touch /alkebulan/generated/cache/test.txt
rm /alkebulan/generated/cache/test.txt
```

---

### Issue: Image generation not working
**Solution:** Enable GD library
```bash
# Check GD status
php -m | grep GD

# If not found, install:
# Ubuntu/Debian
sudo apt-get install php-gd
sudo systemctl restart apache2

# Or edit php.ini
sudo nano /etc/php/8.0/apache2/php.ini
# Find and uncomment: extension=gd

# Reload PHP
sudo systemctl restart apache2
```

---

### Issue: "Blank dashboard" or white screen
**Solution:** Check error logs
```bash
# Check Apache error log
tail -50 /var/log/apache2/error.log

# Check PHP error log
tail -50 /var/log/php-errors.log

# Check file permissions
ls -la /alkebulan/actions/component_generate_enhanced.php

# Check PHP syntax
php -l /alkebulan/actions/component_generate_enhanced.php
```

---

### Issue: Slow performance
**Solution:** Check caching and system resources
```bash
# Check if cache exists
ls -la /alkebulan/generated/cache/advanced_cache.json

# Check cache size
du -h /alkebulan/generated/cache/

# Monitor system
free -h  # Memory
df -h    # Disk space
top      # CPU usage

# Optimize cache
rm /alkebulan/generated/cache/advanced_cache.json  # Clear cache

# Increase PHP limits in php.ini
memory_limit = 512M
max_execution_time = 60
```

---

### Issue: "404 Not Found" on API endpoints
**Solution:** Check OSSN routing
```php
// Make sure endpoint is: /action/alkebulan/[action_name]

// Good URLs:
/action/alkebulan/semantic_analysis
/action/alkebulan/advanced_title
/action/alkebulan/fluent_article

// Common issues:
// - Typo in action name
// - File not in correct directory
// - PHP not installed
// - Web server not configured for /action/ paths
```

---

## Monitoring & Maintenance

### Daily Monitoring
```bash
# Check cache size
du -h /alkebulan/generated/cache/

# Check error logs
tail -20 /var/log/apache2/error.log

# Monitor disk space
df -h | grep -E '^/dev'

# Check generated files
ls -la /alkebulan/generated/
```

### Weekly Tasks
```bash
# Clear old cache (optional)
find /alkebulan/generated/cache/ -mtime +7 -delete

# Rotate logs
logrotate /etc/logrotate.d/apache2

# Backup configuration
cp /alkebulan/actions/component_generate_enhanced.php ~/backups/
```

### Monthly Tasks
```bash
# Optimize database
mysql -u root -p ossn_db -e "OPTIMIZE TABLE ossn_cache;"

# Review usage statistics
tail -1000 /var/log/apache2/access.log | grep "alkebulan"

# Check for updates
# Visit GitHub or check version file
```

---

## Performance Tuning

### PHP Configuration (php.ini)

```ini
# Memory
memory_limit = 512M

# Execution time
max_execution_time = 60

# File upload
upload_max_filesize = 100M
post_max_size = 100M

# GD library
extension=gd

# Enable caching
opcache.enable = 1
opcache.memory_consumption = 256
```

### Apache Configuration (.htaccess)

```apache
# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml application/json
</IfModule>

# Enable caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/html "access 1 day"
    ExpiresByType image/png "access 30 days"
</IfModule>

# Disable directory listing
Options -Indexes
```

### Nginx Configuration

```nginx
server {
    # Enable gzip
    gzip on;
    gzip_types text/plain text/css application/json;
    
    # Cache headers
    location ~* \.(png|jpg|gif|css|js)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
    
    # PHP handler
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## Backup & Recovery

### Backup Strategy
```bash
# Daily backup
0 2 * * * tar -czf /backups/alkebulan-$(date +\%Y\%m\%d).tar.gz /alkebulan/

# Weekly full backup
0 3 0 * * tar -czf /backups/full-$(date +\%Y\%m\%d).tar.gz /var/www/html/

# Keep for 30 days
find /backups/ -name "alkebulan-*" -mtime +30 -delete
```

### Recovery Procedure
```bash
# 1. Stop services
sudo systemctl stop apache2

# 2. Restore from backup
tar -xzf /backups/alkebulan-20260124.tar.gz -C /

# 3. Fix permissions
sudo chown -R www-data:www-data /alkebulan
sudo chmod -R 755 /alkebulan

# 4. Restart services
sudo systemctl start apache2

# 5. Verify
curl http://your-site.com/alkebulan/advanced_generation_dashboard.php
```

---

## Security Hardening

### Access Control
```php
// In dashboard PHP
if(!ossn_isLoggedin()) {
    die('Access denied');
}

// Check admin status (optional)
if(!ossn_is_admin()) {
    die('Admin access required');
}
```

### API Security
```php
// Add rate limiting
$rate_limit = get_user_api_calls();
if($rate_limit > 1000) {
    die('Rate limit exceeded');
}

// Add authentication tokens
if(!verify_api_token($_REQUEST['token'])) {
    die('Invalid token');
}
```

### File Security
```bash
# Disable directory listing
echo "Options -Indexes" > /alkebulan/.htaccess

# Restrict file access
chmod 600 /alkebulan/actions/component_generate_enhanced.php

# Protect sensitive files
chmod 600 /alkebulan/generated/cache/advanced_cache.json
```

---

## Scaling for Production

### Load Balancing
```
┌─────────────────────────────────┐
│   Load Balancer (Nginx)         │
└────┬────────────────────────┬───┘
     │                        │
┌────▼────┐            ┌─────▼────┐
│Server 1  │            │Server 2  │
│PHP 8.0   │            │PHP 8.0   │
│Cache A   │            │Cache B   │
└──────────┘            └──────────┘
     │                        │
     └────────┬───────────────┘
              │
        ┌─────▼──────┐
        │Shared Cache │ (Redis/Memcached)
        │(Sessions)   │
        └─────────────┘
```

### Multi-Server Setup
```bash
# Shared cache with Redis
$redis = new Redis();
$redis->connect('redis-server', 6379);

# Or Memcached
$memcached = new Memcached();
$memcached->addServer('memcached-server', 11211);
```

### Database Optimization
```sql
-- Index cache table
CREATE INDEX idx_cache_key ON cache_table(cache_key);

-- Index results table
CREATE INDEX idx_user_date ON results(user_id, created_date);

-- Optimize table
OPTIMIZE TABLE cache_table;
OPTIMIZE TABLE results;
```

---

## Updating

### Check Version
```bash
grep "Version.*v3.0" /alkebulan/actions/component_generate_enhanced.php
```

### Update Procedure
```bash
# 1. Backup current version
cp /alkebulan/actions/component_generate_enhanced.php ~/backup_v3.0.php

# 2. Download new version
wget https://github.com/alkebulan/releases/advanced_v3.1.php

# 3. Replace file
cp advanced_v3.1.php /alkebulan/actions/component_generate_enhanced.php

# 4. Test
curl http://your-site.com/alkebulan/advanced_generation_dashboard.php

# 5. If issues, rollback
cp ~/backup_v3.0.php /alkebulan/actions/component_generate_enhanced.php
```

---

## Support Resources

### Documentation Files
- **ADVANCED_GENERATION_GUIDE.md** - Complete technical guide
- **ADVANCED_QUICK_REFERENCE.md** - Quick reference
- **V3_ENHANCEMENT_SUMMARY.md** - What's new
- **This File** - Deployment guide

### Useful Commands
```bash
# Check PHP
php -v
php -m | grep GD
php -i | grep -A 10 GD

# Check permissions
ls -la /alkebulan/
getfacl /alkebulan/generated/cache/

# Monitor logs
tail -f /var/log/apache2/error.log
tail -f /var/log/apache2/access.log

# Test endpoint
curl -X POST http://your-site.com/action/alkebulan/semantic_analysis \
  -d "text=Test text"
```

---

## Conclusion

Advanced Local Generation v3.0 is now deployed and ready for production use. 

### Next Steps
1. ✅ Verify dashboard loads
2. ✅ Test semantic analysis
3. ✅ Try title generation
4. ✅ Generate articles
5. ✅ Integrate into your application

### Support
- Check documentation first
- Review error logs
- Test with simple input
- Verify permissions and configuration

**System Status:** ✅ Production Ready

---

**Deployment Date:** January 24, 2026  
**Version:** 3.0  
**Maintainer:** Alkebulan Development Team
