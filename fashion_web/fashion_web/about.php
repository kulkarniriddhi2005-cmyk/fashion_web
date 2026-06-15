<?php
include 'components/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Learn about Zari & Co. — our story, our team, and our passion for premium Indian fashion blending heritage with contemporary style.">
    <title>About Us | Zari & Co.</title>
    <link rel="icon" type="image/png" href="image/logo (1).png">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Banner Section -->
<div class="banner">
    <div class="detail">
        <h1>About Zari & Co.</h1>
        <p>Celebrating Indian craftsmanship, one thread at a time.<br>Where heritage meets haute couture.</p>
        <span>
            <a href="home.php">Home</a>
            <i class="bx bxs-right-arrow-alt"></i>
            About Us
        </span>
    </div>
</div>

<div class="line2"></div>

<!-- Who Are We -->
<div class="who">
    <div class="box-container">
        <div class="box">
            <div class="heading">
                <span>Who Are We</span>
                <h1>A Brand Born from Passion for Indian Fashion</h1>
            </div>
            <p>Zari & Co. was founded in Mumbai with a singular vision — to bring the rich legacy of Indian textiles and artisanship to the modern wardrobe. We design to make you shine. Explore our stunning collection of ethnic and contemporary fashion that fits every vibe and every season. From hand-woven sarees to modern co-ords, we are your ultimate style destination.</p>
            <div class="flex-btn">
                <a href="shop1.php" class="btn">Explore Our Shop</a>
                <a href="contact.php" class="btn">Get in Touch</a>
            </div>
        </div>
        <div class="img-box">
            <img src="image/home.jpg" class="img" alt="Zari & Co. Fashion">
            <img src="image/mask.png" class="shape" alt="Design Element">
        </div>
    </div>
</div>

<!-- Exclusive Collection -->
<div class="exclusive">
    <div class="detail">
        <h1>Exclusive Collection <br>Summer Festive 2025</h1>
        <p>Feel the festive mood with our latest exclusive collection<br>featuring vibrant colours, intricate embroidery, and hand-crafted ornaments.<br>Wear the art of India.</p>
        <a href="shop1.php" class="btn">Shop Now</a>
    </div>
</div>

<!-- CMS Banners -->
<div class="cms-banner">
    <div class="box-container">
        <div class="box">
            <img src="image/cms-banner.avif" alt="Kids Ethnic Collection">
            <div class="detail">
                <span>Get Up to 35% Off</span>
                <h1>On Kids' <br>Ethnic Collection</h1>
                <a href="shop1.php" class="btn">Shop Now</a>
            </div>
        </div>
        <div class="box">
            <img src="image/cms-banner.jpg" alt="Men's Kurta Collection">
            <div class="detail">
                <span>Flat 50% Discount</span>
                <h1>On Men's <br>Kurta Collection</h1>
                <a href="shop1.php" class="btn">Shop Now</a>
            </div>
        </div>
    </div>
</div>

<!-- Story Section -->
<div class="story">
    <div class="box">
        <div class="heading">
            <span style="color: var(--main-color);">Fresh & Latest Collection</span>
            <h1>Up to 30% Off on Your <br>First Purchase at Zari & Co.</h1>
            <p style="color: var(--main-color); font-weight:600;">Use Code: ZARI20 for Extra 20% Off</p>
            <p>Enjoy exclusive deals on trending pieces from our newest arrivals. Kurtas, lehengas, dupattas, and more — crafted for you and styled for the occasion.</p>
            <a href="shop1.php" class="btn">Shop Our Story</a>
        </div>
    </div>
</div>

<!-- Our Team -->
<div class="team">
    <div class="heading">
        <span>Our Creative Team</span>
        <h1>The Designers Behind Zari & Co.</h1>
        <img src="image/separator.png" alt="Separator">
    </div>
    <div class="box-container">
        <?php
        $team_members = [
            ['team.jpg',  'Priya Sharma',  'Creative Director'],
            ['team0.jpg', 'Anjali Mehta',  'Head of Design'],
            ['team1.jpg', 'Rohan Kapoor',  'Brand Strategist'],
            ['team2.jpg', 'Neha Gupta',    'Lead Stylist'],
        ];
        foreach ($team_members as $member) {
            echo '
            <div class="box">
                <img src="image/' . $member[0] . '" class="img" alt="' . $member[1] . '">
                <div class="content">
                    <h2>' . $member[1] . '</h2>
                    <p>' . $member[2] . '</p>
                    <div class="icons">
                        <i class="bx bxl-facebook"></i>
                        <i class="bx bxl-instagram-alt"></i>
                        <i class="bx bxl-linkedin"></i>
                        <i class="bx bxl-twitter"></i>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</div>

<!-- About Company -->
<div class="about">
    <div class="box-container">
        <div class="box">
            <div class="heading">
                <span>About Our Brand</span>
                <h1>India's Premier Fashion <br>Destination</h1>
                <p>Stay ahead of trends while honouring your roots — at Zari & Co., fashion meets comfort, heritage meets modernity, and every stitch tells a story worth wearing.</p>
                <div class="flex-btn">
                    <a href="shop1.php" class="btn">Explore Products</a>
                    <a href="contact.php" class="btn">Have a Query?</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Why Choose Us -->
<div class="box-detail1">
    <?php
    $features = [
        ["discount.png", "Exclusive Discounts",    "Enjoy seasonal deals, loyalty rewards, and member-only offers on our finest collections."],
        ["gift.png",     "Gift Options",            "Send the gift of style with our curated gift cards and festive hampers."],
        ["return.png",   "Easy Return Policy",      "Shop with confidence — our hassle-free 7-day return policy ensures complete peace of mind."],
        ["support.png",  "24/7 Customer Support",   "Our dedicated support team is available around the clock to assist with all your queries."],
    ];
    $index = 1;
    foreach ($features as $feature) {
        echo '
        <div class="detail1">
            <img src="image/' . $feature[0] . '" alt="' . $feature[1] . '" />
            <h3>' . $feature[1] . '</h3>
            <p>' . $feature[2] . '</p>
            <span>' . $index++ . '</span>
        </div>';
    }
    ?>
</div>

<?php include 'components/user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>

</body>
</html>
