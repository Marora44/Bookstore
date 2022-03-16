<html>
	<?php
		$headerOutput = "<h1> Welcome to the Online Bookstore!</h1>
						<h3><p> Register as a Member:</p></h3>";
		include ('header.php'); 
	?>
	<div class="page">
		<form method="post" action="registerMember.php">
			<?php include ('errors.php'); ?>
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
				<button type="submit" class="btn" name="reg_user">Sign Up</button>
			</div>
			<br>
			<div>
				Want to login as a Member? <a href="loginUser.php"> Sign In </a>
			</div>
            <div>
                Want to login as a Publisher? <a href="loginPublisher.php"> Sign In </a>
            </div>
		</form>
	</div>
</html>