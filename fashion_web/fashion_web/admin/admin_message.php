<?php
    include '../components/connect.php';

    if (isset($_COOKIE['seller_id'])) {
        $seller_id = $_COOKIE['seller_id'];
    } else {
        $seller_id = '';
        header('location:login.php');
    }

    if (isset($_POST['delete'])) {
        $delete_id = $_POST['delete_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

        // Verify if the message exists
        $verify_delete = $conn->prepare("SELECT * FROM `contact_messages` WHERE id = ?");
        $verify_delete->execute([$delete_id]);

        if ($verify_delete->rowCount() > 0) {
            // Delete the message
            $delete_msg = $conn->prepare("DELETE FROM `contact_messages` WHERE id = ?");
            $delete_msg->execute([$delete_id]);
            $success_msg[] = 'Message deleted';
        } else {
            $warning_msg[] = 'Message already deleted';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- box icon cdn link -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <title>Admin - View Messages</title>
</head>
<body>
    <?php include '../components/admin_header.php'; ?>
    
    <div class="banner">
        <div class="detail">
            <h1>Messages</h1>
            <p>Here you can view and manage the messages submitted through the contact form.</p>
            <span><a href="dashboard.php">Admin</a><i class="bx bxs-right-arrow-alt"></i>Messages</span>
        </div>
    </div>

    <div class="line2"></div>

    <div class="message-container">
        <div class="heading">
            <h1>Contact Form Messages</h1>
            <img src="../image/separator.png" alt="Separator">
        </div>

        <div class="box-container">
            <?php
            // Fetch all contact messages
            $select_msg = $conn->prepare("SELECT * FROM `contact_messages` ORDER BY `created_at` DESC");
            $select_msg->execute();

            if ($select_msg->rowCount() > 0) {
                while ($fetch_msg = $select_msg->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <h3 class="name">Name: <?= htmlspecialchars($fetch_msg['name']); ?></h3>
                        <h4>Email: <?= htmlspecialchars($fetch_msg['email']); ?></h4>
                        <h4>Subject: <?= htmlspecialchars($fetch_msg['subject']); ?></h4>
                        <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($fetch_msg['message'])); ?></p>
                        <p><strong>Submitted on:</strong> <?= $fetch_msg['created_at']; ?></p>
                        <form action="" method="post">
                            <input type="hidden" name="delete_id" value="<?= $fetch_msg['id']; ?>">
                            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this message?');" class="btn">Delete Message</button>
                        </form>
                    </div>
            <?php  
                }
            } else {
                echo '
                    <div class="empty">
                        <p>No messages found.</p>
                    </div>
                ';
            }
            ?>
        </div>
    </div>

    <?php include '../components/admin_footer.php'; ?>

    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script type="text/javascript">
        <?php include '../js/admin_script.js'; ?>
    </script>

    <!-- alert -->
    <?php include '../components/alert.php'; ?>

</body>
</html>
