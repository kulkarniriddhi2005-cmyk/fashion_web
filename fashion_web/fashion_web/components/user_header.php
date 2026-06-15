<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
?>

<header class="header">
    <section class="flex">
        <a href="home.php" class="logo">
            <img src="image/logo (1).png?v=<?= time(); ?>" width="130px" alt="Zari & Co. Logo">
        </a>
        <nav class="navbar" id="navbar">
            <a href="home.php">Home</a>
            <a href="shop1.php">Shop</a>
            <a href="about.php">About</a>
            <a href="order.php">Orders</a>
            <a href="contact.php">Contact</a>
        </nav>
        <form action="search_product.php" method="post" class="search-form" id="search-form">
            <input type="text" name="search_product" placeholder="Search products..." required maxlength="100" aria-label="Search products">
            <button type="submit" class="bx bx-search-alt-2" name="search_product_btn" aria-label="Search"></button>
        </form>
        <div class="icons">
            <div id="menu-btn" class="bx bx-menu" aria-label="Toggle Menu"></div>
            <div id="search-btn" class="bx bx-search-alt-2" aria-label="Toggle Search"></div>
            <?php
            $total_wishlist_item = 0;
            if ($user_id) {
                $count_wishlist_item = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
                $count_wishlist_item->execute([$user_id]);
                $total_wishlist_item = $count_wishlist_item->fetchColumn();
            } elseif (isset($_SESSION['wishlist'])) {
                $total_wishlist_item = count($_SESSION['wishlist']);
            }
            ?>
            <a href="wishlist.php" class="cart-btn" aria-label="Wishlist">
                <i class="bx bx-heart"></i>
                <sup><?= $total_wishlist_item; ?></sup>
            </a>
            <?php
            $total_cart_item = 0;
            if ($user_id) {
                $count_cart_item = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                $count_cart_item->execute([$user_id]);
                $total_cart_item = $count_cart_item->fetchColumn();
            } elseif (isset($_SESSION['cart'])) {
                $total_cart_item = count($_SESSION['cart']);
            }
            ?>
            <a href="cart.php" class="cart-btn" aria-label="Cart">
                <i class="bx bx-cart"></i>
                <sup><?= $total_cart_item; ?></sup>
            </a>
            <div id="user-btn" class="bx bx-user" aria-label="User Profile"></div>
        </div>
        <div class="profile">
            <?php
            if ($user_id) {
                $select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $select_profile->execute([$user_id]);
                if ($select_profile->rowCount() > 0) {
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <img src="uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="<?= htmlspecialchars($fetch_profile['name']); ?>">
                    <h3 style="margin-bottom:1rem;"><?= htmlspecialchars($fetch_profile['name']); ?></h3>
                    <div class="flex-btn">
                        <a href="profile.php" class="btn">View Profile</a>
                        <a href="components/user_logout.php" onclick="return confirm('Logout from Zari & Co.?');" class="btn">Logout</a>
                    </div>
                    <?php
                }
            } else {
                ?>
                <img src="image/man.png" alt="Guest User">
                <h3 style="margin-bottom:1rem;">Welcome, Guest!</h3>
                <div class="flex-btn">
                    <a href="login.php" class="btn">Login</a>
                    <a href="register.php" class="btn">Register</a>
                </div>
                <?php
            }
            ?>
        </div>
    </section>
</header>