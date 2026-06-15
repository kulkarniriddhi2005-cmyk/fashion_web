-- Drop existing table if it exists
DROP TABLE IF EXISTS `orders`;

-- Create orders table with all necessary fields
CREATE TABLE `orders` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `order_products` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `address_type` varchar(50) NOT NULL,
  `order_status` varchar(20) NOT NULL DEFAULT 'processing',
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `is_guest` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
