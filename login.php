<?php
include 'components/connect.php';

if (isset($_POST['login'])) {
    // Sanitize and trim the email and password inputs
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $pass = trim($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Fetch the user from the database
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE LOWER(email) = LOWER(?) LIMIT 1");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    // Check if the email exists
    if ($select_user->rowCount() > 0) {
        // Compare the passwords (sha1 hashed in this system)
        $hashed_pass = sha1($pass);
        if ($pass === $row['password'] || $hashed_pass === $row['password']) {
            // Password is correct, set the cookie and redirect to home
            setcookie('user_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
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

    <!-- Optional: custom JS -->
    <script type="text/javascript">
        <?php include 'js/script.js'; ?>
    </script>

    <!-- Alert message handler -->
    <?php include 'components/alert.php'; ?>

</body>
</html>
