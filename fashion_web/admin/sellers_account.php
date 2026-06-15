<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    $seller_id = '';
    header('location:login.php');
    exit();
}

// Fetch sellers from the database
$select_sellers = $conn->prepare("SELECT * FROM sellers");
$select_sellers->execute();

// Handle delete if the 'id' is passed in the URL
if (isset($_GET['id'])) {
    $seller_id_to_delete = $_GET['id']; // Get the seller ID from the URL

    echo "Raw ID from URL: " . $seller_id_to_delete . "<br>";

    // Check if the ID exists in the database
    $check_seller = $conn->prepare("SELECT id FROM sellers WHERE id = ?");
    $check_seller->execute([$seller_id_to_delete]);

    if ($check_seller->rowCount() == 0) {
        echo "Seller does not exist in DB!";
        exit();
    }

    // Proceed with deletion
    $delete_seller = $conn->prepare("DELETE FROM sellers WHERE id = ?");
    $delete_seller->execute([$seller_id_to_delete]);

    if ($delete_seller->rowCount() > 0) {
        header('Location: sellers_account.php?msg=deleted');
        exit();
    } else {
        echo "Deletion failed!";
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Sellers</title>
    <!-- box icon cdn link -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include '../components/admin_header.php'; ?>

    <div class="banner">
        <div class="detail">
            <h1>Sellers Accounts</h1>
            <span><a href="dashboard.php">Admin</a><i class="bx bxs-right-arrow-alt"></i>Sellers Accounts</span>
        </div>
    </div>

    <div class="line2"></div>

    <div class="container">
        <div class="heading">
            <h1>Registered Sellers</h1>
            <img src="../image/separator.png" alt="Separator">
        </div>

        <!-- Display success or error messages if any -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert">
                <?php
                if ($_GET['msg'] == 'deleted') {
                    echo "<p class='success'>Seller deleted successfully!</p>";
                } elseif ($_GET['msg'] == 'invalid_id') {
                    echo "<p class='error'>Invalid seller ID!</p>";
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Seller ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($select_sellers->rowCount() > 0) {
                        while ($fetch_sellers = $select_sellers->fetch(PDO::FETCH_ASSOC)) {
                            // Check if 'status' exists in the fetched data
                            $status = isset($fetch_sellers['status']) ? $fetch_sellers['status'] : 'Not specified';
                            ?>
                            <tr>
                                <td><?= $fetch_sellers['id']; ?></td>
                                <td><?= $fetch_sellers['name']; ?></td>
                                <td><?= $fetch_sellers['email']; ?></td>
                                <td><?= $status; ?></td>
                                <td>
                                <a href="edit_seller.php?id=<?= $fetch_sellers['id']; ?>" class="btn"
                                        onclick="return confirm('Are you sure you want to edit this seller?');">
                                        edit
                                    </a>
                                    <a href="sellers_account.php?id=<?= $fetch_sellers['id']; ?>" class="btn"
                                        onclick="return confirm('Are you sure you want to delete this seller?');">
                                        Delete
                                    </a>

                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="5">No sellers registered!</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Add Seller Button -->
        <div class="add-seller">
            <a href="register.php" class="btn">Add New Seller</a>
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
