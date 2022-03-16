<?php 
require_once "config.php";

$authorfirstname = $authorlastname = $authorfnerr = $authorlnerr = $authorerr = "";
$fauthorfirstname = $fauthorlastname = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fauthorfirstname = $_POST['authfirstname'];
    if(empty($fauthorfirstname)) $authorfnerr = "&nbsp;&nbsp;Please enter a first name";
    else if(preg_match("/^[a-zA-Z]*$/", $fauthorfirstname)== false) $authorfnerr = "&nbsp;&nbsp;Name can only conatin letters";
    else $authorfirstname = $fauthorfirstname;
    $fauthorlastname = $_POST['authlastname'];
    if(empty($fauthorlastname)) $authorlnerr = "&nbsp;&nbsp;Please enter a last name";
    else if(preg_match("/^[a-zA-Z]*$/", $fauthorlastname)== false) $authorlnerr = "&nbsp;&nbsp;Name can only conatin letters";
    else $authorlastname = $fauthorlastname;
    if(empty($authorfnerr.$authorlnerr)){
        $authorduplicatequery = mysqli_query($dbConnect,"SELECT * FROM author WHERE firstname = \"{$fauthorfirstname}\" AND lastname = \"{$fauthorlastname}\"");
        if(mysqli_num_rows($authorduplicatequery) > 0) $authorerr = "&nbsp&nbspThis author already exists in our database";
    }
}

?>


<html>
    <title>Add an Authork</title>
    <body>
        <h1>Add an Author</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            First name: <input type="text" name="authfirstname" value="<?php echo $fauthorfirstname ?>"><?php echo $authorfnerr ?><br><br>
            Last name: <input type="text" name="authlastname" value="<?php echo $fauthorlastname ?>"><?php echo $authorlnerr ?><br><br>
            <?php echo $authorerr ?>
            <input type="submit" value="Add">
        </form>
        <br><br>
        <?php echo $authorerr ?>
    </body>

</html>