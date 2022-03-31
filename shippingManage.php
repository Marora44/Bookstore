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

$street = $city = $state = $zip = "";

$streeterr = $cityerr = $stateerr = $ziperr = $passerr = ""; //variables for error messages
$fstreet = $fcity = $fstate = $fzip = $fpass = ""; //values entered in the html form
$sqlmessage = "";

$shipping = mysqli_query($dbConnect, "SELECT addressID FROM StoredShip WHERE userID = {$id}");
$result= mysqli_fetch_assoc($shipping);
$shippingID = $result['addressID'];
$addressinfo = mysqli_query($dbConnect, "SELECT * FROM AddressInfo WHERE id = {$shippingID}");
$address = mysqli_fetch_assoc($addressinfo);
$fstreet = $address['street'];
$fcity = $address['city'];
$fstate = $address['state'];
$fzip = $address['zip'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fstreet = htmlspecialchars(trim($_POST['street']));
    if (empty($fstreet)) $streeterr = "&nbsp;&nbsp;Please enter a street";
    else $street = $fstreet;

    $fcity = htmlspecialchars(trim($_POST['city']));
    if (empty($fcity)) $cityerr = "&nbsp;&nbsp;Please enter a city";
    else $city = $fcity;

    $fstate = htmlspecialchars(trim($_POST['state']));
    if (empty($fstate)) $stateerr = "&nbsp;&nbsp;Please enter a state";
    else $state = $fstate;

    $fzip = htmlspecialchars(trim($_POST['zip']));
    if (empty($fzip)) $ziperr = "&nbsp;&nbsp;Please enter a zip code";
    else $zip = $fzip;

    $fpass = htmlspecialchars(trim($_POST['password']));
    if (empty($fpass)) $passerr = "&nbsp;&nbsp;Please enter a password";
    else {
        $passcheck = "SELECT password from AccountHolder WHERE userID = {$_SESSION['id']}";
        $passres = mysqli_query($dbConnect, $passcheck);
        $pass = (mysqli_fetch_assoc($passres))['password'];
        if ($fpass != $pass) $passerr = "&nbsp;&nbsp;Incorrect password";
        mysqli_free_result($passres);
    }
    if (empty($streeterr . $cityerr . $stateerr . $ziperr . $passerr)) {
        $updateaddress = "UPDATE AddressInfo SET street = \"{$street}\", city = \"{$city}\", state = \"{$state}\", zip = \"{$zip}\" WHERE id = {$shippingID}";
        if (mysqli_query($dbConnect, $updateaddress)) $sqlmessage = "Success";
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
                        <h3><p> $accountName's Address Management Page</p></h3>";
include('header.php');
?>
<title>Update Address Information</title>

<div class="page">
    <h1>Update your Address Information</h1>
    <h4>All fields are required.</h4>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        New Street: <input type="text" name="street" size="25" maxlength="30" value=<?php echo "\"{$fstreet}\"" ?>><?php echo $streeterr ?><br><br>
        New City: <input type="text" name="city" size="25" maxlength="30" value=<?php echo "\"{$fcity}\"" ?>><?php echo $cityerr ?><br><br>
        New State: <input type="text" name="state" size="25" maxlength="30" value=<?php echo "\"{$fstate}\"" ?>><?php echo $stateerr ?><br><br>
        New Zip: <input type="text" name="zip" size="25" maxlength="20" value=<?php echo "\"{$fzip}\"" ?>><?php echo $ziperr ?><br><br>
        Password: <input type="password" name="password"><?php echo $passerr ?><br><br>
        <input type="submit" value="Update">
    </form>
    <br><br><br>
    <?php echo $sqlmessage ?>


</div>

</html>