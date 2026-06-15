<?php
include '../components/connect.php';
if(isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    $seller_id = ''; // Corrected comment syntax
    header('location:login.php');
    exit(); // Stop execution after redirect
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
    <?php include '../components/admin_header.php';?>
    
    <div class="banner">
        <div class="detail">
            <h1>Registered Users</h1>
            <p>View and manage registered customer accounts.<br>Monitor activity and account details.</p>
            <span><a href="dashboard.php">Admin</a><i class="bx bxs-right-arrow-alt"></i>Registered Users</span>
        </div>
    </div>

    <div class="line2"></div>

    <div class="user-container">
        <div class="heading">
            <h1>Registered Users</h1>
            <img src="../image/separator.png">
        </div>
        <div class="box-container">
            <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            
            if($select_users->rowCount() > 0) {
                while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
                    $image_path = "../uploaded_files/" . $fetch_users['image'];
                    ?>
                    <div class="box">
                        <?php if (file_exists($image_path)) { ?>
                            <img src="<?= $image_path; ?>" alt="User Image">
                        <?php } else { ?>
                            <p>No image available</p>
                        <?php } ?>
                        
                        <div class="detail">
                            <p>User ID: <?= $fetch_users['id']; ?></p>
                            <p>User Name: <?= $fetch_users['name']; ?></p>
                            <p>User Email: <?= $fetch_users['email']; ?></p>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '
                    <div class="empty">
                        <p>No users registered yet!</p>
                    </div>
                ';
            }
            ?>
        </div>
    </div>

    <?php include '../components/admin_footer.php';?>

    <!-- Sweetalert CDN link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script type="text/javascript">
        <?php include '../js/admin_script.js'; ?>
    </script>
    
    <!-- Alert -->
    <?php include '../components/alert.php'; ?>
</body>
</html>
