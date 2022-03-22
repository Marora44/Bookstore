<html>
	<?php
		$headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
						<h3><p> Register as a Member:</p></h3>";
		include ('header.php'); 
	?>
	<div class="page">
		<form method="post" action="registerMember.php">
			<?php include ('errors.php'); include ('messages.php'); ?>
			<div>
				<h1><a href="index.php"> Home </a></h1>
			</div>
            <div class="input-group">
				<label>First Name:			</label>
				<input type="text" name="firstname">
			</div>
            <div class="input-group">
				<label>Last Name:			</label>
				<input type="text" name="lastname">
			</div>
			<div class="input-group">
				<label>Username:			</label>
				<input type="text" name="username">
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
				<button type="submit" class="btn" name="reg_member">Sign Up</button>
			</div>
			<br>
			<div>
				Want to login as a Member? <a href="loginMember.php"> Sign In </a>
			</div>
            <div>
                Want to login as a Publisher? <a href="loginPublisher.php"> Sign In </a>
            </div>
		</form>
	</div>
</html>

<?php
    require_once "config.php";

	//triggers when submit is clicked on the form
	if (isset($_POST['reg_member'])) {
		
		//save the entered values on the form in variables
        $firstname = mysqli_real_escape_string($dbConnect, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($dbConnect, $_POST['lastname']);
        $username = mysqli_real_escape_string($dbConnect, $_POST['username']);
        $password_1 = mysqli_real_escape_string($dbConnect, $_POST['password_1']);
        $password_2 = mysqli_real_escape_string($dbConnect, $_POST['password_2']);
		//they will be registering as a member
		$isMember = 1;
		
		//check if any variables are empty and make sure that the passwords match
		if(empty($firstname)) { array_push($errors, "&nbsp;&nbsp;Enter a first name."); }
		if(empty($lastname)) { array_push($errors, "&nbsp;&nbsp;Enter a last name."); }
		if(empty($username)) { array_push($errors, "&nbsp;&nbsp;Enter a username."); }
		if(empty($password_1)) { array_push($errors, "&nbsp;&nbsp;Enter a password."); }
		if($password_1 != $password_2) { array_push($errors, "&nbsp;&nbsp;Passwords do not match."); }
		
		//check if a member with the same username already exists
		$username_exists_query = "SELECT * FROM AccountHolder WHERE username = '$username' LIMIT 1";
		$result = mysqli_query($dbConnect, $username_exists_query);
		$member = mysqli_fetch_assoc($result);
		
		if($member) {
			if($member['username'] === $username) {
				array_push($errors, "Username already taken.");
			}
		}
		
		//check if any errors exist
		if(count($errors) == 0) {			
			//insert the Member information into the AccountHolders table
			$member_query = "INSERT INTO AccountHolder(username,password,isMember,firstname,lastname) VALUES('$username','$password_1','$isMember','$firstname','$lastname')";
            if(mysqli_query($dbConnect, $member_query)) { 
				array_push($messages, "&nbsp;&nbsp;Success.");
				//diplay messages
				foreach($messages as $message) {
				print($message . "<br>");
				}
			}
            else{
                array_push($errors, mysqli_error($dbConnect));
             }	
		}
		else {
			//display errors
			foreach($errors as $error) {
				print($error . "<br>");
			}
		}
	}
?>