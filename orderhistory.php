<?php
session_start();
$headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
                        <h3><p> Order History:</p></h3>";
include('header.php');

require_once "config.php";

$orderid = $_GET['orderid'];
$userid = $_SESSION['id'];
$result = false;
$book = "";

$query = "SELECT * from bookorder where id = $orderid";

?>

<html>
<div class="page">
<table>
  <tr>
    <th>ISBN&ensp;&ensp;</th>
    <th>Title&ensp;&ensp;</th>
    <th>Quantity&ensp;&ensp;</th>
    <th>Price</th>
  </tr>
    <h1>Order History</h1>
    <?php
    $result = mysqli_query($dbConnect, $query);
    while ($row = mysqli_fetch_assoc($result)) {
      $isbn = $row['isbn'];
      $querytitle = "SELECT title from book where isbn = \"{$isbn}\"";
      $resulttitle = mysqli_query($dbConnect, $querytitle);
      while ($rowtitle = mysqli_fetch_assoc($resulttitle)) {
        $book = $rowtitle['title'];
      }
    ?>

      <?php
      $quantity = $row['quantity'];
      $queryprice = "SELECT price from book where isbn = \"{$isbn}\"";
      $resultprice = mysqli_query($dbConnect, $queryprice);
      while ($rowprice = mysqli_fetch_assoc($resultprice)) {
        $price = $rowprice['price'] * $quantity;
      }
      echo "<tr><td>" . $isbn . "&nbsp</td><td>&nbsp<a href=book.php?isbn=$isbn>" . $book . "</a>&nbsp</td><td>" . $quantity . "&nbsp</td><td>" . $price . "&nbsp</td>";
      ?>
    <?php
    }
    mysqli_free_result($result);
    ?>
  </div>
</table>

</html>