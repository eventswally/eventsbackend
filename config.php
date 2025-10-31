<?php
/**
 * Events Wally - Shared Configuration
 * This file is used by both Admin Panel and API
 */

// Environment - Set to 'production' on live server
define('ENVIRONMENT', 'production'); // CHANGED TO PRODUCTION

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'eventswally');
define('DB_USER', 'eventswally');
define('DB_PASS', 'eventswally');

// URL Configuration
if (ENVIRONMENT === 'production') {
    // Live Server URLs
    define('SITE_URL', 'https://events.chatvoo.com');
} else {
    // Local Development URLs
    define('SITE_URL', 'http://localhost/eventswaly');
}

define('API_URL', SITE_URL . '/api');
define('ADMIN_URL', SITE_URL . '/admin');

// Upload Directories
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// Create upload directories if they don't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

$upload_subdirs = ['planners', 'logos', 'gallery', 'categories', 'cities'];
foreach ($upload_subdirs as $subdir) {
    $dir = UPLOAD_DIR . $subdir;
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Database Connection Function (for API)
function getDbConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            )
        );
        return $conn;
    } catch(PDOException $e) {
        if (ENVIRONMENT === 'development') {
            die("Database Connection Error: " . $e->getMessage());
        } else {
            die("Database connection failed. Please try again later.");
        }
    }
}

// Helper Functions
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateSlug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

function uploadImage($file, $subfolder = 'planners') {
    $target_dir = UPLOAD_DIR . $subfolder . '/';
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    
    if (!in_array($file_extension, $allowed_extensions)) {
        return array('success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.');
    }
    
    // Check file size (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        return array('success' => false, 'message' => 'File too large. Maximum 5MB allowed.');
    }
    
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return array(
            'success' => true, 
            'url' => UPLOAD_URL . $subfolder . '/' . $new_filename,
            'path' => $target_file
        );
    } else {
        return array('success' => false, 'message' => 'Failed to upload file.');
    }
}

// Session management (for admin)
if (!session_id()) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit();
    }
}
?>
