<?php
include 'components/connect.php';

try {
    $conn->exec("USE fashion_db");
    
    // Check orders table structure
    $result = $conn->query("DESCRIBE orders");
    echo "<h2>Orders Table Structure:</h2>";
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "Field: " . $row['Field'] . ", Type: " . $row['Type'] . "<br>";
    }
    
    // Check cart table structure
    $result = $conn->query("DESCRIBE cart");
    echo "<h2>Cart Table Structure:</h2>";
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "Field: " . $row['Field'] . ", Type: " . $row['Type'] . "<br>";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
