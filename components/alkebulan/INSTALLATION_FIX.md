# Alkebulan AI v1.0 - Installation Fix Guide

## Problem
Component shows red X (error) instead of activate button in OSSN admin panel.

## Root Cause
The component is installed in the wrong location. It needs to be in the OSSN `/components/` directory, not in the root `/live stream/` directory.

---

## Solution: Proper Installation

### Option 1: Move Component to Correct Location (RECOMMENDED)

**Step 1:** Locate your OSSN installation root
- Go to: `C:\xampp\htdocs\` (your XAMPP web root)
- OSSN should be the main folder there

**Step 2:** Create components folder if it doesn't exist
```
C:\xampp\htdocs\
├── components/          ← Create this folder if missing
│   ├── livestream/
│   └── alkebulan/       ← Move here
└── (other OSSN files)
```

**Step 3:** Move the alkebulan folder
- Cut entire `alkebulan/` folder from: `C:\xampp\htdocs\live stream\alkebulan\`
- Paste into: `C:\xampp\htdocs\components\alkebulan\`

**Step 4:** Verify Structure
```
C:\xampp\htdocs\components\alkebulan\
├── ossn_com.xml          ✓
├── ossn_com.php          ✓
├── classes/              ✓
├── actions/              ✓
├── locale/               ✓
├── plugins/              ✓
└── (documentation files)
```

**Step 5:** Clear Cache & Refresh
1. Go to OSSN Admin → Settings → Cache
2. Clear all caches
3. Refresh admin components page (F5)

**Step 6:** Activate Component
- Should now show **orange Activate button**
- Click Activate
- Should show **orange Disable button** when active

---

### Option 2: Symlink (Alternative)
If you can't move the folder, create a symbolic link:

```powershell
# Run as Administrator
New-Item -ItemType SymbolicLink -Path "C:\xampp\htdocs\components\alkebulan" -Target "C:\xampp\htdocs\live stream\alkebulan"
```

Then refresh admin page.

---

## Manifest Fix Applied ✓

The `ossn_com.xml` file has been updated with proper OSSN format:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<component>
    <name>Alkebulan AI</name>
    <id>alkebulan</id>
    <version>1.0</version>
    <release>stable</release>
    <author>Development Team maina waweru</author>
    <category>plugins</category>
    <description>Advanced AI-powered component...</description>
</component>
```

---

## Component Registration Updated ✓

The `ossn_com.php` file has been updated with proper OSSN callbacks:

- Proper constant definitions
- Correct ossn callbacks for initialization
- Database table creation on first load
- Settings registration
- Locale loading

---

## Post-Installation Checklist

After moving the component to `/components/alkebulan/`:

- [ ] Component folder is in `/components/` directory
- [ ] Admin → Components shows Alkebulan AI
- [ ] Red X changed to orange Activate button
- [ ] Click Activate (button changes to orange Disable)
- [ ] No errors in OSSN error logs
- [ ] Alkebulan AI menu items appear in main menu
- [ ] Can access `/alkebulan/dashboard/` page

---

## Access the Component

Once activated, access via:

1. **Dashboard**: `http://localhost/alkebulan/dashboard/`
2. **Features**: `http://localhost/alkebulan/features/`
3. **Chat**: `http://localhost/alkebulan/assistant/`
4. **Analytics**: `http://localhost/alkebulan/analytics/`
5. **Settings**: `http://localhost/alkebulan/settings/`

---

## Troubleshooting

**If still showing red X:**
1. Check folder structure matches above
2. Verify ossn_com.xml is in component root
3. Check OSSN error logs for PHP errors
4. Ensure PHP 7.0+ and OSSN 7.6+ compatibility
5. Try clearing browser cache (Ctrl+Shift+Delete)

**If getting 404 errors after activation:**
1. Ensure `.htaccess` file exists in component root
2. Check OSSN rewrite rules are enabled
3. Verify page handlers are registered in ossn_com.php

**If database errors:**
1. Ensure MySQL/MariaDB is running
2. Check database user has CREATE TABLE permissions
3. Review OSSN error logs in `/dataroot/logs/`

---

## Support

For issues, check:
- `README.md` - Complete guide
- `QUICK_START.md` - 5-minute setup
- `CHECKLIST.md` - Installation verification

