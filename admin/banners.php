<?php
$page_title = 'Banners';
require_once 'includes/header.php';

$success = $error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Get image name first
    $banner = $conn->query("SELECT image FROM banners WHERE id = $id")->fetch_assoc();
    if ($banner) {
        // Delete image file
        if (file_exists('../uploads/banners/' . $banner['image'])) {
            unlink('../uploads/banners/' . $banner['image']);
        }
        if ($conn->query("DELETE FROM banners WHERE id = $id")) {
            $success = 'Banner deleted successfully!';
        } else {
            $error = 'Error deleting banner!';
        }
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $subtitle = sanitize($_POST['subtitle']);
    $button_text = sanitize($_POST['button_text']);
    $button_link = sanitize($_POST['button_link']);
    $banner_order = (int)$_POST['banner_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $image = '';
    $edit_id = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : 0;

    // Get existing image if editing
    if ($edit_id) {
        $existing = $conn->query("SELECT image FROM banners WHERE id = $edit_id")->fetch_assoc();
        $image = $existing['image'];
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = 'banner_' . time() . '.' . $ext;
            $upload_path = '../uploads/banners/' . $new_filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Delete old image if updating
                if ($image && file_exists('../uploads/banners/' . $image)) {
                    unlink('../uploads/banners/' . $image);
                }
                $image = $new_filename;
            }
        } else {
            $error = 'Invalid file type!';
        }
    }

    if (!$error) {
        if ($edit_id) {
            // Update
            $sql = "UPDATE banners SET title = ?, subtitle = ?, image = ?, button_text = ?, button_link = ?, banner_order = ?, is_active = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssiis", $title, $subtitle, $image, $button_text, $button_link, $banner_order, $is_active, $edit_id);
            $msg = 'Banner updated successfully!';
        } else {
            if (!$image) {
                $error = 'Please upload an image!';
            } else {
                $sql = "INSERT INTO banners (title, subtitle, image, button_text, button_link, banner_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssii", $title, $subtitle, $image, $button_text, $button_link, $banner_order, $is_active);
                $msg = 'Banner added successfully!';
            }
        }

        if (!$error && $stmt->execute()) {
            $success = $msg;
        } elseif (!$error) {
            $error = 'Database error!';
        }
    }
}

// Get edit data
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM banners WHERE id = $id")->fetch_assoc();
}

// Get all banners
$banners = $conn->query("SELECT * FROM banners ORDER BY banner_order ASC");
?>

<div class="top-bar">
    <h4><i class="fas fa-images me-2"></i>Banners Management</h4>
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
                <?php echo $edit_data ? 'Edit Banner' : 'Add Banner'; ?>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="edit_id" value="<?php echo $edit_data['id']; ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['title']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['subtitle']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Banner Image *</label>
                        <input type="file" name="image" class="form-control" accept="image/*" <?php echo $edit_data ? '' : 'required'; ?>>
                        <?php if ($edit_data && $edit_data['image']): ?>
                            <div class="mt-2">
                                <img src="../uploads/banners/<?php echo $edit_data['image']; ?>" class="car-thumb" alt="Banner">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Button Text</label>
                        <input type="text" name="button_text" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['button_text']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Button Link</label>
                        <input type="text" name="button_link" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['button_link']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="banner_order" class="form-control" value="<?php echo $edit_data ? $edit_data['banner_order'] : '0'; ?>">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" <?php echo ($edit_data && $edit_data['is_active']) || !$edit_data ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i><?php echo $edit_data ? 'Update' : 'Add'; ?> Banner
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="banners.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i>All Banners
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($banners->num_rows > 0): ?>
                                <?php while ($banner = $banners->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <img src="../uploads/banners/<?php echo $banner['image']; ?>" class="car-thumb" alt="Banner">
                                        </td>
                                        <td><?php echo htmlspecialchars($banner['title']); ?></td>
                                        <td><?php echo $banner['banner_order']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $banner['is_active'] ? 'success' : 'danger'; ?>">
                                                <?php echo $banner['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="action-btns">
                                            <a href="?edit=<?php echo $banner['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $banner['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No banners found</td>
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
