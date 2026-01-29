<?php
require_once '../config.php';

if (!isAdminLoggedIn()) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>CarsDekho Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --sidebar-width: 260px;
        }
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 20px 0;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-brand {
            color: white;
            font-size: 24px;
            font-weight: 700;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar-brand i {
            margin-right: 10px;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li a {
            display: block;
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid white;
        }
        .sidebar-menu li a i {
            width: 25px;
            margin-right: 10px;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
        }
        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-bar h4 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            font-weight: 600;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: #666;
        }
        .action-btns .btn {
            padding: 5px 10px;
            margin: 2px;
        }
        .car-thumb {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
 
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-car"></i>CarsDekho
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li><a href="header-settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'header-settings.php' ? 'active' : ''; ?>"><i class="fas fa-heading"></i>Header Settings</a></li>
            <li><a href="nav-menu.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'nav-menu.php' ? 'active' : ''; ?>"><i class="fas fa-bars"></i>Navigation Menu</a></li>
            <li><a href="banners.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'banners.php' ? 'active' : ''; ?>"><i class="fas fa-images"></i>Banners</a></li>
            <li><a href="most-searched-cars.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'most-searched-cars.php' ? 'active' : ''; ?>"><i class="fas fa-search"></i>Most Searched Cars</a></li>
            <li><a href="latest-cars.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'latest-cars.php' ? 'active' : ''; ?>"><i class="fas fa-car-side"></i>Latest Cars</a></li>
            <li><a href="footer-settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'footer-settings.php' ? 'active' : ''; ?>"><i class="fas fa-shoe-prints"></i>Footer Settings</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </div>

   
    <div class="main-content">
