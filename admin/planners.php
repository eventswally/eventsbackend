<?php
require_once 'config.php';
$page_title = 'Event Planners';

$success = '';
$error = '';

// Handle Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $pdo->prepare("DELETE FROM event_planners WHERE id = ?")->execute([$id]);
        $success = 'Event planner deleted successfully!';
    } catch (Exception $e) {
        $error = 'Failed to delete planner: ' . $e->getMessage();
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = clean($_POST['name']);
    $city_id = intval($_POST['city_id']);
    $category_id = intval($_POST['category_id']);
    $short_description = clean($_POST['short_description']);
    $description = clean($_POST['description']);
    $phone = clean($_POST['phone']);
    $whatsapp = clean($_POST['whatsapp']);
    $email = clean($_POST['email']);
    $address = clean($_POST['address']);
    $website = clean($_POST['website']);
    $rating = floatval($_POST['rating']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $slug = generateSlug($name);
    
    try {
        if ($id > 0) {
            // Update
            $stmt = $pdo->prepare("UPDATE event_planners SET name=?, slug=?, city_id=?, category_id=?, 
                                   short_description=?, description=?, phone=?, whatsapp=?, email=?, 
                                   address=?, website=?, rating=?, is_featured=? WHERE id=?");
            $stmt->execute([$name, $slug, $city_id, $category_id, $short_description, $description, 
                           $phone, $whatsapp, $email, $address, $website, $rating, $is_featured, $id]);
            $planner_id = $id;
            $success = 'Event planner updated successfully!';
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO event_planners (name, slug, city_id, category_id, 
                                   short_description, description, phone, whatsapp, email, address, 
                                   website, rating, is_featured) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$name, $slug, $city_id, $category_id, $short_description, $description, 
                           $phone, $whatsapp, $email, $address, $website, $rating, $is_featured]);
            $planner_id = $pdo->lastInsertId();
            $success = 'Event planner added successfully!';
        }
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_result = uploadImage($_FILES['image']);
            if ($upload_result['success']) {
                // Set as primary image
                $pdo->prepare("INSERT INTO planner_images (planner_id, image_url, is_primary) VALUES (?, ?, 1)")
                    ->execute([$planner_id, $upload_result['url']]);
            }
        }
        
        header('Location: planners.php?success=1');
        exit();
        
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// Get all planners
$planners = $pdo->query("
    SELECT ep.*, c.name as city_name, cat.name as category_name,
           (SELECT image_url FROM planner_images WHERE planner_id = ep.id AND is_primary = 1 LIMIT 1) as image
    FROM event_planners ep
    LEFT JOIN cities c ON ep.city_id = c.id
    LEFT JOIN categories cat ON ep.category_id = cat.id
    ORDER BY ep.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Get cities and categories for form
$cities = $pdo->query("SELECT * FROM cities WHERE is_active = 1 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Get planner for edit
$edit_planner = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_planner = $pdo->prepare("SELECT * FROM event_planners WHERE id = ?");
    $edit_planner->execute([intval($_GET['edit'])]);
    $edit_planner = $edit_planner->fetch(PDO::FETCH_ASSOC);
}

include 'header.php';
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Operation completed successfully!</div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if (isset($_GET['action']) && $_GET['action'] === 'add' || $edit_planner): ?>
    <div class="stats-card">
        <h4 class="mb-4"><?= $edit_planner ? 'Edit' : 'Add New' ?> Event Planner</h4>
        
        <form method="POST" enctype="multipart/form-data">
            <?php if ($edit_planner): ?>
                <input type="hidden" name="id" value="<?= $edit_planner['id'] ?>">
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Planner Name *</label>
                    <input type="text" name="name" class="form-control" 
                           value="<?= $edit_planner['name'] ?? '' ?>" required>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">City *</label>
                    <select name="city_id" class="form-select" required>
                        <option value="">Select City</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= $city['id'] ?>" 
                                    <?= ($edit_planner && $edit_planner['city_id'] == $city['id']) ? 'selected' : '' ?>>
                                <?= $city['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" 
                                    <?= ($edit_planner && $edit_planner['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                <?= $category['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label">Short Description</label>
                    <input type="text" name="short_description" class="form-control" maxlength="300"
                           value="<?= $edit_planner['short_description'] ?? '' ?>">
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label">Full Description</label>
                    <textarea name="description" class="form-control" rows="4"><?= $edit_planner['description'] ?? '' ?></textarea>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" 
                           value="<?= $edit_planner['phone'] ?? '' ?>">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control" 
                           value="<?= $edit_planner['whatsapp'] ?? '' ?>">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" 
                           value="<?= $edit_planner['email'] ?? '' ?>">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">Rating (0-5)</label>
                    <input type="number" name="rating" class="form-control" step="0.1" min="0" max="5"
                           value="<?= $edit_planner['rating'] ?? '0.0' ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" 
                           value="<?= $edit_planner['address'] ?? '' ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-control" 
                           value="<?= $edit_planner['website'] ?? '' ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Primary Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_featured" class="form-check-input" id="featured"
                               <?= ($edit_planner && $edit_planner['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="featured">
                            Mark as Featured
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Event Planner
                </button>
                <a href="planners.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Event Planners (<?= count($planners) ?>)</h2>
        <a href="planners.php?action=add" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Planner
        </a>
    </div>

    <div class="stats-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Category</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($planners as $planner): ?>
                    <tr>
                        <td>
                            <img src="<?= $planner['image'] ?? 'https://via.placeholder.com/50' ?>" 
                                 alt="<?= htmlspecialchars($planner['name']) ?>" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($planner['name']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($planner['phone']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($planner['city_name']) ?></td>
                        <td><?= htmlspecialchars($planner['category_name']) ?></td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-star-fill"></i> <?= $planner['rating'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($planner['is_featured']): ?>
                                <span class="badge badge-featured">Featured</span>
                            <?php endif; ?>
                            <?php if ($planner['is_active']): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions">
                            <a href="planners.php?edit=<?= $planner['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="planners.php?delete=<?= $planner['id'] ?>" 
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Are you sure you want to delete this planner?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($planners)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No event planners found. <a href="planners.php?action=add">Add your first planner</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
