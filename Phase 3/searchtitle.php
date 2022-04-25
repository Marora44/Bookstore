<?php
require_once "../config.php";
$titleResults = array();

$search = isset($_POST['search']) ? $_POST['search'] : "";

$searchResults = mysqli_query($dbConnect, "SELECT title, author, price from Book where title LIKE '%$search%' AND book.isbn != \"become_member\"");
while($row = mysqli_fetch_assoc($searchResults)){
    $titleResults[] = $row;
}

echo json_encode($titleResults);
?>