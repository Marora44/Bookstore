<?php

session_start();

require_once "config.php";

if ($_SESSION['userMode'] != 'account') header("location: index.php");

INSERT INTO bookorder(id,isbn,quantity,userID,isPlaced) VALUES({$orderID},\"{$isbn}\",{$quantity},{$userID},FALSE)

?>