<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'components/connect.php';

// Get user ID
$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;

if(isset($_POST['place_order'])) {
    // Get form data
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $method = $_POST['method'];
    $address = $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pincode'];
    
    try {
        // Insert into orders table
        $insert_order = $conn->prepare("INSERT INTO `orders` (name, number, email, method, address) VALUES (?,?,?,?,?)");
        $insert_order->execute([
            $name,
            $number,
            $email,
            $method,
            $address
        ]);
        
        // Clear cart if user is logged in
        if($user_id) {
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);
        }
        
        // Return success response
        echo json_encode(['status' => 'success', 'message' => 'Order placed successfully']);
        
    } catch(PDOException $e) {
        // Return error response
        echo json_encode(['status' => 'error', 'message' => 'Could not place order']);
    }
    exit();
}
