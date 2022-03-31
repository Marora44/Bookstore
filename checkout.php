<?php

session_start();

//testing
$_SESSION['id'] = 1;

require_once "config.php";

if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
} else die("something went wrong");

/*  
    todo:
    add checkout button
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

}

?>

<html>

<head>
    <title>Shopping Cart</title>
    <style>
        th,
        td {
            padding-left: 30px;
            padding-right: 30px;
            text-align: left;
        }
    </style>
</head>

<body>
    <?php
    /*     $headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                 <h3><p>Your Cart</p></h3>";
    include('header.php'); */
    ?>
    <table style="table-layout: auto;">
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Quantity</th>
            <th>Type</th>
            <th>Price</th>
        </tr>
        <?php
        $cart = mysqli_query($dbConnect, "SELECT id, isbn, quantity, isDigital FROM bookorder WHERE userID = {$userID} AND isPlaced = FALSE");
        $totalPrice = 0.00;
        while ($cartRow = mysqli_fetch_assoc($cart)) :
            $bookInfo = mysqli_query($dbConnect, "SELECT title, price, quantity FROM book WHERE isbn = \"{$cartRow['isbn']}\"");
            $bookRow = mysqli_fetch_assoc($bookInfo);
            $totalPrice += $cartRow['quantity'] * $bookRow['price'];
            if (!$cartRow['isDigital']) :
        ?>
                <tr>
                    <td><?= $cartRow['isbn'] ?></td>
                    <td><?= $bookRow['title'] ?></td>
                    <td><?= $cartRow['quantity'] ?></td>
                    <td>Physical</td>
                    <td>$<?= $cartRow['quantity'] * $bookRow['price'] ?></td>
                </tr>
            <?php
            endif;
            mysqli_free_result($bookInfo);
            if ($cartRow['isDigital']) :
            ?>
                <tr>
                    <td><?= $cartRow['isbn'] ?></td>
                    <td><?= $bookRow['title'] ?></td>
                    <td><?= $cartRow['quantity'] ?></td>
                    <td>Digital</td>
                    <td>$<?= $cartRow['quantity'] * $bookRow['price'] ?></td>
                </tr>
        <?php
            endif;
        endwhile;
        mysqli_free_result($cart);
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right;font-weight: bold;">TOTAL:&nbsp;</td>
            <td>$<?= $totalPrice ?></td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <table style="width:100%">
    <tr style="text-align: left;">
        <th style="padding-left: 0px;">Payment Info</th>
        <th style="padding-left: 0px;">Billing Info</th>
        <th style="padding-left: 0px;">Shipping Info</th>
    </tr>
    <tr style="text-align: left;">
        <td><form>
            Credit Card Number: <input type="text" name="ccno" pattern="\d{16}" required><br>
            Expiration Date: <input type="month" required name="expDate"><br>
            CCV: <input type="text" pattern="\d{3}" required><br>
            <input type="submit">
        </form></td>
        <td><form>
            Address: <input type="text" name="bAddress" required><br>
            City: <input type="text" name="bCity" required><br>
            State: <input type="text" pattern="[a-zA-Z]{2}" name="bState" required><br>
            Zip: <input type="text" pattern="\d{5}" name="bZip" required><br>
            <input type="submit">
        </form></td>
        <td><form>
            Address: <input type="text" name="sAddress" required><br>
            City: <input type="text" name="bCity" required><br>
            State: <input type="text" pattern="[a-zA-Z]{2}" name="sState" required><br>
            Zip: <input type="text" pattern="\d{5}" name="sZip" required><br>
            <input type="submit">
        </form></td>
    </tr>
    </table>

    </table>
</body>

</html>