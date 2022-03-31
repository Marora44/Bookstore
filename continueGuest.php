<?php
session_start();

require_once "config.php";
if (isset($_SESSION['userMode'])&& $_SESSION['userMode'] != 'guest') header("location: index.php");
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
header("location: storeMain.php");
