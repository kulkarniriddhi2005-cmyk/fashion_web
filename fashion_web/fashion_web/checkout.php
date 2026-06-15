<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
}

if(isset($_POST['place_order'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);
    $address_type = $_POST['address_type'];
    $address_type = filter_var($address_type, FILTER_SANITIZE_STRING);
    $flat = $_POST['flat'];
    $flat = filter_var($flat, FILTER_SANITIZE_STRING);
    $street = $_POST['street'];
    $street = filter_var($street, FILTER_SANITIZE_STRING);
    $city = $_POST['city'];
    $city = filter_var($city, FILTER_SANITIZE_STRING);
    $country = $_POST['country'];
    $country = filter_var($country, FILTER_SANITIZE_STRING);
    $pincode = $_POST['pincode'];
    $pincode = filter_var($pincode, FILTER_SANITIZE_STRING);

    $address = $flat .', '. $street .', '. $city .', '. $country .' - '. $pincode;

    $total_products = '';
    $grand_total = 0;

    if (!empty($user_id)) {
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);
        if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                $select_products->execute([$fetch_cart['product_id']]);
                $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                $total_products .= $fetch_products['name'].' ('.$fetch_cart['qty'].') - ';
                $grand_total += ($fetch_products['price'] * $fetch_cart['qty']);
            }
        }
    } else {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id) {
                $select_products = $conn->prepare("SELECT * FROM products WHERE id=?");
                $select_products->execute([$product_id]);
                if ($select_products->rowCount() > 0) {
                    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                    $total_products .= $fetch_products['name'].' (1) - ';
                    $grand_total += $fetch_products['price'];
                }
            }
        }
    }

    $total_products = rtrim($total_products, ' - ');

    $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $cart_query->execute([$user_id]);
    $cart_total = $cart_query->rowCount();

    if($cart_total > 0){

        $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, address_type) VALUES(?,?,?,?,?,?,?,?,?)");
        $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $grand_total, $address_type]);

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        $message[] = 'order placed successfully!';
    }else{
        $message[] = 'your cart is empty';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="slick.css" />
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <title>checkout</title>
</head>
<body>
    <?php include 'components/user_header.php'; ?>
    <div class="banner">
        <div class="detail">
            <h1>checkout</h1>
            <p>Lorem djofgighighigf</p>
            <span><a href="dashboard.php">home</a><i class="bx bx-right-arrow-alt"></i> contact us</span>
        </div>
    </div>
    <div class="line2"></div>
    <div class="checkout">
        <div class="heading">
            <h1>checkout summary</h1>
            <img src="image/separator.png">
        </div>
        <div class="row">
            <div class="form-container">
                <form action="" method="post" class="register">
                    <input type="hidden" name="p_id" value="<?= $get_id; ?>">
                    <h3>billing details</h3>
                    <div class="flex">
                        <div class="col">
                            <div class="input-field">
                                <p>Your name <span>*</span></p>
                                <input type="text" name="name" placeholder="enter your name" maxlength="50" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>Your number<span>*</span></p>
                                <input type="number" name="number" placeholder="enter your number" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>Your email<span>*</span></p>
                                <input type="email" name="email" placeholder="enter your email" maxlength="50" required>
                            </div>
                            <div class="input-field">
                                <p>payment status<span>*</span></p>
                                <select name="method" class="box">
                                    <option value="cash on delivery">cash on delivery</option>
                                    <option value="credit or debit card">credit or debit card</option>
                                    <option value="net banking">net banking</option>
                                    <option value="upi or rupay">upi or rupay</option>
                                    <option value="paytm">paytm</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <p>address type<span>*</span></p>
                                <select name="address_type" class="box">
                                    <option value="home">home</option>
                                    <option value="office">office</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-field">
                                <p>address line 01<span>*</span></p>
                                <input type="text" name="flat" placeholder="e.g flate & building" maxlength="50" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>address line 02<span>*</span></p>
                                <input type="text" name="street" placeholder="e.g street name" maxlength="50" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>city name<span>*</span></p>
                                <input type="text" name="city" placeholder="enter city name" maxlength="50" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>country name<span>*</span></p>
                                <input type="text" name="country" placeholder="enter country name" maxlength="50" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>pincode<span>*</span></p>
                                <input type="number" name="pincode" placeholder="e.g 111111" maxlength="6" class="box" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="place_order" class="btn">place order</button>
                </form>
            </div>
            <div class="summary">
                <h3>my bag</h3>
                <div class="box-container">
                    <?php
                    $grant_total = 0;
                    if (isset($_GET['get_id'])) {
                        // ... (Your get_id logic remains the same) ...
                    } else {
                        if (!empty($user_id)) {
                            // User is logged in, fetch from database
                            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id=?");
                            $select_cart->execute([$user_id]);
                            if ($select_cart->rowCount() > 0) {
                                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                                    $select_products = $conn->prepare("SELECT  * FROM `products` WHERE id=?");
                                    $select_products->execute([$fetch_cart['product_id']]);
                                    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                                    $sub_total = ($fetch_cart['qty'] * $fetch_products['price']);
                                    $grant_total += $sub_total;
                                    ?>
                                    <div class="flex">
                                        <img src="uploaded_files/<?= $fetch_products['thumb_one']; ?>" class="image">
                                        <div>
                                            <h3 class="name"><?= $fetch_products['name']; ?></h3>
                                            <p class="price">$<?= $fetch_products['price']; ?> X <?= $fetch_cart['qty']; ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<div class="empty"><p>No product added yet!</p></div>';
                            }
                        } else {
                            // User is not logged in, fetch from session
                            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $product_id) {
                                    $select_products = $conn->prepare("SELECT * FROM products WHERE id=?");
                                    $select_products->execute([$product_id]);
                                    if ($select_products->rowCount() > 0) {
                                        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                                        $grant_total += $fetch_products['price'];
                                        ?>
                                        <div class="flex">
                                            <img src="uploaded_files/<?= $fetch_products['thumb_one']; ?>" class="image">
                                            <div>
                                                <h3 class="name"><?= $fetch_products['name']; ?></h3>
                                                <p class="price">$<?= $fetch_products['price']; ?></p>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            } else {
                                echo '<div class="empty"><p>No product added yet!</p></div>';
                            }
                        }
                    }
                    ?>
                </div>
                <div class="grant-total">
                    <span>total amount payable:</span>$<?= $grant_total; ?>/-
                </div>
            </div>
        </div>
    </div>
    <?php include 'components/user_footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/user_script.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>