<html>
	<?php
		$headerOutput = "<h1>Welcome to the Online Bookstore!</h1>
						<h3><p> Member Login Page:</p></h3>";
		include ('header.php'); 
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