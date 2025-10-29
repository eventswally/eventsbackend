<?php
/**
 * Event Planners API - Get planners by city with optional filters
 */

include_once '../config/cors.php';
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get parameters
$city_id = isset($_GET['city_id']) ? intval($_GET['city_id']) : 0;
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$featured_only = isset($_GET['featured']) ? filter_var($_GET['featured'], FILTER_VALIDATE_BOOLEAN) : false;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

try {
    // Build query
    $query = "SELECT 
                ep.id, ep.name, ep.slug, ep.short_description, 
                ep.rating, ep.is_featured, ep.phone, ep.whatsapp,
                c.name as city_name, c.slug as city_slug,
                cat.name as category_name, cat.slug as category_slug, cat.icon as category_icon,
                (SELECT image_url FROM planner_images WHERE planner_id = ep.id AND is_primary = 1 LIMIT 1) as primary_image
              FROM event_planners ep
              LEFT JOIN cities c ON ep.city_id = c.id
              LEFT JOIN categories cat ON ep.category_id = cat.id
              WHERE ep.is_active = 1";
    
    $params = array();
    
    if ($city_id > 0) {
        $query .= " AND ep.city_id = :city_id";
        $params[':city_id'] = $city_id;
    }
    
    if ($category_id > 0) {
        $query .= " AND ep.category_id = :category_id";
        $params[':category_id'] = $category_id;
    }
    
    if (!empty($search)) {
        $query .= " AND (ep.name LIKE :search OR ep.short_description LIKE :search)";
        $params[':search'] = "%{$search}%";
    }
    
    if ($featured_only) {
        $query .= " AND ep.is_featured = 1";
    }
    
    $query .= " ORDER BY ep.is_featured DESC, ep.rating DESC, ep.name ASC";
    $query .= " LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($query);
    
    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    
    $planners = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $planners[] = array(
            "id" => (int)$row['id'],
            "name" => $row['name'],
            "slug" => $row['slug'],
            "short_description" => $row['short_description'],
            "rating" => (float)$row['rating'],
            "is_featured" => (bool)$row['is_featured'],
            "phone" => $row['phone'],
            "whatsapp" => $row['whatsapp'],
            "city" => array(
                "name" => $row['city_name'],
                "slug" => $row['city_slug']
            ),
            "category" => array(
                "name" => $row['category_name'],
                "slug" => $row['category_slug'],
                "icon" => $row['category_icon']
            ),
            "primary_image" => $row['primary_image'] ? $row['primary_image'] : 'https://via.placeholder.com/400x300?text=Event+Planner'
        );
    }
    
    http_response_code(200);
    echo json_encode(array(
        "success" => true,
        "count" => count($planners),
        "data" => $planners
    ));
    
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        "success" => false,
        "message" => "Failed to fetch planners.",
        "error" => $e->getMessage()
    ));
}
