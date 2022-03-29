<?php

session_start();

//testing
$_SESSION['id'] = 1;

require_once "config.php";

if(isset($_SESSION['id'])){
    $userID = $_SESSION['id'];
}
else die("something went wrong");

?>

<html>

<head>
    <title>Shopping Cart</title>
    <style>
        th {
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Your Cart</h1>
    <table width="70%">
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Quantity</th>
            <th>Price</th>

        </tr>
        <?php
        $cart = mysqli_query($dbConnect, "SELECT isbn,quantity FROM bookorder WHERE userID = {$userID} AND isPlaced = FALSE");
        $totalPrice = 0.00;
        while ($cartRow = mysqli_fetch_assoc($cart)) :
            $bookInfo = mysqli_query($dbConnect, "SELECT title, price FROM book WHERE isbn = \"{$cartRow['isbn']}\"");
            $bookRow = mysqli_fetch_assoc($bookInfo);
            $totalPrice += $cartRow['quantity'] * $bookRow['price'];
        ?>
            <tr>
                <td><?= $cartRow['isbn'] ?></td>
                <td><?= $bookRow['title'] ?></td>
                <td><?= $cartRow['quantity'] ?></td>
                <td>$<?= $cartRow['quantity'] * $bookRow['price'] ?></td>
            </tr>
        <?php
        
        endwhile;
        mysqli_free_result($cart);
        mysqli_free_result($bookInfo);
        ?>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: right;font-weight: bold;">TOTAL:&nbsp;</td>
            <td>$<?= $totalPrice ?></td>
        </tr>
    </table>
</body>

</html>