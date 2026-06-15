<?php
include 'components/connect.php';

try {
    // Create database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS fashion_db");
    $conn->exec("USE fashion_db");
    
    // Read and execute the SQL files for all tables
    $sql = file_get_contents('create_users_table.sql');
    $conn->exec($sql);
    
    $sql = file_get_contents('create_orders_table.sql');
    $conn->exec($sql);
    
    $sql = file_get_contents('create_cart_table.sql');
    $conn->exec($sql);
    
    echo "Database and tables created successfully!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
