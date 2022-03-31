<?php
session_start();

require_once "config.php";

$isbn = "";
$isyear = FALSE;
$quantity = $orderID = 0;
$tempcount = 1;
if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
} else die("something went wrong");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['atc'])) {
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
        //checks if the user already has at least one of the book in their cart alredy, if so update the quantity otherwise create a new entry for that book
        $numInCart = mysqli_query($dbConnect, "SELECT quantity FROM bookorder WHERE id = {$orderID} AND isbn = \"{$isbn}\" AND isDigital = {$isDigital}");
        if (mysqli_num_rows($numInCart) < 1) $addToCart = mysqli_query($dbConnect, "INSERT INTO bookorder(id,isbn,quantity,userID,isDigital,isPlaced) VALUES({$orderID},\"{$isbn}\",{$quantity},{$userID},{$isDigital},FALSE)");
        else $addToCart = mysqli_query($dbConnect, "UPDATE bookorder SET quantity = quantity + {$quantity} WHERE id = {$orderID} AND isbn = \"{$isbn}\" AND isDigital = $isDigital");

        if ($addToCart) {
            if (!$isDigital) {
                $updateInventory = mysqli_query($dbConnect, "UPDATE book SET quantity = quantity - {$quantity} WHERE isbn = \"{$isbn}\"");
                if (!$updateInventory) die("error updating inventory");
            }
        } else die("error adding to cart");
        mysqli_free_result($checkOrders);
    }

    if (isset($_POST['search'])) {
        $searchResults = true;
        if ($_POST['searchby'] == "title") {

            #store the method of search 
            $search = mysqli_real_escape_string($dbConnect, $_POST['keyword']);

            #run the query to search Book using keyword LIKE
            $searchQuery = "SELECT * from Book where title LIKE '%$search%' ";
        }

        if ($_POST['searchby'] == "author") {
            #store the method of search 
            $search = $_POST['keyword'];
            #get author firstname,lastname from author id
            #$resultsearchauthorname = mysqli_query($dbConnect, "SELECT id from (select concat(firstname, ' ', lastname) as authorname, id from author) where authorname LIKE '%$search%'");
            #select books where authorid is in $resultsearchauthorname

            #run the query to search Book using keyword LIKE
            $searchQuery = "SELECT * FROM book WHERE authorID IN (SELECT id from author where concat(firstname,' ',lastname) LIKE '%$search%')";
            #"SELECT authorID from Book where authorID IN (select concat(firstname, ' ', lastname) as authorname, id from author where concat(firstname, ' ', lastname) LIKE '%$search%')");
        }
        if ($_POST['searchby'] == "year"){
            $isyear = TRUE;
            if($_POST['keyword'] == ""){
                $_POST['keyword'] = "2022";

            }
            $year = $_POST['keyword'];
            $search = mysqli_real_escape_string($dbConnect, $_POST['keyword']);

            $searchQuery = "SELECT book.isbn, book.title, book.authorID, book.genre, book.price, book.isDigital, book.isPhysical, pubID, sum(bookorder.quantity) as quan from book inner join bookorder on book.isbn = bookorder.isbn where (bookorder.isPlaced = 1 AND substring(orderDate,1,4) = $year) group by isbn order by quan desc";
            
        }
        //echo $searchQuery;
    }
    if (isset($_POST['reset'])) unset($searchResults);
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

<?php
    $headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                 <h3><p>All Books</p></h3>";
    include('header.php');
    ?>

<div class="page">
    <form method="POST">
        <label for="searchby">Search By:</label>
        <select name="searchby" id="searchby">
            <option value="title">Title</option>
            <option value="author">Author</option>
            <option value="year">Best selling of Year</option>
        </select>

        <br>
        <label for="keyword">Keyword</label><br>
        <input type="text" id="keyword" name="keyword"><br>
        <div class="input-group">
            <input type="submit" class="btn" name="search" value="search">
            <input type="submit" class="btn" name="reset" value="reset">
        </div>

    </form>

    
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
        if (!isset($searchResults)) $booksQuery = "SELECT isbn,title,authorID,price,quantity,isDigital,isPhysical from book";
        else $booksQuery = $searchQuery;
        $showBooks = mysqli_query($dbConnect, $booksQuery);
        while ($row = mysqli_fetch_assoc($showBooks)) :
            if($isyear){
            $instock = $row['quan'] > 0;
            }else{
                $instock = $row['quantity'] > 0;
            }
            $author = mysqli_query($dbConnect, "SELECT id, firstname, lastname FROM author WHERE id = {$row['authorID']}");
            $authRow = mysqli_fetch_assoc($author);
            $authorName = $authRow['firstname'] . "&nbsp;" . $authRow['lastname'];
            mysqli_free_result($author);
            if ($row['isPhysical']) :
        ?>
                <tr>
                    <td><?= $row['isbn'] ?></td>
                    <?php 
                    $temptitle = $row['title'];
                    $tempisbn = $row['isbn'];
                    echo "<td><a href=Book.php?isbn=$tempisbn>$temptitle</a></td>"?>
                    <td><?= $authorName ?></td>
                    <td>Physical</td>
                    <td>$<?= $row['price'] ?></td>
                    <td>
                        <form style="margin: 10 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <input type="hidden" name="isbn" value="<?= $row['isbn'] ?>" />
                            <input type="hidden" name="type" value="physical" />
                            <input name="quantity" style="width: 4em" type="number" step="1" min="1" max="<?= $isyear ? $row['quan'] : $row['quantity'] ?>" <?= $instock ? "value=\"1\"" : "value=\"0\" disabled" ?>>
                            &nbsp;
                            <input type="submit" name="atc" <?= $instock ? "value=\"Add to Cart\"" : "value=\"Out of Stock\" disabled" ?>>
                        </form>
                    </td>
                    <td>
                        <?php 
                        if($isyear){
                            echo "<td>Best Selling Rank: $tempcount </td>";
                            $tempcount += 1;
                        }
                        ?>
                    </td>
                </tr>
            <?php
            endif;
            if ($row['isDigital']) :
            ?>
                <tr>
                    <td><?= $row['isbn'] ?></td>
                    <?php 
                    $temptitle = $row['title'];
                    $tempisbn = $row['isbn'];
                    echo "<td><a href=Book.php?isbn=$tempisbn>$temptitle</a></td>"?>
                    <td><?= $authorName ?></td>
                    <td>Digital</td>
                    <td>$<?= $row['price'] ?></td>
                    <td>
                        <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <input type="hidden" name="isbn" value="<?= $row['isbn'] ?>" />
                            <input type="hidden" name="type" value="digital" />
                            <input name="quantity" style="width: 4em" type="number" step="1" min="1" value="1">
                            &nbsp;
                            <input type="submit" name="atc" value="Add to Cart">
                        </form>
                    </td>
                    <td>
                        <?php 
                        if($isyear){
                            echo "<td>Best Selling Rank: $tempcount </td>";
                            $tempcount += 1;
                        }
                        ?>
                    </td>
                </tr>
        <?php
            endif;
        endwhile;
        mysqli_free_result($showBooks);
        ?>
    </table>
    </div>

</html>