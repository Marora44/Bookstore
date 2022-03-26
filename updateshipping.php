<?php

require_once "config.php";

$headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
						<h3><p> Update Shipping Methods:</p></h3>";
include ('header.php');

$query1 = "";
$price = 0;
$fprice = 0;
$method = "";
$fmethod = "";
$priceerr = "";
$methoderr = "";
$result = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fprice = htmlspecialchars($_POST['price']);
    if(empty($fprice)) $priceerr = "&nbsp;&nbsp;Please enter a valid price";
    else $price = floatval($fprice);
    $fmethod = $_POST['method'];
    if(empty($fmethod)){
        if(empty($fmethod)) $methoderr = "&nbsp;&nbsp;Please select a valid shipping method";
    }else $method = $fmethod;
    if(empty($priceerr) && empty($methoderr)){
        $query1 = "UPDATE ShippingMethods SET price = $fprice WHERE method = \"$method\"";
        $result = mysqli_query($dbConnect, $query1);
        #mysqli_free_result($result);
    }
    
}
?>

<html>

<body>
    <h1>Manage Shipping Methods:</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Method: <select name="method">
            <option selected hidden value=""> Select a method</option>
            <?php
            $query = "SELECT * FROM ShippingMethods";
            $result = mysqli_query($dbConnect, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $selected = $result == $row['method'] ? "selected" : "";
                echo "<option {$selected} value = \"{$row['method']}\">{$row['method']} {$row['price']} </option>\n";
            }
            mysqli_free_result($result);
            ?>
            </select>
            <?php echo $methoderr ?>
            <br><br>
        Update Price:<input type="number" name="price" size="8" min="0.01" max="10000.00" step="0.01" value=<?php echo $fprice ?>><?php echo $priceerr?> <br><br>
        <input type="submit">
    </form>
    <br><br>
    <?php 
    if($result && $method != "")
    echo "Successfully updated the price of $method" 
    ?>
            