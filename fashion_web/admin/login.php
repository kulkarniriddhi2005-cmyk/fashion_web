<?php
include '../components/connect.php';

if (isset($_POST['login'])) {
    // Sanitize and trim the email and password inputs
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $pass = trim($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Fetch the user from the database
    $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE LOWER(email) = LOWER(?) LIMIT 1");
    $select_seller->execute([$email]);
    $row = $select_seller->fetch(PDO::FETCH_ASSOC);

    // Check if the email exists
    if ($select_seller->rowCount() > 0) {
        // Compare the passwords (no hashing here)
        if ($pass === $row['password']) {
            // Password is correct, set the cookie and redirect to dashboard
            setcookie('seller_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
            header('location:dashboard.php');
            exit;
        } else {
            // Incorrect password
            $warning_msg[] = 'Incorrect email or password';
        }
    } else {
        // No user found with that email
        $warning_msg[] = 'Incorrect email or password';
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- box icon cdn link -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <title>Login - Zari & Co. Seller Dashboard</title>
</head>
<body>
    <div class="form-container form">
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3>Seller Login</h3>
            <p style="text-align: center; margin-bottom: 2rem; color: #666; font-size: 1rem;">
                Welcome back to Zari & Co. Seller Dashboard. Manage your products, track orders, and grow your fashion business.
            </p>

            <div class="input-field">
                <p>Your Email<span>*</span></p>
                <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
            </div>

            <div class="input-field">
                <p>Your Password<span>*</span></p>
                <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
            </div>

            <p class="link">Do not have an account? <a href="register.php">Register now</a></p>
            <button class="btn" type="submit" name="login">Login Now</button>
        </form>
    </div>

    <!-- SweetAlert CDN link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script type="text/javascript">
        <?php include 'script.js' ?>
    </script>

    <!-- alert -->
    <?php include '../components/alert.php'; ?>
</body>
</html>
