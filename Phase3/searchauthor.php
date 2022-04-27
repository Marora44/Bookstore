<?php
require_once "../config.php";
$authorResults = array();

$search = isset($_POST['search']) ? $_POST['search'] : "";

$searchResults = mysqli_query($dbConnect, "SELECT title, authorID, price FROM book WHERE authorID IN (SELECT id from author where concat(firstname,' ',lastname) LIKE '%$search%') AND book.isbn != \"become_member\"");
while($row = mysqli_fetch_assoc($searchResults)){
    $author = mysqli_fetch_assoc(mysqli_query($dbConnect, "SELECT * FROM author WHERE id = {$row['authorID']}"));
    $row['author'] = $author['firstname'] . " " . $author['lastname'];
    $authorResults[] = $row;
    
}

echo json_encode($authorResults);
?>