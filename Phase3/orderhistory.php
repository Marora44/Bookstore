<?php
require_once "../config.php";
$orderHistory = array();
$tempOrders = array();

$userID = isset($_POST['id']) ? $_POST['id'] : "7";

$result = mysqli_query($dbConnect,"SELECT id, orderDate FROM bookorder WHERE userID = {$userID} AND isPlaced = TRUE");
$currentID = 0;
while ($idRow = mysqli_fetch_assoc($result)) {
    $order = array();
    $order['id'] = $idRow['id'];
    $order['date'] = $idRow['orderDate'];
    $totalPrice = 0.00;
    $orderInfo = mysqli_query($dbConnect,"SELECT * FROM bookorder WHERE id = {$order['id']}");
    while($orderRow = mysqli_fetch_assoc($orderInfo)){
        $bookInfo = mysqli_query($dbConnect, "SELECT price, quantity FROM book WHERE isbn = \"{$orderRow['isbn']}\"");
        $bookRow = mysqli_fetch_assoc($bookInfo);
        $totalPrice += $orderRow['quantity'] * $bookRow['price'];
    }
    $order['total'] = $totalPrice;
    $tempOrders[] = $order;
}

$lastAdded = 0;
foreach($tempOrders as $row){
    if($row['id'] != $lastAdded) $orderHistory[] = $row;
    $lastAdded = $row['id'];
}

echo json_encode($orderHistory);
?>