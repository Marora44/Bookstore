<html>
	<?php
		$headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
						<h3><p> Publisher Login Page:</p></h3>";
		include ('header.php'); 
	?>
	<div class="page">
		<form method="post" action="loginPublisher.php">
			<div>
				<h1><a href="index.php"> Home </a></h1>
			</div>
			<?php include ('errors.php'); include ('messages.php'); ?>
			<div class="input-group">
				<label>Publisher Name:	</label>
				<input type="name" name="name">
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
				<a href="loginMember.php"> Login as a Member </a>
			</div>
            <div>
				<a href="continueGuest.php"> Continue as a Guest </a>
			</div>
			<div>
				<a href="registerMember.php"> Sign up as a Member </a>
			</div>
			<div>
				<a href="registerPublisher.php"> Sign as a Publisher </a>
			</div>
		</form>
	</div>
</html>

<?php
    require_once "config.php";

	session_start();

    //variable to not print out that publisher does not exist if there is information missing
    $DNE = 0;

    //this section handles when "submit" is clicked on the form
	if (isset($_POST['sign_in'])) {
		$name = mysqli_real_escape_string($dbConnect, $_POST['name']);
		$password = mysqli_real_escape_string($dbConnect, $_POST['password']);
		
		if(empty($name)) { array_push($errors, "Enter a Publisher Name."); $DNE = 1;}
		if(empty($password)) { array_push($errors, "Enter a password."); $DNE = 1;}
		
		$name_exists_query = "SELECT * FROM Publisher WHERE name = '$name' LIMIT 1";
		$result = mysqli_query($dbConnect, $name_exists_query);
		$publisher = mysqli_fetch_assoc($result);
		
		if(!$publisher && $DNE==0) {
				array_push($errors, "Publisher account does not exist.");
		}
		else {
			if(!($publisher['password'] === $password) && $DNE==0) {
				array_push($errors, "Password is incorrect.");
			}
		}
		
		//check to see if we need to print out errors
		if(count($errors) == 0) {			
			
			///save the publisher as a session variable
			$_SESSION['publisher'] = $publisher;
			$_SESSION['userMode'] = 'publisher';
			$id = (int) $publisher['id'];
			header('Location: publisherLanding.php?id=' . $id);
			exit();
		}
		else {
			//display errors
			foreach($errors as $error) {
				print($error . "<br>");
			}
		}
	}
?>