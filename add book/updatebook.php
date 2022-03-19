<?php

//include ('header.php');
require_once "config.php";
//for testing
$_SESSION['userMode'] = 'pub';
$_SESSION['id'] = 1;

$isbn = $title = $genre = "";
$isdigital = $isphysical = 0;
$isbnerr = $titleerr = $authorerr = $genreerr = $mediumerr = $priceerr = $passerr = ""; //variables for error messages
$authorID = 0;
$price = 0.00;
$fisbn = $ftitle = $fgenre = $fauthid = $fprice = $fdigit = $fphys = $fpass = ""; //values entered in the html form
$sqlmessage = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // $fisbn = htmlspecialchars(trim($_POST['isbn']));
    // if(empty($fisbn)) $isbnerr = "&nbsp;&nbsp;Please enter an ISBN";
    // else if(preg_match("/^[0-9]*$/", $fisbn)== false) $isbnerr = "&nbsp;&nbsp;ISBN must contain only numbers";
    // else if(strlen($fisbn) != 13) $isbnerr = "&nbsp;&nbsp;ISBN must be 13 numbers";
    // else{
    //     $isbnquery = mysqli_query($dbConnect,"SELECT * FROM book WHERE isbn = {$fisbn}");
    //     if (mysqli_num_rows($isbnquery) > 0) $isbnerr = "&nbsp;&nbsp;This book already exists in our database (You can update existing books <a href=\"updatebook.php\">here</a>)";
    //     else $isbn = $fisbn;
    // }
    $ftitle = htmlspecialchars(trim($_POST['title']));
    if(empty($ftitle)) $titleerr = "&nbsp;&nbsp;Please enter a title";
    else $title = $ftitle;
    if (array_key_exists('author',$_POST))$fauthID = htmlspecialchars(trim($_POST['author']));
    if(empty($fauthID)) $authorerr = "&nbsp;&nbsp;Please select an author (Don't see your author? Add it <a href=\"addauthor.php\">here</a>)";
    else $authorID = intval($fauthID);
    $fgenre = htmlspecialchars(trim($_POST['genre']));
    if(empty($fgenre)) $genreerr = "&nbsp;&nbsp;Please enter a genre";
    else $genre = $fgenre;
    if (array_key_exists('isdigital',$_POST)) $fdigit = htmlspecialchars(trim($_POST['isdigital']));
    if (array_key_exists('isphysical',$_POST)) $fphys = htmlspecialchars(trim($_POST['isphysical']));
    if (empty($fdigit) && empty($fphys)) $mediumerr = "Please select at least one";
    else {
        $isdigital = intval(!empty($fdigit));
        $isphysical = intval(!empty($fphys));
    } 
    $fprice = htmlspecialchars($_POST['price']);
    if(empty($fprice)) $priceerr = "&nbsp;&nbsp;Please enter a valid price";
    else $price = floatval($fprice);
    $fpass = htmlspecialchars(trim($_POST['password']));
    if(empty($fpass)) $passerr = "&nbsp;&nbsp;Please enter a password";
    else{
        $passcheck = "SELECT password from publisher WHERE id = {$_SESSION['id']}";
        $passres = mysqli_query($dbConnect, $passcheck);
        $pass = (mysqli_fetch_assoc($passres))['password'];
        if($fpass != $pass) $passerr = "&nbsp;&nbsp;Incorrect password";
        mysqli_free_result($passres);
    }
    if(empty($isbnerr.$titleerr.$authorerr.$genreerr.$mediumerr.$priceerr.$passerr)){
        $addbook = "INSERT INTO book(isbn,title,authorID,genre,price,isDigital,isPhysical,pubID) VALUES(\"{$isbn}\",\"{$title}\",{$authorID},\"{$genre}\",{$price},{$isdigital},{$isphysical},{$_SESSION['id']})";
        if(mysqli_query($dbConnect,$addbook)) $sqlmessage = "Success";
        else{
           $sqlerr = mysqli_error($dbConnect);
           $sqlmessage = "Error: {$addbook} <br> {$sqlerr}"; 
        }
         
    }
    
}