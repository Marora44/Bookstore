<?php

session_start();

require_once "config.php";

if ($_SESSION['userMode'] != 'account') header("location: index.php");

$orderID = 0;
$becomeMemberISBN = "become_member";
$id = $_SESSION['id'];
$order_exists_query = "SELECT id FROM BookOrder WHERE userID = $id AND isPlaced = FALSE";
$result = mysqli_query($dbConnect, $order_exists_query);

    //set the $orderID to the correct ID if a cart exists or creates an appropriate one if not
    if (mysqli_num_rows($result) > 0) {
        $orderID = mysqli_fetch_assoc($result)['id'];
    }
    else {
        $highestID = mysqli_query($dbConnect, "SELECT MAX(id) maxID FROM bookorder");
        if ($highestID == NULL) {$highestID = 0;}
        if (mysqli_num_rows($highestID) < 1) $orderID = 1;
        else $orderID = mysqli_fetch_assoc($highestID)['maxID'] + 1;
    }
    mysqli_free_result($result);
    
    $query = "INSERT INTO bookorder(id,isbn,quantity,userID,isDigital,isPlaced) VALUES({$orderID},\"{$becomeMemberISBN}\",1,{$id},TRUE,FALSE)";

    mysqli_query($dbConnect, $query);

    header("Location: shoppingcart.php");
    exit();
?>