<?php
$page_title = 'Header Settings';
require_once 'includes/header.php';

$success = $error = '';


$settings = $conn->query("SELECT * FROM header_settings WHERE id = 1")->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logo_text = sanitize($_POST['logo_text']);
    $phone = sanitize($_POST['phone']);
    $email = sanitize($_POST['email']);

    $logo_image = $settings['logo_image'];

   
    if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['logo_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = 'logo_' . time() . '.' . $ext;
            $upload_path = '../uploads/logo/' . $new_filename;

            if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $upload_path)) {
             
                if ($logo_image && file_exists('../uploads/logo/' . $logo_image)) {
                    unlink('../uploads/logo/' . $logo_image);
                }
                $logo_image = $new_filename;
            }
        } else {
            $error = 'Invalid file type. Allowed: jpg, jpeg, png, gif, webp';
        }
    }

    if (!$error) {
        $sql = "UPDATE header_settings SET logo_text = ?, logo_image = ?, phone = ?, email = ? WHERE id = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $logo_text, $logo_image, $phone, $email);

        if ($stmt->execute()) {
            $success = 'Header settings updated successfully!';
           
            $settings = $conn->query("SELECT * FROM header_settings WHERE id = 1")->fetch_assoc();
        } else {
            $error = 'Error updating settings!';
        }
    }
}
?>

<div class="top-bar">
    <h4><i class="fas fa-heading me-2"></i>Header Settings</h4>
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

<div class="card">
    <div class="card-header">
        <i class="fas fa-cog me-2"></i>Update Header Settings
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Logo Text</label>
                    <input type="text" name="logo_text" class="form-control" value="<?php echo htmlspecialchars($settings['logo_text']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Logo Image (Optional)</label>
                    <input type="file" name="logo_image" class="form-control" accept="image/*">
                    <?php if ($settings['logo_image']): ?>
                        <div class="mt-2">
                            <img src="../uploads/logo/<?php echo $settings['logo_image']; ?>" alt="Logo" style="max-height: 50px;">
                            <small class="text-muted d-block">Current logo</small>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($settings['phone']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($settings['email']); ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Save Changes
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
