<?php
require_once 'config.php';
$page_title = 'Categories';

$success = '';
$error = '';

// Handle Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([intval($_GET['delete'])]);
        $success = 'Category deleted successfully!';
    } catch (Exception $e) {
        $error = 'Cannot delete category. It may have associated planners.';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = clean($_POST['name']);
    $slug = generateSlug($name);
    $icon = clean($_POST['icon']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    try {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE categories SET name=?, slug=?, icon=?, is_active=? WHERE id=?");
            $stmt->execute([$name, $slug, $icon, $is_active, $id]);
            $success = 'Category updated successfully!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, icon, is_active) VALUES (?,?,?,?)");
            $stmt->execute([$name, $slug, $icon, $is_active]);
            $success = 'Category added successfully!';
        }
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// Get all categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY display_order ASC, name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Get category for edit
$edit_category = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_category = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $edit_category->execute([intval($_GET['edit'])]);
    $edit_category = $edit_category->fetch(PDO::FETCH_ASSOC);
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
            <h5 class="mb-4"><?= $edit_category ? 'Edit' : 'Add New' ?> Category</h5>
            
            <form method="POST">
                <?php if ($edit_category): ?>
                    <input type="hidden" name="id" value="<?= $edit_category['id'] ?>">
                <?php endif; ?>
                
                <div class="mb-3">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" class="form-control" 
                           value="<?= $edit_category['name'] ?? '' ?>" required autofocus>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Icon (Emoji)</label>
                    <input type="text" name="icon" class="form-control" 
                           value="<?= $edit_category['icon'] ?? '' ?>" 
                           placeholder="e.g., 📸 or 🎂">
                    <small class="text-muted">Use emoji or leave empty</small>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="active"
                               <?= (!$edit_category || $edit_category['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="active">
                            Active
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-save"></i> Save Category
                </button>
                
                <?php if ($edit_category): ?>
                    <a href="categories.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="stats-card">
            <h5 class="mb-4">All Categories (<?= count($categories) ?>)</h5>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Planners</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): 
                            $planner_count = $pdo->prepare("SELECT COUNT(*) FROM event_planners WHERE category_id = ?");
                            $planner_count->execute([$category['id']]);
                            $count = $planner_count->fetchColumn();
                        ?>
                        <tr>
                            <td style="font-size: 24px;"><?= $category['icon'] ?></td>
                            <td><strong><?= htmlspecialchars($category['name']) ?></strong></td>
                            <td><code><?= $category['slug'] ?></code></td>
                            <td>
                                <?php if ($category['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $count ?> planners</td>
                            <td class="table-actions">
                                <a href="categories.php?edit=<?= $category['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="categories.php?delete=<?= $category['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Delete this category? This will also delete all associated planners!')">
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
