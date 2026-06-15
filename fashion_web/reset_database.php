<?php
try {
    // Create initial connection without database
    $conn = new PDO("mysql:host=localhost", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS fashion_db");
    $conn->exec("USE fashion_db");
    
    // Drop existing tables
    $conn->exec("DROP TABLE IF EXISTS orders");
    $conn->exec("DROP TABLE IF EXISTS cart");
    $conn->exec("DROP TABLE IF EXISTS users");
    
    // Create tables
    $sql = file_get_contents('create_users_table.sql');
    $conn->exec($sql);
    echo "Users table created successfully!<br>";
    
    $sql = file_get_contents('create_orders_table.sql');
    $conn->exec($sql);
    echo "Orders table created successfully!<br>";
    
    $sql = file_get_contents('create_cart_table.sql');
    $conn->exec($sql);
    echo "Cart table created successfully!<br>";
    
    echo "All tables have been reset and recreated successfully!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
