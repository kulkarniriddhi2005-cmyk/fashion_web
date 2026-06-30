<?php
include 'components/connect.php';

if (isset($_POST['login'])) {
    // Sanitize and trim the email input
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $pass = $_POST['pass'];

    // Fetch the user from the database
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE LOWER(email) = LOWER(?) LIMIT 1");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    // Check if the email exists
    if ($select_user->rowCount() > 0) {
        // Check password (supports new password_hash or old sha1 fallback)
        $hashed_pass = sha1($pass);
        $is_valid_password = false;
        
        if (password_verify($pass, $row['password'])) {
            $is_valid_password = true;
        } elseif ($pass === $row['password'] || $hashed_pass === $row['password']) {
            $is_valid_password = true;
            // Rehash password with new algorithm
            $new_hash = password_hash($pass, PASSWORD_DEFAULT);
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$new_hash, $row['id']]);
        }

        if ($is_valid_password) {
            setcookie('user_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $product_id) {
                    $product_id = trim($product_id);
                    if ($product_id === '') continue;

                    $check = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
                    $check->execute([$row['id'], $product_id]);
                    if ($check->rowCount() === 0) {
                        $price_stmt = $conn->prepare("SELECT price FROM products WHERE id = ? LIMIT 1");
                        $price_stmt->execute([$product_id]);
                        $price_row = $price_stmt->fetch(PDO::FETCH_ASSOC);
                        if ($price_row) {
                            $conn->prepare("INSERT INTO cart (id, user_id, product_id, price, qty) VALUES (?, ?, ?, ?, ?)")
                                ->execute([unique_id(), $row['id'], $product_id, $price_row['price'], '1']);
                        }
                    }
                }
                $_SESSION['cart'] = [];
            }

            if (!empty($_SESSION['wishlist'])) {
                foreach ($_SESSION['wishlist'] as $product_id) {
                    $product_id = trim($product_id);
                    if ($product_id === '') continue;

                    $check = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
                    $check->execute([$row['id'], $product_id]);
                    if ($check->rowCount() === 0) {
                        $price_stmt = $conn->prepare("SELECT price FROM products WHERE id = ? LIMIT 1");
                        $price_stmt->execute([$product_id]);
                        $price_row = $price_stmt->fetch(PDO::FETCH_ASSOC);
                        if ($price_row) {
                            $conn->prepare("INSERT INTO wishlist (id, user_id, product_id, price) VALUES (?, ?, ?, ?)")
                                ->execute([unique_id(), $row['id'], $product_id, $price_row['price']]);
                        }
                    }
                }
                $_SESSION['wishlist'] = [];
            }

            header('location:home.php');
            exit;
        } else {
            $warning_msg[] = 'Incorrect email or password';
        }
    } else {
        $warning_msg[] = 'Incorrect email or password';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Zari & Co.</title>

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">

    <!-- Custom CSS with cache busting -->
    <link rel="stylesheet" type="text/css" href="css/admin_style.css?v=<?php echo time(); ?>">
</head>
<body>

    <div class="form-container form">
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3>Login Now</h3>

            <div class="input-field">
                <p>Your Email <span>*</span></p>
                <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
            </div>

            <div class="input-field">
                <p>Your Password <span>*</span></p>
                <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
            </div>

            <p class="link">Do not have an account? <a href="register.php">Register now</a></p>
            <button class="btn" type="submit" name="login">Login Now</button>
        </form>
    </div>

    <!-- SweetAlert CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- Custom JS -->
    <script src="js/user_script.js?v=<?= time(); ?>"></script>

    <!-- Alert message handler -->
    <?php include 'components/alert.php'; ?>

</body>
</html>
