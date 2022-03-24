<?php

require_once "config.php";


?>


<html>
<title>Home</title>

<body>
    <h3>All Books</h3>
    <table width="50%">
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
        </tr>
        <?php
        $books = mysqli_query($dbConnect, "SELECT isbn,title,authorID,price from book");
        while ($row = mysqli_fetch_assoc($books)) :
            $author = mysqli_query($dbConnect, "SELECT id, firstname, lastname FROM author WHERE id = {$row['authorID']}");
            $authRow = mysqli_fetch_assoc($author);
            $authorName = $authRow['firstname'] . "&nbsp;" . $authRow['lastname'];
            mysqli_free_result($author);
        ?>
            <tr>
                <td><?= $row['isbn'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $authorName ?></td>
                <td>$<?= $row['price'] ?></td>
            </tr>
        <?php 
        endwhile; 
        mysqli_free_result($books);
        ?>
    </table>
</body>

</html>