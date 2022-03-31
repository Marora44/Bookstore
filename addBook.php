<?php

session_start();

require_once "config.php";

$publisherName = $_SESSION['publisherName'];

if($_SESSION['userMode'] != 'publisher' AND $_SESSION['userMode'] != 'admin') header("location: index.php");

$isbn = $title = $genre = "";
$isdigital = $isphysical = 0;
$isbnerr = $titleerr = $authorerr = $genreerr = $mediumerr = $priceerr = $passerr = ""; //variables for error messages
$authorID = 0;
$price = 0.00;
$fisbn = $ftitle = $fgenre = $fauthid = $fprice = $fdigit = $fphys = $fpass = ""; //values entered in the html form
$sqlmessage = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fisbn = htmlspecialchars(trim($_POST['isbn']));
    if(empty($fisbn)) $isbnerr = "&nbsp;&nbsp;Please enter an ISBN";
    else if(preg_match("/^[0-9]*$/", $fisbn)== false) $isbnerr = "&nbsp;&nbsp;ISBN must contain only numbers";
    else if(strlen($fisbn) != 13) $isbnerr = "&nbsp;&nbsp;ISBN must be 13 numbers";
    else{
        $isbnquery = mysqli_query($dbConnect,"SELECT * FROM book WHERE isbn = {$fisbn}");
        if (mysqli_num_rows($isbnquery) > 0) $isbnerr = "&nbsp;&nbsp;This book already exists in our database (You can update existing books <a href=\"updatebook/index.php\">here</a>)";
        else $isbn = $fisbn;
    }
    $ftitle = htmlspecialchars(trim($_POST['title']));
    if(empty($ftitle)) $titleerr = "&nbsp;&nbsp;Please enter a title";
    else $title = $ftitle;
    if (array_key_exists('author',$_POST))$fauthID = htmlspecialchars(trim($_POST['author']));
    if(empty($fauthID)) $authorerr = "&nbsp;&nbsp;Please select an author";
    else $authorID = intval($fauthID);
    $fgenre = htmlspecialchars(trim($_POST['genre']));
    if(empty($fgenre)) $genreerr = "&nbsp;&nbsp;Please enter a genre";
    else $genre = $fgenre;
    if (array_key_exists('isdigital',$_POST)) $fdigit = htmlspecialchars(trim($_POST['isdigital']));
    if (array_key_exists('isphysical',$_POST)) $fphys = htmlspecialchars(trim($_POST['isphysical']));
    if (empty($fdigit) && empty($fphys)) $mediumerr = "Please select at least one";
    else {
        $isdigital = intval(!empty($fdigit));
        $isphysical = intval(!empty($fphys));
    } 
    $fprice = htmlspecialchars($_POST['price']);
    if(empty($fprice)) $priceerr = "&nbsp;&nbsp;Please enter a valid price";
    else $price = floatval($fprice);
    $fpass = htmlspecialchars(trim($_POST['password']));
    if(empty($fpass)) $passerr = "&nbsp;&nbsp;Please enter a password";
    else{
        $passcheck = "SELECT password from publisher WHERE id = {$_SESSION['id']}";
        $passres = mysqli_query($dbConnect, $passcheck);
        $pass = (mysqli_fetch_assoc($passres))['password'];
        if($fpass != $pass) $passerr = "&nbsp;&nbsp;Incorrect password";
        mysqli_free_result($passres);
    }
    if(empty($isbnerr.$titleerr.$authorerr.$genreerr.$mediumerr.$priceerr.$passerr)){
        $addbook = "INSERT INTO book(isbn,title,authorID,genre,price,isDigital,isPhysical,pubID,quantity) VALUES(\"{$isbn}\",\"{$title}\",{$authorID},\"{$genre}\",{$price},{$isdigital},{$isphysical},{$_SESSION['id']},10)";
        if(mysqli_query($dbConnect,$addbook)) $sqlmessage = "Success";
        else{
           $sqlerr = mysqli_error($dbConnect);
           $sqlmessage = "Error: {$addbook} <br> {$sqlerr}"; 
        }
         
    }
    
}

?>

<html>
    <?php
        $headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                        <h3><p> $publisherName's Publisher Landing Page</p></h3>";
        include ('header.php');
    ?>
    <div class="page">
        <title>Add a Book</title>

        <body>
            <div>
		        <h1><a href="index.php"> Home </a></h1>
	        </div>
            <h1>Add a Book</h1>
            <h4>All fields are required.</h4>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                ISBN: <input type="text" name="isbn" size="25" maxlength="13" value=<?php echo $fisbn ?>><?php echo $isbnerr?><br><br>
                Title: <input type="text" name="title" size="25" maxlength="50" value=<?php echo "\"{$ftitle}\"" ?>><?php echo $titleerr?><br><br>
                Author: <select name="author">
                    <option <?php if ($authorID == 0) echo "selected";?> disabled hidden>Select an author</option>
                    <?php
                    $query = "SELECT id, firstname, lastname FROM author";
                    $result = mysqli_query($dbConnect, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = $authorID == $row['id'] ? "selected" : "";
                        echo "<option {$selected} value = \"{$row['id']}\">{$row['firstname']} {$row['lastname']}</option>\n";
                    }
                    mysqli_free_result($result);
                    ?>
                </select><?php echo $authorerr ?>&nbsp;(Don't see your author? Add it <a href="addauthor.php">here</a>)<br><br>
                Genre: <input type="text" name="genre" size="25" value=<?php echo "\"{$fgenre}\"" ?>><?php echo $genreerr?><br><br>
                <p style="margin-bottom: 0.5em; margin-top:0cm">Medium: <?php echo $mediumerr?></p>
                <input style="width: 25%;" type="checkbox" name="isphysical" value = "checked" <?php echo $fphys?>> Physical <br>
                <input style="width: 25%;" type="checkbox" name="isdigital" value = "checked" <?php echo $fdigit?>> Digital <br><br>
                Price: <input type="number" name="price" size="8" min="0.01" max="10000.00" step="0.01" value=<?php echo $fprice ?>><?php echo $priceerr?><br><br>
                Publisher ID: <input type="text" name="id" value="<?php echo $_SESSION['id'];?>" disabled><br><br>
                Password: <input type="password" name="password"><?php echo $passerr?><br><br>
                <input type="submit" value="Add">
            </form>
            <br><br><br>
            <?php echo $sqlmessage?>


        </body>
    </div>
</html>