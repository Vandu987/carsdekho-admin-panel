<?php
$page_title = 'Navigation Menu';
require_once 'includes/header.php';

$success = $error = '';


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($conn->query("DELETE FROM nav_menu WHERE id = $id")) {
        $success = 'Menu item deleted successfully!';
    } else {
        $error = 'Error deleting menu item!';
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_name = sanitize($_POST['menu_name']);
    $menu_link = sanitize($_POST['menu_link']);
    $menu_order = (int)$_POST['menu_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (isset($_POST['edit_id']) && $_POST['edit_id']) {
    
        $id = (int)$_POST['edit_id'];
        $sql = "UPDATE nav_menu SET menu_name = ?, menu_link = ?, menu_order = ?, is_active = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiii", $menu_name, $menu_link, $menu_order, $is_active, $id);

        if ($stmt->execute()) {
            $success = 'Menu item updated successfully!';
        } else {
            $error = 'Error updating menu item!';
        }
    } else {
       
        $sql = "INSERT INTO nav_menu (menu_name, menu_link, menu_order, is_active) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $menu_name, $menu_link, $menu_order, $is_active);

        if ($stmt->execute()) {
            $success = 'Menu item added successfully!';
        } else {
            $error = 'Error adding menu item!';
        }
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM nav_menu WHERE id = $id")->fetch_assoc();
}


$menu_items = $conn->query("SELECT * FROM nav_menu ORDER BY menu_order ASC");
?>

<div class="top-bar">
    <h4><i class="fas fa-bars me-2"></i>Navigation Menu</h4>
    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-<?php echo $edit_data ? 'edit' : 'plus'; ?> me-2"></i>
                <?php echo $edit_data ? 'Edit Menu Item' : 'Add Menu Item'; ?>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="edit_id" value="<?php echo $edit_data['id']; ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Menu Name</label>
                        <input type="text" name="menu_name" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['menu_name']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Menu Link</label>
                        <input type="text" name="menu_link" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['menu_link']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="menu_order" class="form-control" value="<?php echo $edit_data ? $edit_data['menu_order'] : '0'; ?>">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" <?php echo ($edit_data && $edit_data['is_active']) || !$edit_data ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i><?php echo $edit_data ? 'Update' : 'Add'; ?> Menu Item
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="nav-menu.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i>All Menu Items
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Name</th>
                                <th>Link</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($menu_items->num_rows > 0): ?>
                                <?php while ($item = $menu_items->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $item['menu_order']; ?></td>
                                        <td><?php echo htmlspecialchars($item['menu_name']); ?></td>
                                        <td><small><?php echo htmlspecialchars($item['menu_link']); ?></small></td>
                                        <td>
                                            <span class="badge bg-<?php echo $item['is_active'] ? 'success' : 'danger'; ?>">
                                                <?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="action-btns">
                                            <a href="?edit=<?php echo $item['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No menu items found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
