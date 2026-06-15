<?php
try {
    // Create database connection
    $conn = new PDO("mysql:host=localhost;dbname=fashion_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Setting up new orders table...</h2>";
    
    // Read and execute the SQL file
    $sql = file_get_contents('new_orders_table.sql');
    
    // Show the SQL that will be executed
    echo "<h3>SQL to be executed:</h3>";
    echo "<pre>" . htmlspecialchars($sql) . "</pre><br>";
    
    // Execute the SQL
    $conn->exec($sql);
    echo "Orders table created successfully!<br>";
    
    // Verify the table structure
    $result = $conn->query("DESCRIBE orders");
    echo "<h3>New Orders Table Structure:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        foreach($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    
    // Show indexes
    $result = $conn->query("SHOW INDEX FROM orders");
    echo "<h3>Table Indexes:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Key Name</th><th>Column Name</th><th>Index Type</th></tr>";
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Key_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Column_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Index_type']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch(PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "</div>";
}
?>
