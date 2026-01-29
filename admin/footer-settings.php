<?php
$page_title = 'Footer Settings';
require_once 'includes/header.php';

$success = $error = '';


$settings = $conn->query("SELECT * FROM footer_settings WHERE id = 1")->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $about_text = sanitize($_POST['about_text']);
    $address = sanitize($_POST['address']);
    $phone = sanitize($_POST['phone']);
    $email = sanitize($_POST['email']);
    $facebook_link = sanitize($_POST['facebook_link']);
    $twitter_link = sanitize($_POST['twitter_link']);
    $instagram_link = sanitize($_POST['instagram_link']);
    $youtube_link = sanitize($_POST['youtube_link']);
    $copyright_text = sanitize($_POST['copyright_text']);

    $sql = "UPDATE footer_settings SET about_text = ?, address = ?, phone = ?, email = ?, facebook_link = ?, twitter_link = ?, instagram_link = ?, youtube_link = ?, copyright_text = ? WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $about_text, $address, $phone, $email, $facebook_link, $twitter_link, $instagram_link, $youtube_link, $copyright_text);

    if ($stmt->execute()) {
        $success = 'Footer settings updated successfully!';
        $settings = $conn->query("SELECT * FROM footer_settings WHERE id = 1")->fetch_assoc();
    } else {
        $error = 'Error updating settings!';
    }
}
?>

<div class="top-bar">
    <h4><i class="fas fa-shoe-prints me-2"></i>Footer Settings</h4>
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
        <i class="fas fa-cog me-2"></i>Update Footer Settings
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">About Text</label>
                    <textarea name="about_text" class="form-control" rows="3"><?php echo htmlspecialchars($settings['about_text']); ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($settings['address']); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($settings['phone']); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($settings['email']); ?>">
                </div>
            </div>

            <h6 class="mt-4 mb-3"><i class="fas fa-share-alt me-2"></i>Social Media Links</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-facebook me-1"></i>Facebook</label>
                    <input type="url" name="facebook_link" class="form-control" value="<?php echo htmlspecialchars($settings['facebook_link']); ?>" placeholder="https://facebook.com/yourpage">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-twitter me-1"></i>Twitter</label>
                    <input type="url" name="twitter_link" class="form-control" value="<?php echo htmlspecialchars($settings['twitter_link']); ?>" placeholder="https://twitter.com/yourhandle">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-instagram me-1"></i>Instagram</label>
                    <input type="url" name="instagram_link" class="form-control" value="<?php echo htmlspecialchars($settings['instagram_link']); ?>" placeholder="https://instagram.com/yourhandle">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-youtube me-1"></i>YouTube</label>
                    <input type="url" name="youtube_link" class="form-control" value="<?php echo htmlspecialchars($settings['youtube_link']); ?>" placeholder="https://youtube.com/yourchannel">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Copyright Text</label>
                    <input type="text" name="copyright_text" class="form-control" value="<?php echo htmlspecialchars($settings['copyright_text']); ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Save Changes
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
