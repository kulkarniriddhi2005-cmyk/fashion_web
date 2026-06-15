<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'components/connect.php';

if(isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['qty'];
    
    if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
        // For guest users, we don't support quantity updates
        $_SESSION['warning_msg'] = 'Quantity updates not supported for guest users.';
        header('location: cart.php');
        exit();
    } else {
        // Logged in user - update database cart
        try {
            $update_qty = $conn->prepare("UPDATE `cart` SET qty = ? WHERE id = ?");
            $update_qty->execute([$qty, $cart_id]);
        } catch(PDOException $e) {
            $_SESSION['warning_msg'] = 'Error updating cart: ' . $e->getMessage();
        }
    }
    
    $_SESSION['success_msg'] = 'Cart updated successfully!';
    header('location: cart.php');
    exit();
}

if(isset($_GET['delete'])) {
    $cart_id = $_GET['delete'];
    
    if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
        // Guest user - remove from session cart
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = array_values(array_diff($_SESSION['cart'], [$cart_id]));
        }
    } else {
        // Logged in user - remove from database cart
        try {
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
            $delete_cart->execute([$cart_id]);
        } catch(PDOException $e) {
            $_SESSION['warning_msg'] = 'Error removing item: ' . $e->getMessage();
        }
    }
    
    $_SESSION['success_msg'] = 'Item removed from cart!';
    header('location: cart.php');
    exit();
}
?>
