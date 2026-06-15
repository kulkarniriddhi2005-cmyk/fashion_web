<?php
include 'components/connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

// Delete single cart item
if (isset($_POST['delete_item'])) {
    if (!empty($user_id)) {
        $cart_id = filter_var($_POST['cart_id'], FILTER_SANITIZE_NUMBER_INT);
        $verify_delete = $conn->prepare("SELECT * FROM cart WHERE id = ?");
        $verify_delete->execute([$cart_id]);

        if ($verify_delete->rowCount() > 0) {
            $delete_cart = $conn->prepare("DELETE FROM cart WHERE id = ?");
            $delete_cart->execute([$cart_id]);
            $success_msg[] = 'Item removed from cart.';
        } else {
            $warning_msg[] = 'Item not found in cart.';
        }
    } else {
        $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
        if (($key = array_search($product_id, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $success_msg[] = 'Item removed from cart.';
        } else {
            $warning_msg[] = 'Item not found in cart.';
        }
    }
}

// Empty entire cart
if (isset($_POST['empty_cart'])) {
    if (!empty($user_id)) {
        $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $clear_cart->execute([$user_id]);
    } else {
        $_SESSION['cart'] = [];
    }
    $success_msg[] = 'Your cart has been cleared.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your shopping cart at Zari & Co. Review your selected items and proceed to checkout.">
    <title>My Cart | Zari & Co.</title>
    <link rel="icon" type="image/png" href="image/logo (1).png">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Banner -->
<div class="banner">
    <div class="detail">
        <h1>My Cart</h1>
        <p>Review your selected items and proceed to checkout.</p>
        <span>
            <a href="home.php">Home</a>
            <i class="bx bxs-right-arrow-alt"></i>
            Cart
        </span>
    </div>
</div>

<div class="line2"></div>

<div class="cart-container">
    <div class="heading" style="margin-bottom: 2rem;">
        <h1>Shopping Cart</h1>
    </div>

    <div class="cart-items">
        <?php
        $total_price = 0;

        if (!empty($user_id)) {
            $select_cart = $conn->prepare("SELECT cart.*, products.name, products.price, products.thumb_one FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
            $select_cart->execute([$user_id]);

            if ($select_cart->rowCount() > 0) {
                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                    $product_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                    $total_price += $product_total;
                    ?>
                    <div class="cart-box" id="cart-item-<?= $fetch_cart['id']; ?>">
                        <img src="uploaded_files/<?= htmlspecialchars($fetch_cart['thumb_one']); ?>" alt="<?= htmlspecialchars($fetch_cart['name']); ?>">
                        <h3><?= htmlspecialchars($fetch_cart['name']); ?></h3>
                        <p>Price: <strong>&#8377; <?= number_format($fetch_cart['price'], 2); ?></strong></p>
                        <p>Qty: <strong><?= (int)$fetch_cart['quantity']; ?></strong></p>
                        <p>Subtotal: <strong>&#8377; <?= number_format($product_total, 2); ?></strong></p>
                        <form method="post">
                            <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                            <button class="delete_item" type="submit" name="delete_item" style="background:#e63946;color:#fff;border:none;padding:8px 16px;border-radius:5px;cursor:pointer;">
                                <i class="bx bx-trash"></i> Remove
                            </button>
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="empty"><p>Your cart is empty! <a href="shop1.php">Continue Shopping</a></p></div>';
            }
        } else {
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $product_id) {
                    $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
                    $select_products->execute([$product_id]);

                    if ($select_products->rowCount() > 0) {
                        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                        $total_price += $fetch_products['price'];
                        ?>
                        <div class="cart-box">
                            <img src="uploaded_files/<?= htmlspecialchars($fetch_products['thumb_one']); ?>" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
                            <h3><?= htmlspecialchars($fetch_products['name']); ?></h3>
                            <p>Price: <strong>&#8377; <?= number_format($fetch_products['price'], 2); ?></strong></p>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                                <button class="delete_item" type="submit" name="delete_item" style="background:#e63946;color:#fff;border:none;padding:8px 16px;border-radius:5px;cursor:pointer;">
                                    <i class="bx bx-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                        <?php
                    }
                }
            } else {
                echo '<div class="empty"><p>Your cart is empty! <a href="shop1.php">Continue Shopping</a></p></div>';
            }
        }
        ?>
    </div>

    <?php if ($total_price > 0): ?>
    <div class="cart-total">
        <p>Grand Total: <span>&#8377; <?= number_format($total_price, 2); ?></span></p>
        <div class="button">
            <form method="post" style="display:inline;">
                <button class="btn" name="empty_cart" type="submit" onclick="return confirm('Are you sure you want to clear your cart?');">Clear Cart</button>
            </form>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'components/user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<?php include 'components/alert.php'; ?>

</body>
</html>