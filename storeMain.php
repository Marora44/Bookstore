<?php
session_start();

//testing
$_SESSION['id'] = 1;

require_once "config.php";

$isbn = "";
$quantity = $orderID = 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];
    $checkorders = mysqli_query($dbConnect,"SELECT id FROM bookorder WHERE userID = {$_SESSION['id']} AND isPlaced = FALSE"); //check if the user has an active cart (unplaced order)
    if(mysqli_num_rows($checkorders) > 0) $orderID = mysqli_fetch_assoc($checkorders)['id'];
    else{
        $highestID = mysqli_query($dbConnect,"SELECT MAX(id) maxID FROM bookorder");
        if(mysqli_num_rows($highestID) < 1) $orderID = 1;
        else $orderID = mysqli_fetch_assoc($highestID)['maxID'] + 1;
    }
    /*
        to do:
        check if book in cart
        add/update cart
        update stock in book table
    */
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

</html>