<?php
/**
 * Fix Localhost URLs - Convert to Live Domain
 * Visit this file in browser to automatically update all localhost URLs
 * URL: http://localhost/eventswaly/fix_urls.php OR https://events.chatvoo.com/fix_urls.php
 */

require_once 'config.php';

// Create database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch(PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}

// Security check - only run if not already in production or if forced
$force = isset($_GET['force']) && $_GET['force'] === 'yes';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Localhost URLs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #6750A4;
            margin-bottom: 10px;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .alert-info {
            background: #E3F2FD;
            border-left: 4px solid #2196F3;
            color: #1565C0;
        }
        .alert-success {
            background: #E8F5E9;
            border-left: 4px solid #4CAF50;
            color: #2E7D32;
        }
        .alert-warning {
            background: #FFF3E0;
            border-left: 4px solid #FF9800;
            color: #E65100;
        }
        .stats {
            background: #F5F5F5;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .stats h3 {
            margin-top: 0;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #6750A4;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 5px;
            font-weight: 600;
        }
        .btn:hover {
            background: #4F378B;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #6750A4;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Localhost URLs</h1>
        <p>This tool converts all localhost URLs to your live domain.</p>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $force) {
            try {
                // Count existing localhost URLs
                $logos_count = $pdo->query("SELECT COUNT(*) FROM event_planners WHERE logo LIKE 'http://localhost%'")->fetchColumn();
                $videos_count = $pdo->query("SELECT COUNT(*) FROM event_planners WHERE video_url LIKE 'http://localhost%'")->fetchColumn();
                $images_count = $pdo->query("SELECT COUNT(*) FROM planner_images WHERE image_url LIKE 'http://localhost%'")->fetchColumn();
                
                $total = $logos_count + $videos_count + $images_count;
                
                if ($total === 0) {
                    echo '<div class="alert alert-info">';
                    echo '<strong>✅ No localhost URLs found!</strong><br>';
                    echo 'All URLs are already using the correct domain.';
                    echo '</div>';
                } else {
                    // Update logos
                    $stmt = $pdo->prepare("UPDATE event_planners SET logo = REPLACE(logo, 'http://localhost/eventswaly', 'https://events.chatvoo.com') WHERE logo LIKE 'http://localhost%'");
                    $stmt->execute();
                    $logos_updated = $stmt->rowCount();
                    
                    // Update video URLs
                    $stmt = $pdo->prepare("UPDATE event_planners SET video_url = REPLACE(video_url, 'http://localhost/eventswaly', 'https://events.chatvoo.com') WHERE video_url LIKE 'http://localhost%'");
                    $stmt->execute();
                    $videos_updated = $stmt->rowCount();
                    
                    // Update planner images
                    $stmt = $pdo->prepare("UPDATE planner_images SET image_url = REPLACE(image_url, 'http://localhost/eventswaly', 'https://events.chatvoo.com') WHERE image_url LIKE 'http://localhost%'");
                    $stmt->execute();
                    $images_updated = $stmt->rowCount();
                    
                    echo '<div class="alert alert-success">';
                    echo '<strong>✅ URLs Updated Successfully!</strong>';
                    echo '</div>';
                    
                    echo '<div class="stats">';
                    echo '<h3>Update Summary:</h3>';
                    echo '<table>';
                    echo '<tr><th>Item</th><th>Count</th></tr>';
                    echo '<tr><td>Logos Updated</td><td>' . $logos_updated . '</td></tr>';
                    echo '<tr><td>Video URLs Updated</td><td>' . $videos_updated . '</td></tr>';
                    echo '<tr><td>Gallery Images Updated</td><td>' . $images_updated . '</td></tr>';
                    echo '<tr><th>Total Updated</th><th>' . ($logos_updated + $videos_updated + $images_updated) . '</th></tr>';
                    echo '</table>';
                    echo '</div>';
                    
                    // Show samples
                    echo '<div class="alert alert-info">';
                    echo '<strong>Sample Updated URLs:</strong><br>';
                    $samples = $pdo->query("SELECT image_url FROM planner_images WHERE image_url LIKE 'https://events.chatvoo.com%' LIMIT 3")->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($samples as $sample) {
                        echo '<code>' . htmlspecialchars($sample) . '</code><br>';
                    }
                    echo '</div>';
                }
                
                echo '<a href="admin/planners.php" class="btn">Go to Admin Panel</a>';
                echo '<a href="fix_urls.php" class="btn">Refresh Page</a>';
                
            } catch (Exception $e) {
                echo '<div class="alert alert-warning">';
                echo '<strong>❌ Error:</strong> ' . htmlspecialchars($e->getMessage());
                echo '</div>';
            }
        } else {
            // Show current status
            try {
                $logos_count = $pdo->query("SELECT COUNT(*) FROM event_planners WHERE logo LIKE 'http://localhost%'")->fetchColumn();
                $videos_count = $pdo->query("SELECT COUNT(*) FROM event_planners WHERE video_url LIKE 'http://localhost%'")->fetchColumn();
                $images_count = $pdo->query("SELECT COUNT(*) FROM planner_images WHERE image_url LIKE 'http://localhost%'")->fetchColumn();
                
                $total = $logos_count + $videos_count + $images_count;
                
                echo '<div class="alert alert-info">';
                echo '<strong>📊 Current Status:</strong><br>';
                echo 'Found <strong>' . $total . '</strong> URLs with localhost that need updating.';
                echo '</div>';
                
                if ($total > 0) {
                    echo '<div class="stats">';
                    echo '<h3>URLs to Update:</h3>';
                    echo '<table>';
                    echo '<tr><th>Item</th><th>Count</th></tr>';
                    echo '<tr><td>Logos</td><td>' . $logos_count . '</td></tr>';
                    echo '<tr><td>Video URLs</td><td>' . $videos_count . '</td></tr>';
                    echo '<tr><td>Gallery Images</td><td>' . $images_count . '</td></tr>';
                    echo '</table>';
                    echo '</div>';
                    
                    echo '<div class="alert alert-warning">';
                    echo '<strong>⚠️ What This Will Do:</strong><br>';
                    echo 'This will replace:<br>';
                    echo '<code>http://localhost/eventswaly/uploads/...</code><br>';
                    echo 'with:<br>';
                    echo '<code>https://events.chatvoo.com/uploads/...</code>';
                    echo '</div>';
                    
                    echo '<form method="POST">';
                    echo '<button type="submit" class="btn btn-danger">🔧 Fix All URLs Now</button>';
                    echo '</form>';
                } else {
                    echo '<div class="alert alert-success">';
                    echo '<strong>✅ All Good!</strong><br>';
                    echo 'No localhost URLs found. Everything is using the correct domain.';
                    echo '</div>';
                }
                
            } catch (Exception $e) {
                echo '<div class="alert alert-warning">';
                echo '<strong>❌ Error:</strong> ' . htmlspecialchars($e->getMessage());
                echo '</div>';
            }
        }
        ?>
        
        <hr style="margin: 30px 0;">
        
        <h3>📝 Manual SQL Alternative</h3>
        <p>If you prefer, you can also run the SQL file: <code>fix_localhost_urls.sql</code></p>
        
        <h3>⚠️ Important Note</h3>
        <p><strong>DELETE THIS FILE</strong> after fixing URLs for security reasons!</p>
    </div>
</body>
</html>
