<?php
include 'components/connect.php';

try {
    // Check if orders table exists
    $result = $conn->query("SHOW TABLES LIKE 'orders'");
    if($result->rowCount() > 0) {
        echo "Orders table exists<br>";
        
        // Show table structure
        $result = $conn->query("DESCRIBE orders");
        echo "<h3>Orders Table Structure:</h3>";
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "Field: " . $row['Field'] . ", Type: " . $row['Type'] . "<br>";
        }
        
        // Show any orders in the table
        $result = $conn->query("SELECT * FROM orders");
        echo "<h3>Orders in Table:</h3>";
        if($result->rowCount() > 0) {
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "Order ID: " . $row['id'] . ", User ID: " . $row['user_id'] . 
                     ", Total: $" . $row['total_price'] . ", Status: " . $row['status'] . "<br>";
            }
        } else {
            echo "No orders found in table<br>";
        }
    } else {
        echo "Orders table does not exist!<br>";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
