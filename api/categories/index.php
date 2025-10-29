<?php
/**
 * Categories API - Get all active categories
 */

include_once '../config/cors.php';
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT id, name, slug, icon 
              FROM categories 
              WHERE is_active = 1 
              ORDER BY display_order ASC, name ASC";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $categories = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categories[] = array(
            "id" => (int)$row['id'],
            "name" => $row['name'],
            "slug" => $row['slug'],
            "icon" => $row['icon']
        );
    }
    
    http_response_code(200);
    echo json_encode(array(
        "success" => true,
        "data" => $categories
    ));
    
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        "success" => false,
        "message" => "Failed to fetch categories.",
        "error" => $e->getMessage()
    ));
}
