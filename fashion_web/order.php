<?php
include 'components/connect.php';

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

if (empty($user_id)) {
    header('location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <title>My Orders | Zari & Co.</title>
</head>
<body>

    <?php include 'components/user_header.php'; ?>

    <div class="banner">
        <div class="detail">
            <h1>My Orders</h1>
            <p>Track your orders here.</p>
            <span><a href="home.php">Home</a> <i class="bx bx-right-arrow-alt"></i> My Orders</span>
        </div>
    </div>

    <div class="line2"></div>

    <div class="orders">
        <div class="heading">
            <h1>My Orders</h1>
            <img src="image/separator.png" alt="Separator">
        </div>
        <div class="box-container">
            <?php
            $select_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY date DESC");
            $select_orders->execute([$user_id]);

            if ($select_orders->rowCount() > 0) {
                while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                    $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
                    $select_products->execute([$fetch_orders['product_id']]);

                    if ($select_products->rowCount() > 0) {
                        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                        $status_color = 'orange';
                        if ($fetch_orders['status'] === 'delivered') {
                            $status_color = 'green';
                        } elseif ($fetch_orders['status'] === 'canceled') {
                            $status_color = 'red';
                        }
                        ?>
                        <div class="box">
                            <a href="view_order.php?get_id=<?= htmlspecialchars($fetch_orders['id']); ?>">
                                <div class="icon">
                                    <div class="icon-box">
                                        <img src="uploaded_files/<?= htmlspecialchars($fetch_products['thumb_one']); ?>" class="img1" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
                                        <img src="uploaded_files/<?= htmlspecialchars($fetch_products['thumb_two']); ?>" class="img2" alt="">
                                    </div>
                                </div>
                                <div class="content">
                                    <p class="date"><i class="bx bxs-calendar-alt"></i><span><?= htmlspecialchars($fetch_orders['date']); ?></span></p>
                                    <div class="row">
                                        <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3>
                                        <p class="price">&#8377; <?= number_format($fetch_products['price'], 2); ?>/-</p>
                                        <p class="status" style="color:<?= $status_color; ?>"><?= htmlspecialchars($fetch_orders['status']); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                }
            } else {
                echo '<div class="empty"><p>No orders placed yet! <a href="shop1.php">Start shopping</a></p></div>';
            }
            ?>
        </div>
    </div>

    <?php include 'components/user_footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
