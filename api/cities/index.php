<?php
/**
 * Cities API - Get all active cities
 */

include_once '../config/cors.php';
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT id, name, slug 
              FROM cities 
              WHERE is_active = 1 
              ORDER BY display_order ASC, name ASC";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $cities = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cities[] = array(
            "id" => (int)$row['id'],
            "name" => $row['name'],
            "slug" => $row['slug']
        );
    }
    
    http_response_code(200);
    echo json_encode(array(
        "success" => true,
        "data" => $cities
    ));
    
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        "success" => false,
        "message" => "Failed to fetch cities.",
        "error" => $e->getMessage()
    ));
}
