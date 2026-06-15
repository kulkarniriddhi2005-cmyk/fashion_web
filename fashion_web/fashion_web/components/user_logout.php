<?php
include 'connect.php';

setcookie('user_id', '', time() - 3600, '/');
header('location:../home.php');
exit;
?>
