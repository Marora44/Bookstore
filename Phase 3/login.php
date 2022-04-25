<?php
require_once "../config.php";
//this page returns a single string with the user's ID if login is successful or an error message preceeded by the '!' character if it is not

$username = isset($_POST['username']) ? $_POST['username'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

if(empty($username)) echo "!Enter a username";
else if(empty($password)) echo "!Enter a username";
else{
    $result = mysqli_query($dbConnect,"SELECT username, password, userID FROM accountholder WHERE username = \"{$username}\" AND password = \"{$password}\"");
    if (mysqli_num_rows($result) < 1) echo "!Incorrect username/password";
    else {
        $row = mysqli_fetch_assoc($result);
        echo $row['id'];
    }
    mysqli_free_result($result);
}


?>