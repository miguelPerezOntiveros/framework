<?php
	require 'db_connection.inc.php';
	require 'config.inc.php';

	function checkPassword($conn){
		$sql = 'select user, pass, name from user, user_type where type = user_type.id and user = \''.$_POST['userName'].'\'';
		$result = $conn->query($sql);

		if ($result->num_rows > 0 && $row = $result->fetch_assoc()) 
			return array( $row['name']);
			
		$conn->close();
		return array();
	}

	session_name($config['projectName']);
	session_start();
	unset($_SESSION['userName']);
	unset($_SESSION['type']);
	session_destroy();

	$incorrectPassword = false; 
	if(isset($_POST['userName']) && isset($_POST['password']) ){
		$userInfo = checkPassword($conn);
		if(count($userInfo) == 0) 
			$incorrectPassword = true;
		else {
			session_name($config['projectName']);
			session_start();
			$_SESSION['userName']= $_POST['userName'];		
			$_SESSION['type'] = $userInfo[0];

			header('Location: index.php');
			exit('I should have redirected');
		}
	}
?>

<!DOCTYPE html>
<html lang="en">

<?php require 'head.inc.php';?>

<body>
	<div id = "container">
		<div id ="body">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 bs-callout-left">
						<h2><?= $config['projectName'] ?></h2>
					</div>
					<br><br><br>
				</div>
				<br>
				<div class=<?= '\'alert alert-danger '.(!$incorrectPassword? ' hidden': '').'\''?> role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					Invalid Credentials
				</div>

				<div class="row">
					<div class="col-lg-4 bs-callout-left col-lg-offset-4" style='background-color: #DDD'>
						<br/><br/>
						<form method="post" id='loginForm' action=<?="'".basename($_SERVER['SCRIPT_NAME'])."'"?>>
							<div class="form-group">
								<label for="userName">User name</label>
								<input type="text" id="userName" name="userName" class="form-control" placeholder="User name">
							</div>

							<div class="form-group">
								<label for="exampleInputFile">Password</label>
								<input type="password" id="password" name="password" class="form-control" placeholder="Password">
							</div>
							<br>
							<p>	
								<button style="width: 100%" type="button" class="btn btn-success" id='submitBtn' onclick="$('#loginForm').submit();">Log In</button>
								<br><br>
								<button style="width: 100%" type="button" class="btn btn-warning">Create Account</button>
							</p>
							<br>
						</form>
					</div>
				</div>
				<script type="text/javascript">
					$('.form-control').keypress(function(event) {
						if (event.keyCode == 13 || event.which == 13) 
							$('#loginForm').submit();
					});
				</script>
			</div>
		</div>
		<?php require 'foot.inc.php';?>
	</div>
</body>
</html>