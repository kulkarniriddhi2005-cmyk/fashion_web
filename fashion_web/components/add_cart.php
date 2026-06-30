<?php
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
    $product_id = trim($_POST['product_id'] ?? '');
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    $is_ajax = isset($_POST['ajax_add_cart']);

    if ($product_id === '') {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Invalid product.']);
            exit;
        }
        $error_msg[] = 'Invalid product.';
    } elseif (!empty($user_id)) {
        try {
            $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
            $verify_cart->execute([$user_id, $product_id]);
            $existing = $verify_cart->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $new_qty = (int)$existing['qty'] + $qty;
                $update_qty = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ?");
                $update_qty->execute([(string)$new_qty, $existing['id']]);
                $message = 'Cart quantity updated!';
            } else {
                $select_price = $conn->prepare("SELECT price FROM products WHERE id = ? LIMIT 1");
                $select_price->execute([$product_id]);
                $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

                if ($fetch_price) {
                    $insert_cart = $conn->prepare("INSERT INTO cart (id, user_id, product_id, price, qty) VALUES (?, ?, ?, ?, ?)");
                    $insert_cart->execute([unique_id(), $user_id, $product_id, $fetch_price['price'], (string)$qty]);
                    $message = 'Product added to cart!';
                } else {
                    if ($is_ajax) {
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'error', 'message' => 'Product not found!']);
                        exit;
                    }
                    $error_msg[] = 'Product not found!';
                }
            }

            if (!isset($error_msg)) {
                if ($is_ajax) {
                    $count_cart = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                    $count_cart->execute([$user_id]);
                    $total = $count_cart->fetchColumn();
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'success', 'message' => $message ?? 'Product added to cart!', 'total' => $total]);
                    exit;
                }
                $success_msg[] = $message ?? 'Product added to cart successfully!';
            }
        } catch (PDOException $e) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Please try again.']);
                exit;
            }
            $error_msg[] = 'Something went wrong. Please try again.';
        }
    } else {
        if (!in_array($product_id, $_SESSION['cart'], true)) {
            $_SESSION['cart'][] = $product_id;
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'Product added to cart!', 'total' => count($_SESSION['cart'])]);
                exit;
            }
            $success_msg[] = 'Product added to cart!';
        } else {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'warning', 'message' => 'Product already exists in your cart!']);
                exit;
            }
            $warning_msg[] = 'Product already exists in your cart!';
        }
    }
}
?>
