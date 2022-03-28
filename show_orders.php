<?php
require_once "config.php";

$headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
						<h3><p> Order History</p></h3>";
include ('header.php');
//manual testing, userid == 2
$_SESSION['id'] = 1;
$userid = $_SESSION['id'];
$_SESSION['orderid'] = 1;
$orderid = 1;
$bookorderisbn = 0;
$price = 0;
$query = "SELECT id, orderDate, quantity, book.isbn, price from bookorder natural join book where userID = $userid";
?>

<html>
<h1> Past Orders</h1>
<table>
  <tr>
    <th>ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>Date</th>
    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price</th>
  </tr>
            <?php
                $result = mysqli_query($dbConnect, $query);
                echo "<table>";
                while ($row = mysqli_fetch_assoc($result)){
                    $orderid = $row['id'];
                    $orderdate = $row['orderDate'];
                    $quantity = $row['quantity'];
                    $bookorderisbn = $row['isbn'];
                    #query each book for total price:
                    $price = ($row['price']) * $quantity;
                    echo "<tr><td><a href=orderhistory.php>".$orderid."</a>&nbsp</td><td>&nbsp".$orderdate."&nbsp</td><td>&nbsp".$price."</td></tr>";
                  }
                echo "</table>";
                ?>
                
            <?php mysqli_free_result($result);?>
</table>
</html>