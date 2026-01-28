<?php
$page_title = 'Dashboard';
require_once 'includes/header.php';

// Get counts
$banners_count = $conn->query("SELECT COUNT(*) as count FROM banners")->fetch_assoc()['count'];
$searched_count = $conn->query("SELECT COUNT(*) as count FROM most_searched_cars")->fetch_assoc()['count'];
$latest_count = $conn->query("SELECT COUNT(*) as count FROM latest_cars")->fetch_assoc()['count'];
$menu_count = $conn->query("SELECT COUNT(*) as count FROM nav_menu")->fetch_assoc()['count'];
?>

<div class="top-bar">
    <h4><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h4>
    <span>Welcome, <?php echo $_SESSION['admin_username']; ?>!</span>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-center p-4">
            <i class="fas fa-images fa-3x mb-3" style="color: #667eea;"></i>
            <h3><?php echo $banners_count; ?></h3>
            <p class="text-muted mb-0">Banners</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center p-4">
            <i class="fas fa-search fa-3x mb-3" style="color: #28a745;"></i>
            <h3><?php echo $searched_count; ?></h3>
            <p class="text-muted mb-0">Most Searched Cars</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center p-4">
            <i class="fas fa-car-side fa-3x mb-3" style="color: #ffc107;"></i>
            <h3><?php echo $latest_count; ?></h3>
            <p class="text-muted mb-0">Latest Cars</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center p-4">
            <i class="fas fa-bars fa-3x mb-3" style="color: #dc3545;"></i>
            <h3><?php echo $menu_count; ?></h3>
            <p class="text-muted mb-0">Menu Items</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-link me-2"></i>Quick Links
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="header-settings.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-heading me-2"></i>Update Header Settings
                    </a>
                    <a href="banners.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus me-2"></i>Add New Banner
                    </a>
                    <a href="most-searched-cars.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus me-2"></i>Add Most Searched Car
                    </a>
                    <a href="latest-cars.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus me-2"></i>Add Latest Car
                    </a>
                    <a href="footer-settings.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-shoe-prints me-2"></i>Update Footer Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-eye me-2"></i>Preview Website
            </div>
            <div class="card-body text-center p-5">
                <i class="fas fa-globe fa-4x mb-3" style="color: #667eea;"></i>
                <p>View your website frontend</p>
                <a href="../index.php" target="_blank" class="btn btn-primary">
                    <i class="fas fa-external-link-alt me-2"></i>Open Website
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
