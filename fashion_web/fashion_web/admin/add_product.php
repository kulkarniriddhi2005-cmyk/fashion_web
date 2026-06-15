<?php
	include '../components/connect.php';
	if(isset($_COOKIE['seller_id'])) {
		$seller_id = $_COOKIE['seller_id'];
		}
		else {
			$seller_id = '';
			header('location:login.php');
		}
        //save product in database
        if(isset($_POST['publish'])){
            $id= unique_id();

            $name=$_POST['name'];
            $name=filter_var($name,FILTER_SANITIZE_STRING);

            $price=$_POST['price'];
            $price=filter_var($price,FILTER_SANITIZE_STRING);

            $content=$_POST['content'];
            $content=filter_var($content,FILTER_SANITIZE_STRING);

            $category=$_POST['category'];
            $category=filter_var($category,FILTER_SANITIZE_STRING);

            $stock=$_POST['stock'];
            $stock=filter_var($stock,FILTER_SANITIZE_STRING);
            $status='active';

            $thumb_one = $_FILES['thumb_one']['name'];
            $thumb_one = filter_var($thumb_one, FILTER_SANITIZE_STRING);
            $thumb_one_tmp_name = $_FILES['thumb_one']['tmp_name'];
            $thumb_one_folder = '../uploaded_files/' . $thumb_one;

            $thumb_two = $_FILES['thumb_two']['name'];
            $thumb_two = filter_var($thumb_two, FILTER_SANITIZE_STRING);
            $thumb_two_tmp_name = $_FILES['thumb_two']['tmp_name'];
            $thumb_two_folder = '../uploaded_files/' . $thumb_two;

            $thumb_three = $_FILES['thumb_three']['name'];
            $thumb_three = filter_var($thumb_three, FILTER_SANITIZE_STRING);
            $thumb_three_tmp_name = $_FILES['thumb_three']['tmp_name'];
            $thumb_three_folder = '../uploaded_files/' . $thumb_three;


            $insert_product = $conn->prepare("INSERT INTO `products`(id,seller_id,name,price,category,thumb_one,thumb_two,thumb_three,stock,product_detail,status) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            $insert_product->execute([$id,$seller_id,$name,$price,$category,$thumb_one,$thumb_two,$thumb_three,$stock,$content,$status]);
            
            move_uploaded_file($thumb_one_tmp_name,$thumb_one_folder);
            move_uploaded_file($thumb_two_tmp_name,$thumb_two_folder);
            move_uploaded_file($thumb_three_tmp_name,$thumb_three_folder);

            $success_msg[]='product inserted successfully';
        }


        //save product in database as draft
        if(isset($_POST['draft'])){
            $id= unique_id();

            $name=$_POST['name'];
            $name=filter_var($name,FILTER_SANITIZE_STRING);

            $price=$_POST['price'];
            $price=filter_var($price,FILTER_SANITIZE_STRING);

            $content=$_POST['content'];
            $content=filter_var($content,FILTER_SANITIZE_STRING);

            $category=$_POST['category'];
            $category=filter_var($category,FILTER_SANITIZE_STRING);

            $stock=$_POST['stock'];
            $stock=filter_var($stock,FILTER_SANITIZE_STRING);
            $status='deactive';

            $thumb_one = $_FILES['thumb_one']['name'];
            $thumb_one = filter_var($thumb_one, FILTER_SANITIZE_STRING);
            $thumb_one_tmp_name = $_FILES['thumb_one']['tmp_name'];
            $thumb_one_folder = '../uploaded_files/' . $thumb_one;

            $thumb_two = $_FILES['thumb_two']['name'];
            $thumb_two = filter_var($thumb_two, FILTER_SANITIZE_STRING);
            $thumb_two_tmp_name = $_FILES['thumb_two']['tmp_name'];
            $thumb_two_folder = '../uploaded_files/' . $thumb_two;

            $thumb_three = $_FILES['thumb_three']['name'];
            $thumb_three = filter_var($thumb_three, FILTER_SANITIZE_STRING);
            $thumb_three_tmp_name = $_FILES['thumb_three']['tmp_name'];
            $thumb_three_folder = '../uploaded_files/' . $thumb_three;


            $insert_product = $conn->prepare("INSERT INTO `products`(id,seller_id,name,price,category,thumb_one,thumb_two,thumb_three,stock,product_detail,status) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            $insert_product->execute([$id,$seller_id,$name,$price,$category,$thumb_one,$thumb_two,$thumb_three,$stock,$content,$status]);
            
            move_uploaded_file($thumb_one_tmp_name,$thumb_one_folder);
            move_uploaded_file($thumb_two_tmp_name,$thumb_two_folder);
            move_uploaded_file($thumb_three_tmp_name,$thumb_three_folder);

            $success_msg[]='product save as draft successfully';
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
				<h1>Add product</h1>
				<p>Add new products to your Zari & Co. store.<br>Upload images, set pricing, and manage inventory.</p>
				<span><a href="dashboard.php">admin</a><i class="bx bxs-right-arrow-alt"></i>Add product</span>
			</div>
		</div>

		<div class="line2"></div>


		<div class="add_product">
    <div class="heading">
        <h1>Add product</h1>
        <img src="../image/separator.png">
    </div>
        <div class="form-container">
            <form action="" method="post" enctype="multipart/form-data" class="register">
                <div class="flex">
                    <div class="col">
                        <div class="input-field">
                            <p>product name <span>*</span></p>
                            <input type="text" name="name" maxlength="100" placeholder="add product name" required class="box">
                        </div>
                        <div class="input-field">
                            <p>product price <span>*</span></p>
                            <input type="number" name="price" maxlength="100" placeholder="add product price" required class="box">
                        </div>
                        <div class="input-field">
                            <p>total stock <span>*</span></p>
                            <input type="number" name="stock" maxlength="10" min="0" max="9999999999" placeholder="add product stock" required class="box">
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-field">
                            <p>thumbnail one <span>*</span></p>
                            <input type="file" name="thumb_one" accept="image/*" required class="box">
                        </div>
                        <div class="input-field">
                            <p>thumbnail two <span>*</span></p>
                            <input type="file" name="thumb_two" accept="image/*" required class="box">
                        </div>
                        <div class="input-field">
                            <p>thumbnail three <span>*</span></p>
                            <input type="file" name="thumb_three" accept="image/*" required class="box">
                        </div>
                    </div>
                </div>
                <div class="input-field">
                    <p>product category <span>*</span></p>
                    <select name="category" required class="box">
                        <option disabled selected>select product category</option>
                        <option value="kids section">kids section</option>
                        <option value="men's section">men's section</option>
                        <option value="co-ordsets">co-ordsets</option>
                        <option value="bottoms">bottoms</option>
                        <option value="shirts and tops">shirts and tops</option>
                        <option value="western dresses">western dresses</option>
                        <option value="ethinic wears">ethinic wears</option>
                    </select>
                </div>
                <div class="input-field">
                    <p>product category <span>*</span></p>
                    <textarea class="box" name="content" required></textarea>
                </div>
                <div class="flex-btn">
                    <button type="submit" name="publish" class="btn">publish now</button>
                    <button type="submit" name="draft" class="btn">save as draft</button>
                </div>
            </form>
        </div>






    </div>

	<?php include '../components/admin_footer.php';?>

	<!-- sweetalert cdn link  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script type="text/javascript">
        <?php include '../js/admin_script.js' ?>
    </script>
	<!-- alert  -->
	<?php include '../components/alert.php'; ?>
</body>
</html>
