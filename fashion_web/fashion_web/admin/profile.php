<?php
include '../components/connect.php';

if(isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    $seller_id = '';
    header('location:login.php');
}

// Ensure $seller_id is set and validated (you might want to fetch this from a session or request)
if (isset($seller_id)) {

    // Prepare query to select products for the given seller
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE seller_id = ?");
    $select_products->execute([$seller_id]);
    $total_products = $select_products->rowCount();

    // Prepare query to select orders for the given seller
    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
    $select_orders->execute([$seller_id]);
    $total_orders = $select_orders->rowCount();

    // Fetch seller profile using the correct column name (assumed to be 'id' here)
    $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
    $select_profile->execute([$seller_id]);
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

    // You can now use $total_products, $total_orders, and $fetch_profile as needed
    echo "Total Products: " . $total_products . "<br>";
    echo "Total Orders: " . $total_orders . "<br>";

} else {
    echo "Seller ID is not set.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- box icon cdn link -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <title>Login - Zari & Co. Seller Dashboard</title>
</head>
<body>
   
    <div class="banner">
        <div class="detail">
            <h1>Seller Profile</h1>
            <p>Manage your seller profile and account settings.<br>Keep your information up to date.</p>
            <span><a href="dashboard.php">admin</a><i class="bx bxs-right-arrow-alt"></i>seller profile</span>
        </div>
    </div>

    <div class="line2"></div>

    <div class="sellers-profile">
        <div class="heading">
            <h1>Seller Profile</h1>
            <img src="../image/separator.png" alt="Separator">
        </div>
        <div class="detail">
            <div class="seller">
                <?php if ($fetch_profile): ?>
                    <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="Profile Image">
                    <h3> <?= $fetch_profile['name']; ?></h3>
                    <span>Seller</span>
                    <a href="update.php" class="btn">Update Profile</a>
                <?php else: ?>
                    <p>Profile not found</p>
                <?php endif; ?>
            </div>

            <div class="flex">
            <div class="box">
                    <span><?= $total_products; ?></span>
                    <p>total products</p>
                    <a href="view_product.php" class="btn">View Products</a>
                </div>
                <div class="box">
                    <span><?= $total_orders; ?></span>
                    <p>total orders place</p>
                    <a href="admin_order.php" class="btn">View Orders</a>

                </div>
            </div>

            
        </div>
    </div>

    <?php include '../components/admin_footer.php'; ?>

    <!-- SweetAlert CDN link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script type="text/javascript">
        <?php include 'script.js'; ?>
    </script>

    <!-- alert -->
    <?php include '../components/alert.php'; ?>
</body>
</html>
