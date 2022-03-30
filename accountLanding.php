<?php

require_once "config.php";
session_start();

// if (isset($_GET['id'])) {

//     //get the id passed to the url on redirect
//     $memberPage_id = (int) $_GET['id'];

//     //get all member ids
//     $all_member_id_query = "SELECT userID FROM AccountHolder";
//     $all_member_id_result = mysqli_query($dbConnect, $all_member_id_query);

//     //if the id passed in the url redirect is in the list of all member ids, the id is valid
//     $in_all_member_id = False;
//     while ($member_id = mysqli_fetch_assoc($all_member_id_result)) {
//         if (in_array($memberPage_id, $member_id)) {
//             $in_all_member_id = True;
//             break;
//         }
//     }
// }

//redirect off the page if userMode isn't member or id isn't valid
//if ($in_all_member_id != True) header("location: index.php");
if ($_SESSION['userMode'] != 'account') header("location: index.php");

?>

<html>
<?php 
$headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                 <h3><p>Account Landing Page</p></h3>";
include('header.php');
?>
<div style="text-align:center">
    <h1><a href="storeMain.php">View Books</a></h1>
    <h1>or</h1>
    <h1><a href="show_orders.php">View Order History</a></h1>
</div>
<div style="text-align:center">
    <h3><a href="memberaccountmanage.php">Update Account Info (NEEDS TO BE MADE)</a>
        <h3>
</div>

</html>