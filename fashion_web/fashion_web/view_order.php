<?php
// Corrected file path
include $_SERVER['DOCUMENT_ROOT'] . '/fashion_web/components/connect.php';

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    header('location:order.php');
    exit();
}


if(isset($_POST['cenceled'])){
    $update_order =  $conn->prepare("UPDATE `orders` SET status = ? WHERE ID = ?");
    $update_order->execute(['canceled',$get_id]);
    header('location:order.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Box Icons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <title>Zari & Co. | My Orders</title>
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

    <div class="view_order">
        <div class="heading">
            <h1>Order Details</h1>
            <img src="image/separator.png">
        </div>
        <div class="box-container">
            <?php
            $grant_total = 0;

            $select_order = $conn->prepare("SELECT * FROM `orders` WHERE id=? LIMIT 1");
            $select_order->execute([$get_id]);

            if ($select_order->rowCount() > 0) {
                while ($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)) {
                    $select_product = $conn->prepare("SELECT * FROM `products` WHERE id=? LIMIT 1");
                    $select_product->execute([$fetch_order['product_id']]);

                    if ($select_product->rowCount() > 0) {
                        while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {
                            $sub_total = ($fetch_order['price'] * $fetch_order['qty']);
                            $grant_total += $sub_total;
            ?>
                            <div class="box">
                                <div class="col">
                                    <div class="product-images">
                                        <div class="main-image">
                                            <img id="featuredImage" src="uploaded_files/<?= htmlspecialchars($fetch_product['thumb_one']); ?>" alt="">
                                        </div>
                                        <div class="thumbnails">
                                            <img src="uploaded_files/<?= htmlspecialchars($fetch_product['thumb_one']); ?>" onclick="document.getElementById('featuredImage').src=this.src">
                                            <img src="uploaded_files/<?= htmlspecialchars($fetch_product['thumb_two']); ?>" onclick="document.getElementById('featuredImage').src=this.src">
                                            <img src="uploaded_files/<?= htmlspecialchars($fetch_product['thumb_three']); ?>" onclick="document.getElementById('featuredImage').src=this.src">
                                        </div>
                                    </div>
                                    <p class="date"><i class="bx bxs-calendar-alt"></i><span><?= htmlspecialchars($fetch_order['date']); ?></span></p>
                                    <div class="detail">
                                        <p class="price">$<?= htmlspecialchars($fetch_product['price']); ?> X <?= htmlspecialchars($fetch_order['qty']); ?></p>
                                        <p class="name"><?= htmlspecialchars($fetch_product['name']); ?></p>
                                        <p class="grant-total">Total Amount Payable: <span>$<?= number_format($grant_total, 2); ?>/-</span></p>
                                    </div>
                                </div>
                                <div class="col">
                                    <p class="title">Billing Address</p>
                                    <p class="user"><i class="bx bxs-phone-outgoing"></i><?= htmlspecialchars($fetch_order['number']); ?></p>
                                    <p class="user"><i class="bx bxs-envelope"></i><?= htmlspecialchars($fetch_order['email']); ?></p>
                                    <p class="user"><i class="bx bxs-map-alt"></i><?= htmlspecialchars($fetch_order['address']); ?></p>
                                    <p class="title">Status</p>
                                    <p class="status" style="color:<?php 
                                        if ($fetch_order['status'] == 'delivered') {
                                            echo "green";
                                        } elseif ($fetch_order['status'] == 'canceled') {
                                            echo "red";
                                        } else {
                                            echo "orange";
                                        }
                                    ?>"><?= htmlspecialchars($fetch_order['status']); ?></p>

                                    <?php if ($fetch_order['status'] == 'canceled') { ?>
                                        <a href="checkout.php?get_id=<?= htmlspecialchars($fetch_product['id']); ?>" class="btn">Order Again</a>
                                    <?php } else { ?>
                                        <form action="" method="post">
                                            <button type="submit" name="canceled" class="btn" onclick="return confirm('Do you want to cancel this product?');">Cancel</button>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
            <?php
                        }
                    }
                }
            } else {
                echo '
                <div class="empty">
                    <p>No orders placed yet</p>
                </div>
                ';
            }
            ?>
        </div>
    </div>

    <?php include 'components/user_footer.php'; ?>

    <!-- SweetAlert CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- JavaScript -->
    <script type="text/javascript">
        <?php include 'script.js'; ?>
    </script>

    <!-- Alert System -->
    <?php include '../components/alert.php'; ?>
</body>

</html>
