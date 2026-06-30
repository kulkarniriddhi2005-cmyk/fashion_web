<?php
include 'components/connect.php';

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

if (isset($_POST['place_order'])) {
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_SPECIAL_CHARS);
    $number = filter_var(trim($_POST['number']), FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $method = filter_var(trim($_POST['method']), FILTER_SANITIZE_SPECIAL_CHARS);
    $address_type = filter_var(trim($_POST['address_type']), FILTER_SANITIZE_SPECIAL_CHARS);
    $flat = filter_var(trim($_POST['flat']), FILTER_SANITIZE_SPECIAL_CHARS);
    $street = filter_var(trim($_POST['street']), FILTER_SANITIZE_SPECIAL_CHARS);
    $city = filter_var(trim($_POST['city']), FILTER_SANITIZE_SPECIAL_CHARS);
    $country = filter_var(trim($_POST['country']), FILTER_SANITIZE_SPECIAL_CHARS);
    $pincode = filter_var(trim($_POST['pincode']), FILTER_SANITIZE_SPECIAL_CHARS);

    $address = $flat . ', ' . $street . ', ' . $city . ', ' . $country . ' - ' . $pincode;

    if (empty($user_id)) {
        $warning_msg[] = 'Please login to place an order.';
    } else {
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);

        if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                $select_products->execute([$fetch_cart['product_id']]);
                $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);

                if ($fetch_products) {
                    $insert_order = $conn->prepare("INSERT INTO `orders` (id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, qty, status, payment_status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_order->execute([
                        unique_id(),
                        $user_id,
                        $fetch_products['seller_id'],
                        $name,
                        $number,
                        $email,
                        $address,
                        $address_type,
                        $method,
                        $fetch_cart['product_id'],
                        $fetch_cart['price'],
                        $fetch_cart['qty'],
                        'in progress',
                        'pending'
                    ]);
                }
            }

            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);

            header('location: order.php');
            exit;
        } else {
            $warning_msg[] = 'Your cart is empty.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <title>Checkout | Zari & Co.</title>
</head>
<body>
    <?php include 'components/user_header.php'; ?>
    <div class="banner">
        <div class="detail">
            <h1>Checkout</h1>
            <p>Complete your order securely with Zari & Co.</p>
            <span><a href="home.php">Home</a><i class="bx bxs-right-arrow-alt"></i> Checkout</span>
        </div>
    </div>
    <div class="line2"></div>
    <div class="checkout">
        <div class="heading">
            <h1>Checkout Summary</h1>
            <img src="image/separator.png" alt="Separator">
        </div>
        <div class="row">
            <div class="form-container">
                <form action="" method="post" class="register">
                    <h3>Billing Details</h3>
                    <?php if (empty($user_id)): ?>
                        <p class="empty" style="margin-bottom:1rem;">Please <a href="login.php">login</a> to place your order.</p>
                    <?php endif; ?>
                    <div class="flex">
                        <div class="col">
                            <div class="input-field">
                                <p>Your name <span>*</span></p>
                                <input type="text" name="name" placeholder="Enter your name" maxlength="50" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>Your number<span>*</span></p>
                                <input type="text" name="number" placeholder="Enter your number" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>Your email<span>*</span></p>
                                <input type="email" name="email" placeholder="Enter your email" maxlength="50" required>
                            </div>
                            <div class="input-field">
                                <p>Payment method<span>*</span></p>
                                <select name="method" class="box">
                                    <option value="cash on delivery">Cash on Delivery</option>
                                    <option value="credit or debit card">Credit or Debit Card</option>
                                    <option value="net banking">Net Banking</option>
                                    <option value="upi or rupay">UPI or RuPay</option>
                                    <option value="paytm">Paytm</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <p>Address type<span>*</span></p>
                                <select name="address_type" class="box">
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-field">
                                <p>Address line 01<span>*</span></p>
                                <input type="text" name="flat" placeholder="e.g. flat & building" maxlength="50" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>Address line 02<span>*</span></p>
                                <input type="text" name="street" placeholder="e.g. street name" maxlength="50" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>City name<span>*</span></p>
                                <input type="text" name="city" placeholder="Enter city name" maxlength="50" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>Country name<span>*</span></p>
                                <input type="text" name="country" placeholder="Enter country name" maxlength="50" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>Pincode<span>*</span></p>
                                <input type="text" name="pincode" placeholder="e.g. 110001" maxlength="6" class="box" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="place_order" class="btn" <?= empty($user_id) ? 'disabled' : ''; ?>>Place Order</button>
                </form>
            </div>
            <div class="summary">
                <h3>My Bag</h3>
                <div class="box-container">
                    <?php
                    $grant_total = 0;
                    if (!empty($user_id)) {
                        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id=?");
                        $select_cart->execute([$user_id]);
                        if ($select_cart->rowCount() > 0) {
                            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                                $select_products = $conn->prepare("SELECT * FROM `products` WHERE id=?");
                                $select_products->execute([$fetch_cart['product_id']]);
                                $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                                if ($fetch_products) {
                                    $qty = max(1, (int)($fetch_cart['qty'] ?? 1));
                                    $sub_total = $qty * (float)$fetch_products['price'];
                                    $grant_total += $sub_total;
                                    ?>
                                    <div class="flex">
                                        <img src="uploaded_files/<?= htmlspecialchars($fetch_products['thumb_one']); ?>" class="image" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
                                        <div>
                                            <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3>
                                            <p class="price">&#8377; <?= number_format($fetch_products['price'], 2); ?> x <?= $qty; ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        } else {
                            echo '<div class="empty"><p>No product added yet!</p></div>';
                        }
                    } elseif (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $product_id) {
                            $select_products = $conn->prepare("SELECT * FROM products WHERE id=?");
                            $select_products->execute([$product_id]);
                            if ($select_products->rowCount() > 0) {
                                $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                                $grant_total += (float)$fetch_products['price'];
                                ?>
                                <div class="flex">
                                    <img src="uploaded_files/<?= htmlspecialchars($fetch_products['thumb_one']); ?>" class="image" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
                                    <div>
                                        <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3>
                                        <p class="price">&#8377; <?= number_format($fetch_products['price'], 2); ?></p>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    } else {
                        echo '<div class="empty"><p>No product added yet!</p></div>';
                    }
                    ?>
                </div>
                <div class="grant-total">
                    <span>Total amount payable:</span> &#8377; <?= number_format($grant_total, 2); ?>/-
                </div>
            </div>
        </div>
    </div>
    <?php include 'components/user_footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/user_script.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
