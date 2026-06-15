<?php
// This is a component — included directly inside home.php
// No separate HTML/DOCTYPE needed

if (!isset($conn)) {
    include 'components/connect.php';
}
?>

<div class="popular-brands">
    <div class="controls" style="display:flex; justify-content:flex-end; padding: 0 2rem 1rem;">
        <i class='bx bx-chevron-left left' style="font-size:2rem; cursor:pointer; color:var(--main-color);"></i>
        <i class='bx bx-chevron-right right' style="font-size:2rem; cursor:pointer; color:var(--main-color);"></i>
    </div>

    <div class="popular-brands-content">
        <?php
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE status = ?");
        $select_products->execute(['active']);

        if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <form action="" method="post" class="box">
                    <div class="icon-box">
                        <img src="uploaded_files/<?= htmlspecialchars($fetch_products['thumb_one']); ?>" class="img1" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
                        <div class="price-tag">&#8377; <?= number_format($fetch_products['price'], 2); ?>/-</div>
                    </div>

                    <h3 class="product-name"><?= htmlspecialchars($fetch_products['name']); ?></h3>

                    <div class="button-icons">
                        <button type="submit" name="add_to_cart" value="<?= $fetch_products['id']; ?>" <?php if ($fetch_products['stock'] == 0) echo 'disabled title="Out of Stock"'; ?>>
                            <i class="bx bx-cart"></i>
                        </button>
                        <button type="submit" name="add_to_wishlist" value="<?= $fetch_products['id']; ?>">
                            <i class="bx bx-heart"></i>
                        </button>
                        <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" title="View Product">
                            <i class="bx bxs-show"></i>
                        </a>
                    </div>

                    <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">

                    <div class="flex-btn">
                        <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn" <?php if ($fetch_products['stock'] == 0) echo 'style="pointer-events:none;opacity:0.5;"'; ?>>Buy Now</a>
                        <input type="number" name="qty" required min="1" value="1" max="<?= (int)$fetch_products['stock']; ?>" class="qty" <?php if ($fetch_products['stock'] == 0) echo 'disabled'; ?>>
                    </div>
                </form>
        <?php
            }
        } else {
            echo '<div class="empty"><p>No products available yet. Check back soon!</p></div>';
        }
        ?>
    </div>
</div>