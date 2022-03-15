<?php
$serverAddress = "localhost";
$username = "root";
$password = "";

$dbConnect = mysqli_connect($serverAddress, $username, $password) or die("Could not connect: " . mysqli_error($dbConnect));

$bookstore = mysqli_select_db($dbConnect, 'Bookstore') or die('Could not select database');

?>