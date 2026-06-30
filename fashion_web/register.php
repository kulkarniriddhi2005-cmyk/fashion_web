<?php
include 'components/connect.php';

if (isset($_POST['register'])) {
    $id = unique_id();

    $name = $_POST['name'];
    $name = htmlspecialchars($name);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    if ($pass != $cpass) {
        $warning_msg[] = 'Confirm password does not match';
    } else {
        $image = $_FILES['image']['name'];
        $image = htmlspecialchars($image);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id() . '.' . $ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_files/' . $rename;

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($ext), $allowed_extensions)) {
            $warning_msg[] = 'Only image files (jpg, jpeg, png, gif) are allowed';
        } elseif ($image_size > 5000000) {
            $warning_msg[] = 'Image size is too large (max 5MB)';
        } else {
            $select_user = $conn->prepare("SELECT * FROM users WHERE email=?");
            $select_user->execute([$email]);

            if ($select_user->rowCount() > 0) {
                $warning_msg[] = 'Email already exists';
            } else {
                $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
                $insert_user = $conn->prepare("INSERT INTO users(id, name, email, password, image) VALUES(?, ?, ?, ?, ?)");
                $insert_user->execute([$id, $name, $email, $hashed_pass, $rename]);

                move_uploaded_file($image_tmp_name, $image_folder);
                $success_msg[] = 'New user registered! Please login now.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Zari & Co.</title>

    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/admin_style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="form-container form">
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h3>Register Now</h3>
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>Your Name <span>*</span></p>
                        <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Your Email <span>*</span></p>
                        <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
                    </div>
                </div>
                <div class="col">
                    <div class="input-field">
                        <p>Your Password <span>*</span></p>
                        <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Confirm Your Password <span>*</span></p>
                        <input type="password" name="cpass" placeholder="Confirm your password" maxlength="50" required class="box">
                    </div>
                </div>
            </div>
            <div class="input-field">
                <p>Select Profile <span>*</span></p>
                <input type="file" name="image" accept="image/*" required class="box">
            </div>
            <p class="link">Already have an account? <a href="login.php">Login now</a></p>
            <button class="btn" type="submit" name="register">Register Now</button>
        </form>
    </div>

    <!-- SweetAlert CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script src="js/user_script.js?v=<?= time(); ?>"></script>

    <!-- Alert -->
    <?php include 'components/alert.php'; ?>
</body>

</html>
