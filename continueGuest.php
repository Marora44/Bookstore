<?php
    //create user
	$user_query = "INSERT INTO User VALUES()";
	if(mysqli_query($dbConnect, $user_query)) { 
		array_push($messages, "&nbsp;&nbsp;ID Generated.");
	}
	else{
        array_push($errors, mysqli_error($dbConnect));
    }

    $_SESSION['userMode'] = 'guest';

    if($_SESSION['userMode'] != 'guest') header("location: guestLanding.php");
?>