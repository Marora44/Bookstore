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

$cc = $ccv = $expDate = "";

$ccerr = $ccverr = $expDateerr = ""; //variables for error messages
$fcc = $fccv = $fexpDate = ""; //values entered in the html form
$sqlmessage = "";

$payment= mysqli_query($dbConnect, "SELECT paymentID FROM StoredPay WHERE userID = {$id}");
$result= mysqli_fetch_assoc($payment);
$paymentID = $result['paymentID'];
$paymentinfo = mysqli_query($dbConnect, "SELECT * FROM PaymentInfo WHERE id = {$paymentID}");
$payment = mysqli_fetch_assoc($paymentinfo);
$fcc = $payment['cc'];
$fccv = $payment['ccv'];
$fexpDate = $payment['expDate'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fcc = htmlspecialchars(trim($_POST['cc']));
    if (empty($fcc)) $ccerr = "&nbsp;&nbsp;Please enter a cc";
    else $cc = $fcc;

    $fccv = htmlspecialchars(trim($_POST['ccv']));
    if (empty($fccv)) $ccverr = "&nbsp;&nbsp;Please enter a ccv";
    else $ccv = $fccv;

    $fexpDate = htmlspecialchars(trim($_POST['expDate']));
    if (empty($fexpDate)) $expDateerr = "&nbsp;&nbsp;Please enter an expiration date";
    else $expDate = $fexpDate;

    $fpass = htmlspecialchars(trim($_POST['password']));
    if (empty($fpass)) $passerr = "&nbsp;&nbsp;Please enter a password";
    else {
        $passcheck = "SELECT password from AccountHolder WHERE userID = {$_SESSION['id']}";
        $passres = mysqli_query($dbConnect, $passcheck);
        $pass = (mysqli_fetch_assoc($passres))['password'];
        if ($fpass != $pass) $passerr = "&nbsp;&nbsp;Incorrect password";
        mysqli_free_result($passres);
    }
    if (empty($ccerr . $ccverr . $expDateerr . $passerr)) {
        $updatepayment = "UPDATE paymentInfo SET cc = \"{$cc}\", ccv = \"{$ccv}\", expDate = \"{$expDate}\" WHERE id = {$paymentID}";
        if (mysqli_query($dbConnect, $updatepayment)) $sqlmessage = "Success";
        else {
            $sqlerr = mysqli_error($dbConnect);
            $sqlmessage = "Error: {$updatepayment} <br> {$sqlerr}";
        }
    }
}

?>

<html>
<?php
$headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                        <h3><p> $accountName's Payment Management Page</p></h3>";
include('header.php');
?>
<title>Update Payment Information</title>

<div class="page">
    <div>
        <h1><a href="index.php"> Home </a></h1>
    </div>
    <h1>Update your Payment Information</h1>
    <h4>All fields are required.</h4>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        New CC: <input type="text" name="cc" size="25" maxlength="30" value=<?php echo "\"{$fcc}\"" ?>><?php echo $ccerr ?><br><br>
        New CVV: <input type="text" name="ccv" size="25" maxlength="30" value=<?php echo "\"{$fccv}\"" ?>><?php echo $ccverr ?><br><br>
        New Expiration Date: <input type="text" name="expDate" size="25" maxlength="30" value=<?php echo "\"{$fexpDate}\"" ?>><?php echo $expDateerr ?><br><br>
        Password: <input type="password" name="password"><?php echo $passerr ?><br><br>
        <input type="submit" value="Update">
    </form>
    <br><br><br>
    <?php echo $sqlmessage ?>


</div>

</html>