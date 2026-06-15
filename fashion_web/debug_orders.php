<?php
session_start();
include 'components/connect.php';

echo "<h1>Orders Debug Page</h1>";

// Display session information
echo "<h2>Session Information</h2>";
echo "<pre>";
echo "SESSION user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set') . "<br>";
echo "COOKIE user_id: " . (isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : 'Not set') . "<br>";
echo "SESSION guest_id: " . (isset($_SESSION['guest_id']) ? $_SESSION['guest_id'] : 'Not set') . "<br>";
echo "SESSION guest_order_id: " . (isset($_SESSION['guest_order_id']) ? $_SESSION['guest_order_id'] : 'Not set') . "<br>";
echo "</pre>";

// Get all orders from the database
echo "<h2>All Orders in Database</h2>";
try {
    $select_all_orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
    
    if ($select_all_orders->rowCount() > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>User ID</th><th>Name</th><th>Email</th><th>Total</th><th>Created At</th><th>Is Guest</th></tr>";
        
        while ($order = $select_all_orders->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($order['id']) . "</td>";
            echo "<td>" . htmlspecialchars($order['user_id']) . "</td>";
            echo "<td>" . htmlspecialchars($order['name']) . "</td>";
            echo "<td>" . htmlspecialchars($order['email']) . "</td>";
            echo "<td>$" . htmlspecialchars($order['total_price']) . "</td>";
            echo "<td>" . htmlspecialchars($order['created_at']) . "</td>";
            echo "<td>" . ($order['is_guest'] ? 'Yes' : 'No') . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No orders found in the database.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Check orders table structure
echo "<h2>Orders Table Structure</h2>";
try {
    $table_info = $conn->query("DESCRIBE orders");
    
    if ($table_info->rowCount() > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($column = $table_info->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Could not retrieve table structure.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
