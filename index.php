<?php
require_once 'config.php';
$header = $conn->query("SELECT * FROM header_settings WHERE id = 1")->fetch_assoc();
$nav_menu = $conn->query("SELECT * FROM nav_menu WHERE is_active = 1 ORDER BY menu_order ASC");
$banners = $conn->query("SELECT * FROM banners WHERE is_active = 1 ORDER BY banner_order ASC");
$searched_cars = $conn->query("SELECT * FROM most_searched_cars WHERE is_active = 1 ORDER BY search_count DESC LIMIT 8");
$latest_cars = $conn->query("SELECT * FROM latest_cars WHERE is_active = 1 ORDER BY created_at DESC LIMIT 8");
$footer = $conn->query("SELECT * FROM footer_settings WHERE id = 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($header['logo_text']); ?> - Find Your Perfect Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    .swiper-button-next:after, .swiper-button-prev:after {
    font-family: swiper-icons;
    font-size: 27px !important;
    }
    </style>
</head>
<body>

    <header class="main-header">
      
        <div class="top-bar d-none d-lg-block">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <span><i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($header['phone']); ?></span>
                        <span class="ms-4"><i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($header['email']); ?></span>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>

       
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <?php if ($header['logo_image']): ?>
                        <img src="uploads/logo/<?php echo $header['logo_image']; ?>" alt="Logo" height="40">
                    <?php else: ?>
                        <i class="fas fa-car text-primary me-2"></i>
                        <span class="fw-bold text-primary"><?php echo htmlspecialchars($header['logo_text']); ?></span>
                    <?php endif; ?>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php while ($menu = $nav_menu->fetch_assoc()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo htmlspecialchars($menu['menu_link']); ?>">
                                    <?php echo htmlspecialchars($menu['menu_name']); ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                    <div class="d-flex ms-3">
                        <a href="#" class="btn btn-outline-primary me-2">Login</a>
                        <a href="#" class="btn btn-primary">Sign Up</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

   
    <section class="banner-section">
        <?php if ($banners->num_rows > 0): ?>
            <div class="swiper bannerSwiper">
                <div class="swiper-wrapper">
                    <?php while ($banner = $banners->fetch_assoc()): ?>
                        <div class="swiper-slide">
                            <div class="banner-slide" style="background-image: url('uploads/banners/<?php echo $banner['image']; ?>');">
                                <div class="banner-overlay">
                                    <div class="container">
                                        <div class="banner-content">
                                            <h1><?php echo htmlspecialchars($banner['title']); ?></h1>
                                            <?php if ($banner['subtitle']): ?>
                                                <p><?php echo htmlspecialchars($banner['subtitle']); ?></p>
                                            <?php endif; ?>
                                            <?php if ($banner['button_text']): ?>
                                                <a href="<?php echo htmlspecialchars($banner['button_link']); ?>" class="btn btn-primary btn-lg">
                                                    <?php echo htmlspecialchars($banner['button_text']); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        <?php else: ?>
         
            <div class="default-banner">
                <div class="container">
                    <div class="banner-content text-center">
                        <h1>Find Your Perfect Car</h1>
                        <p>Search from thousands of new & used cars</p>
                        <div class="search-box mt-4">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="input-group input-group-lg">
                                        <input type="text" class="form-control" placeholder="Search for cars...">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>

  
    <section class="most-searched-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Most Searched Cars</h2>
                <p class="section-subtitle">Explore the most popular cars searched by users</p>
            </div>

            <?php if ($searched_cars->num_rows > 0): ?>
                <div class="row">
                    <?php while ($car = $searched_cars->fetch_assoc()): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="car-card">
                                <div class="car-image">
                                    <img src="uploads/cars/<?php echo $car['car_image']; ?>" alt="<?php echo htmlspecialchars($car['car_name']); ?>">
                                    <span class="search-badge">
                                        <i class="fas fa-search me-1"></i><?php echo number_format($car['search_count']); ?> searches
                                    </span>
                                </div>
                                <div class="car-details">
                                    <h5 class="car-name"><?php echo htmlspecialchars($car['car_name']); ?></h5>
                                    <p class="car-price"><?php echo htmlspecialchars($car['price_range']); ?></p>
                                    <a href="#" class="btn btn-outline-primary btn-sm w-100">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-car fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No cars found. Add cars from admin panel.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

  
    <section class="latest-cars-section py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Latest Cars</h2>
                <p class="section-subtitle">Discover the newest arrivals in our collection</p>
            </div>

            <?php if ($latest_cars->num_rows > 0): ?>
                <div class="row">
                    <?php while ($car = $latest_cars->fetch_assoc()): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="latest-car-card">
                                <div class="car-image">
                                    <img src="uploads/cars/<?php echo $car['car_image']; ?>" alt="<?php echo htmlspecialchars($car['car_name']); ?>">
                                    <?php if ($car['launch_date'] && strtotime($car['launch_date']) > strtotime('-30 days')): ?>
                                        <span class="new-badge">NEW</span>
                                    <?php endif; ?>
                                </div>
                                <div class="car-details">
                                    <h5 class="car-name"><?php echo htmlspecialchars($car['car_name']); ?></h5>
                                    <p class="car-price"><?php echo htmlspecialchars($car['price']); ?></p>
                                    <div class="car-specs">
                                        <?php if ($car['fuel_type']): ?>
                                            <span><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($car['fuel_type']); ?></span>
                                        <?php endif; ?>
                                        <?php if ($car['transmission']): ?>
                                            <span><i class="fas fa-cog"></i> <?php echo htmlspecialchars($car['transmission']); ?></span>
                                        <?php endif; ?>
                                        <?php if ($car['engine']): ?>
                                            <span><i class="fas fa-tachometer-alt"></i> <?php echo htmlspecialchars($car['engine']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="car-actions mt-3">
                                        <a href="#" class="btn btn-primary btn-sm">View Details</a>
                                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-heart"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="#" class="btn btn-outline-primary btn-lg">View All Cars <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-car-side fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No latest cars found. Add cars from admin panel.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

   
    <footer class="main-footer">
        <div class="footer-top py-5">
            <div class="container">
                <div class="row">
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-widget">
                            <h4 class="widget-title">
                                <i class="fas fa-car me-2"></i><?php echo htmlspecialchars($header['logo_text']); ?>
                            </h4>
                            <p><?php echo htmlspecialchars($footer['about_text']); ?></p>
                            <div class="social-links mt-3">
                                <?php if ($footer['facebook_link']): ?>
                                    <a href="<?php echo htmlspecialchars($footer['facebook_link']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <?php endif; ?>
                                <?php if ($footer['twitter_link']): ?>
                                    <a href="<?php echo htmlspecialchars($footer['twitter_link']); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                                <?php if ($footer['instagram_link']): ?>
                                    <a href="<?php echo htmlspecialchars($footer['instagram_link']); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                                <?php if ($footer['youtube_link']): ?>
                                    <a href="<?php echo htmlspecialchars($footer['youtube_link']); ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-widget">
                            <h4 class="widget-title">Quick Links</h4>
                            <ul class="footer-links">
                                <li><a href="#">New Cars</a></li>
                                <li><a href="#">Used Cars</a></li>
                                <li><a href="#">Sell Car</a></li>
                                <li><a href="#">Compare Cars</a></li>
                                <li><a href="#">Car News</a></li>
                            </ul>
                        </div>
                    </div>

                    
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-widget">
                            <h4 class="widget-title">Popular Brands</h4>
                            <ul class="footer-links">
                                <li><a href="#">Maruti Suzuki</a></li>
                                <li><a href="#">Hyundai</a></li>
                                <li><a href="#">Tata</a></li>
                                <li><a href="#">Mahindra</a></li>
                                <li><a href="#">Toyota</a></li>
                            </ul>
                        </div>
                    </div>

                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-widget">
                            <h4 class="widget-title">Contact Us</h4>
                            <ul class="contact-info">
                                <?php if ($footer['address']): ?>
                                    <li>
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo htmlspecialchars($footer['address']); ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($footer['phone']): ?>
                                    <li>
                                        <i class="fas fa-phone"></i>
                                        <span><?php echo htmlspecialchars($footer['phone']); ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($footer['email']): ?>
                                    <li>
                                        <i class="fas fa-envelope"></i>
                                        <span><?php echo htmlspecialchars($footer['email']); ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="footer-bottom py-3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0"><?php echo htmlspecialchars($footer['copyright_text']); ?></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#">Privacy Policy</a>
                        <span class="mx-2">|</span>
                        <a href="#">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        
        var bannerSwiper = new Swiper(".bannerSwiper", {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>
</body>
</html>
