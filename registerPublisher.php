<html>
	<?php
		$headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
						<h3><p> Register as a Publisher:</p></h3>";
		include ('header.php'); 
	?>
	<div class="page">
		<form method="post" action="registerPublisher.php">
			<?php include ('errors.php'); ?>
			<div>
				<h1><a href="index.php"> Home </a></h1>
			</div>
            <div class="input-group">
				<label>Publisher Name:			</label>
				<input type="text" name="publishername">
			</div>
			<div class="input-group">
				<label>Password:		</label>
				<input type="password" name="password_1">
			</div>
			<div class="input-group">
				<label>Confirm Password:</label>
				<input type="password" name="password_2">
			</div>
			<div class="input-group">
				<button type="submit" class="btn" name="reg_publisher">Sign Up</button>
			</div>
			<br>
			<div>
                Want to login as a Publisher? <a href="loginPublisher.php"> Sign In </a>
			</div>
            <div>
                Want to login as a Member? <a href="loginMember.php"> Sign In </a>
            </div>
		</form>
	</div>
</html>

<?php
    require_once "config.php";

	//triggers when submit is clicked on the form
	if (isset($_POST['reg_publisher'])) {
		
		//save the entered values on the form in variables
        $publishername = mysqli_real_escape_string($dbConnect, $_POST['publishername']);
        $password_1 = mysqli_real_escape_string($dbConnect, $_POST['password_1']);
        $password_2 = mysqli_real_escape_string($dbConnect, $_POST['password_2']);
		
		//check if any variables are empty and make sure that the passwords match
		if(empty($publishername)) { array_push($errors, "&nbsp;&nbsp;Enter a publisher name."); }
		if(empty($password_1)) { array_push($errors, "&nbsp;&nbsp;Enter a password."); }
		if($password_1 != $password_2) { array_push($errors, "&nbsp;&nbsp;Passwords do not match."); }
		
		//check if a publisher with the same username already exists
		$publisher_exists_query = "SELECT * FROM Publisher WHERE name = '$publishername' LIMIT 1";
		$result = mysqli_query($dbConnect, $publisher_exists_query);
		$publisher = mysqli_fetch_assoc($result);
		
		if($publisher) {
			if($publisher['name'] === $publisherame) {
				array_push($errors, "Publisher name already taken.");
			}
		}
		
		//check if any errors exist
		if(count($errors) == 0) {			
			//insert the Member information into the AccountHolders table
			$member_query = "INSERT INTO Publisher (password, name) VALUES('$password', '$publishername')";
			mysqli_query($dbConnect, $member_query);	
		}
		else {
			//display errors
			foreach($errors as $error) {
				print($error . "<br>");
			}
		}
	}
?>