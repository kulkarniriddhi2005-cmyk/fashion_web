<?php
include 'components/connect.php';

try {
    // Update users table
    $select_users = $conn->query("SELECT id, password FROM users");
    while ($user = $select_users->fetch()) {
        if (strlen($user['password']) === 40) { // Length of SHA1 hash
            // First get the original password by comparing with SHA1
            $select_original = $conn->prepare("SELECT password FROM users WHERE id = ? AND password = ?");
            $select_original->execute([$user['id'], $user['password']]);
            
            if ($select_original->rowCount() > 0) {
                // Now hash the original password with password_hash
                $new_hash = password_hash('123456', PASSWORD_DEFAULT); // Reset to default password 123456
                $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->execute([$new_hash, $user['id']]);
            }
        }
    }

    // Update sellers table
    $select_sellers = $conn->query("SELECT id, password FROM sellers");
    while ($seller = $select_sellers->fetch()) {
        if (strlen($seller['password']) === 40) { // Length of SHA1 hash
            // First get the original password by comparing with SHA1
            $select_original = $conn->prepare("SELECT password FROM sellers WHERE id = ? AND password = ?");
            $select_original->execute([$seller['id'], $seller['password']]);
            
            if ($select_original->rowCount() > 0) {
                // Now hash the original password with password_hash
                $new_hash = password_hash('123456', PASSWORD_DEFAULT); // Reset to default password 123456
                $update = $conn->prepare("UPDATE sellers SET password = ? WHERE id = ?");
                $update->execute([$new_hash, $seller['id']]);
            }
        }
    }

    echo "All passwords have been reset to '123456'. Please login with this password and change it immediately.";
} catch (PDOException $e) {
    echo "Error updating password hashing: " . $e->getMessage();
}
?>
