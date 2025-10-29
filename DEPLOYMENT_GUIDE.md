# Events Wally - Live Server Deployment Guide

## 🚀 Pre-Deployment Checklist

### 1. Single Configuration File

**✅ All settings are now in ONE file: `config.php`**

Update this file ONLY:

#### **config.php** (root directory)
```php
// Environment - Change to 'production' on live server
define('ENVIRONMENT', 'production');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'eventswally');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');

// URL Configuration (auto-switches based on ENVIRONMENT)
if (ENVIRONMENT === 'production') {
    define('SITE_URL', 'https://events.chatvoo.com');
} else {
    define('SITE_URL', 'http://localhost/eventswaly');
}
```

**That's it!** Both admin panel and API will use these settings automatically.

### 2. Database Schema Updates

Run these SQL commands to add new features (logo, video, gallery):

```sql
-- Add logo column
ALTER TABLE `event_planners` 
ADD COLUMN `logo` VARCHAR(255) NULL AFTER `primary_image` 
COMMENT 'Vendor logo URL';

-- Add video URL column
ALTER TABLE `event_planners` 
ADD COLUMN `video_url` VARCHAR(500) NULL AFTER `logo` 
COMMENT 'Video URL (YouTube, MP4, etc.)';

-- Verify changes
DESCRIBE event_planners;
```

## 📂 File Structure

Your server should have this structure:

```
/public_html/ or /htdocs/
├── config.php              🆕 SINGLE CONFIG FILE (update this!)
├── admin/
│   ├── config.php          ✅ Includes shared config
│   ├── header.php          ✅ Modernized UI
│   ├── index.php
│   ├── login.php
│   ├── planners.php
│   ├── categories.php
│   ├── cities.php
│   └── add.php             ✅ Uses shared config
├── api/
│   ├── config/
│   │   ├── database.php    ✅ Uses shared config
│   │   └── cors.php
│   ├── planners/
│   │   ├── index.php
│   │   └── detail.php      ✅ Added logo & video support
│   ├── categories/
│   └── cities/
├── uploads/
│   ├── planners/
│   ├── logos/              🆕 Create this folder
│   └── gallery/            🆕 Create this folder
└── index.php
```

## 🔧 Server Setup Instructions

### Step 1: Upload Files

1. Upload all files to your server via FTP/SFTP
2. Ensure proper file permissions:
   ```bash
   chmod 755 admin/
   chmod 755 api/
   chmod 777 uploads/
   chmod 777 uploads/planners/
   chmod 777 uploads/logos/
   chmod 777 uploads/gallery/
   ```

### Step 2: Create Required Folders

```bash
mkdir -p uploads/logos
mkdir -p uploads/gallery
chmod 777 uploads/logos
chmod 777 uploads/gallery
```

### Step 3: Database Setup

1. Import your database:
   ```bash
   mysql -u your_user -p eventswally < database/eventswally.sql
   ```

2. Run schema updates (from above)

3. Test connection by visiting:
   ```
   https://events.chatvoo.com/api/cities/index.php
   ```

### Step 4: Update Configuration

**Edit ONE file: `config.php`**

1. **Set Environment to Production:**
   ```php
   define('ENVIRONMENT', 'production');
   ```

2. **Update Database Credentials:**
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'eventswally');
   define('DB_USER', 'your_db_username');
   define('DB_PASS', 'your_db_password');
   ```

3. **Verify URLs are correct:**
   ```php
   define('SITE_URL', 'https://events.chatvoo.com');
   ```

4. **Test API Endpoints:**
   - https://events.chatvoo.com/api/cities/index.php
   - https://events.chatvoo.com/api/categories/index.php
   - https://events.chatvoo.com/api/planners/index.php

### Step 5: Create Admin User

Visit: `https://events.chatvoo.com/admin/add.php`
- Create your first admin user
- **Delete or secure this file after creation**

### Step 6: Test Admin Panel

1. Go to: `https://events.chatvoo.com/admin/`
2. Login with credentials
3. Test all functionalities:
   - ✅ Add/Edit Cities
   - ✅ Add/Edit Categories
   - ✅ Add/Edit Event Planners
   - ✅ Upload images
   - ✅ Upload logos
   - ✅ Add video URLs

## 🎨 Admin Panel Features (Modernized)

