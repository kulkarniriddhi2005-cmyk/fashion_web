<?php
if (!isset($conn)) {
    include 'components/connect.php';
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

if (isset($_POST['add_to_wishlist']) || isset($_POST['ajax_add_wishlist'])) {
    $product_id = trim($_POST['product_id'] ?? '');
    $is_ajax = isset($_POST['ajax_add_wishlist']);

    if ($product_id === '') {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Invalid product.']);
            exit;
        }
        $error_msg[] = 'Invalid product.';
    } elseif (!empty($user_id)) {
        $verify_wishlist = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ? AND product_id = ?");
        $verify_wishlist->execute([$user_id, $product_id]);
        $is_in_wishlist = $verify_wishlist->fetchColumn();

        if ($is_in_wishlist > 0) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'warning', 'message' => 'Product already exists in your wishlist!']);
                exit;
            }
            $warning_msg[] = 'Product already exists in your wishlist!';
        } else {
            $select_price = $conn->prepare("SELECT price FROM products WHERE id = ? LIMIT 1");
            $select_price->execute([$product_id]);
            $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

            if ($fetch_price) {
                $insert_wishlist = $conn->prepare("INSERT INTO wishlist (id, user_id, product_id, price) VALUES (?, ?, ?, ?)");
                $insert_wishlist->execute([unique_id(), $user_id, $product_id, $fetch_price['price']]);
                if ($is_ajax) {
                    $count_wish = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
                    $count_wish->execute([$user_id]);
                    $total = $count_wish->fetchColumn();
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'success', 'message' => 'Product added to wishlist!', 'total' => $total]);
                    exit;
                }
                $success_msg[] = 'Product added to wishlist successfully!';
            } else {
                if ($is_ajax) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Product not found!']);
                    exit;
                }
                $error_msg[] = 'Product not found!';
            }
        }
    } else {
        if (!in_array($product_id, $_SESSION['wishlist'], true)) {
            $_SESSION['wishlist'][] = $product_id;
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'Product added to wishlist!', 'total' => count($_SESSION['wishlist'])]);
                exit;
            }
            $success_msg[] = 'Product added to wishlist!';
        } else {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'warning', 'message' => 'Product already exists in your wishlist!']);
                exit;
            }
            $warning_msg[] = 'Product already exists in your wishlist!';
        }
    }
}
?>
