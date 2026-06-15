<?php
include 'components/connect.php';

try {
    // Read the SQL file
    $sql = file_get_contents('create_orders_tables.sql');
    
    // Execute the SQL statements
    $conn->exec($sql);
    
    echo "Orders tables created successfully!";
} catch(PDOException $e) {
    echo "Error creating orders tables: " . $e->getMessage();
}
?>
