<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    header('location:login.php');
    exit();
}

// Check if ID is provided in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location: sellers_account.php?msg=invalid_id');
    exit();
}

$edit_id = $_GET['id'];

// Fetch seller details to prefill form
$select_seller = $conn->prepare("SELECT * FROM sellers WHERE id = ?");
$select_seller->execute([$edit_id]);

if ($select_seller->rowCount() === 0) {
    header('location: sellers_account.php?msg=seller_not_found');
    exit();
}

$seller = $select_seller->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email  = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

    // Update query
    $update = $conn->prepare("UPDATE sellers SET name = ?, email = ?, status = ? WHERE id = ?");
    $update->execute([$name, $email, $status, $edit_id]);

    header('location: sellers_account.php?msg=updated');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Seller</title>
    <link rel="stylesheet" href="../css/admin_style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="container1">
    <h2>Edit Seller</h2>

    <form action="" method="POST" class="form">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($seller['name']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($seller['email']) ?>" required>

        <label>Status:</label>
        <input type="text" name="status" value="<?= htmlspecialchars($seller['status']) ?>">

        <button type="submit" class="btn">Update Seller</button>
    </form>

    <br>
    <a href="sellers_account.php" class="btn1">Back to Seller List</a>
</div>

<?php include '../components/admin_footer.php'; ?>
</body>
</html>
