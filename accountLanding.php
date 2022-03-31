<?php

require_once "config.php";

session_start();

if ($_SESSION['userMode'] != 'account' AND $_SESSION['userMode'] != 'member' AND $_SESSION['userMode'] != 'admin') header("location: index.php");

?>

<html>
<?php
$headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                 <h3><p>Account Landing Page</p></h3>";
include('header.php');
?>
<div style="text-align:center">
    <h1><a href="storeMain.php">View Books</a></h1>
    <h1>or</h1>
    <h1><a href="show_orders.php">View Order History</a></h1>
</div>
<div style="text-align:center">
    <h3><a href="accountManage.php">Update Account Info</a><h3> 
    <h3><a href="shippingManage.php">Update Shipping Info</a><h3>
    <h3><a href="paymentManage.php">Update Payment Info</a><h3>
</div>

</html>