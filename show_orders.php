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
$check = 0;
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
                  $quantity = $row['quantity'];
                  $bookorderisbn = $row['isbn'];
                  $orderdate = $row['orderDate'];
                  #if its the same order
                  if($check == $orderid)
                  {
                    $price += (($row['price']) * $quantity);
                    #echo"".$row['price'];
                    #echo"&nbsp".$row['quantity'];
                    #echo $bookorderisbn;
                  }else #must be a new order
                  {
                    #if we definitely have atleast 1 order:
                    if($check != 0)
                    {
                      #dump the TOTAL price before we get the new one
                      echo "<td>&nbsp".$price."</td></tr><td>";
                    }
                    $price = (($row['price']) * $quantity);
                    echo "<tr><td><a href=orderhistory.php?orderid=$orderid>".$orderid."</a>&nbsp</td><td>&nbsp".$orderdate."&nbsp</td>";
                    
                    #echo"&nbsp".$row['price'];
                    #echo"&nbsp".$row['quantity'];
                    #echo $bookorderisbn;
                  }
                    #set check to (last) orderid
                    $check = $orderid;
                  }
                  #dump price for the last row, lest it be left out
                  echo "<td>&nbsp".$price."</td></tr><td>";
                echo "</table>";
                ?>
                
            <?php mysqli_free_result($result);?>
</table>
                
</html>