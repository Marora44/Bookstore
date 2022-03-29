<?php 
require_once "config.php";

$authorfirstname = $authorlastname = $authorfnerr = $authorlnerr = $authorerr = "";
$fauthorfirstname = $fauthorlastname = "";
$sqlmessage = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fauthorfirstname = $_POST['authfirstname'];
    if(empty($fauthorfirstname)) $authorfnerr = "&nbsp;&nbsp;Please enter a first name";
    else if(preg_match("/^[a-zA-Z]*$/", $fauthorfirstname)== false) $authorfnerr = "&nbsp;&nbsp;Name can only conatin letters";
    $fauthorlastname = $_POST['authlastname'];
    if(empty($fauthorlastname)) $authorlnerr = "&nbsp;&nbsp;Please enter a last name";
    else if(preg_match("/^[a-zA-Z]*$/", $fauthorlastname)== false) $authorlnerr = "&nbsp;&nbsp;Name can only conatin letters";
    if(empty($authorfnerr.$authorlnerr)){
        $authorduplicatequery = mysqli_query($dbConnect,"SELECT * FROM author WHERE firstname = \"{$fauthorfirstname}\" AND lastname = \"{$fauthorlastname}\"");
        if(mysqli_num_rows($authorduplicatequery) > 0) $authorerr = "This author already exists in our database";
        mysqli_free_result($authorduplicatequery);
        if(empty($authorerr)){
            $authorfirstname = $fauthorfirstname;
            $authorlastname = $fauthorlastname;
            $addauthor = "INSERT INTO author(firstname,lastname) VALUES(\"{$authorfirstname}\",\"{$authorlastname}\")";
            if(mysqli_query($dbConnect,$addauthor)) $sqlmessage = "Success";
            else{
            $sqlerr = mysqli_error($dbConnect);
            $sqlmessage = "Error: {$addauthor} <br> {$sqlerr}"; 
            }
        }
    }
}

?>


<html>
    <title>Add an Author</title>
    <body>
        <h1>Add an Author</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            First name: <input type="text" name="authfirstname" value="<?php echo $fauthorfirstname ?>"><?php echo $authorfnerr ?><br><br>
            Last name: <input type="text" name="authlastname" value="<?php echo $fauthorlastname ?>"><?php echo $authorlnerr ?><br><br>
            <input type="submit" value="Add">
        </form>
        <br>
        <?php echo $authorerr ?>
        <?php echo $sqlmessage ?>
    </body>

</html>