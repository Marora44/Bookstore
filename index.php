<?php

session_start();

$mode = isset($_SESSION['userMode']) ? $_SESSION['userMode'] : '';
<<<<<<< HEAD
if (strlen($mode) > 0 AND $mode != 'guest') header("location: {$mode}Landing.php");
=======
if(strlen($mode) > 0) header("location: {$mode}Landing.php");
>>>>>>> e04d9659f2a7b0c81c89db11b40abaa8bd33f9ea
else session_abort()
?>

<html>
	<?php
		$headerOutput = "<h1> Welcome to the Online Bookstore</h1>";
		include ('header.php'); 
	?>
	<div style="text-align:center">    
		<h1><a href="loginMember.php">Login as a Member</a></h1>
        <h1>or</h1>
        <h1><a href="loginPublisher.php">Login as a Publisher</a></h1>
	</div>
    <div style="text-align:center">    
		<h3><a href="continueGuest.php">Continue as a Guest</a><h3>
	</div>
	<div style="text-align:center">    
		<h3><a href="registerMember.php">Member Registration</a><h3>
	</div>
	<div style="text-align:center">    
		<h3><a href="registerPublisher.php">Publisher Registration</a><h3>
	</div>
</html>

<?php
 
?>