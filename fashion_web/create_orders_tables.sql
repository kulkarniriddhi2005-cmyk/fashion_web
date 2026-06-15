-- Create orders table if it doesn't exist
CREATE TABLE IF NOT EXISTS `orders` (
  `id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `order_details` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `address_type` varchar(20) NOT NULL DEFAULT 'home',
  `order_status` varchar(20) NOT NULL DEFAULT 'processing',
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `is_guest` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
