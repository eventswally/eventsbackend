<?php
require_once 'config.php';
$page_title = 'Cities';

$success = '';
$error = '';

// Handle Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $pdo->prepare("DELETE FROM cities WHERE id = ?")->execute([intval($_GET['delete'])]);
        $success = 'City deleted successfully!';
    } catch (Exception $e) {
        $error = 'Cannot delete city. It may have associated planners.';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = clean($_POST['name']);
    $slug = generateSlug($name);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    try {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE cities SET name=?, slug=?, is_active=? WHERE id=?");
            $stmt->execute([$name, $slug, $is_active, $id]);
            $success = 'City updated successfully!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO cities (name, slug, is_active) VALUES (?,?,?)");
            $stmt->execute([$name, $slug, $is_active]);
            $success = 'City added successfully!';
        }
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// Get all cities
$cities = $pdo->query("SELECT * FROM cities ORDER BY display_order ASC, name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Get city for edit
$edit_city = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_city = $pdo->prepare("SELECT * FROM cities WHERE id = ?");
    $edit_city->execute([intval($_GET['edit'])]);
    $edit_city = $edit_city->fetch(PDO::FETCH_ASSOC);
}

include 'header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="stats-card">
            <h5 class="mb-4"><?= $edit_city ? 'Edit' : 'Add New' ?> City</h5>
            
            <form method="POST">
                <?php if ($edit_city): ?>
                    <input type="hidden" name="id" value="<?= $edit_city['id'] ?>">
                <?php endif; ?>
                
                <div class="mb-3">
                    <label class="form-label">City Name *</label>
                    <input type="text" name="name" class="form-control" 
                           value="<?= $edit_city['name'] ?? '' ?>" required autofocus>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="active"
                               <?= (!$edit_city || $edit_city['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="active">
                            Active
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-save"></i> Save City
                </button>
                
                <?php if ($edit_city): ?>
                    <a href="cities.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="stats-card">
            <h5 class="mb-4">All Cities (<?= count($cities) ?>)</h5>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Planners</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cities as $city): 
                            $planner_count = $pdo->prepare("SELECT COUNT(*) FROM event_planners WHERE city_id = ?");
                            $planner_count->execute([$city['id']]);
                            $count = $planner_count->fetchColumn();
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($city['name']) ?></strong></td>
                            <td><code><?= $city['slug'] ?></code></td>
                            <td>
                                <?php if ($city['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $count ?> planners</td>
                            <td class="table-actions">
                                <a href="cities.php?edit=<?= $city['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="cities.php?delete=<?= $city['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Delete this city? This will also delete all associated planners!')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
