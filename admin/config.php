<?php
/**
 * Admin Panel Configuration
 * Now using shared config file
 */

// Include shared configuration
require_once __DIR__ . '/../config.php';

// Database Connection (PDO)
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch(PDOException $e) {
    if (ENVIRONMENT === 'development') {
        die("Database Connection Error: " . $e->getMessage());
    } else {
        die("Database connection failed. Please contact administrator.");
    }
}
