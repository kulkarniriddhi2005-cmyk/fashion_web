<?php
session_start();
include 'components/connect.php';

$success_msg = [];
$warning_msg = [];

// Check if user is logged in
$is_guest = !isset($_SESSION['user_id']) && !isset($_COOKIE['user_id']);
$user_id = null;

if (!$is_guest) {
    $user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
}

if (isset($_POST['track_order'])) {
    $order_id = trim($_POST['order_id']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $query = "SELECT o.*, GROUP_CONCAT(CONCAT(p.name, ' (', oi.qty, ')') SEPARATOR ', ') as products
                      FROM orders o
                      JOIN order_items oi ON o.id = oi.order_id
                      JOIN products p ON oi.product_id = p.id
                      WHERE o.id = ?";
            $params = [$order_id];

            if ($is_guest) {
                $query .= " AND o.email = ? AND o.is_guest = 1";
                $params[] = $email;
            } else {
                $query .= " AND o.user_id = ? AND o.is_guest = 0";
                $params[] = $user_id;
            }

            $query .= " GROUP BY o.id LIMIT 1";
            $select_order = $conn->prepare($query);
            $select_order->execute($params);

            if ($select_order->rowCount() > 0) {
                $order = $select_order->fetch(PDO::FETCH_ASSOC);
                $_SESSION['tracked_order'] = $order;
                header('Location: view_order.php?get_id=' . $order_id);
                exit();
            } else {
                $warning_msg[] = 'No order found with the provided details.';
            }
        } catch (PDOException $e) {
            error_log('Error tracking order: ' . $e->getMessage());
            $warning_msg[] = 'Error processing your request. Please try again.';
        }
    } else {
        $warning_msg[] = 'Invalid email format';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track Order - Swara Fashion</title>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'components/user_header.php'; ?>

    <div class="banner">
        <div class="detail">
            <h1>Track Order</h1>
            <p>Track your order status</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i> track order</span>
        </div>
    </div>

    <div class="line2"></div>

    <div class="form-container">
        <form action="" method="post" class="track-order">
            <div class="heading">
                <h1>Track Your Order</h1>
                <img src="image/separator.png">
            </div>
            <div class="input-field">
                <p>Order ID <span>*</span></p>
                <input type="text" name="order_id" placeholder="Enter your order ID" required class="box">
            </div>
            <div class="input-field">
                <p>Email <span>*</span></p>
                <input type="email" name="email" placeholder="Enter your email" required class="box">
            </div>
            <input type="submit" name="track_order" value="Track Order" class="btn">
            
            <?php if (isset($_SESSION['last_order'])): ?>
            <div class="last-order">
                <p>Your last order:</p>
                <p>Order ID: <?= htmlspecialchars($_SESSION['last_order']['id']) ?></p>
                <p>Email: <?= htmlspecialchars($_SESSION['last_order']['email']) ?></p>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <?php include 'components/user_footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
