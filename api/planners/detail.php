<?php
/**
 * Event Planner Detail API - Get full details of a planner
 */

include_once '../config/cors.php';
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get planner ID or slug
$planner_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$planner_slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if ($planner_id === 0 && empty($planner_slug)) {
    http_response_code(400);
    echo json_encode(array(
        "success" => false,
        "message" => "Planner ID or slug is required."
    ));
    exit();
}

try {
    // Get planner details
    $query = "SELECT 
                ep.*, 
                c.name as city_name, c.slug as city_slug,
                cat.name as category_name, cat.slug as category_slug, cat.icon as category_icon
              FROM event_planners ep
              LEFT JOIN cities c ON ep.city_id = c.id
              LEFT JOIN categories cat ON ep.category_id = cat.id
              WHERE ep.is_active = 1";
    
    if ($planner_id > 0) {
        $query .= " AND ep.id = :id";
    } else {
        $query .= " AND ep.slug = :slug";
    }
    
    $stmt = $db->prepare($query);
    
    if ($planner_id > 0) {
        $stmt->bindParam(':id', $planner_id, PDO::PARAM_INT);
    } else {
        $stmt->bindParam(':slug', $planner_slug);
    }
    
    $stmt->execute();
    $planner = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$planner) {
        http_response_code(404);
        echo json_encode(array(
            "success" => false,
            "message" => "Planner not found."
        ));
        exit();
    }
    
    // Get planner images
    $images_query = "SELECT image_url, is_primary 
                     FROM planner_images 
                     WHERE planner_id = :planner_id 
                     ORDER BY is_primary DESC, display_order ASC";
    $images_stmt = $db->prepare($images_query);
    $images_stmt->bindParam(':planner_id', $planner['id']);
    $images_stmt->execute();
    
    $images = array();
    while ($img = $images_stmt->fetch(PDO::FETCH_ASSOC)) {
        $images[] = $img['image_url'];
    }
    
    // Get planner packages
    $packages_query = "SELECT package_name, price, description, features 
                       FROM planner_packages 
                       WHERE planner_id = :planner_id AND is_active = 1
                       ORDER BY display_order ASC";
    $packages_stmt = $db->prepare($packages_query);
    $packages_stmt->bindParam(':planner_id', $planner['id']);
    $packages_stmt->execute();
    
    $packages = array();
    while ($pkg = $packages_stmt->fetch(PDO::FETCH_ASSOC)) {
        $packages[] = array(
            "name" => $pkg['package_name'],
            "price" => $pkg['price'],
            "description" => $pkg['description'],
            "features" => $pkg['features'] ? explode("\n", $pkg['features']) : array()
        );
    }
    
    // Update view count
    $update_views = "UPDATE event_planners SET views = views + 1 WHERE id = :id";
    $update_stmt = $db->prepare($update_views);
    $update_stmt->bindParam(':id', $planner['id']);
    $update_stmt->execute();
    
    // Prepare response
    $response = array(
        "id" => (int)$planner['id'],
        "name" => $planner['name'],
        "slug" => $planner['slug'],
        "description" => $planner['description'],
        "short_description" => $planner['short_description'],
        "phone" => $planner['phone'],
        "whatsapp" => $planner['whatsapp'],
        "email" => $planner['email'],
        "address" => $planner['address'],
        "website" => $planner['website'],
        "rating" => (float)$planner['rating'],
        "is_featured" => (bool)$planner['is_featured'],
        "views" => (int)$planner['views'] + 1,
        "logo" => $planner['logo'] ?? null,
        "video_url" => $planner['video_url'] ?? null,
        "primary_image" => count($images) > 0 ? $images[0] : null,
        "city" => array(
            "name" => $planner['city_name'],
            "slug" => $planner['city_slug']
        ),
        "category" => array(
            "name" => $planner['category_name'],
            "slug" => $planner['category_slug'],
            "icon" => $planner['category_icon']
        ),
        "images" => count($images) > 0 ? $images : array('https://via.placeholder.com/800x600?text=Event+Planner'),
        "packages" => $packages
    );
    
    http_response_code(200);
    echo json_encode(array(
        "success" => true,
        "data" => $response
    ));
    
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        "success" => false,
        "message" => "Failed to fetch planner details.",
        "error" => $e->getMessage()
    ));
}
