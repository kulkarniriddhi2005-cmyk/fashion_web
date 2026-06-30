<?php
include 'components/connect.php';

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
$pid = trim($_GET['pid'] ?? '');

include 'components/add_wishlist.php';
include 'components/add_cart.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <title>Product Details | Zari & Co.</title>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>Product Description</h1>
        <p>Discover unique styles and curated fashion.</p>
        <span><a href="home.php">Home</a><i class="bx bxs-right-arrow-alt"></i>Product Details</span>
    </div>
</div>

<div class="line2"></div>

<div class="heading">
    <span>Product Description</span>
    <h1>We are passionate about making beautiful more beautiful</h1>
    <img src="image/separator.png" alt="Separator">
</div>

<div class="view_page">
<?php
if ($pid) {
    $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $select_product->execute([$pid]);

    if ($select_product->rowCount() > 0) {
        while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {
?>
    <form action="" method="post" class="product-display box">
        <div class="product-images">
            <div class="main-image">
                <img id="featuredImage" src="uploaded_files/<?= htmlspecialchars($fetch_product['thumb_one']); ?>" alt="<?= htmlspecialchars($fetch_product['name']); ?>">
            </div>
            <div class="thumbnails">
                <img src="uploaded_files/<?= htmlspecialchars($fetch_product['thumb_one']); ?>" onclick="document.getElementById('featuredImage').src=this.src" alt="Thumbnail 1">
                <img src="uploaded_files/<?= htmlspecialchars($fetch_product['thumb_two']); ?>" onclick="document.getElementById('featuredImage').src=this.src" alt="Thumbnail 2">
                <img src="uploaded_files/<?= htmlspecialchars($fetch_product['thumb_three']); ?>" onclick="document.getElementById('featuredImage').src=this.src" alt="Thumbnail 3">
            </div>
        </div>

        <div class="product-info">
            <?php if ($fetch_product['stock'] > 9) { ?>
                <span class="stock" style="color: green;">In stock</span>
            <?php } elseif ($fetch_product['stock'] == 0) { ?>
                <span class="stock" style="color: red;">Out of stock</span>
            <?php } else { ?>
                <span class="stock" style="color: red;">Hurry, only <?= (int)$fetch_product['stock']; ?> left!</span>
            <?php } ?>

            <h2><?= htmlspecialchars($fetch_product['name']); ?></h2>
            <p class="price" style="font-size:1.5rem;color:var(--main-color);margin-bottom:1rem;">&#8377; <?= number_format($fetch_product['price'], 2); ?>/-</p>
            <p class="product-description"><?= nl2br(htmlspecialchars($fetch_product['product_detail'])); ?></p>

            <input type="hidden" name="product_id" value="<?= htmlspecialchars($fetch_product['id']); ?>">
            <input type="hidden" name="qty" value="1">

            <div class="button-group">
                <button type="submit" name="add_to_wishlist" value="<?= htmlspecialchars($fetch_product['id']); ?>" class="btn1">Add To Wishlist</button>
                <button type="submit" name="add_to_cart" value="<?= htmlspecialchars($fetch_product['id']); ?>" class="btn1" <?= ($fetch_product['stock'] == 0) ? 'disabled' : ''; ?>>Add To Cart</button>
            </div>
        </div>
    </form>

    <div class="product">
        <div class="heading">
            <h1>Similar Products</h1>
            <p>Explore more styles you may like</p>
            <img src="image/separator.png" alt="Separator">
        </div>

        <?php include 'components/homeshop.php'; ?>

        <div class="more">
            <a href="shop1.php" class="btn">View All Products</a>
        </div>
    </div>

<?php
        }
    } else {
        echo '<div class="empty"><p>Product not found!</p></div>';
    }
} else {
    echo '<div class="empty"><p>No product selected!</p></div>';
}
?>
</div>

<?php include 'components/user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js?v=<?= time(); ?>"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>
