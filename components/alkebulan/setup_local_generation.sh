#!/usr/bin/env bash

# Alkebulan Local Generation System - Setup Script
# This script prepares the enhanced generation system for production use

echo "================================="
echo "Alkebulan Enhanced Generation v2.0"
echo "Setup & Activation Script"
echo "================================="
echo ""

# Check if running as root
if [[ ! "$EUID" -ne 0 ]]; then
   echo "⚠️  Warning: Running as root. This script should be run as www-data or PHP user."
fi

# Define paths
ALKEBULAN_DIR="alkebulan"
GENERATED_DIR="$ALKEBULAN_DIR/generated"

echo "📁 Creating directory structure..."

# Create directories
mkdir -p "$GENERATED_DIR/text"
mkdir -p "$GENERATED_DIR/images"
mkdir -p "$GENERATED_DIR/audio"
mkdir -p "$GENERATED_DIR/video"
mkdir -p "$GENERATED_DIR/exports"
mkdir -p "$GENERATED_DIR/cache"

echo "✓ Directories created"
echo ""

echo "🔒 Setting permissions..."

# Set permissions
chmod 755 "$GENERATED_DIR"
chmod 755 "$GENERATED_DIR/text"
chmod 755 "$GENERATED_DIR/images"
chmod 755 "$GENERATED_DIR/audio"
chmod 755 "$GENERATED_DIR/video"
chmod 755 "$GENERATED_DIR/exports"
chmod 755 "$GENERATED_DIR/cache"

# Make files writable by web server
chown -R www-data:www-data "$GENERATED_DIR" 2>/dev/null || echo "⚠️  Could not change ownership (may need sudo)"

echo "✓ Permissions set"
echo ""

echo "🔍 System Check..."

# Check PHP version
PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo "  PHP Version: $PHP_VERSION"

# Check for GD library
if php -m | grep -q gd; then
    echo "  ✓ GD Library: Installed"
else
    echo "  ⚠️  GD Library: Not installed (images will use fallback)"
fi

# Check for TTS tools
if command -v espeak &> /dev/null; then
    echo "  ✓ espeak: Installed"
else
    echo "  ⚠️  espeak: Not installed"
fi

if command -v festival &> /dev/null; then
    echo "  ✓ festival: Installed"
else
    echo "  ⚠️  festival: Not installed"
fi

if command -v ffmpeg &> /dev/null; then
    echo "  ✓ ffmpeg: Installed"
else
    echo "  ⚠️  ffmpeg: Not installed (video features use fallback)"
fi

echo ""
echo "📊 Disk Space Check..."
DISK_FREE=$(df -h . | awk 'NR==2 {print $4}')
echo "  Available space: $DISK_FREE"
echo ""

echo "✅ Setup Complete!"
echo ""
echo "================================="
echo "NEXT STEPS"
echo "================================="
echo ""
echo "1. Access the dashboard:"
echo "   👉 /alkebulan/enhanced_generation_dashboard.php"
echo ""
echo "2. Test content generation:"
echo "   POST /action/alkebulan/component_generate_local/generate_content_bundle"
echo ""
echo "3. Optional: Install additional tools"
echo "   - For better TTS: apt install espeak festival"
echo "   - For video support: apt install ffmpeg"
echo ""
echo "4. Check generated content:"
echo "   📁 $GENERATED_DIR/"
echo ""
echo "================================="
echo "DOCUMENTATION"
echo "================================="
echo ""
echo "  📖 Enhanced Generation Guide: ENHANCED_GENERATION_GUIDE.md"
echo "  📊 Enhancement Summary: V2_ENHANCEMENT_SUMMARY.md"
echo "  🎨 Dashboard: enhanced_generation_dashboard.php"
echo "  ⚙️  API Handler: component_generate_local.php"
echo ""
echo "================================="
echo "All systems ready! ✅"
echo "================================="
