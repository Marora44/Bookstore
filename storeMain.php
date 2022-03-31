<?php
session_start();

require_once "config.php";

$isbn = "";
$quantity = $orderID = 0;
if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
} else die("something went wrong");
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Add to Cart'])) {
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
    if(isset($_POST['search'])){    
 
        if ($_POST['searchby'] == "title") {

            #store the method of search 
            $search = mysqli_real_escape_string($dbConnect, $_POST['keyword']);

            #run the query to search Book using keyword LIKE
            $resultsearchtitle = mysqli_query($dbConnect, "SELECT * from Book where title LIKE '%$search%' ");
            while ($searchrow = mysqli_fetch_assoc($resultsearchtitle)):
                $instock = $searchrow['quantity'] > 0;
                $author = mysqli_query($dbConnect, "SELECT id, firstname, lastname FROM author WHERE id = {$searchrow['authorID']}");
                $authRow = mysqli_fetch_assoc($author);
                $authorName = $authRow['firstname'] . "&nbsp;" . $authRow['lastname'];
                mysqli_free_result($author);
                if($searchrow['isPhysical']) :
            ?>
                <tr>
                    <td><?= $searchrow['isbn'] ?></td>
                    <td><?= $searchrow['title'] ?></td>
                    <td><?= $authorName ?></td>
                    <td>Physical</td>
                    <td>$<?= $searchrow['price'] ?></td>
                    <td>
                        <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <input type="hidden" name="isbn" value="<?= $searchrow['isbn'] ?>" />
                            <input type="hidden" name="type" value="physical" />
                            <input name="quantity" style="width: 4em" type="number" step="1" min = "1" max="<?= $searchrow['quantity'] ?>" <?= $instock ? "value=\"1\"" : "value=\"0\" disabled" ?>>
                            &nbsp;
                            <input type="submit" <?= $instock ? "value=\"Add to Cart\"" : "value=\"Out of Stock\" disabled" ?>>
                        </form>
                    </td>
                </tr>
            <?php
                endif;
                if($searchrow['isDigital']) :
            ?>
            <tr>
                    <td><?= $searchrow['isbn'] ?></td>
                    <td><?= $searchrow['title'] ?></td>
                    <td><?= $authorName ?></td>
                    <td>Digital</td>
                    <td>$<?= $searchrow['price'] ?></td>
                    <td>
                        <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <input type="hidden" name="isbn" value="<?= $searchrow['isbn'] ?>" />
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
            mysqli_free_result($resultsearchtitle);
            
        }
        if ($_POST['searchby'] == "author"){  
             #store the method of search 
             $search = mysqli_real_escape_string($dbConnect, $_POST['keyword']);

             #get author firstname,lastname from author id
             #$resultsearchauthorname = mysqli_query($dbConnect, "SELECT id from (select concat(firstname, ' ', lastname) as authorname, id from author) where authorname LIKE '%$search%'");
             #select books where authorid is in $resultsearchauthorname

             #run the query to search Book using keyword LIKE
             $resultsearchauthorname = mysqli_query($dbConnect, "select * from Book where authorID IN (select id from author where (firstname LIKE '%$search%') OR (lastname LIKE '%$search%'))");
             #"SELECT authorID from Book where authorID IN (select concat(firstname, ' ', lastname) as authorname, id from author where concat(firstname, ' ', lastname) LIKE '%$search%')");
             
             while ($searchrow = mysqli_fetch_assoc($resultsearchauthorname)):
                 $instock = $searchrow['quantity'] > 0;
                 $author = mysqli_query($dbConnect, "SELECT id, firstname, lastname FROM author WHERE id = {$searchrow['authorID']}");
                 $authRow = mysqli_fetch_assoc($author);
                 $authorName = $authRow['firstname'] . "&nbsp;" . $authRow['lastname'];
                 mysqli_free_result($author);
                 if($searchrow['isPhysical']) :
             ?>
                 <tr>
                     <td><?= $searchrow['isbn'] ?></td>
                     <td><?= $searchrow['title'] ?></td>
                     <td><?= $authorName ?></td>
                     <td>Physical</td>
                     <td>$<?= $searchrow['price'] ?></td>
                     <td>
                         <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                             <input type="hidden" name="isbn" value="<?= $searchrow['isbn'] ?>" />
                             <input type="hidden" name="type" value="physical" />
                             <input name="quantity" style="width: 4em" type="number" step="1" min = "1" max="<?= $searchrow['quantity'] ?>" <?= $instock ? "value=\"1\"" : "value=\"0\" disabled" ?>>
                             &nbsp;
                             <input type="submit" <?= $instock ? "value=\"Add to Cart\"" : "value=\"Out of Stock\" disabled" ?>>
                         </form>
                     </td>
                 </tr>
             <?php
                 endif;
                 if($searchrow['isDigital']) :
             ?>
             <tr>
                     <td><?= $searchrow['isbn'] ?></td>
                     <td><?= $searchrow['title'] ?></td>
                     <td><?= $authorName ?></td>
                     <td>Digital</td>
                     <td>$<?= $searchrow['price'] ?></td>
                     <td>
                         <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                             <input type="hidden" name="isbn" value="<?= $searchrow['isbn'] ?>" />
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
             mysqli_free_result($resultsearchauthorname);
        }
    }
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

<form method = "POST">
<label for="searchby">Search By:</label>
<select name="searchby" id="searchby">
    <option value="title">Title</option>
    <option value="author">Author</option>
</select>

<br>
    <label for="keyword">Keyword</label><br>
    <input type="text" id="keyword" name="keyword"><br>
    <div class="input-group">
        <input type="submit" class="btn" name="search">
    </div>

</form>

<body>
    <?php
/*     $headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                 <h3><p>All Books</p></h3>";
    include('header.php'); */
    ?>
        <?php
        if ($_SERVER["REQUEST_METHOD"] != "POST"){
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
        }
        ?>
    </table>
</body>

</html>

