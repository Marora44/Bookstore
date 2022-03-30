<?php
session_start();

//testing
$_SESSION['id'] = 1;

require_once "config.php";

$isbn = "";
$quantity = $orderID = 0;
if(isset($_SESSION['id'])){
    $userID = $_SESSION['id'];
}
else die("something went wrong");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];
    //check if the user has an active cart (unplaced order)
    $checkOrders = mysqli_query($dbConnect,"SELECT id FROM bookorder WHERE userID = {$userID} AND isPlaced = FALSE");
    //set the $orderID to the correct ID if a cart exists or creates an appropriate one if not
    if(mysqli_num_rows($checkOrders) > 0) $orderID = mysqli_fetch_assoc($checkOrders)['id']; 
    else{
        $highestID = mysqli_query($dbConnect,"SELECT MAX(id) maxID FROM bookorder");
        if(mysqli_num_rows($highestID) < 1) $orderID = 1;
        else $orderID = mysqli_fetch_assoc($highestID)['maxID'] + 1;
    }
    mysqli_free_result($checkOrders);
    //checks if the user already has at least one of the book in their cart alredy, if so update the quantity otherwise create a new entry for that book 
    $numInCart = mysqli_query($dbConnect,"SELECT quantity FROM bookorder WHERE id = {$orderID} AND isbn = \"{$isbn}\""); 
    if(mysqli_num_rows($numInCart) < 1) $addToCart = mysqli_query($dbConnect,"INSERT INTO bookorder(id,isbn,quantity,userID,isPlaced) VALUES({$orderID},\"{$isbn}\",{$quantity},{$userID},FALSE)");
    else $addToCart = mysqli_query($dbConnect,"UPDATE bookorder SET quantity = quantity + {$quantity} WHERE id = {$orderID} AND isbn = \"{$isbn}\"");

    if($addToCart){
        $updateInventory = mysqli_query($dbConnect, "UPDATE book SET quantity = quantity - {$quantity} WHERE isbn = \"{$isbn}\"");
        if(!$updateInventory) die("error updating inventory");
    }
    else die("error adding to cart");
}

?>


<html>
    <?php
		$headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
						<h3><p> Book Catalogue:</p></h3>";
		include ('header.php'); 
	?>
    <div class="page">
        <div>
		    <h1><a href="index.php"> Home </a></h1>
	    </div>
        <head>
            <title>Home</title>
            <style>
                th {
                    text-align: left;
                }
            </style>
        </head>

        <body>
            <h1>All Books</h1>
            <table width="70%">
                <tr>
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th></th>
                </tr>
                <?php
                $books = mysqli_query($dbConnect, "SELECT isbn,title,authorID,price,quantity from book");
                while ($row = mysqli_fetch_assoc($books)) :
                    $instock = $row['quantity'] > 0;
                    $author = mysqli_query($dbConnect, "SELECT id, firstname, lastname FROM author WHERE id = {$row['authorID']}");
                    $authRow = mysqli_fetch_assoc($author);
                    $authorName = $authRow['firstname'] . "&nbsp;" . $authRow['lastname'];
                    mysqli_free_result($author);
                ?>
                    <tr>
                        <td><?= $row['isbn'] ?></td>
                        <td><?= $row['title'] ?></td>
                        <td><?= $authorName ?></td>
                        <td>$<?= $row['price'] ?></td>
                        <td>
                            <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <input type="hidden" name="isbn" value="<?= $row['isbn'] ?>" />
                                <input name="quantity" style="width: 4em" type="number" step="1" max="<?= $row['quantity'] ?>" <?= $instock ? "value=\"1\"" : "value=\"0\" disabled" ?>>
                                &nbsp;
                                <input type="submit" <?= $instock ? "value=\"Add to Cart\"" : "value=\"Out of Stock\" disabled" ?>>
                            </form>
                        </td>
                    </tr>
                <?php
                endwhile;
                mysqli_free_result($books);
                ?>
            </table>
        </body>
    </div>
</html>