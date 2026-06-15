<?php
include 'components/connect.php';

try {
    // Create order_items table
    $conn->exec("CREATE TABLE IF NOT EXISTS `order_items` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `order_id` VARCHAR(255) NOT NULL,
        `product_id` INT NOT NULL,
        `qty` INT NOT NULL,
        `price` DECIMAL(10,2) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Add updated_at column to orders table if not exists
    $conn->exec("ALTER TABLE `orders` 
                ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL 
                AFTER `date`");

    echo "Order items table created successfully!";
} catch(PDOException $e) {
    echo "Error creating order_items table: " . $e->getMessage();
}
?>
