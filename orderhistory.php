<?php
#$orderid = $_REQUEST['orderid'];
        $headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
                        <h3><p> Member Login Page:</p></h3>";
        include ('header.php'); 

require_once "config.php";

//manual testing
$_SESSION['id'] = 2;
$orderid = $_GET['orderid'];
$userid = $_SESSION['id'];
$result = false;
$book = "";

$query = "SELECT * from bookorder natural join book where id = $orderid";

// if($_SERVER["REQUEST_METHOD"] == "POST"){
//     $fprice = htmlspecialchars($_POST['price']);
//     if(empty($fprice)) $priceerr = "&nbsp;&nbsp;Please enter a valid price";
//     else $price = floatval($fprice);
//     $fmethod = $_POST['method'];
//     if(empty($fmethod)){
//         if(empty($fmethod)) $methoderr = "&nbsp;&nbsp;Please select a valid shipping method";
//     }else $method = $fmethod;
//     if(empty($priceerr) && empty($methoderr)){
//         $query1 = "UPDATE ShippingMethods SET price = $fprice WHERE method = \"$userid\"";
//         $result = mysqli_query($dbConnect, $query1);
//         #mysqli_free_result($result);
//     }
    
// }
?>


<table>
  <tr>
    <th>ISBN&ensp;&ensp;</th>
    <th>Title&ensp;&ensp;</th>
    <th>Quantity&ensp;&ensp;</th>
    <th>Price</th>
  </tr>
<body>
    <h1>Order History</h1>
        <div class="page">
        <?php
            //$query = "SELECT * FROM ShippingMethods";
            $result = mysqli_query($dbConnect, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                #$selected = $result == $row['isbn'] ? "selected" : "";
                $isbn = $row['isbn'];
                $book = $row['title'];
                #echo "$orderid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                #echo "$isbn &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                ?>
                <?php
                
                #echo "$book &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                ?>
                <?php
                $quantity = $row['quantity'];
                $price = $row['price'] * $quantity;
                #echo "$quantity &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "<tr><td>".$isbn."</a>&nbsp</td><td>&nbsp".$book."&nbsp</td><td>".$quantity."&nbsp</td><td>".$price."&nbsp</td>";
                ?>
                <br>
                <?php
                //echo $row['title'];
            }
            mysqli_free_result($result);
            ?>
        </div>
        
    <br><br>

            