<?php
require_once "config.php";
?>

<html>

<body>
    <h1>Manage Shipping Methods:</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Method: <select name="method">
            <option> Select a method</option>
            <?php
            $query = "SELECT * FROM ShippingMethods";
            $result = mysqli_query($dbConnect, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $selected = $result == $row['method'] ? "selected" : "";
                echo "<option {$selected} value = \"{$row['method']}\">{$row['price']} </option>\n";
            }
            mysqli_free_result($result);
            ?>

<?php
   
   echo 'Manage Shipping Methods';
   

?>
