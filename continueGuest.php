<?php
    session_start();

    require_once "config.php";

    $user_query = $sqlmessage = "";

    $user_query = "INSERT INTO User VALUES()";
        if (mysqli_query($dbConnect, $user_query)) $sqlmessage = "Success";
        else {
            $sqlerr = mysqli_error($dbConnect);
            $sqlmessage = "Error: {$user_query} <br> {$sqlerr}";
        }

    $guest_id = "SELECT Max(id) FROM User";

    $_SESSION['userMode'] = 'guest';
    $_SESSION['id'] = $guest_id;

    if($_SESSION['userMode'] == 'guest') header("location: storeMain.php");
?>