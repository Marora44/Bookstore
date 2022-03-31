<?php

session_start();

//testing
//$_SESSION['id'] = 1;

require_once "config.php";

if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
} else die("something went wrong");

$payID = 0;
$ccno = $expMo = $expY = $cvv = $bAddress = $bCity = $bState = $sZip = $sAddress = $sCity = $sState = $sZip = "";
$enterPay = $entership = true;


// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     if(isset($_POST['checkout'])){
//         $ccno = $_POST['ccno'];
//         $expMo = substr($payEnter['expDate'], 5, 2);
//         $expY = substr($_POST['expDate'],2,2);
//         $cvv = $_POST['cvv'];
//         if(isset)
//     }
// }

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

        fieldset {
            border: 0;
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
            <?php

            $storedMethodsPay = mysqli_query($dbConnect, "SELECT paymentID FROM storedPay WHERE userID = {$userID}");
            if (mysqli_num_rows($storedMethodsPay) > 0) :
                $payMethods = mysqli_query($dbConnect, "SELECT * FROM paymentinfo WHERE id in (SELECT paymentID FROM storedPay WHERE userID = {$userID})")
            ?>
                <td>
                    <form method="POST">
                        Use a Stored Payment Method: <select name=selectedmethod>
                            <?php
                            while ($row = mysqli_fetch_assoc($payMethods)) {
                                $selected = $payID == $payMethods['id'] ? "selected" : "";
                                $nums = substr($row['cc'], 11);
                                echo "<option {$selected} value = \"{$row['id']}\">Card ending in ** {$nums}</option>\n";
                            }
                            mysqli_free_result($payMethods);
                            ?>
                        </select>
                        <input type="submit" name="pickPay" value="Use this Payment">
                        <input type="submit" name="enterPay" value="Enter manually">
                    </form>
                </td>
                <td></td>
            <?php
            endif;
            $payEnter = $enterPay ? "" : "disabled";
            mysqli_free_result($storedMethodsPay);

            $storedMethodsship = mysqli_query($dbConnect, "SELECT addressID FROM storedship WHERE userID = {$userID}");
            if (mysqli_num_rows($storedMethodsship) > 0) :
                $shipMethods = mysqli_query($dbConnect, "SELECT * FROM addressinfo WHERE id in (SELECT addressID FROM storedship WHERE userID = {$userID})")
            ?>
                <td>
                    <form method="POST">
                        Use a Stored Shipping Address: <select name=selectedmethod>
                            <?php
                            while ($row = mysqli_fetch_assoc($shipMethods)) {
                                $selected = $shipID == $shipMethods['id'] ? "selected" : "";
                                echo "<option {$selected} value = \"{$row['id']}\">{$row['street']},{$row['city']},{$row['state']}</option>\n";
                            }
                            mysqli_free_result($shipMethods);
                            ?>
                        </select>
                        <input type="submit" name="pickship" value="Use this Address">
                        <input type="submit" name="entership" value="Enter manually">
                    </form>
                </td>

            <?php
            endif;
            $shipEnter = $entership ? "" : "disabled";
            mysqli_free_result($storedMethodsship);
            ?>
        </tr>
        <tr style="text-align: left;">
            <td>
                <form id="checkout" method="POST">
                    <fieldset <?= $payEnter ?>>
                        Credit Card Number: <input type="text" name="ccno" pattern="\d{16}" required><br>
                        Expiration Date: <input type="month" required name="expDate"><br>
                        CCV: <input type="text" pattern="\d{3}" required><br><br>
                    </fieldset>
                </form>
            </td>
            <td>
                <fieldset form="checkout" <?= $payEnter ?>>
                    Address: <input type="text" name="bAddress" required><br>
                    City: <input type="text" name="bCity" required><br>
                    State: <input type="text" pattern="[a-zA-Z]{2}" name="bState" required><br>
                    Zip: <input type="text" pattern="\d{5}" name="bZip" required><br><br>
                    <input type="checkbox" name="savePay"> Save this Payment Method;
                </fieldset>
            </td>
            <td>
                <fieldset form="checkout" <?= $shipEnter ?>>
                    Address: <input type="text" name="sAddress" required><br>
                    City: <input type="text" name="sCity" required><br>
                    State: <input type="text" pattern="[a-zA-Z]{2}" name="sState" required><br>
                    Zip: <input type="text" pattern="\d{5}" name="bZip" required><br><br>
                    <input type="checkbox" name="savePay"> Save this Address;
                </fieldset>
                <input type="submit" form="checkout">
            </td>
        </tr>
    </table>

    </table>
</body>

</html>