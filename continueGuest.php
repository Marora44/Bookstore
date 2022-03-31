<?php
session_start();

require_once "config.php";
if (isset($_SESSION['userMode']) && $_SESSION['userMode'] != 'guest'  AND $_SESSION['userMode'] != 'admin') header("location: index.php");
else if (!isset($_SESSION['id'])) {
        $user_query = $sqlmessage = "";

        $user_query = "INSERT INTO User VALUES()";
        if (mysqli_query($dbConnect, $user_query)) $sqlmessage = "Success";
        else {
            $sqlerr = mysqli_error($dbConnect);
            $sqlmessage = "Error: {$user_query} <br> {$sqlerr}";
        }

        $idQ = mysqli_query($dbConnect, "SELECT Max(id) FROM User");
        $guest_id = mysqli_fetch_array($idQ);
        $_SESSION['userMode'] = 'guest';
        $_SESSION['id'] = $guest_id[0];
    }
?>

<html>
<?php
$headerOutput = "<h1> Welcome to the Online Bookstore</h1>";
include('header.php');
?>
<div style="text-align:center">
    <h1><a href="storeMain.php">View Books</a></h1>
    <h3>or</h3>
    <h2><a href="registerAccount.php">Account Registration</a></h2>
    <h2><a href="registerPublisher.php">Publisher Registration</a></h2>
</div>

</html>
