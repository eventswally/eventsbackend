# Events Wally - Quick Setup Guide

## Step-by-Step Installation

### Step 1: Database Setup (5 minutes)

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Click "Start" for Apache
   - Click "Start" for MySQL

2. **Create Database**
   - Open browser and go to: http://localhost/phpmyadmin
   - Click "New" in left sidebar
   - Create database named: `eventswally`
   - Set collation to: `utf8mb4_unicode_ci`

3. **Import Database Structure**
   - Select the `eventswally` database
   - Click "Import" tab
   - Click "Choose File"
   - Select: `C:\xampp\htdocs\eventswaly\database\eventswally.sql`
   - Click "Go" at the bottom
   - Wait for success message

4. **Import Dummy Data**
   - Stay on Import tab
   - Click "Choose File" again
   - Select: `C:\xampp\htdocs\eventswaly\database\dummy_data.sql`
   - Click "Go"
   - You should now have 20 sample event planners!

### Step 2: Test Backend API (2 minutes)

Open these URLs in your browser to verify API is working:

1. **Cities**: http://localhost/eventswaly/api/cities/
   - Should show JSON with cities list

2. **Categories**: http://localhost/eventswaly/api/categories/
   - Should show JSON with categories

3. **Planners**: http://localhost/eventswaly/api/planners/?city_id=1
   - Should show JSON with Karachi planners

If you see JSON data, API is working! ✅

### Step 3: Test Admin Panel (2 minutes)

1. **Open Admin Login**
   - URL: http://localhost/eventswaly/admin/login.php

2. **Login**
   - Username: `admin`
   - Password: `admin123`

3. **Explore Admin Panel**
   - Dashboard: See statistics
   - Cities: Manage cities
   - Categories: Manage categories
   - Event Planners: Add/Edit planners

### Step 4: Setup Android App (10 minutes)

1. **Open in Android Studio**
   - Open Android Studio
   - File → Open
   - Select: `C:\Users\Malik\AndroidStudioProjects\EventsWally`
   - Wait for Gradle sync to complete

2. **Configure API URL**
   - Open: `app/src/main/java/com/eventswally/api/ApiClient.java`
   - Line 13 should have: `http://10.0.2.2/eventswaly/api/`
   - This works for Android Emulator
   - For real device, see "Real Device Setup" below

3. **Sync Gradle**
   - Click "Sync Now" if prompted
   - Wait for dependencies to download

4. **Run the App**
   - Click green "Run" button (or Shift+F10)
   - Select emulator or connected device
   - Wait for app to install and launch

### Step 5: Use the App

1. **First Launch**
   - You'll see splash screen with Events Wally logo
   - Then city selection screen

2. **Select a City**
   - Choose any city (e.g., Karachi)
   - You'll see list of event planners in that city

3. **Browse Planners**
   - Scroll through the list
   - Use search bar to search
   - Click category chips to filter
   - Pull down to refresh

4. **View Planner Details**
   - Click any planner card
   - See full details, packages, contact info
   - Click "Call Now" or "WhatsApp" to contact

5. **Change City**
   - Click menu (3 dots) in top right
   - Select "Change City"
   - Choose different city

---

## Real Device Setup

### Testing on Your Phone (Same WiFi Required)

1. **Find Your Computer's IP Address**
   ```
   Windows: Open CMD and type: ipconfig
   Look for "IPv4 Address" (e.g., 192.168.1.5)
   ```

2. **Update API URL**
   - Open `ApiClient.java`
   - Change line 13 to:
   ```java
   private static final String BASE_URL = "http://192.168.1.5/eventswaly/api/";
   ```
   - Replace `192.168.1.5` with YOUR IP address

3. **Enable USB Debugging on Phone**
   - Settings → About Phone
   - Tap "Build Number" 7 times
   - Go back to Settings → Developer Options
   - Enable "USB Debugging"

4. **Connect Phone via USB**
   - Connect phone to computer
   - Allow USB debugging when prompted
   - Select your phone in Android Studio
   - Click Run

5. **Connect Phone and Computer to Same WiFi**
   - Both devices must be on same network
   - Computer running XAMPP
   - Phone running the app

---

## Troubleshooting

### API Not Working

**Problem**: "Network error" in app

**Solutions**:
- ✅ Check XAMPP Apache is running (green in XAMPP Control)
- ✅ Test API in browser first: http://localhost/eventswaly/api/cities/
- ✅ For emulator, use: `http://10.0.2.2/eventswaly/api/`
- ✅ For real device, use your computer's IP: `http://192.168.1.X/eventswaly/api/`
- ✅ Disable firewall temporarily to test

### Database Connection Error

**Problem**: "Connection Error" in admin or API

**Solutions**:
- ✅ Check MySQL is running in XAMPP (green)
- ✅ Verify database name is `eventswally`
- ✅ Check username is `root` and password is empty
- ✅ Import SQL files again if tables are missing

### App Build Errors

**Problem**: Gradle sync fails or app won't build

**Solutions**:
- ✅ File → Invalidate Caches → Invalidate and Restart
- ✅ Build → Clean Project, then Build → Rebuild Project
- ✅ Check internet connection (for downloading dependencies)
- ✅ Update Android Studio to latest version

### Images Not Showing

**Problem**: Planner images show placeholder

**Solutions**:
- ✅ Check `uploads` folder exists: `C:\xampp\htdocs\eventswaly\uploads\`
- ✅ Give folder write permissions
- ✅ Upload images through admin panel
- ✅ Dummy data uses external URLs (Unsplash) - needs internet

### Admin Login Not Working

**Problem**: Can't login to admin panel

**Solutions**:
- ✅ Username is: `admin` (lowercase)
- ✅ Password is: `admin123`
- ✅ Check database has `admin_users` table
- ✅ Re-import `eventswally.sql` if needed

---

## Next Steps

### Add Your Own Data

1. **Login to Admin Panel**
   - http://localhost/eventswaly/admin/

2. **Add More Cities**
   - Go to Cities page
   - Add your city

3. **Add Event Planners**
   - Go to Event Planners page
   - Click "Add New Planner"
   - Fill in details
   - Upload image
   - Save

4. **Mark as Featured**
   - Edit any planner
   - Check "Mark as Featured"
   - Featured planners appear first in app

### Customize the App

1. **Change Colors**
   - Edit: `app/src/main/res/values/colors.xml`
   - Change primary colors

2. **Change App Name**
   - Edit: `app/src/main/res/values/strings.xml`
   - Change `app_name`

3. **Change Logo**
   - Replace: `app/src/main/res/drawable/ic_logo.xml`
   - Or use PNG in `res/drawable/`

---

## Support

For issues or questions:
- Check README.md for detailed documentation
- Review API endpoints in browser
- Check XAMPP error logs
- Review Android Studio Logcat for app errors

**Congratulations! 🎉**
Your Events Wally app is now ready to use!
