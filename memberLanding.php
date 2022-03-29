<?php

//include ('header.php');
require_once "config.php";

if (isset($_GET['id'])) {
		
    //get the id passed to the url on redirect
    $memberPage_id = (int) $_GET['id'];
    
    //get all member ids
    $all_member_id_query = "SELECT userID FROM AccountHolder";
    $all_member_id_result = mysqli_query($dbConnect, $all_member_id_query);

    //if the id passed in the url redirect is in the list of all member ids, the id is valid
    $in_all_member_id = False;
    while($member_id = mysqli_fetch_assoc($all_member_id_result)) {
        if (in_array($memberPage_id, $member_id)) {
            $in_all_member_id = True;
            break;
        }
    }
}

//redirect off the page if userMode isn't member or id isn't valid
//if($_SESSION['userMode'] != 'member' OR $in_all_member_id != True) header("location: index.php");

?>