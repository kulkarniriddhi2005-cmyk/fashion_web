<?php
	include '../components/connect.php';
	if(isset($_COOKIE['seller_id'])) {
		$seller_id = $_COOKIE['seller_id'];
	} else {
		$seller_id = '';
		header('location:login.php');
	}
	if(isset($_POST['update_order'])){
		$order_id = $_POST['order_id'];
		$order_id = filter_var($order_id, FILTER_SANITIZE_STRING);
		$update_payment = $_POST['update_payment'];
		$update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
	    $update_pay = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
	    $update_pay->execute([$update_payment, $order_id]);
		$success_msg[] = 'order payment_status updated';

	
	}

	// Delete message logic
	if(isset($_POST['delete'])){
		$delete_id = $_POST['order_id'];
		$delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
		$verify_delete = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
		$verify_delete->execute([$delete_id]);
		if($verify_delete->rowCount() > 0){
			$delete_msg = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
			$delete_msg->execute([$delete_id]);
			$success_msg[] = 'order deleted';
		} else {
			$warning_msg[] = 'order already deleted';
		}
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
			<h1>My Orders</h1>
			<p>Track and manage all customer orders.<br>Update order status and handle fulfillment.</p>
			<span><a href="dashboard.php">Admin</a><i class="bx bxs-right-arrow-alt"></i>My Orders</span>
		</div>
	</div>

	<div class="line2"></div>

	<div class="order-container">
		<div class="heading">
			<h1>Total orders placed</h1>
			<img src="../image/separator.png">
		</div>
		<div class="box-container">
			<?php
			// Corrected SQL query with seller_id condition
			$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
			$select_orders->execute([$seller_id]);
		
			if($select_orders->rowCount() > 0) {
				while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
			?>
				<div class="box">
       <div class="status" style="color: <?php if($fetch_orders['status'] == 'in progress'){echo "limegreen";}else{echo "red";} ?>"><?= $fetch_orders['status']; ?>
                </div>
                <div class="detail">
                    <p>User name : <span><?= $fetch_orders['name']; ?></span></p>
                    <p>User id : <span><?= $fetch_orders['user_id']; ?></span></p>
                    <p>placed on : <span><?= $fetch_orders['date']; ?></span></p>
                    <p>number : <span><?= $fetch_orders['number']; ?></span></p>
                    <p>total price : <span><?= $fetch_orders['price']; ?></span></p>
                    <p>payment method : <span><?= $fetch_orders['method']; ?></span></p>
                    <p>address : <span><?= $fetch_orders['address']; ?></span></p>
                </div>
				<form action="" method="post">
					<input type="hidden" name="order_id" value="><?= $fetch_orders['user_id']; ?>">
					<select name="updated_payment" class="box" style="width:90%;">
						<option select disable><?= $fetch_orders['payment_status']; ?></option>
						<option value="pending">pending </option>
						<option value="confirm">confirm </option>
				</select>
				<div class="flex-btn">
					<button type="submit" name="update_order" class="btn">update order</button>
					
				<button type="submit" name="delete" onclick="return confirm('want to delete this message ');" class="btn">delete order</button>
							</div>

		
				</form>
				

				</div>
			<?php  
				}
			} else {
				echo '
					<div class="empty">
						<p>No orders placed yet!</p>
					</div>
				';
			}
			?>
		</div>
	</div>

	<?php include '../components/admin_footer.php';?>

	<!-- sweetalert cdn link -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
	<script type="text/javascript">
		<?php include '../js/admin_script.js' ?>
	</script>
	<!-- alert -->
	<?php include '../components/alert.php'; ?>
</body>
</html>
