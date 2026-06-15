<?php

$db_name = 'mysql:host=localhost;dbname=fashion_db';
$user_name = 'root';
$user_password = '';

try {
    $conn = new PDO($db_name, $user_name, $user_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (!function_exists('unique_id')) {
    function unique_id($l = 13) {
        return substr(md5(uniqid(mt_rand(), true)), 0, $l);
    }
}

// ... other connection related code ...
?>