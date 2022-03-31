<?php
session_start();

require_once "config.php";

if ($_SESSION['userMode'] != 'publisher' AND $_SESSION['userMode'] != 'admin') header("location: index.php");

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else {
    header("Location: index.php");
    die("something went wrong");
}

$publisherName = $_SESSION['publisherName'];

$name = $password = "";
$nameerr = $passworderr = $passerr = ""; //variables for error messages
$fname = $fpassword = $fpass = ""; //values entered in the html form
$sqlmessage = "";

$publisherinfo = mysqli_query($dbConnect, "SELECT * FROM Publisher WHERE id = \"{$id}\"");
$publisher = mysqli_fetch_assoc($publisherinfo);
$fname = $publisher['name'];
$fpassword = $publisher['password'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = htmlspecialchars(trim($_POST['name']));
    if (empty($fname)) $nameerr = "&nbsp;&nbsp;Please enter a name";
    else $name = $fname;

    $fpassword = htmlspecialchars(trim($_POST['newpassword']));
    if (empty($fpassword)) $passwordeerr = "&nbsp;&nbsp;Please enter a password";
    else $password = $fpassword;

    $fpass = htmlspecialchars(trim($_POST['currentpassword']));
    if (empty($fpass)) $passerr = "&nbsp;&nbsp;Please enter a password";
    else {
        $passcheck = "SELECT password from Publisher WHERE id = {$_SESSION['id']}";
        $passres = mysqli_query($dbConnect, $passcheck);
        $pass = (mysqli_fetch_assoc($passres))['password'];
        if ($fpass != $pass) $passerr = "&nbsp;&nbsp;Incorrect password";
        mysqli_free_result($passres);
    }
    if (empty($nameerr . $passworderr . $passerr)) {
        $updatepublisher = "UPDATE Publisher SET name = \"{$name}\", password = \"{$password}\" WHERE id = \"{$id}\"";
        if (mysqli_query($dbConnect, $updatepublisher)) $sqlmessage = "Success";
        else {
            $sqlerr = mysqli_error($dbConnect);
            $sqlmessage = "Error: {$updatepublisher} <br> {$sqlerr}";
        }
    }
}

?>

<html>
<?php
$headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                        <h3><p> $publisherName's Account Management Page</p></h3>";
include('header.php');
?>
<title>Update Publisher Information</title>

<div class="page">
    <div>
        <h1><a href="index.php"> Home </a></h1>
    </div>
    <h1>Update your Publisher Information</h1>
    <h4>All fields are required.</h4>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        New Name: <input type="text" name="name" size="25" maxlength="20" value=<?php echo "\"{$fname}\"" ?>><?php echo $nameerr ?><br><br>
        New Password: <input type="text" name="newpassword" size="25" maxlength="20" value=<?php echo "\"{$fpassword}\"" ?>><?php echo $passworderr ?><br><br>
        Current Password: <input type="password" name="currentpassword"><?php echo $passerr ?><br><br>
        <input type="submit" value="Update">
    </form>
    <br><br><br>
    <?php echo $sqlmessage ?>


</div>

</html>