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
		  $logout = isset($_SESSION['userMode']) && $_SESSION['userMode'] != "guest" ? "<h3 style=\"float: right;\"><a style=\"color: white;\" href=\"logout.php\"> Logout </a></h3>" : "<h3 style=\"float: right;\"><a style=\"color: white;\" href=\"index.php\"> Login </a></h3>";
		  echo $home . $logout;
		  ?>
        </div>
		
	</body>
	<br>
</html>