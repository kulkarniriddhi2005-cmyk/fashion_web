<?php
include 'components/connect.php';

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

include 'components/add_wishlist.php';
include 'components/add_cart.php';

$search_term = '';
if (isset($_POST['search_product'])) {
    $search_term = trim($_POST['search_product']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Zari & Co.</title>
    <link rel="icon" type="image/png" href="image/logo (1).png">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>Search Results</h1>
        <p>Find your perfect style from our collection.</p>
        <span>
            <a href="home.php">Home</a>
            <i class="bx bxs-right-arrow-alt"></i>
            Search
        </span>
    </div>
</div>

<div class="line2"></div>

<div class="products">
    <div class="heading">
        <h1><?= $search_term ? 'Results for "' . htmlspecialchars($search_term) . '"' : 'Search Products'; ?></h1>
        <img src="image/separator.png" alt="Separator">
    </div>

    <div class="box-container">
        <?php
        if ($search_term !== '') {
            $like = '%' . $search_term . '%';
            $select_products = $conn->prepare("SELECT * FROM products WHERE status = ? AND (name LIKE ? OR category LIKE ? OR product_detail LIKE ?)");
            $select_products->execute(['active', $like, $like, $like]);

            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
                    <form action="" method="post" class="box">
                        <div class="icon">
                            <div class="icon-box">
                                <img src="uploaded_files/<?= htmlspecialchars($fetch_products['thumb_one']); ?>" class="img1" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
                            </div>
                        </div>

                        <?php if ($fetch_products['stock'] > 9) { ?>
                            <span class="stock" style="color: green;">In Stock</span>
                        <?php } elseif ($fetch_products['stock'] == 0) { ?>
                            <span class="stock" style="color: red;">Out of Stock</span>
                        <?php } else { ?>
                            <span class="stock" style="color: orange;">Only <?= (int)$fetch_products['stock']; ?> Left!</span>
                        <?php } ?>

                        <p class="price">&#8377; <?= number_format($fetch_products['price'], 2); ?>/-</p>

                        <div class="content">
                            <h3><?= htmlspecialchars($fetch_products['name']); ?></h3>
                            <div class="action-icons">
                                <button type="submit" name="add_to_cart" value="<?= htmlspecialchars($fetch_products['id']); ?>" title="Add to Cart" <?= ($fetch_products['stock'] == 0) ? 'disabled' : ''; ?>>
                                    <i class="bx bx-cart"></i>
                                </button>
                                <button type="submit" name="add_to_wishlist" value="<?= htmlspecialchars($fetch_products['id']); ?>" title="Add to Wishlist">
                                    <i class="bx bx-heart"></i>
                                </button>
                                <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="view-icon" title="View Details">
                                    <i class="bx bxs-show"></i>
                                </a>
                            </div>

                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($fetch_products['id']); ?>">

                            <div class="flex-btn">
                                <a href="checkout.php?get_id=<?= htmlspecialchars($fetch_products['id']); ?>" class="btn" <?= ($fetch_products['stock'] == 0) ? 'style="pointer-events: none; opacity: 0.5;"' : ''; ?>>Buy Now</a>
                                <input type="number" name="qty" required min="1" value="1" max="<?= (int)$fetch_products['stock']; ?>" class="qty" <?= ($fetch_products['stock'] == 0) ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                    </form>
        <?php
                }
            } else {
                echo '<div class="empty"><p>No products found matching your search. <a href="shop1.php">Browse all products</a></p></div>';
            }
        } else {
            echo '<div class="empty"><p>Enter a search term using the header search bar.</p></div>';
        }
        ?>
    </div>
</div>

<?php include 'components/user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js?v=<?= time(); ?>"></script>
<?php include 'components/alert.php'; ?>

</body>
</html>
