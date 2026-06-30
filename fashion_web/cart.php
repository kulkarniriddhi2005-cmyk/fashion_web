<?php
include 'components/connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

// Handle update cart quantity
if (isset($_POST['update_cart'])) {
    $cart_id = trim($_POST['cart_id']);
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    
    if (!empty($user_id)) {
        $update_qty = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ? AND user_id = ?");
        $update_qty->execute([(string)$qty, $cart_id, $user_id]);
        $success_msg[] = 'Cart quantity updated.';
    } else {
        $warning_msg[] = 'Guests cannot update quantity (please login to update quantities).';
    }
}

// Delete single cart item
if (isset($_POST['delete_item'])) {
    if (!empty($user_id)) {
        $cart_id = trim($_POST['cart_id'] ?? '');
        $verify_delete = $conn->prepare("SELECT * FROM cart WHERE id = ? AND user_id = ?");
        $verify_delete->execute([$cart_id, $user_id]);
        
        if ($verify_delete->rowCount() > 0) {
            $delete_cart = $conn->prepare("DELETE FROM cart WHERE id = ?");
            $delete_cart->execute([$cart_id]);
            $success_msg[] = 'Item removed from cart.';
        } else {
            $warning_msg[] = 'Item not found in cart.';
        }
    } else {
        $product_id = trim($_POST['product_id'] ?? '');
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
        <p>Review your selected items before checkout.</p>
        <span>
            <a href="home.php">Home</a>
            <i class="bx bxs-right-arrow-alt"></i>
            Cart
        </span>
    </div>
</div>

<div class="line2"></div>

<div class="cart-total" style="box-shadow: none;">
    <div class="heading">
        <h1>Shopping Cart</h1>
        <img src="image/separator.png" alt="Separator">
    </div>
    
    <div class="cart-container">
        <div class="cart-items">
            <?php
            $grand_total = 0;
            if (!empty($user_id)) {
                $select_cart = $conn->prepare("SELECT cart.*, products.name, products.price as product_price, products.thumb_one, products.stock FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
                $select_cart->execute([$user_id]);

                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $sub_total = $fetch_cart['price'] * $fetch_cart['qty'];
                        $grand_total += $sub_total;
                        ?>
                        <form action="" method="post" class="cart-box">
                            <input type="hidden" name="cart_id" value="<?= htmlspecialchars($fetch_cart['id']); ?>">
                            <img src="uploaded_files/<?= htmlspecialchars($fetch_cart['thumb_one']); ?>" alt="<?= htmlspecialchars($fetch_cart['name']); ?>">
                            <h3><?= htmlspecialchars($fetch_cart['name']); ?></h3>
                            <p>Price: &#8377; <?= number_format($fetch_cart['price'], 2); ?></p>
                            <div style="display:flex; justify-content:center; align-items:center; margin:10px 0;">
                                <input type="number" name="qty" min="1" max="<?= (int)$fetch_cart['stock'] ?>" value="<?= (int)$fetch_cart['qty'] ?>" style="width:70px; padding:5px; border:1px solid #ccc; text-align:center;">
                                <button type="submit" name="update_cart" class="bx bx-edit" style="background:var(--main-color); margin-left:10px; width:30px; height:30px; color:white; display:flex; justify-content:center; align-items:center;" title="Update Quantity"></button>
                            </div>
                            <p style="color:var(--main-color); font-weight:bold;">Subtotal: &#8377; <?= number_format($sub_total, 2); ?></p>
                            <button type="submit" name="delete_item" onclick="return confirm('Remove this item?');" style="margin-top:10px; width:100%;"><i class="bx bx-trash"></i> Remove</button>
                        </form>
                        <?php
                    }
                } else {
                    echo '<p>Your cart is empty! <a href="shop1.php" style="color:var(--main-color); text-decoration:underline;">Explore our collection</a></p>';
                }
            } else {
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $product_id) {
                        $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
                        $select_products->execute([$product_id]);

                        if ($select_products->rowCount() > 0) {
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                            $grand_total += $fetch_products['price']; // default qty 1 for guests
                            ?>
                            <form action="" method="post" class="cart-box">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id); ?>">
                                <img src="uploaded_files/<?= htmlspecialchars($fetch_products['thumb_one']); ?>" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
                                <h3><?= htmlspecialchars($fetch_products['name']); ?></h3>
                                <p>Price: &#8377; <?= number_format($fetch_products['price'], 2); ?></p>
                                <p style="color:var(--main-color); font-weight:bold;">Subtotal: &#8377; <?= number_format($fetch_products['price'], 2); ?></p>
                                <button type="submit" name="delete_item" onclick="return confirm('Remove this item?');" style="margin-top:10px; width:100%;"><i class="bx bx-trash"></i> Remove</button>
                            </form>
                            <?php
                        }
                    }
                } else {
                    echo '<p>Your cart is empty! <a href="shop1.php" style="color:var(--main-color); text-decoration:underline;">Explore our collection</a></p>';
                }
            }
            ?>
        </div>
        
        <?php if ($grand_total > 0) { ?>
        <div style="margin-top:20px; text-align:right; border-top:1px solid #ccc; padding-top:20px;">
            <h2 style="margin-bottom:15px;">Grand Total: <span style="color:var(--main-color);">&#8377; <?= number_format($grand_total, 2); ?>/-</span></h2>
            <div style="display:flex; justify-content:flex-end; gap:15px; flex-wrap:wrap;">
                <form action="" method="post">
                    <button type="submit" name="empty_cart" class="btn" style="background:#e63946; margin:0;" onclick="return confirm('Empty your entire cart?');">Empty Cart</button>
                </form>
                <a href="checkout.php" class="btn" style="margin:0;">Proceed to Checkout</a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php include 'components/user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js?v=<?= time(); ?>"></script>
<?php include 'components/alert.php'; ?>

</body>
</html>