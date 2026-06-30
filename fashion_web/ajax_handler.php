<?php
// ajax_handler.php
// Pure JSON endpoint for AJAX requests. No HTML output.

include 'components/connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear any buffers to ensure no accidental output
if (ob_get_length()) ob_clean();

// Check if it's a cart or wishlist request
if (isset($_POST['ajax_add_cart'])) {
    include 'components/add_cart.php';
} elseif (isset($_POST['ajax_add_wishlist'])) {
    include 'components/add_wishlist.php';
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
exit();
