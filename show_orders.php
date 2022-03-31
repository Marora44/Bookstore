<?php
session_start();
require_once "config.php";

$userid = $_SESSION['id'];

$bookorderisbn = 0;
$price = 0;
$query = "SELECT * from bookorder where userID = $userid and isPlaced = 1";
$check = 0;
?>

<html>
<?php
$headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
                  <h3><p> Order History</p></h3>";
include('header.php');
?>
<div class="page">
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
    while ($row = mysqli_fetch_assoc($result)) {
      $orderid = $row['id'];
      $quantity = $row['quantity'];
      $bookorderisbn = $row['isbn'];
      $orderdate = $row['orderDate'];
      $complete = $row['isPlaced'];
      $queryprice = "SELECT price, isbn from book where isbn = \"{$row['isbn']}\"";
      $resultprice = mysqli_query($dbConnect, $queryprice);
      
      while($rowprice = mysqli_fetch_assoc($resultprice)){
        $price=$rowprice['price'] * $quantity;
      }
      

      if($check != $orderid){
        if($orderid > 0){
          echo "<tr><td><a href=orderhistory.php?orderid=$orderid>" . $orderid . "</a>&nbsp</td><td>&nbsp" . $orderdate . "&nbsp</td><td>".$price."</td>";
        }
      }
        $check = $orderid;
      
      
        
        //echo $price;
        #echo"&nbsp".$row['price'];
        #echo"&nbsp".$row['quantity'];
        #echo $bookorderisbn;
      
      #set check to (last) orderid
      $check = $orderid;
    }
    #dump price for the last row, lest it be left out
    echo "</table>";
    ?>

    <?php mysqli_free_result($result); ?>
  </table>
</div>

</html>