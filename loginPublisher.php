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
			<?php include ('errors.php'); ?>
			<div class="input-group">
				<label>Publisher ID:	</label>
				<input type="id" name="id">
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
				<a href="loginUser.php"> Login as User </a>
			</div>
            <div>
				<a href="continueGuest.php"> Continue as Guest </a>
			</div>
			<div>
				<a href="registerMember.php"> Sign Up Member </a>
			</div>
			<div>
				<a href="registerPublisher.php"> Sign Up Publisher </a>
			</div>
		</form>
	</div>
</html>