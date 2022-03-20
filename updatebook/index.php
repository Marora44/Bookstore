<?php
session_start();
//include ('header.php');
require_once "../config.php";
//for testing
$_SESSION['userMode'] = 'pub';
$_SESSION['id'] = 1;

$isbn = $fisbn = $isbnerr = "";



if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fisbn = htmlspecialchars(trim($_POST['isbn']));
    if(empty($fisbn)) $isbnerr = "&nbsp;&nbsp;Please enter an ISBN";
    else if(preg_match("/^[0-9]*$/", $fisbn)== false) $isbnerr = "&nbsp;&nbsp;ISBN must contain only numbers";
    else if(strlen($fisbn) != 13) $isbnerr = "&nbsp;&nbsp;ISBN must be 13 numbers";
    else{
        $isbnquery = mysqli_query($dbConnect,"SELECT * FROM book WHERE isbn = {$fisbn}");
        if (mysqli_num_rows($isbnquery) == 0) $isbnerr = "&nbsp;&nbsp;This book doesn't exist in our database (You can add a book <a href=\"../addbook.php\">here</a>)";
        else{
            $row = mysqli_fetch_assoc($isbnquery);
            if ($row['pubID'] != $_SESSION['id']) $isbnerr = "&nbsp;&nbsp;You are not the publisher of this book (you can only update books that were added under this account)";
        }
        if(empty($isbnerr)){
            $_SESSION['isbn'] = $fisbn;
            header("location: updatebook.php");
            die("something went wrong");
        }
    }
}

?>



<html>
<title>Update a Book</title>

<body>
    <!-- <div>
		<h1><a href="index.php"> Home </a></h1>
	</div> -->
    <h1>Update a Book</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Enter the ISBN of the book you wish to update: <br><br><input type="text" name="isbn" size="25" maxlength="13" value=<?php echo $fisbn ?>><?php echo $isbnerr?><br><br>
        <input type="submit" value="Continue">
    </form>

</body>

</html>