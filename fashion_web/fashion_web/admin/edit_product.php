<?php
include '../components/connect.php';

// Check if the form is submitted for deletion
if (isset($_POST['delete'])) {
    // Get the seller ID from the form
    $seller_id = $_POST['seller_id'];
    $seller_id = filter_var($seller_id, FILTER_SANITIZE_NUMBER_INT); // Ensure it's a valid integer

    if ($seller_id) {
        // Fetch seller details to check if there are any associated files to delete (like a profile picture)
        $fetch_seller = $conn->prepare("SELECT * FROM sellers WHERE id = ?");
        $fetch_seller->execute([$seller_id]);
        $seller = $fetch_seller->fetch(PDO::FETCH_ASSOC);

        // If seller exists, proceed to delete
        if ($seller) {
            // Check if the seller has a profile image, and delete it if exists
            if (!empty($seller['profile_image'])) {
                $image_path = '../uploaded_files/' . $seller['profile_image'];
                if (file_exists($image_path)) {
                    unlink($image_path);  // Delete the profile image file
                }
            }

            // Now delete the seller from the database
            $delete_seller = $conn->prepare("DELETE FROM sellers WHERE id = ?");
            $delete_seller->execute([$seller_id]);

            // After deletion, redirect back to the sellers list page with a success message
            header('Location: sellers_account.php?msg=deleted');
            exit();
        } else {
            // If the seller doesn't exist, show an error message
            header('Location: sellers_account.php?msg=no_seller_found');
            exit();
        }
    } else {
        // If the seller ID is invalid, show an error message
        header('Location: sellers_account.php?msg=invalid_id');
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Sellers</title>
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
                    } elseif ($_GET['msg'] == 'no_seller_found') {
                        echo "<p class='error'>Seller not found!</p>";
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
                    // Fetch all sellers from the database
                    $select_sellers = $conn->prepare("SELECT * FROM sellers");
                    $select_sellers->execute();

                    if ($select_sellers->rowCount() > 0) {
                        while ($fetch_sellers = $select_sellers->fetch(PDO::FETCH_ASSOC)) {
                            $status = isset($fetch_sellers['status']) ? $fetch_sellers['status'] : 'Not specified';
                            ?>
                            <tr>
                                <td><?= $fetch_sellers['id']; ?></td>
                                <td><?= $fetch_sellers['name']; ?></td>
                                <td><?= $fetch_sellers['email']; ?></td>
                                <td><?= $status; ?></td>
                                <td>
                                    <a href="edit_seller.php?id=<?= $fetch_sellers['id']; ?>" class="btn">Edit</a>
                                    <!-- Delete form to delete the seller -->
                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this seller?');">
                                        <input type="hidden" name="seller_id" value="<?= $fetch_sellers['id']; ?>">
                                        <button type="submit" name="delete" class="btn">Delete</button>
                                    </form>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript">
        <?php include '../js/admin_script.js'; ?>
    </script>

    <!-- alert -->
    <?php include '../components/alert.php'; ?>
</body>

</html>
