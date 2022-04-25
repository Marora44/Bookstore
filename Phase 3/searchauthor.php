<?php
require_once "../config.php";
$authorResults = array();

$search = isset($_POST['search']) ? $_POST['search'] : "";

$searchResults = mysqli_query($dbConnect, "SELECT title, author, price from Book where author LIKE '%$search%' AND book.isbn != \"become_member\"");
while($row = mysqli_fetch_assoc($searchResults)){
    $authorResults[] = $row;
}

echo json_encode($authorResults);
?>