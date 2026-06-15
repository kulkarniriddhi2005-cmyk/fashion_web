<?php
include '../components/connect.php';

if(isset($_POST['register'])){
    $id = unique_id();

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $cpass = $_POST['cpass'];
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    // Check if password matches
    if($pass != $cpass){
        $warning_msg[] = 'Confirm password does not match';
    } else {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id() . '.' . $ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/' . $rename;

        // File validation (extension and size check)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed_extensions)) {
            $warning_msg[] = 'Only image files (jpg, jpeg, png, gif) are allowed';
        } elseif ($image_size > 5000000) { // Limit image size to 5MB
            $warning_msg[] = 'Image size is too large (max 5MB)';
        } else {
            // Check if email already exists
            $select_seller = $conn->prepare("SELECT * FROM sellers WHERE email=?");
            $select_seller->execute([$email]);

            if ($select_seller->rowCount() > 0) {
                $warning_msg[] = 'Email already exists';
            } else {
                // Insert new seller (without password hashing)
                $insert_seller = $conn->prepare("INSERT INTO sellers(id, name, email, password, image) VALUES(?, ?, ?, ?, ?)");
                $insert_seller->execute([$id, $name, $email, $pass, $rename]);

                // Move the uploaded file
                move_uploaded_file($image_tmp_name, $image_folder);
                $success_msg[] = 'New seller registered! Please login now.';
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
    <!-- box icon cdn link  -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <title>Zari & Co. | Seller Dashboard</title>
</head>

<body>
    <div class="form-container form">
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h3>Become a Zari & Co. Seller</h3>
            <p style="text-align: center; margin-bottom: 2rem; color: #666; font-size: 1rem;">
                Join our premium marketplace and showcase your exclusive ethnic and contemporary designs to thousands of fashion lovers across India. Partner with us today!
            </p>
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>Your Name<span>*</span></p>
                        <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Your Email<span>*</span></p>
                        <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
                    </div>
                </div>
                <div class="col">
                    <div class="input-field">
                        <p>Your Password<span>*</span></p>
                        <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Confirm Your Password<span>*</span></p>
                        <input type="password" name="cpass" placeholder="Confirm your password" maxlength="50" required class="box">
                    </div>
                </div>
            </div>
            <div class="input-field">
                <p>Select Profile<span>*</span></p>
                <input type="file" name="image" accept="image/*" required class="box">
            </div>
            <p class="link">Already have an account? <a href="login.php">Login now</a></p>
            <button class="btn" type="submit" name="register">Register Now</button>
        </form>
    </div>

    <!-- SweetAlert CDN link  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script type="text/javascript">
        <?php include 'script.js' ?>
    </script>
    <!-- Alert  -->
    <?php include '../components/alert.php'; ?>
</body>

</html>
