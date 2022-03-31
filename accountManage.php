<?php
session_start();

require_once "config.php";

if ($_SESSION['userMode'] != 'account' AND $_SESSION['userMode'] != 'member') header("location: index.php");

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else {
    header("Location: index.php");
    die("something went wrong");
}

$accountName = $_SESSION['username'];

$username = $firstname = $lastname = $password = "";
$usernameerr = $firstnameerr = $lastnameerr = $passworderr = $passerr = ""; //variables for error messages
$fusername = $ffirstname = $flastname = $fpassword = $fpass = ""; //values entered in the html form
$sqlmessage = "";

$accountinfo = mysqli_query($dbConnect, "SELECT * FROM AccountHolder WHERE userID = \"{$id}\"");
$account = mysqli_fetch_assoc($accountinfo);
$fusername = $account['username'];
$ffirstname = $account['firstname'];
$flastname = $account['lastname'];
$fpassword = $account['password'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fusername = htmlspecialchars(trim($_POST['username']));
    if (empty($fusername)) $usernameerr = "&nbsp;&nbsp;Please enter a username";
    else $username = $fusername;

    $ffirstname = htmlspecialchars(trim($_POST['firstname']));
    if (empty($ffirstname)) $firstnameerr = "&nbsp;&nbsp;Please enter a first name";
    else $firstname = $ffirstname;

    $flastname = htmlspecialchars(trim($_POST['lastname']));
    if (empty($flastname)) $lastnameerr = "&nbsp;&nbsp;Please enter a last name";
    else $lastname = $flastname;

    $fpassword = htmlspecialchars(trim($_POST['newpassword']));
    if (empty($fpassword)) $passwordeerr = "&nbsp;&nbsp;Please enter a password";
    else $password = $fpassword;

    $fpass = htmlspecialchars(trim($_POST['currentpassword']));
    if (empty($fpass)) $passerr = "&nbsp;&nbsp;Please enter a password";
    else {
        $passcheck = "SELECT password from AccountHolder WHERE userID = {$_SESSION['id']}";
        $passres = mysqli_query($dbConnect, $passcheck);
        $pass = (mysqli_fetch_assoc($passres))['password'];
        if ($fpass != $pass) $passerr = "&nbsp;&nbsp;Incorrect password";
        mysqli_free_result($passres);
    }
    if (empty($usernameerr . $firstnameerr . $lastnameerr . $passworderr . $passerr)) {
        $updateaccount = "UPDATE AccountHolder SET username = \"{$username}\", firstname = \"{$firstname}\", lastname = \"{$lastname}\", password = \"{$password}\" WHERE userID = \"{$id}\"";
        if (mysqli_query($dbConnect, $updateaccount)) $sqlmessage = "Success";
        else {
            $sqlerr = mysqli_error($dbConnect);
            $sqlmessage = "Error: {$updateaccount} <br> {$sqlerr}";
        }
    }
}

?>

<html>
<?php
$headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                        <h3><p> $accountName's Account Management Page</p></h3>";
include('header.php');
?>
<title>Update Account Information</title>

<div class="page">
    <h1>Update your Account Information</h1>
    <h4>All fields are required.</h4>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        New Username: <input type="text" name="username" size="25" maxlength="20" value=<?php echo "\"{$fusername}\"" ?>><?php echo $usernameerr ?><br><br>
        New First Name: <input type="text" name="firstname" size="25" maxlength="30" value=<?php echo "\"{$ffirstname}\"" ?>><?php echo $firstnameerr ?><br><br>
        New Last Name: <input type="text" name="lastname" size="25" maxlength="30" value=<?php echo "\"{$flastname}\"" ?>><?php echo $lastnameerr ?><br><br>
        New Password: <input type="text" name="newpassword" size="25" maxlength="20" value=<?php echo "\"{$fpassword}\"" ?>><?php echo $passworderr ?><br><br>
        Current Password: <input type="password" name="currentpassword"><?php echo $passerr ?><br><br>
        <input type="submit" value="Update">
    </form>
    <br><br><br>
    <?php echo $sqlmessage ?>


</div>

</html>