### New Design
- ✅ Material 3 Design System
- ✅ Modern gradient sidebar
- ✅ Improved typography
- ✅ Better spacing & layouts
- ✅ Responsive tables
- ✅ Professional cards & forms
- ✅ Smooth hover effects

### Color Scheme
- Primary: `#6750A4` (Purple)
- Gradient: `#6750A4` to `#7C4DFF`
- Featured: `#FF6B35` to `#F7931E` (Orange)

## 📱 Android App Configuration

The Android app is already configured for live server:

**ApiClient.java:**
```java
private static final String BASE_URL = "https://events.chatvoo.com/api/";
```

No changes needed in the Android app!

## 🔒 Security Recommendations

### 1. Protect Admin Panel
Create `.htaccess` in `/admin/` folder:

```apache
# Disable directory browsing
Options -Indexes

# Protect sensitive files
<FilesMatch "\.(log|sql|md)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 2. Secure Uploads Folder
Create `.htaccess` in `/uploads/` folder:

```apache
# Prevent PHP execution in uploads
php_flag engine off

# Allow only images
<FilesMatch "\.(php|phtml|php3|php4|php5|pl|py|jsp|asp|htm|shtml|sh|cgi)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### 3. Database Security
- Use strong passwords
- Create separate MySQL user with limited privileges
- Never use root user in production

### 4. File Permissions
```bash
# Folders: 755
chmod 755 admin/ api/

# Config files: 644
chmod 644 admin/config.php api/config/database.php

# Uploads: 777 (but protected by .htaccess)
chmod 777 uploads/ -R
```

### 5. Hide Sensitive Files
Add to main `.htaccess`:

```apache
<FilesMatch "\.(md|log|sql|git)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

## 🧪 Testing Checklist

### API Endpoints
- [ ] GET `/api/cities/index.php`
- [ ] GET `/api/categories/index.php`  
- [ ] GET `/api/planners/index.php`
- [ ] GET `/api/planners/detail.php?id=1`

### Admin Panel
- [ ] Login at `/admin/`
- [ ] Add new city
- [ ] Add new category
- [ ] Add new event planner
- [ ] Upload planner image
- [ ] Upload logo
- [ ] Add video URL
- [ ] Add gallery images
- [ ] Edit existing planner
- [ ] Delete planner
- [ ] Featured toggle works

### Android App
- [ ] Launch app
- [ ] Select city
- [ ] View event planners list
- [ ] Click on planner
- [ ] See logo displayed
- [ ] See video player
- [ ] See image gallery
- [ ] Click gallery images
- [ ] Call button works
- [ ] WhatsApp button works

## 🐛 Troubleshooting

### Issue: "Database Connection Error"
**Solution:** Check database credentials in config files

### Issue: "404 Not Found" on API
**Solution:** Enable mod_rewrite in Apache:
```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

### Issue: Images not uploading
**Solution:** Check folder permissions:
```bash
chmod 777 uploads/ -R
```

### Issue: White screen (PHP errors)
**Solution:** Enable error display temporarily:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### Issue: CORS errors in app
**Solution:** Check `api/config/cors.php` headers

## 📊 Monitoring

### Check Logs
```bash
# Apache error log
tail -f /var/log/apache2/error.log

# PHP error log  
tail -f /var/log/php/error.log

# Custom admin log
tail -f admin/admin_add_error.log
```

### Database Health
```sql
-- Check tables
SHOW TABLES;

-- Check planners
SELECT COUNT(*) FROM event_planners;

-- Check recent uploads
SELECT name, created_at FROM event_planners ORDER BY created_at DESC LIMIT 10;
```

## 🎉 Post-Deployment

1. **Delete or secure** `admin/add.php`
2. **Test all features** in Android app
3. **Monitor** error logs for first 24 hours
4. **Backup** database regularly:
   ```bash
   mysqldump -u user -p eventswally > backup_$(date +%Y%m%d).sql
   ```

5. **Set up automated backups** (cron job):
   ```bash
   0 2 * * * mysqldump -u user -p eventswally > /backups/db_$(date +\%Y\%m\%d).sql
   ```

## 📞 Support

If you encounter issues:
1. Check error logs
2. Verify database connection
3. Test API endpoints manually
4. Check folder permissions
5. Review Apache/PHP configuration

---

**✅ Your Events Wally platform is now ready for production!**

Last Updated: October 28, 2025
Version: 2.0 (Material 3 Update)
