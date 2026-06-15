<?php
include 'components/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}
include 'components/add_wishlist.php';
include 'components/add_cart.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Zari & Co. — Premium Indian Fashion. Discover ethnic and contemporary collections crafted with heritage and styled for today.">
    <title>Zari & Co. | Premium Indian Fashion</title>
    <link rel="icon" type="image/png" href="image/logo (1).png">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Slick Slider -->
    <link rel="stylesheet" type="text/css" href="slick.css" />
    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Hero Slider -->
<div class="container-fluid">
    <div class="hero-slider">
        <div class="slider-item">
            <img src="image/home-slide.jpg" alt="Zari & Co. New Arrivals">
            <div class="slider-caption">
                <h1>Street style has its <br>own rules</h1>
                <p>Discover curated ethnic and contemporary looks<br>crafted for the modern Indian woman.<br>Style that speaks before you do.</p>
                <a href="shop1.php" class="btn">Shop Now</a>
            </div>
        </div>
        <div class="slider-item">
            <img src="image/home-slide0.jpg" alt="Premium Indian Fashion">
            <div class="slider-caption">
                <h1>Crafted in India,<br>Worn Worldwide</h1>
                <p>Heritage weaves, luxury fabrics, and timeless silhouettes<br>reimagined for the contemporary lifestyle.<br>Where tradition meets trend.</p>
                <a href="shop1.php" class="btn">Explore Collection</a>
            </div>
        </div>
        <div class="slider-item">
            <img src="image/home-slide1.jpg" alt="Ethnic Fashion Collection">
            <div class="slider-caption">
                <h1>Draped in Heritage,<br>Styled for Today</h1>
                <p>From elegant sarees to modern kurtas and co-ords,<br>Zari & Co. brings you fashion that celebrates<br>your culture with contemporary flair.</p>
                <a href="shop1.php" class="btn">View Lookbook</a>
            </div>
        </div>
        <div class="slider-item">
            <img src="image/home-slide2.jpg" alt="Seasonal Sale">
            <div class="slider-caption">
                <h1>End of Season<br>Sale — Up to 50% Off</h1>
                <p>Grab your favourites before they're gone.<br>Limited stock on premium kurta sets, dupattas and more.<br>Shop the sale today.</p>
                <a href="shop1.php" class="btn">Shop Sale</a>
            </div>
        </div>
    </div>
    <div class="controls">
        <i class="bx bx-left-arrow-alt prev"></i>
        <i class="bx bx-right-arrow-alt next"></i>
    </div>
</div>

<!-- Services Section -->
<div class="services">
    <div class="box-container">
        <div class="box">
            <div class="icon">
                <img src="image/service.png" alt="Secure Online Shopping">
            </div>
            <div class="detail">
                <h4>Secure Shopping</h4>
                <span>100% Safe & Secure</span>
            </div>
        </div>
        <div class="box">
            <div class="icon">
                <img src="image/services2.png" alt="Quality Products">
            </div>
            <div class="detail">
                <h4>Premium Quality</h4>
                <span>Handpicked Collections</span>
            </div>
        </div>
        <div class="box">
            <div class="icon">
                <img src="image/services.png" alt="Fast Delivery">
            </div>
            <div class="detail">
                <h4>Pan-India Delivery</h4>
                <span>2–5 Business Days</span>
            </div>
        </div>
        <div class="box">
            <div class="icon">
                <img src="image/services0.png" alt="Customer Support">
            </div>
            <div class="detail">
                <h4>Customer Support</h4>
                <span>24×7 Assistance</span>
            </div>
        </div>
        <div class="box">
            <div class="icon">
                <img src="image/service.png" alt="Easy Returns">
            </div>
            <div class="detail">
                <h4>Easy Returns</h4>
                <span>7-Day Return Policy</span>
            </div>
        </div>
        <div class="box">
            <div class="icon">
                <img src="image/services1.png" alt="Exclusive Offers">
            </div>
            <div class="detail">
                <h4>Exclusive Offers</h4>
                <span>Members Get More</span>
            </div>
        </div>
    </div>
</div>

<!-- Sub Banner -->
<img src="image/-sub-banner.jpg" class="sub-banner" alt="Zari & Co. Fashion Banner">

<!-- Frame Container -->
<div class="frame-container">
    <div class="box-container">
        <div class="frame">
            <div class="detail">
                <span>Shop Seasonal</span>
                <h2>Up to 50% Off</h2>
                <h1>All Seasonal Fashion</h1>
                <a href="shop1.php" class="btn">Shop Now</a>
            </div>
        </div>
        <div class="box">
            <div class="box-detail">
                <img src="image/lookbook4.jpg" class="img" alt="Fresh Collections">
                <div class="img-detail">
                    <span>Wide Range</span>
                    <h1>Fresh Latest Collections</h1>
                    <a href="shop1.php" class="btn">Shop Now</a>
                </div>
            </div>
            <div class="box-detail">
                <img src="image/lookbook5.jpg" class="img" alt="Ethnic Wear">
                <div class="img-detail">
                    <span>Exclusively Crafted</span>
                    <h1>Ethnic & Contemporary</h1>
                    <a href="shop1.php" class="btn">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About Us -->
