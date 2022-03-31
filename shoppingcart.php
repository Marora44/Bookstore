<?php

session_start();
$headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
                        <h3><p> Member Login Page:</p></h3>";
        include ('header.php'); 
require_once "config.php";

if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
} else die("something went wrong");

/*  
    todo:
    add checkout button
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn = $_POST['isbn'];
    $id = $_POST['id'];
    $cartQuantity = $_POST['cQuantity'];
    $formQuantity = $_POST['fQuantity'];
    $isDigital = (int) ($_POST['type'] == "digital");
    if (array_key_exists('del', $_POST)) {

        $del = mysqli_query($dbConnect, "DELETE from bookorder WHERE isbn = \"{$isbn}\" AND id = {$id} AND isDigital = $isDigital");
        if ($del) {
            if(!$isDigital){
            $updateInventory = mysqli_query($dbConnect, "UPDATE book SET quantity = quantity + {$cartQuantity} WHERE isbn = \"{$isbn}\"");
            if (!$updateInventory) die("error updating inventory");
            }
        } else die("error removing from cart");
    } else if (array_key_exists('update', $_POST)) {
        $amtRemoved = $cartQuantity - $formQuantity;
        $updateCart = mysqli_query($dbConnect, "UPDATE bookorder SET quantity = {$formQuantity} WHERE isbn = \"{$isbn}\" AND id = {$id} AND isDigital = $isDigital"); 
        if ($updateCart) {
            $updateInventory = mysqli_query($dbConnect, "UPDATE book SET quantity = quantity + {$amtRemoved} WHERE isbn = \"{$isbn}\"");
            if (!$updateInventory) die("error updating inventory");
        } else die("error updating cart");
    }
}

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
<?php
    $headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                 <h3><p>Your Cart</p></h3>";
    include('header.php');
    ?>
    <table width="40%">
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Quantity</th>
            <th>Type</th>
            <th>Price</th>
            <th></th>

        </tr>
        <?php
        $cart = mysqli_query($dbConnect, "SELECT id, isbn, quantity, isDigital FROM bookorder WHERE userID = {$userID} AND isPlaced = FALSE");
        $totalPrice = 0.00;
        while ($cartRow = mysqli_fetch_assoc($cart)) :
            $bookInfo = mysqli_query($dbConnect, "SELECT title, price, quantity FROM book WHERE isbn = \"{$cartRow['isbn']}\"");
            $bookRow = mysqli_fetch_assoc($bookInfo);
            $totalPrice += $cartRow['quantity'] * $bookRow['price'];
            if(!$cartRow['isDigital']) :
        ?>
            <tr>
                <td><?= $cartRow['isbn'] ?></td>
                <td><?= $bookRow['title'] ?></td>
                <td width="30%">
                    <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="updateQuantity">
                        <input type="hidden" name="isbn" value="<?= $cartRow['isbn'] ?>" />
                        <input type="hidden" name="id" value="<?= $cartRow['id'] ?>" />
                        <input type="hidden" name="cQuantity" value="<?= $cartRow['quantity'] ?>"/>
                        <input type="hidden" name="type" value="physical" />
                        <input name="fQuantity" value="<?= $cartRow['quantity'] ?>" style="width: 4em" type="number" step="1" max="<?= $bookRow['quantity'] + $cartRow['quantity'] ?>" min="1">
                        &nbsp;
                        <input type="submit" name="update" value="Update">
                    </form>
                </td>
                <td>Physical</td>
                <td>$<?= $cartRow['quantity'] * $bookRow['price'] ?></td>
                <td><input type="submit" name="del" value="Remove from cart" form="updateQuantity"></td>
            </tr>
        <?php
            endif;
            mysqli_free_result($bookInfo);
            if($cartRow['isDigital']) :
                ?>
                <tr>
                    <td><?= $cartRow['isbn'] ?></td>
                    <td><?= $bookRow['title'] ?></td>
                    <td width="20%">
                        <form style="margin: 10 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="updateQuantity">
                            <input type="hidden" name="isbn" value="<?= $cartRow['isbn'] ?>" />
                            <input type="hidden" name="id" value="<?= $cartRow['id'] ?>" />
                            <input type="hidden" name="cQuantity" value="<?= $cartRow['quantity'] ?>"/>
                            <input type="hidden" name="type" value="digital" />
                            <input name="fQuantity" value="<?= $cartRow['quantity'] ?>" style="width: 4em" type="number" step="1" min="1">
                            &nbsp;
                            <input type="submit" name="update" value="Update">
                        </form>
                    </td>
                    <td>Digital</td>
                    <td>$<?= $cartRow['quantity'] * $bookRow['price'] ?></td>
                    <td><input type="submit" name="del" value="Remove from cart" form="updateQuantity"></td>
                </tr>
                <?php
                    endif;
        endwhile;
        mysqli_free_result($cart);
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