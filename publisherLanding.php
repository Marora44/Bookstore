<?php 
    require_once "config.php";

session_start();

if (isset($_GET['id'])) {
		
    //get the id passed to the url on redirect
    $publisherPage_id = (int) $_GET['id'];
    
    //get all publisher ids
    $all_publisher_id_query = "SELECT id FROM Publisher";
    $all_publisher_id_result = mysqli_query($dbConnect, $all_publisher_id_query);

    //if the id passed in the url redirect is in the list of all publisher ids, the id is valid
    $in_all_publisher_id = False;
    while($publisher_id = mysqli_fetch_assoc($all_publisher_id_result)) {
        if (in_array($publisherPage_id, $publisher_id)) {
            $in_all_publisher_id = True;
            break;
        }
    }
}

$publisherName_query = "SELECT name FROM Publisher WHERE id = $publisherPage_id";
$publisherName_query_result = mysqli_query($dbConnect, $publisherName_query);
$publisherName_array = mysqli_fetch_assoc($publisherName_query_result);
$publisherName = $publisherName_array['name'];
$_SESSION['publisherName'] = $publisherName;

//redirect off the page if userMode isn't publisher or id isn't valid
if($in_all_publisher_id != True) header("location: index.php");
if($_SESSION['userMode'] != 'publisher') header("location: index.php");

?>

<html>
    <?php
        $headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
                        <h3><p> $publisherName's Publisher Landing Page</p></h3>";
        include ('header.php');
    ?>
    <div class="page">
        <div>
            <h1><a href="index.php"> Home </a></h1>
        </div>
        <h1><?php echo $publisherName ?>'s Books:</h1>
        <table width="70%">
            <tr>
                <th>Title</th>
                <th>ISBN</th>
                <th>Author</th>
                <th></th>
                <th></th>
            </tr>

            <?php
                $allBooks_query = "SELECT * FROM Book WHERE pubID = $publisherPage_id";
                $allBooks_query_result = mysqli_query($dbConnect, $allBooks_query);
                while ($row = mysqli_fetch_assoc($allBooks_query_result)) :
                    $author = mysqli_query($dbConnect, "SELECT id, firstname, lastname FROM author WHERE id = {$row['authorID']}");
                    $authRow = mysqli_fetch_assoc($author);
                    $authorName = $authRow['firstname'] . "&nbsp;" . $authRow['lastname'];
                    mysqli_free_result($author);
            ?>
                <tr>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['isbn'] ?></td>
                    <td><?= $authorName ?></td>
                    <?php
                        $page = "updatebook/updatebook.php?isbn=";
                        $isbn = (string) $row['isbn'];
                        $link = $page . $isbn;
                    ?>
                    <td><a href="<?php echo $link ?>">Edit Book</a></td>
                </tr> 
            <?php
                endwhile;
                mysqli_free_result($allBooks_query_result);
            ?>
        </table>
	</div>
    <div style="text-align:center">
        <?php $link2 = "updatebook/index.php"; ?>
        <h1><a href="<?php echo $link2 ?>">Edit Book</a></h1>
        <h1><a href="addBook.php">Add Book</a></h1>
        <h1><a href="addauthor.php">Add Author</a></h1>
	</div>
    <div style="text-align:center">    
		<h3><a href="publisheraccountmanage.php">Edit Account Info (NEEDS TO BE MADE)</a><h3>
	</div>
</html>