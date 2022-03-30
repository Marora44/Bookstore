<?php

session_start();

require_once "config.php";

if ($_SESSION['userMode'] != 'account') header("location: index.php");

$becomeMemberISBN = "become_member";
$id = $_SESSION['id'];

    //check if the user has an active cart (unplaced order)
    $checkOrders = mysqli_query($dbConnect, "SELECT id FROM bookorder WHERE userID = {$id} AND isPlaced = FALSE");
    //set the $orderID to the correct ID if a cart exists or creates an appropriate one if not
    if (mysqli_num_rows($checkOrders) > 0) $orderID = mysqli_fetch_assoc($checkOrders)['id'];
    else {
        $highestID = mysqli_query($dbConnect, "SELECT MAX(id) maxID FROM bookorder");
        if (mysqli_num_rows($highestID) < 1) $orderID = 1;
        else $orderID = mysqli_fetch_assoc($highestID)['maxID'] + 1;
    }
    mysqli_free_result($checkOrders);

    mysqli_query($dbConnect, "INSERT INTO bookorder(id,isbn,quantity,userID,isDigital,isPlaced) VALUES({$orderID},\"{$becomeMemberISBN}\",{$quantity},{$id},TRUE,FALSE)");