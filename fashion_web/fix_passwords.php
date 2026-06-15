<?php
include 'components/connect.php';

try {
    // Fix users table
    $select_users = $conn->query("SELECT id, password FROM users");
    while ($user = $select_users->fetch()) {
        // If password is not already a bcrypt hash (doesn't start with $2y$)
        if (!str_starts_with($user['password'], '$2y$')) {
            $new_hash = password_hash($user['password'], PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$new_hash, $user['id']]);
        }
    }

    // Fix sellers table
    $select_sellers = $conn->query("SELECT id, password FROM sellers");
    while ($seller = $select_sellers->fetch()) {
        // If password is not already a bcrypt hash (doesn't start with $2y$)
        if (!str_starts_with($seller['password'], '$2y$')) {
            $new_hash = password_hash($seller['password'], PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE sellers SET password = ? WHERE id = ?");
            $update->execute([$new_hash, $seller['id']]);
        }
    }

    echo "Passwords have been properly hashed. You can now login with your original passwords.";
} catch (PDOException $e) {
    echo "Error fixing passwords: " . $e->getMessage();
}
?>
