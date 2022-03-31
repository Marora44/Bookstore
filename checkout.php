<?php

session_start();

//testing
//$_SESSION['id'] = 1;

require_once "config.php";

if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
//    echo $userID;
} else die("something went wrong");
$cartIDq = mysqli_query($dbConnect, "SELECT id FROM bookorder WHERE userID = {$userID} AND isPlaced = FALSE");
$_SESSION['cartID'] = mysqli_fetch_assoc($cartIDq)['id'];
if (isset($_SESSION['cartID'])) {
    $cartID = $_SESSION['cartID'];
} else die("something went wrong");

$payID = 0;
$ccno = $expMo = $expY = $cvv = $bAddress = $bCity = $bState = $bZip = $sAddress = $sCity = $sState = $sZip = "";
$enterPay = $entership = true;



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['checkout'])) {
        //print_r($_POST);
        $ccno = $_POST['ccno'];
        $expMo = substr($_POST['expDate'], 5, 2);
        $expY = substr($_POST['expDate'], 2, 2);
        $expDate = $expMo . "/" . $expY;
        $cvv = $_POST['cvv'];
        $bAddress = $_POST['bAddress'];
        $bCity = $_POST['bCity'];
        $bState = $_POST['bState'];
        $bZip = $_POST['bZip'];
        $sAddress = $_POST['sAddress'];
        $sCity = $_POST['sCity'];
        $sState = $_POST['sState'];
        $sState = $_POST['sZip'];
        $billingInsert = mysqli_query($dbConnect, "INSERT INTO addressinfo(street,city,state,zip) VALUES(\"{$bAddress}\",\"{$bCity}\",\"{$bState}\",\"{$bZip}\")");
        $getMaxAID = mysqli_query($dbConnect, "SELECT max(id) FROM addressinfo");
        $maxAID = mysqli_fetch_array($getMaxAID)[0];
        $payinsert = mysqli_query($dbConnect, "INSERT INTO paymentinfo(cc,cvv,expDate,billingID) VALUES(\"{$ccno}\",\"{$cvv}\",\"{$expDate}\",{$maxAID})");
        $getMaxSID = mysqli_query($dbConnect, "SELECT max(id) FROM paymentinfo");
        $maxSID = mysqli_fetch_array($getMaxSID)[0];
        mysqli_query($dbConnect, "INSERT INTO pays VALUES($cartID,$maxSID)");
        if (isset($_POST['savePay'])) mysqli_query($dbConnect, "INSERT INTO storedPay VALUES({$userID},{$maxSID})");
        $shippingInsert = mysqli_query($dbConnect, "INSERT INTO addressinfo(street,city,state,zip) VALUES(\"{$sAddress}\",\"{$sCity}\",\"{$sState}\",\"{$sZip}\")");
        $getMaxAID = mysqli_query($dbConnect, "SELECT max(id) FROM addressinfo");
        //mysqli_query($dbConnect, "INSERT INTO ships VALUES($cartID,$maxAID)");
        if (isset($_POST['saveShip'])) mysqli_query($dbConnect, "INSERT INTO storedShip VALUES({$userID},{$maxAID})");
        mysqli_query($dbConnect, "INSERT INTO buys VALUES($userID,$cartID)");
        mysqli_query($dbConnect, "UPDATE bookorder SET isPlaced = true WHERE id = {$cartID}");
        if(isset($_SESSION['become_mem'])) {
            mysqli_query($dbConnect, "UPDATE accountholder SET isMember = 1 WHERE userID = {$userID}");
            $_SESSION['userMode'] = "member";
            unset($_SESSION['become_mem']);
        }
        //header("location: shoppingcart.php");
    }
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

        fieldset {
            border: 0;
        }
    </style>
</head>

