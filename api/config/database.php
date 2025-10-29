<?php
/**
 * Events Wally - Database Configuration
 * Now using shared config file
 */

// Include shared configuration
require_once __DIR__ . '/../../config.php';

class Database {
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
        } catch(PDOException $e) {
            if (ENVIRONMENT === 'development') {
                echo "Connection Error: " . $e->getMessage();
            } else {
                echo "Database connection failed.";
            }
        }

        return $this->conn;
    }
}
