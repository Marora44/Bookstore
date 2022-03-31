<?php

require_once "config.php";

session_start();

if ($_SESSION['userMode'] != 'admin') header("location: index.php");

?>

<html>
<?php
$headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                 <h3><p>Admin Landing Page</p></h3>";
include('header.php');
?>
<div style="text-align:center">
    <h1><a href="updateshipping.php">Update Shipping Prices</a></h1>
    <h1><a href="addBook.php">Add Book</a></h1>
    <h1><a href="addauthor.php">Add Author</a></h1>
</div>

</html>