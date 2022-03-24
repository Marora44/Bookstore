<?php

require_once "config.php";


?>


<html>

<head>
    <title>Home</title>
    <style>
        th {
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>All Books</h1>
    <table width="70%">
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
            <th></th>
        </tr>
        <?php
        $books = mysqli_query($dbConnect, "SELECT isbn,title,authorID,price,quantity from book");
        while ($row = mysqli_fetch_assoc($books)) :
            $instock = $row['quantity'] > 0;
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
                <td>
                    <form style="margin: 5 auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input type="hidden" name="isbn" value="<?= $row['isbn'] ?>" />
                        <input style="width: 4em" type="number" step="1" max="<?= $row['quantity'] ?>" <?= $instock ? "value=\"1\"" : "value=\"0\" disabled" ?>>
                        &nbsp;
                        <input type="submit" <?= $instock ? "value=\"Add to Cart\"" : "value=\"Out of Stock\" disabled" ?>>
                    </form>
                </td>
            </tr>
        <?php
        endwhile;
        mysqli_free_result($books);
        ?>
    </table>
</body>

</html>