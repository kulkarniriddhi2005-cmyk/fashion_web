<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'components/connect.php';

function isCartEmpty($conn, $user_id = null) {
    if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
        // Guest user - check session cart
        return !isset($_SESSION['cart']) || empty($_SESSION['cart']);
    } else {
        // Logged in user - check database cart
        $user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
        $select_cart = $conn->prepare("SELECT COUNT(*) FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);
        return $select_cart->fetchColumn() == 0;
    }
}

// Get user ID if logged in
$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;

// Check if cart is empty
if (isCartEmpty($conn, $user_id)) {
    $_SESSION['warning_msg'][] = 'Your cart is empty!';
    header('location: cart.php');
    exit();
}
?>
