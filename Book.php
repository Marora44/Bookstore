<?php
session_start();

if (isset($_GET['isbn'])) $_SESSION['isbn'] = $_GET['isbn'];
if (isset($_SESSION['isbn'])) $isbn = $_SESSION['isbn'];

$query = "SELECT * from Book where isbn = $isbn";
$title = "";

$userID = $_SESSION['id'];
$headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
                        <h3><p> $title </p></h3>";
include('header.php');
$alreadyreviewed = "";
$notpurchased = "";
require_once "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cart'])) {
        if (isset($_GET['isbn'])) $_SESSION['isbn'] = $_GET['isbn'];
        if (isset($_SESSION['isbn'])) $isbn = $_SESSION['isbn'];
        $quantity = $_POST['quantity'];
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
        #check if they have purchased the book
        $purchasedbook = mysqli_query($dbConnect, "SELECT count(id) from bookorder where userID = $userID and isbn = $isbn");
        while ($purchasedbook1 = mysqli_fetch_assoc($purchasedbook)) {
            if ($purchasedbook1['count(id)'] == 0) {
                $notpurchased = "You must purchase the book before leaving a review!";
            } else {
                $alreadyposted = mysqli_query($dbConnect, "SELECT count(username) from review natural join accountholder where isbn = $isbn and userID = $userID");
                while ($alreadyposted1 = mysqli_fetch_assoc($alreadyposted)) {
                    if ($alreadyposted1['count(username)'] == 0) {
                        $newreview = mysqli_real_escape_string($dbConnect, $_POST['newreview']);
                        $newrating = mysqli_real_escape_string($dbConnect, $_POST['rating']);
                        $querynewreview = "insert into review values (0, \"$isbn\", \"$newreview\", $newrating, (SELECT username from accountholder where userID = $userID))";
                        $resultnewreview = mysqli_query($dbConnect, $querynewreview);
                    } else {
                        $alreadyreviewed = "You have already left a review!";
                    }
                }
            }
        }
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
    <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="hidden" name="isbn" value="<?= $row['isbn'] ?>" />
        <input type="hidden" name="type" value="physical" />
        <input name="quantity" style="width: 4em" type="number" step="1" min="1" max="<?= $row['quantity'] ?>" <?= $instock ? "value=\"1\"" : "value=\"0\" disabled" ?>>
        &nbsp;
        <input type="submit" <?= $instock ? "value=\"Add to Cart\"" : "value=\"Out of Stock\" disabled" ?> name="cart">
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
?>

<br><br>



<form method="POST">
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
        <button type="submit" class="btn" name="submitreview">Post Review</button>
    </div>
    <?php
    echo $alreadyreviewed;
    echo $notpurchased;
    ?>
</form>
</div>
</html>