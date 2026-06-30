<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:login.php');
    exit();
}

$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id=?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id=?");
$select_orders->execute([$user_id]);
$total_orders = $select_orders->rowCount();

$select_message = $conn->prepare("SELECT * FROM `message` WHERE user_id=?");
$select_message->execute([$user_id]);
$total_message = $select_message->rowCount();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zari & Co. | My Account</title>

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">

    <!-- Custom CSS with cache busting -->
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <div class="banner">
        <div class="detail">
            <h1>My profile</h1>
            <p>View and manage your account details and order history.</p>
            <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>my profile</span>
        </div>
    </div>
    
    <div class="line2"></div>
    
    <div class="profile">
        <div class="heading">
            <h1>profile detail</h1>
            <img src="image/separator.png">
        </div>
        <div class="details">
            <div class="user">
                <img src="uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>">
                <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
                <p>user</p>
                <a href="update.php" class="btn">update profile</a>
            </div>
            
            <div class="box-container">
                <div class="box">
                    <div class="flex">
                        <h3> <i class="bx bxs-food-menu"></i> <?=$total_orders;?></h3>
                        <a href="order.php" class="btn">view orders</a>
                    </div>
                </div>
                <div class="box">
                    <div class="flex">
                        <h3><i class="bx bxs-chat"></i> <?=$total_message;?></h3>
                        <a href="contact.php" class="btn">send message</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'components/user_footer.php'; ?>

    <!-- SweetAlert CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script src="js/user_script.js?v=<?= time(); ?>"></script>

    <!-- Alert message handler -->
    <?php include 'components/alert.php'; ?>

</body>

</html>