<div class="about-us">
    <div class="box-container">
        <div class="img-box">
            <div class="box">
                <div class="heading">
                    <span>Why Choose Us</span>
                    <h1>Why Zari & Co.?</h1>
                    <img src="image/separator.png" alt="Decorative separator">
                </div>
                <p>At Zari & Co., we believe fashion is more than clothing — it's a celebration of identity, culture, and craftsmanship. We source the finest Indian fabrics, work with skilled artisans, and create collections that honour our heritage while embracing modern aesthetics. From luxurious silk sarees to chic contemporary co-ords, every piece tells a story.</p>
                <a href="about.php" class="btn">Know More</a>
                <a href="contact.php" class="btn">Contact Us</a>
            </div>
        </div>
    </div>
</div>

<!-- Sub Banners -->
<div class="sub-banner">
    <div class="box-container">
        <img src="image/sub-banner1.jpg" alt="Summer Collection">
        <img src="image/sub-banner.jpg" height="85%" alt="New Arrivals Banner">
    </div>
</div>

<!-- Shop by Categories -->
<div class="categories">
    <div class="heading">
        <h1>Shop by Categories</h1>
    </div>
    <div class="box-container">
        <div class="box"><img src="image/cat.webp" alt="Sarees"></div>
        <div class="box"><img src="image/cat0.webp" alt="Kurtas"></div>
        <div class="box"><img src="image/cat1.png" alt="Lehengas"></div>
        <div class="box"><img src="image/cat2.webp" alt="Ethnic Wear"></div>
        <div class="box"><img src="image/cat3.webp" alt="Western Wear"></div>
        <div class="box"><img src="image/cat4.webp" alt="Dupattas"></div>
        <div class="box"><img src="image/cat5.webp" alt="Accessories"></div>
        <div class="box"><img src="image/cat6.webp" alt="Men's Collection"></div>
        <div class="box"><img src="image/cat7.webp" alt="Kids Wear"></div>
        <div class="box"><img src="image/cat8.avif" alt="Sale Items"></div>
    </div>
</div>

<!-- Sub Banners -->
<div class="sub-banner">
    <img src="image/sub-banner2.jpg" alt="Collection Banner">
    <img src="image/sub-banner3.jpg" style="margin-top: -.4rem;" alt="Lookbook Banner">
</div>

<!-- Frame Container 2 -->
<div class="frame-container-2">
    <div class="frame">
        <div class="detail">
            <span>Shop Seasonal</span>
            <h2>Up to 50% Off</h2>
            <h1>All Seasonal Fashion</h1>
            <a href="shop1.php" class="btn">Shop Now</a>
        </div>
    </div>
    <div class="box">
        <img src="image/sub-banner4.jpg" alt="Season Special">
    </div>
</div>

<!-- Sub Banner -->
<div class="sub-banner">
    <img src="image/sub-banner5.jpg" alt="Zari & Co. Special Collection">
</div>

<!-- Guarantee Section -->
<div class="gurantee">
    <div class="heading">
        <h1>Our Guarantee</h1>
        <p>At Zari & Co., your trust is our most valuable asset. We are committed to delivering authenticity, quality, and an unmatched shopping experience with every order.</p>
        <img src="image/separator.png" alt="Separator">
    </div>
    <div class="box-container con">
        <img src="image/sale3.webp" alt="Quality Guarantee">
        <img src="image/sale4.jpg" alt="Authentic Indian Fashion">
        <img src="image/sale7.jpg" alt="Premium Fabrics">
        <img src="image/sale6.jpg" alt="Customer Promise">
    </div>
</div>

<!-- Offer 1 — Countdown Timer -->
<div class="offer-1">
    <div class="detail">
        <h1>Special Discount for All<br>Latest Fashion Products</h1>
        <p>Our year-end sale is live! Get exclusive discounts on sarees, kurtas, ethnic co-ords, and accessories. Don't miss out on the biggest fashion sale of the season — curated exclusively for you by Zari & Co.</p>
        <div class="container">
            <div id="countdown" style="color: #fff;">
                <ul>
                    <li><span id="days"></span>Days</li>
                    <li><span id="hours"></span>Hours</li>
                    <li><span id="minutes"></span>Minutes</li>
                    <li><span id="seconds"></span>Seconds</li>
                </ul>
            </div>
        </div>
        <a href="shop1.php" class="btn">Shop Now</a>
    </div>
</div>

<!-- Latest Products -->
<div class="products">
    <div class="heading">
        <h1>Our Latest Collection</h1>
    </div>
    <?php include 'components/homeshop.php'; ?>
</div>

<!-- Offer 2 -->
<div class="offer-2">
    <div class="detail">
        <h1>We Pride Ourselves on<br>Exceptional Fashion Design</h1>
        <p>From the first sketch to the final stitch, every Zari & Co. creation is a masterpiece. We collaborate with the finest Indian artisans and designers to bring you garments that are as unique as you are. Experience fashion that tells your story.</p>
        <a href="shop1.php" class="btn">Shop Now</a>
    </div>
</div>

<div class="heading">
    <img src="image/separator.png" alt="Separator">
</div>

<?php include 'components/user_footer.php'; ?>

<!-- SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<!-- jQuery + Slick -->
<script src="jquary.js"></script>
<script src="slick.js"></script>
<!-- Main Script -->
<script type="text/javascript">
<?php include 'script.js' ?>
</script>
<script src="js/user_script.js?v=<?= time(); ?>"></script>
<!-- Alerts -->
<?php include 'components/alert.php'; ?>

</body>
</html>