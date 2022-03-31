<?php
session_start();
#$orderid = $_REQUEST['orderid'];
#$title = $_GET['title'];
$isbn = $_GET['isbn'];
#if (isset($_GET['isbn'])) $_SESSION['isbn'] = $_GET['isbn'];
#if (isset($_SESSION['isbn'])) $isbn = $_SESSION['isbn'];
$query = "SELECT * from Book where isbn = $isbn";
$title = "A Gamer's Dream";
$_SESSION['id'] = 1;
$userID = 3;
$headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
                        <h3><p> $title </p></h3>";
include('header.php');

require_once "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "test";
    if (isset($_POST['cart'])) {
        echo "world";
        #$isbn = $_POST['isbn'];
        #$quantity = $_POST['quantity'];
        $quantity = 1;
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
        $numInCart = mysqli_query($dbConnect, "SELECT quantity FROM bookorder WHERE id = {$orderID} AND isbn = {$isbn}");
        if (mysqli_num_rows($numInCart) < 1) $addToCart = mysqli_query($dbConnect, "INSERT INTO bookorder(id,isbn,quantity,userID,isPlaced) VALUES({$orderID},{$isbn},{$quantity},{$userID},FALSE)");
        else $addToCart = mysqli_query($dbConnect, "UPDATE bookorder SET quantity = quantity + {$quantity} WHERE id = {$orderID} AND isbn = {$isbn}");

        if ($addToCart) {
            $updateInventory = mysqli_query($dbConnect, "UPDATE book SET quantity = quantity - {$quantity} WHERE isbn = {$isbn}");
            if (!$updateInventory) die("error updating inventory");
        } else die("error adding to cart");
    }
    if (isset($_POST['submitreview'])) {
        echo "hello world";
        //save the entered values on the form in variables
        $newreview = mysqli_real_escape_string($dbConnect, $_POST['newreview']);
        $newrating = mysqli_real_escape_string($dbConnect, $_POST['rating']);

        $querynewreview = "insert into review values (0, \"$isbn\", \"$newreview\", $newrating, (SELECT username from accountholder where userID = 1))";

        $resultnewreview = mysqli_query($dbConnect, $querynewreview);
    }
}

?>
<html>
<div class="page">

    <table>
        <tr>
            <th>Title&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</th>
            <th>ISBN&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</th>
            <th>Author&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</th>
            <th>Publisher&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</th>
            <th>Price</th>
        </tr>

    </table>
</div>

</html>

<?php
$result = mysqli_query($dbConnect, $query);
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['title'];
    echo '&ensp;&ensp;';
    echo '&ensp;&ensp;';
    echo '&ensp;&ensp;';
    echo $row['isbn'];
    echo '&ensp;&ensp;';
    $instock = $row['quantity'] > 0;
    $author = $row['authorID'];
    $publisher = $row['pubID'];
    $resultauthor = mysqli_query($dbConnect, "SELECT firstname, lastname from author where id = $author");
    while ($rowauthor = mysqli_fetch_assoc($resultauthor)) {
        echo $rowauthor['firstname'] . " " . $rowauthor['lastname'];
    }
    echo '&ensp;&ensp;';
    echo '&ensp;';
    $resultpublisher = mysqli_query($dbConnect, "SELECT name from publisher where id = $publisher");
    while ($rowpublisher = mysqli_fetch_assoc($resultpublisher)) {
        echo $rowpublisher['name'];
    }
    echo '&ensp;&ensp;';
    echo '&ensp;&ensp;';
    echo '&ensp;&ensp;';
    echo '&ensp;&ensp;';
    echo '&ensp;&ensp;';
    echo $row['price'];
}

?>
<td>
    <form style="margin: 8 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="hidden" name="isbn" value="<?= $row['isbn'] ?>" />
        &nbsp;
        <input type="submit" name="cart" <?= $instock ? "value=\"Add to Cart\"" : "value=\"Out of Stock\" disabled" ?>>
    </form>
</td>

<?php
$queryavgrating = "SELECT avg(rating) as avgrating from review where isbn = $isbn";
$avgrating = mysqli_query($dbConnect, $queryavgrating);
while ($row = mysqli_fetch_assoc($avgrating)) {
    echo "<h2>Review Average: " . $row['avgrating'] . "</h2>";
}

$queryreviews = "SELECT rating, rtext, username from review where isbn = $isbn";
$reviews = mysqli_query($dbConnect, $queryreviews);
while ($row = mysqli_fetch_assoc($reviews)) {
    $review = $row['rtext'];
    $rating = $row['rating'];
    $username = $row['username'];

    echo "<h3>" . $username . ":&ensp;" . $rating . "/5</h3>";
    echo $review;
}

$purchased = "SELECT isbn from bookorder where userID = $userID AND isPlaced = 1 AND isbn = $isbn";
$purchasedresult = mysqli_query($dbConnect,$purchased);
$ispurchased = mysqli_num_rows($purchasedresult) > 0;
?>
<html>
<br><br>



<form method = "POST">
<label for="rating">Select a Rating:</label>
<select name="rating" id="rating">
    <option value=1>1</option>
    <option value=2>2</option>
    <option value=3>3</option>
    <option value=4>4</option>
    <option values=5>5</option>
</select>

<br>
    <label for="newreview">Write a review:</label><br>
    <input type="text" id="newreview" name="newreview"><br>
    <div class="input-group">
        <input type="submit" class="btn" name="submitreview"  <?= $ispurchased ? "value=\"submit\"" : "value=\"Purchase required to leave a review\" disabled" ?>>
    </div>

</form>

</html>

<?php

?>