<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    header('location:login.php');
    exit;
}

// Fetch the seller data
$select_seller = $conn->prepare("SELECT * FROM sellers WHERE id = ? LIMIT 1");
$select_seller->execute([$seller_id]);
$fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);

// Ensure the data is fetched properly
if ($fetch_seller === false) {
    // Handle error if seller data is not found
    echo "Seller data not found!";
    exit; // Stop execution
}

$prev_pass = $fetch_seller['password']; // Previously stored plain text password
$prev_image = $fetch_seller['image'];

if (isset($_POST['update'])) {
    $name = $_POST['name'] ?? '';
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $email = $_POST['email'] ?? '';
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Updating name
    if (!empty($name)) {
        $update_name = $conn->prepare("UPDATE sellers SET name = ? WHERE id = ?");
        $update_name->execute([$name, $seller_id]);
        $success_msg[] = 'Username updated successfully';
    }

    // Updating email
    if (!empty($email)) {
        $select_email = $conn->prepare("SELECT * FROM sellers WHERE id != ? AND email = ?");
        $select_email->execute([$seller_id, $email]);

        if ($select_email->rowCount() > 0) {
            $warning_msg[] = 'Email already exists';
        } else {
            $update_email = $conn->prepare("UPDATE sellers SET email = ? WHERE id = ?");
            $update_email->execute([$email, $seller_id]);
            $success_msg[] = 'Email updated successfully';
        }
    }

    // Handling image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = uniqid() . '.' . $ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/' . $rename;

        if ($image_size > 2000000) {
            $warning_msg[] = 'Image size is too large';
        } else {
            // Update the image in the database
            $update_image = $conn->prepare("UPDATE sellers SET image = ? WHERE id = ?");
            $update_image->execute([$rename, $seller_id]);
            move_uploaded_file($image_tmp_name, $image_folder);

            // Delete the old image if it exists and is different from the new image
            if ($prev_image != '' && $prev_image != $rename) {
                unlink('../uploaded_files/' . $prev_image); // Delete old image
            }
            $success_msg[] = 'Image uploaded successfully';
        }
    }

    // Handling password change (without hashing)
    $old_pass = $_POST['old_pass'] ?? '';
    $new_pass = $_POST['new_pass'] ?? '';
    $cpass = $_POST['cpass'] ?? '';

    if (!empty($old_pass)) {
        // Trim the old password to avoid accidental spaces
        $old_pass = trim($old_pass);

        // Compare the old password directly (no hashing)
        if ($old_pass !== $prev_pass) {
            $warning_msg[] = 'Old password not matched';
        } elseif ($new_pass != $cpass) {
            $warning_msg[] = 'Confirm password does not match';
        } elseif (!empty($new_pass)) {
            // Update the password without hashing
            $update_pass = $conn->prepare("UPDATE sellers SET password = ? WHERE id = ?");
            $update_pass->execute([$new_pass, $seller_id]);
            $success_msg[] = 'Password updated successfully';
        } else {
            $warning_msg[] = 'Please enter a new password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <title>Update Profile - Zari & Co. Seller Dashboard</title>
</head>
<body>

<div class="banner">
    <div class="detail">
        <h1>Update Profile</h1>
        <p>Update your profile information and preferences.<br>Change your password or profile picture.</p>
        <span><a href="dashboard.php">Admin</a><i class="bx bxs-right-arrow-alt"></i>Update Profile</span>
    </div>
</div>
<div class="line2"></div>
<div class="form-container">
    <form action="" method="post" enctype="multipart/form-data" class="register">
        <div class="img-box">
            <img src="../uploaded_files/<?= htmlspecialchars($fetch_seller['image']); ?>" alt="Profile Image">
        </div>
        <h3>Update Profile</h3>
        <div class="flex">
            <div class="col">
                <div class="input-field">
                    <p>Your Name<span>*</span></p>
                    <input type="text" name="name" value="<?= htmlspecialchars($fetch_seller['name']); ?>" maxlength="50" class="box">
                </div>
                <div class="input-field">
                    <p>Your Email<span>*</span></p>
                    <input type="email" name="email" value="<?= htmlspecialchars($fetch_seller['email']); ?>" maxlength="50" class="box">
                </div>
                <div class="input-field">
                    <p>Select Profile<span>*</span></p>
                    <input type="file" name="image" accept="image/*" class="box">
                </div>
            </div>
            <div class="col">
                <div class="input-field">
                    <p>Old Password<span>*</span></p>
                    <input type="password" name="old_pass" placeholder="Enter your old password" maxlength="50" class="box">
                </div>
                <div class="input-field">
                    <p>New Password<span>*</span></p>
                    <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="50" class="box">
                </div>
                <div class="input-field">
                    <p>Confirm Your Password<span>*</span></p>
                    <input type="password" name="cpass" placeholder="Confirm your password" maxlength="50" class="box">
                </div>
            </div>
        </div>
        <button class="btn" type="submit" name="update">Update Profile</button>
    </form>
</div>

<?php include '../components/admin_footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
    <?php include 'script.js'; ?>
</script>
<?php include '../components/alert.php'; ?>

</body>
</html>
