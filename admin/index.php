<?php
require_once 'config.php';
$page_title = 'Dashboard';

// Get statistics
$stats = array();

// Total cities
$stmt = $pdo->query("SELECT COUNT(*) as count FROM cities WHERE is_active = 1");
$stats['cities'] = $stmt->fetch()['count'];

// Total categories
$stmt = $pdo->query("SELECT COUNT(*) as count FROM categories WHERE is_active = 1");
$stats['categories'] = $stmt->fetch()['count'];

// Total planners
$stmt = $pdo->query("SELECT COUNT(*) as count FROM event_planners WHERE is_active = 1");
$stats['planners'] = $stmt->fetch()['count'];

// Featured planners
$stmt = $pdo->query("SELECT COUNT(*) as count FROM event_planners WHERE is_featured = 1 AND is_active = 1");
$stats['featured'] = $stmt->fetch()['count'];

// Total views
$stmt = $pdo->query("SELECT SUM(views) as total FROM event_planners");
$stats['views'] = $stmt->fetch()['total'] ?? 0;

// Recent planners
$recent_planners = $pdo->query("
    SELECT ep.name, ep.created_at, c.name as city, cat.name as category 
    FROM event_planners ep
    LEFT JOIN cities c ON ep.city_id = c.id
    LEFT JOIN categories cat ON ep.category_id = cat.id
    ORDER BY ep.created_at DESC LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h2 class="mb-4">Dashboard</h2>

<div class="row">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div class="ms-3">
                    <h3 class="mb-0"><?= $stats['cities'] ?></h3>
                    <p class="text-muted mb-0">Cities</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <i class="bi bi-grid"></i>
                </div>
                <div class="ms-3">
                    <h3 class="mb-0"><?= $stats['categories'] ?></h3>
                    <p class="text-muted mb-0">Categories</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <i class="bi bi-people"></i>
                </div>
                <div class="ms-3">
                    <h3 class="mb-0"><?= $stats['planners'] ?></h3>
                    <p class="text-muted mb-0">Event Planners</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                    <i class="bi bi-star"></i>
                </div>
                <div class="ms-3">
                    <h3 class="mb-0"><?= $stats['featured'] ?></h3>
                    <p class="text-muted mb-0">Featured</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="stats-card">
            <h5 class="mb-4">Recently Added Event Planners</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>City</th>
                            <th>Category</th>
                            <th>Added On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_planners as $planner): ?>
                        <tr>
                            <td><?= htmlspecialchars($planner['name']) ?></td>
                            <td><?= htmlspecialchars($planner['city']) ?></td>
                            <td><?= htmlspecialchars($planner['category']) ?></td>
                            <td><?= date('M d, Y', strtotime($planner['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent_planners)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No planners added yet</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="stats-card">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-grid gap-2">
                <a href="planners.php?action=add" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Event Planner
                </a>
                <a href="cities.php" class="btn btn-outline-primary">
                    <i class="bi bi-geo-alt"></i> Manage Cities
                </a>
                <a href="categories.php" class="btn btn-outline-primary">
                    <i class="bi bi-grid"></i> Manage Categories
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="stats-card">
            <h5 class="mb-3">API Endpoints</h5>
            <div class="small">
                <p><strong>Cities:</strong><br>
                <code><?= SITE_URL ?>/api/cities/</code></p>
                
                <p><strong>Categories:</strong><br>
                <code><?= SITE_URL ?>/api/categories/</code></p>
                
                <p><strong>Planners:</strong><br>
                <code><?= SITE_URL ?>/api/planners/?city_id=1</code></p>
                
                <p><strong>Planner Details:</strong><br>
                <code><?= SITE_URL ?>/api/planners/detail.php?id=1</code></p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
