<?php

use LDAP\Result;

require_once "../config.php";
//this page returns a single string with the user's ID if login is successful or an error message preceeded by the '!' character if it is not

$username = $_POST['username'];
$password = $_POST['password'];

$login = array();

$result = mysqli_query($dbConnect, "SELECT username, password, userID FROM accountholder WHERE username = \"{$username}\" AND password = \"{$password}\"");
if (mysqli_num_rows($result) < 1) $login['success'] = "!Incorrect username/password";
else {
    $row = mysqli_fetch_assoc($result);
    $login['success'] = "success";
    $login['id'] = $row['id'];
}
mysqli_free_result($result);

echo json_encode($login);

?>