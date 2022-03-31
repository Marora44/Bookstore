<html>
	<head>
		<style>
			html {
				background-color: #ffed00;
			}
			form {
				width: 300;
				clear: both;
			}
			form input {
				width: 100%;
				clear: both;
			}
            body {
              margin: 0;
            }
			h5
			{
			  line-height:0px;
			}
            .header {
              padding: 15px;
              text-align: center;
              background: #4c00ff;
              color: white;
              font-size: 20px;
			  overflow: hidden;
            }
			.page {
				padding-left: 50px;
			}
			.logout_btn {
				padding-right: 25px;
				text-align: right;
			}
		</style>
	</head>
	<body>
        <div class="header">
          <?php echo $headerOutput;
		  $home = "<h3 style=\"float: left;\"><a style=\"color: white;\" href=\"index.php\"> Home </a></h3>";
		  $logout = isset($_SESSION['userMode']) && $_SESSION['userMode'] != "guest" ? "<h3 style=\"float: right;\"><a style=\"color: white;\" href=\"logout.php\"> Logout </a></h3>" : "<h3 style=\"float: right;\"><a style=\"color: white;\" href=\"loginAccount.php\"> Login </a></h3>";
		  if(isset($_SESSION['userMode'])) {
			  $userMode = $_SESSION['userMode'];
			  if ($userMode == 'account') {
				  echo "<center>You are a Base Account Holder. Would You Like to ";
				  echo "<a href='becomeMember.php'>Become a Member</a></center>";
			  }
			  else if ($userMode == 'publisher') {
				  echo "<center>You are a Publisher</center>";
			  }
			  else if ($userMode == 'member') {
				  echo "<center>You are a Premium Member</center>";
			  }
			  else if ($userMode == 'admin') {
				  echo "<center>Welcome Admin</center>";
			  }
			  else if ($userMode == 'guest') {
				  echo "<center>You are a Guest</center>";
			  }
			  else { echo ""; }
		  }
		  ?>
		  <?= $home.$logout ?>
		  <h3 style="float: left;"><a style="color: white;" href="shoppingcart.php"> Cart </a></h3>
        </div>
		
	</body>
	<br>
</html>