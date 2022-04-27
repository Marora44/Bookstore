<?php
require_once "../config.php";
$titleResults = array();

$search = isset($_POST['search']) ? $_POST['search'] : "";

$searchResults = mysqli_query($dbConnect, "SELECT title, authorID, price from Book where title LIKE '%$search%' AND book.isbn != \"become_member\"");
while($row = mysqli_fetch_assoc($searchResults)){
    $author = mysqli_fetch_assoc(mysqli_query($dbConnect, "SELECT * FROM author WHERE id = {$row['authorID']}"));
    $row['author'] = $author['firstname'] . " " . $author['lastname'];
    $titleResults[] = $row;
    
}

echo json_encode($titleResults);
?>