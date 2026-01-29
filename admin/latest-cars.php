<?php
$page_title = 'Latest Cars';
require_once 'includes/header.php';

$success = $error = '';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $car = $conn->query("SELECT car_image FROM latest_cars WHERE id = $id")->fetch_assoc();
    if ($car) {
        if (file_exists('../uploads/cars/' . $car['car_image'])) {
            unlink('../uploads/cars/' . $car['car_image']);
        }
        if ($conn->query("DELETE FROM latest_cars WHERE id = $id")) {
            $success = 'Car deleted successfully!';
        } else {
            $error = 'Error deleting car!';
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_name = sanitize($_POST['car_name']);
    $price = sanitize($_POST['price']);
    $fuel_type = sanitize($_POST['fuel_type']);
    $transmission = sanitize($_POST['transmission']);
    $engine = sanitize($_POST['engine']);
    $launch_date = sanitize($_POST['launch_date']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $car_image = '';
    $edit_id = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : 0;

    if ($edit_id) {
        $existing = $conn->query("SELECT car_image FROM latest_cars WHERE id = $edit_id")->fetch_assoc();
        $car_image = $existing['car_image'];
    }

    if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['car_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = 'latest_' . time() . '.' . $ext;
            $upload_path = '../uploads/cars/' . $new_filename;

            if (move_uploaded_file($_FILES['car_image']['tmp_name'], $upload_path)) {
                if ($car_image && file_exists('../uploads/cars/' . $car_image)) {
                    unlink('../uploads/cars/' . $car_image);
                }
                $car_image = $new_filename;
            }
        } else {
            $error = 'Invalid file type!';
        }
    }

    if (!$error) {
        if ($edit_id) {
            $sql = "UPDATE latest_cars SET car_name = ?, car_image = ?, price = ?, fuel_type = ?, transmission = ?, engine = ?, launch_date = ?, is_active = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $launch_date = $launch_date ?: null;
            $stmt->bind_param("sssssssii", $car_name, $car_image, $price, $fuel_type, $transmission, $engine, $launch_date, $is_active, $edit_id);
            $msg = 'Car updated successfully!';
        } else {
            if (!$car_image) {
                $error = 'Please upload an image!';
            } else {
                $sql = "INSERT INTO latest_cars (car_name, car_image, price, fuel_type, transmission, engine, launch_date, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $launch_date = $launch_date ?: null;
                $stmt->bind_param("sssssssi", $car_name, $car_image, $price, $fuel_type, $transmission, $engine, $launch_date, $is_active);
                $msg = 'Car added successfully!';
            }
        }

        if (!$error && $stmt->execute()) {
            $success = $msg;
        } elseif (!$error) {
            $error = 'Database error!';
        }
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM latest_cars WHERE id = $id")->fetch_assoc();
}

$cars = $conn->query("SELECT * FROM latest_cars ORDER BY created_at DESC");
?>

<div class="top-bar">
    <h4><i class="fas fa-car-side me-2"></i>Latest Cars</h4>
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
                <?php echo $edit_data ? 'Edit Car' : 'Add Car'; ?>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="edit_id" value="<?php echo $edit_data['id']; ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Car Name *</label>
                        <input type="text" name="car_name" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['car_name']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Car Image *</label>
                        <input type="file" name="car_image" class="form-control" accept="image/*" <?php echo $edit_data ? '' : 'required'; ?>>
                        <?php if ($edit_data && $edit_data['car_image']): ?>
                            <div class="mt-2">
                                <img src="../uploads/cars/<?php echo $edit_data['car_image']; ?>" class="car-thumb" alt="Car">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price *</label>
                        <input type="text" name="price" class="form-control" placeholder="e.g., Rs. 8.5 Lakh" value="<?php echo $edit_data ? htmlspecialchars($edit_data['price']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fuel Type</label>
                        <select name="fuel_type" class="form-control">
                            <option value="">Select</option>
                            <option value="Petrol" <?php echo ($edit_data && $edit_data['fuel_type'] == 'Petrol') ? 'selected' : ''; ?>>Petrol</option>
                            <option value="Diesel" <?php echo ($edit_data && $edit_data['fuel_type'] == 'Diesel') ? 'selected' : ''; ?>>Diesel</option>
                            <option value="Electric" <?php echo ($edit_data && $edit_data['fuel_type'] == 'Electric') ? 'selected' : ''; ?>>Electric</option>
                            <option value="Hybrid" <?php echo ($edit_data && $edit_data['fuel_type'] == 'Hybrid') ? 'selected' : ''; ?>>Hybrid</option>
                            <option value="CNG" <?php echo ($edit_data && $edit_data['fuel_type'] == 'CNG') ? 'selected' : ''; ?>>CNG</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transmission</label>
                        <select name="transmission" class="form-control">
                            <option value="">Select</option>
                            <option value="Manual" <?php echo ($edit_data && $edit_data['transmission'] == 'Manual') ? 'selected' : ''; ?>>Manual</option>
                            <option value="Automatic" <?php echo ($edit_data && $edit_data['transmission'] == 'Automatic') ? 'selected' : ''; ?>>Automatic</option>
                            <option value="Both" <?php echo ($edit_data && $edit_data['transmission'] == 'Both') ? 'selected' : ''; ?>>Both</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Engine</label>
                        <input type="text" name="engine" class="form-control" placeholder="e.g., 1197 cc" value="<?php echo $edit_data ? htmlspecialchars($edit_data['engine']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Launch Date</label>
                        <input type="date" name="launch_date" class="form-control" value="<?php echo $edit_data ? $edit_data['launch_date'] : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" <?php echo ($edit_data && $edit_data['is_active']) || !$edit_data ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i><?php echo $edit_data ? 'Update' : 'Add'; ?> Car
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="latest-cars.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i>All Latest Cars
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Car Name</th>
                                <th>Price</th>
                                <th>Fuel</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($cars->num_rows > 0): ?>
                                <?php while ($car = $cars->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <img src="../uploads/cars/<?php echo $car['car_image']; ?>" class="car-thumb" alt="Car">
                                        </td>
                                        <td><?php echo htmlspecialchars($car['car_name']); ?></td>
                                        <td><?php echo htmlspecialchars($car['price']); ?></td>
                                        <td><?php echo htmlspecialchars($car['fuel_type']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $car['is_active'] ? 'success' : 'danger'; ?>">
                                                <?php echo $car['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="action-btns">
                                            <a href="?edit=<?php echo $car['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $car['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No cars found</td>
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
