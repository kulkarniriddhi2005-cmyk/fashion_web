<?php
// Ensure connect is included (if not already)
if (!isset($conn)) {
    include 'components/connect.php';
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

if (isset($_POST['add_to_cart']) || isset($_POST['ajax_add_cart'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $is_ajax = isset($_POST['ajax_add_cart']);

    if (!empty($user_id)) {
        try {
            $verify_cart = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ? AND product_id = ?");
            $verify_cart->execute([$user_id, $product_id]);
            $is_in_cart = $verify_cart->fetchColumn();

            if ($is_in_cart > 0) {
                if ($is_ajax) { echo json_encode(['status' => 'warning', 'message' => 'Product already exists in your cart!']); exit; }
                $warning_msg[] = 'Product already exists in your cart!';
            } else {
                $select_price = $conn->prepare("SELECT price FROM products WHERE id = ? LIMIT 1");
                $select_price->execute([$product_id]);
                $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

                if ($fetch_price) {
                    $insert_cart = $conn->prepare("INSERT INTO cart (user_id, product_id, price, quantity) VALUES (?, ?, ?, 1)");
                    $insert_cart->execute([$user_id, $product_id, $fetch_price['price']]);
                    if ($is_ajax) {
                        // Get updated count
                        $count_cart = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                        $count_cart->execute([$user_id]);
                        $total = $count_cart->fetchColumn();
                        echo json_encode(['status' => 'success', 'message' => 'Product added to cart!', 'total' => $total]);
                        exit;
                    }
                    $success_msg[] = 'Product added to cart successfully!';
                } else {
                    if ($is_ajax) { echo json_encode(['status' => 'error', 'message' => 'Product not found!']); exit; }
                    $error_msg[] = 'Product not found!';
                }
            }
        } catch (PDOException $e) {
            if ($is_ajax) { echo json_encode(['status' => 'error', 'message' => 'Something went wrong.']); exit; }
            $error_msg[] = 'Something went wrong. Please try again.';
        }
    } else {
        if (!in_array($product_id, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $product_id;
            if ($is_ajax) {
                echo json_encode(['status' => 'success', 'message' => 'Product added to cart!', 'total' => count($_SESSION['cart'])]);
                exit;
            }
            $success_msg[] = 'Product added to cart!';
        } else {
            if ($is_ajax) { echo json_encode(['status' => 'warning', 'message' => 'Product already exists in your cart!']); exit; }
            $warning_msg[] = 'Product already exists in your cart!';
        }
    }
}
?>