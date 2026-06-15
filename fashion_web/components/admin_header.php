
<header>
    <div class="logo">
        <img src="../image/logo (1).png" width="150">
    </div>
    <div class="right">
        <div class="bx bxs-user" id="user-btn">
        </div>
        <div class="toggle-btn"><i class="bx bx-menu"></i></div>
    </div>
    <div class="profile">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id= ?");
        $select_profile->execute([$seller_id]);
        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>">
            <h3 style="margin-bottom:.5rem;"><?= $fetch_profile['name']; ?></h3>
            <p>seller</p>
            <div class="flex-btn">
                <a href="profile.php" class="btn">view profile</a>
                <a href="../components/admin_logout.php" onclick="return confirm('logout from this website');"
                    class="btn">logout</a>
            </div>
        <?php } else { ?>
            <img src="../image/man.png">
            <h3 style="margin-bottom:1rem;">please login or register</h3>
            <div class="flex-btn">
                <a href="login.php" class="btn">login</a>
                <a href="register.php" class="btn">register</a>
            </div>
        <?php }
        
        ?>
    </div>
</header>
<div class="sidebar">
    <?php
    $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id= ?");
    $select_profile->execute([$seller_id]);
    if ($select_profile->rowCount() > 0) {
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="profile">
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img">
            <p><?= $fetch_profile['name']; ?></p>
        </div>
    <?php } ?>
    <h5>menu</h5>
    <div class="navbar">
        <ul>
            <li><a href="dashboard.php"><i class="bx bxs-home-smile"></i>dashboard</a></li>
            <li><a href="add_product.php"><i class="bx bxs-shopping-bags"></i>add product</a></li>
            <li><a href="view_product.php"><i class="bx bxs-food-menu"></i>view product</a></li>
            <li><a href="user_account.php"><i class="bx bxs-home-smile"></i>account</a></li>
            <li><a href="../components/admin_logout.php" onclick="return confirm('logout from this website');"><i
                        class="bx bxs-out"></i>logout</a></li>
        </ul>
    </div>
    <h5>find us</h5>
    <div class="social-links">
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-instagram-alt"></i>
        <i class="bx bxl-linkedin"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-pinterest-alt"></i>
    </div>
</div>
