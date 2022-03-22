<html>
	<?php
		$headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
						<h3><p> Member Login Page:</p></h3>";
		//include ('header.php'); 
	?>
	<div class="page">
		<form method="post" action="loginMember.php">
			<div>
				<h1><a href="index.php"> Home </a></h1>
			</div>
			<?php include ('errors.php'); include ('messages.php'); ?>
			<div class="input-group">
				<label>Username:	</label>
				<input type="username" name="username">
			</div>
			<div class="input-group">
				<label>Password:</label>
				<input type="password" name="password">
			</div>
			<div class="input-group">
				<button type="submit" class="btn" name="sign_in">Sign In</button>
			</div>
			<br>
            <div>
				<a href="loginPublisher.php"> Login as a Publisher </a>
			</div>
            <div>
				<a href="continueGuest.php"> Continue as a Guest </a>
			</div>
			<div>
				<a href="registerMember.php"> Sign up as a Member </a>
			</div>
			<div>
				<a href="registerPublisher.php"> Sign up as a Publisher </a>
			</div>
		</form>
	</div>
</html>

<?php
    require_once "config.php";

    //variable to not print out that member does not exist if there is information missing
    $DNE = 0;

    //this section handles when "submit" is clicked on the form
	if (isset($_POST['sign_in'])) {
		$username = mysqli_real_escape_string($dbConnect, $_POST['username']);
		$password = mysqli_real_escape_string($dbConnect, $_POST['password']);
		
		if(empty($username)) { array_push($errors, "Enter a username."); $DNE = 1;}
		if(empty($password)) { array_push($errors, "Enter a password."); $DNE = 1;}
		
		$username_exists_query = "SELECT * FROM AccountHolder WHERE username = '$username' LIMIT 1";
		$result = mysqli_query($dbConnect, $username_exists_query);
		$member = mysqli_fetch_assoc($result);
		
		if(!$member && $DNE==0) {
				array_push($errors, "Member does not exist.");
		}
		else {
			if(!($member['password'] === $password) && $DNE==0) {
				array_push($errors, "Password is incorrect.");
			}
		}
		
		//check to see if we need ot print out errors
		if(count($errors) == 0) {			
			
			//save the member as a session variable
			$_SESSION['member'] = $member;
			$id = (int) $member['id'];
		}
		else {
			//display errors
			foreach($errors as $error) {
				print($error . "<br>");
			}
		}
	}
?>