<body>
    <?php
    $headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
    <h3><p> Checkout:</p></h3>";
    include('header.php');
    require_once "config.php";

    ?>
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
            if($cartRow['isbn'] == "become_member") $_SESSION['become_mem'] = true;
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

            // $storedMethodsPay = mysqli_query($dbConnect, "SELECT paymentID FROM storedPay WHERE userID = {$userID}");
            // if (mysqli_num_rows($storedMethodsPay) > 0) :
            //     $payMethods = mysqli_query($dbConnect, "SELECT * FROM paymentinfo WHERE id in (SELECT paymentID FROM storedPay WHERE userID = {$userID})");
            ?>
            <!-- <td>
                    <form method="POST">
                        Use a Stored Payment Method: <select name=selectedmethod>
                            <?php
                            // while ($row = mysqli_fetch_assoc($payMethods)) {
                            //     print_r($row);
                            //     $selected = $payID == $payMethods['id'] ? "selected" : "";
                            //     $nums = substr($row['cc'], 11);
                            //     echo "<option {$selected} value = \"{$row['id']}\">Card ending in ** {$nums}</option>\n";
                            // }
                            // mysqli_free_result($payMethods);
                            ?>
                        </select>
                        <input type="submit" name="pickPay" value="Use this Payment">
                        <input type="submit" name="enterPay" value="Enter manually">
                    </form>
                </td>
                <td></td> -->
            <?php
            // endif;

            // $payEnter = $enterPay ? "required" : "disabled";
            // mysqli_free_result($storedMethodsPay);

            // $storedMethodsship = mysqli_query($dbConnect, "SELECT addressID FROM storedship WHERE userID = {$userID}");
            // if (mysqli_num_rows($storedMethodsship) > 0) :
            //     $shipMethods = mysqli_query($dbConnect, "SELECT * FROM addressinfo WHERE id in (SELECT addressID FROM storedship WHERE userID = {$userID})")
            ?>
            <!-- <td>
                    <form method="POST">
                        Use a Stored Shipping Address: <select name=selectedmethod>
                            <?php
                            // while ($row = mysqli_fetch_assoc($shipMethods)) {
                            //     $selected = $shipID == $shipMethods['id'] ? "selected" : "";
                            //     echo "<option {$selected} value = \"{$row['id']}\">{$row['street']},{$row['city']},{$row['state']}</option>\n";
                            // }
                            // mysqli_free_result($shipMethods);
                            ?>
                        </select>
                        <input type="submit" name="pickship" value="Use this Address">
                        <input type="submit" name="entership" value="Enter manually">
                    </form>
                </td> -->

            <?php
            // endif;
            // $shipEnter = $entership ? "" : "disabled";
            // mysqli_free_result($storedMethodsship);
            ?>
        </tr>
        <tr style="text-align: left;">
            <td>
                <form id="checkout" method="POST">
                    Credit Card Number: <input type="text" name="ccno" pattern="\d{16}" required><br>
                    Expiration Date: <input type="month" name="expDate" required><br>
                    CCV: <input type="text" pattern="\d{3}" name="cvv" required><br><br>
                </form>
            </td>
            <td>
                Address: <input type="text" form="checkout" name="bAddress" required><br>
                City: <input type="text" form="checkout" name="bCity" required><br>
                State: <input type="text" form="checkout" pattern="[a-zA-Z]{2}" name="bState" required><br>
                Zip: <input type="text" form="checkout" pattern="\d{5}" name="bZip" required><br><br>
                <input type="checkbox" form="checkout" name="savePay"> Save this Payment Method
            </td>
            <td>
                Address: <input type="text" form="checkout" name="sAddress" required><br>
                City: <input type="text" form="checkout" name="sCity" required><br>
                State: <input type="text" form="checkout" pattern="[a-zA-Z]{2}" name="sState" required><br>
                Zip: <input type="text" form="checkout" pattern="\d{5}" name="sZip" required><br><br>
                <input type="checkbox" form="checkout" name="saveShip"> Save this Address
                <input type="submit" form="checkout" name="checkout">
            </td>
        </tr>
    </table>

    </table>
</body>

</html>