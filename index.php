<?php

session_start();

$mode = isset($_SESSION['userMode']) ? $_SESSION['userMode'] : '';
if (strlen($mode) > 0) header("location: {$mode}Landing.php");
else session_abort()
?>

<html>
<?php
$headerOutput = "<h1> Welcome to the Online Bookstore</h1>";
include('header.php');
?>
<div style="text-align:center">
	<h1><a href="loginAccount.php">Account Login</a></h1>
	<h1>or</h1>
	<h1><a href="loginPublisher.php">Publisher Login</a></h1>
</div>
<div style="text-align:center">
	<h3><a href="continueGuest.php">Continue as a Guest</a>
		<h3>
</div>
<div style="text-align:center">
	<h3><a href="registerAccount.php">Account Registration</a>
		<h3>
</div>
<div style="text-align:center">
	<h3><a href="registerPublisher.php">Publisher Registration</a>
		<h3>
</div>

</html>

<?php

?>