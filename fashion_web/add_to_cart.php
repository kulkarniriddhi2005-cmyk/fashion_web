<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'components/connect.php';

if(isset($_POST['add_to_cart'])){
    $product_id = $_POST['product_id'];
    $qty = $_POST['qty'] ?? 1;
    
    // Initialize guest session if not logged in
    if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
        if (!isset($_SESSION['guest_id'])) {
            $_SESSION['guest_id'] = 'GUEST_' . time() . bin2hex(random_bytes(8));
        }
        $user_id = $_SESSION['guest_id'];
        $is_guest = true;
    } else {
        $user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
        $is_guest = false;
    }

    try {
        if ($is_guest) {
            // Handle guest cart in session
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // For guest users, just store product IDs in an array
            if (!in_array($product_id, $_SESSION['cart'])) {
                $_SESSION['cart'][] = $product_id;
            }
            
            $_SESSION['success_msg'] = 'Product added to cart!';
        } else {
            // Handle logged-in user cart in database
            $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
            $check_cart->execute([$user_id, $product_id]);
            
            if($check_cart->rowCount() > 0){
                $update_qty = $conn->prepare("UPDATE `cart` SET qty = qty + ? WHERE user_id = ? AND product_id = ?");
                $update_qty->execute([$qty, $user_id, $product_id]);
            } else {
                $insert_cart = $conn->prepare("INSERT INTO `cart` (user_id, product_id, qty) VALUES (?,?,?)");
                $insert_cart->execute([$user_id, $product_id, $qty]);
            }
            
            $_SESSION['success_msg'] = 'Product added to cart!';
        }
    } catch(PDOException $e) {
        $_SESSION['warning_msg'] = 'Error adding to cart: ' . $e->getMessage();
    }
    
    header('location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
