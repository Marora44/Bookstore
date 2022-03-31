<?php
session_start();

require_once "config.php";

if($_SESSION['userMode'] != 'publisher' && $_SESSION['userMode'] != 'admin') header("location: index.php");

if (isset($_GET['isbn'])) {
    $_SESSION['isbn'] = $_GET['isbn'];
    header("Location: updatebook.php");
} else {
    header("Location: index.php");
    die("something went wrong");
}
?>