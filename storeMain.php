<?php
session_start();

//testing
$_SESSION['id'] = 1;

require_once "config.php";

$isbn = "";
$quantity = $orderID = 0;
if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
} else die("something went wrong");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];
    $isDigital = (int) ($_POST['type'] == "digital");
    //check if the user has an active cart (unplaced order)
    $checkOrders = mysqli_query($dbConnect, "SELECT id FROM bookorder WHERE userID = {$userID} AND isPlaced = FALSE");
    //set the $orderID to the correct ID if a cart exists or creates an appropriate one if not
    if (mysqli_num_rows($checkOrders) > 0) $orderID = mysqli_fetch_assoc($checkOrders)['id'];
    else {
        $highestID = mysqli_query($dbConnect, "SELECT MAX(id) maxID FROM bookorder");
        if (mysqli_num_rows($highestID) < 1) $orderID = 1;
        else $orderID = mysqli_fetch_assoc($highestID)['maxID'] + 1;
    }
    mysqli_free_result($checkOrders);
    //checks if the user already has at least one of the book in their cart alredy, if so update the quantity otherwise create a new entry for that book
    
    $numInCart = mysqli_query($dbConnect, "SELECT quantity FROM bookorder WHERE id = {$orderID} AND isbn = \"{$isbn}\" AND isDigital = {$isDigital}");
    if (mysqli_num_rows($numInCart) < 1) $addToCart = mysqli_query($dbConnect, "INSERT INTO bookorder(id,isbn,quantity,userID,isDigital,isPlaced) VALUES({$orderID},\"{$isbn}\",{$quantity},{$userID},{$isDigital},FALSE)");
    else $addToCart = mysqli_query($dbConnect, "UPDATE bookorder SET quantity = quantity + {$quantity} WHERE id = {$orderID} AND isbn = \"{$isbn}\" AND isDigital = $isDigital");

    if ($addToCart) {
        if(!$isDigital){
            $updateInventory = mysqli_query($dbConnect, "UPDATE book SET quantity = quantity - {$quantity} WHERE isbn = \"{$isbn}\"");
            if (!$updateInventory) die("error updating inventory");
        }
    } 
    else die("error adding to cart");
}

?>


<html>

<head>
    <title>Home</title>
    <style>
        th {
            text-align: left;
        }
    </style>
</head>

<body>
    <?php
/*     $headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                 <h3><p>All Books</p></h3>";
    include('header.php'); */
    ?>
    <table width="70%">
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Type</th>
            <th>Price</th>
            <th></th>
        </tr>
        <?php
        $books = mysqli_query($dbConnect, "SELECT isbn,title,authorID,price,quantity,isDigital,isPhysical from book");
        while ($row = mysqli_fetch_assoc($books)) :
            $instock = $row['quantity'] > 0;
            $author = mysqli_query($dbConnect, "SELECT id, firstname, lastname FROM author WHERE id = {$row['authorID']}");
            $authRow = mysqli_fetch_assoc($author);
            $authorName = $authRow['firstname'] . "&nbsp;" . $authRow['lastname'];
            mysqli_free_result($author);
            if($row['isPhysical']) :
        ?>
            <tr>
                <td><?= $row['isbn'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $authorName ?></td>
                <td>Physical</td>
                <td>$<?= $row['price'] ?></td>
                <td>
                    <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input type="hidden" name="isbn" value="<?= $row['isbn'] ?>" />
                        <input type="hidden" name="type" value="physical" />
                        <input name="quantity" style="width: 4em" type="number" step="1" min = "1" max="<?= $row['quantity'] ?>" <?= $instock ? "value=\"1\"" : "value=\"0\" disabled" ?>>
                        &nbsp;
                        <input type="submit" <?= $instock ? "value=\"Add to Cart\"" : "value=\"Out of Stock\" disabled" ?>>
                    </form>
                </td>
            </tr>
        <?php
            endif;
            if($row['isDigital']) :
        ?>
        <tr>
                <td><?= $row['isbn'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $authorName ?></td>
                <td>Digital</td>
                <td>$<?= $row['price'] ?></td>
                <td>
                    <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input type="hidden" name="isbn" value="<?= $row['isbn'] ?>" />
                        <input type="hidden" name="type" value="digital" />
                        <input name="quantity" style="width: 4em" type="number" step="1" min = "1" value="1">
                        &nbsp;
                        <input type="submit" value="Add to Cart">
                    </form>
                </td>
            </tr>
        <?php
            endif;  
        endwhile;
        mysqli_free_result($books);
        ?>
    </table>
</body>

</html>