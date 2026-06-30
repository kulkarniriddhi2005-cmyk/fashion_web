<?php
include 'components/connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

// Handle AJAX Wishlist Removal
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "remove_wishlist") {
    $product_id  = trim($_POST['product_id'] ?? '');
    $wishlist_id = isset($_POST['wishlist_id']) ? trim($_POST['wishlist_id']) : null;

    $response = ["success" => false, "message" => "Invalid request"];

    if (!empty($user_id) && $wishlist_id) {
        $query = $conn->prepare("DELETE FROM wishlist WHERE id = ? AND user_id = ?");
        if ($query->execute([$wishlist_id, $user_id])) {
            $response = ["success" => true, "message" => "Product removed from wishlist"];
        } else {
            $response["message"] = "Error removing product";
        }
    } else {
        if (in_array($product_id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = array_values(array_diff($_SESSION['wishlist'], [$product_id]));
            $response = ["success" => true, "message" => "Product removed from wishlist"];
        } else {
            $response["message"] = "Product not found in wishlist";
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your wishlist at Zari & Co. — save your favourite Indian fashion pieces and shop them later.">
    <title>My Wishlist | Zari & Co.</title>
    <link rel="icon" type="image/png" href="image/logo (1).png">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Banner -->
<div class="banner">
    <div class="detail">
        <h1>My Wishlist</h1>
        <p>Your saved favourites — shop them anytime you're ready.</p>
        <span>
            <a href="home.php">Home</a>
            <i class="bx bxs-right-arrow-alt"></i>
            Wishlist
        </span>
    </div>
</div>

<div class="line2"></div>

<div class="wishlist-container">
    <div class="heading" style="margin-bottom: 2rem;">
        <h1>My Wishlist</h1>
    </div>

    <div class="wishlist-items">
        <?php
        if (!empty($user_id)) {
            $select_wishlist = $conn->prepare("SELECT wishlist.*, products.name, products.price, products.thumb_one FROM wishlist JOIN products ON wishlist.product_id = products.id WHERE wishlist.user_id = ?");
            $select_wishlist->execute([$user_id]);

            if ($select_wishlist->rowCount() > 0) {
                while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="wishlist-box" id="wishlist-item-<?= htmlspecialchars($fetch_wishlist['id']); ?>">
                        <img src="uploaded_files/<?= htmlspecialchars($fetch_wishlist['thumb_one']); ?>" alt="<?= htmlspecialchars($fetch_wishlist['name']); ?>">
                        <h3><?= htmlspecialchars($fetch_wishlist['name']); ?></h3>
                        <p>&#8377; <?= number_format($fetch_wishlist['price'], 2); ?></p>
                        <a href="view_page.php?pid=<?= $fetch_wishlist['product_id']; ?>" class="btn" style="display:block;margin-bottom:8px;">View Product</a>
                        <button type="button" class="remove-wishlist-btn"
                            data-wishlist-id="<?= htmlspecialchars($fetch_wishlist['id']); ?>"
                            data-product-id="<?= htmlspecialchars($fetch_wishlist['product_id']); ?>">
                            <i class="bx bx-trash"></i> Remove
                        </button>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="empty"><p>Your wishlist is empty! <a href="shop1.php">Explore our collection</a></p></div>';
            }
        } else {
            if (!empty($_SESSION['wishlist'])) {
                foreach ($_SESSION['wishlist'] as $product_id) {
                    $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
                    $select_products->execute([$product_id]);

                    if ($select_products->rowCount() > 0) {
                        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <div class="wishlist-box" id="wishlist-item-guest-<?= htmlspecialchars($product_id); ?>">
                            <img src="uploaded_files/<?= htmlspecialchars($fetch_products['thumb_one']); ?>" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
                            <h3><?= htmlspecialchars($fetch_products['name']); ?></h3>
                            <p>&#8377; <?= number_format($fetch_products['price'], 2); ?></p>
                            <a href="view_page.php?pid=<?= $product_id; ?>" class="btn" style="display:block;margin-bottom:8px;">View Product</a>
                            <button type="button" class="remove-wishlist-btn" data-product-id="<?= htmlspecialchars($product_id); ?>">
                                <i class="bx bx-trash"></i> Remove
                            </button>
                        </div>
                        <?php
                    }
                }
            } else {
                echo '<div class="empty"><p>Your wishlist is empty! <a href="shop1.php">Explore our collection</a></p></div>';
            }
        }
        ?>
    </div>
</div>

<?php include 'components/user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".remove-wishlist-btn").forEach(button => {
        button.addEventListener("click", function () {
            const wishlistId = this.getAttribute("data-wishlist-id");
            const productId  = this.getAttribute("data-product-id");
            const productBox = wishlistId
                ? document.getElementById("wishlist-item-" + wishlistId)
                : document.getElementById("wishlist-item-guest-" + productId);

            const formData = new FormData();
            formData.append("wishlist_id", wishlistId || "");
            formData.append("product_id",  productId);
            formData.append("action",      "remove_wishlist");

            fetch("wishlist.php", { 
                method: "POST", 
                body: formData,
                credentials: 'same-origin'
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (productBox) {
                            productBox.style.transition = "opacity 0.4s ease";
                            productBox.style.opacity = "0";
                            setTimeout(() => productBox.remove(), 400);
                        }
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(err => console.error("Error:", err));
        });
    });
});
</script>

<script src="js/user_script.js"></script>

</body>
</html>
