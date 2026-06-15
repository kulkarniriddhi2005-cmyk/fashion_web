<?php
include 'components/connect.php';

try {
    // Add is_guest column to orders table
    $conn->exec("ALTER TABLE `orders` 
                ADD COLUMN IF NOT EXISTS `is_guest` TINYINT(1) NOT NULL DEFAULT 0");

    echo "Orders table updated successfully!";
} catch(PDOException $e) {
    echo "Error updating orders table: " . $e->getMessage();
}
?>
