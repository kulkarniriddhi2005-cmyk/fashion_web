<?php
include 'components/connect.php';

$uid = $conn->query("SELECT id FROM users LIMIT 1")->fetchColumn();
$pid = $conn->query("SELECT id FROM products LIMIT 1")->fetchColumn();

if (!$uid || !$pid) {
    die("Need at least one user and product in DB\n");
}

$wid = unique_id();
$conn->prepare('INSERT INTO wishlist (id, user_id, product_id, price) VALUES (?,?,?,?)')
    ->execute([$wid, $uid, $pid, '10']);
echo "wishlist insert OK\n";
$conn->prepare('DELETE FROM wishlist WHERE id=?')->execute([$wid]);

$cid = unique_id();
$conn->prepare('INSERT INTO cart (id, user_id, product_id, price) VALUES (?,?,?,?)')
    ->execute([$cid, $uid, $pid, '10']);
echo "cart insert OK\n";
$conn->prepare('DELETE FROM cart WHERE id=?')->execute([$cid]);

echo "get_wishlist_count: " . get_wishlist_count($conn, $uid) . "\n";
echo "get_cart_count: " . get_cart_count($conn, $uid) . "\n";
