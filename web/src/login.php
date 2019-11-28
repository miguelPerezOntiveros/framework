<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	require $_SERVER["DOCUMENT_ROOT"].'/src/set_config_and_params.inc.php';
	require $_SERVER["DOCUMENT_ROOT"].'/src/db_connection.inc.php';

	function checkPassword($pdo, $username){
		$sql = 'select user, pass, name, landing_page from user, user_type where type = user_type.id and user = \''.$username.'\'';
		error_log('login.php - sql:' .$sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		if($row = $stmt->fetch(PDO::FETCH_NUM))
			return $row;		
		return array();
	}

	session_name($config['_projectName']);
	session_start();
	unset($_SESSION['userName']);
	unset($_SESSION['type']);
	session_destroy();

	$incorrectPassword = false; 
	if(isset($_POST['userName']) && isset($_POST['password']) ){
		error_log('login.php - processing data from html form.');
		$userInfo = checkPassword($pdo, $_POST['userName']);
		error_log('login.php - '.$_POST['userName'].' is trying to log in');
		if(count($userInfo) == 0 || $userInfo[1] != $_POST['password']) {
			$incorrectPassword = true;
			error_log('login.php - incorrect pwd on html form.');
		}
		else {
			error_log('login.php - setting session data now for project '.$config['_projectName']);
			session_name($config['_projectName']);
			session_start();
			$_SESSION['userName']= $_POST['userName'];		
			$_SESSION['type'] = $userInfo[2];

			header('Location: '.$userInfo[3].'?sidebar='.$_GET['sidebar']);
			exit('I should have redirected');
		}
	} else if(isset($_POST['id_token'])){
		$tokeninfo = file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=".$_POST['id_token']);
		error_log("Token info: ".$tokeninfo);
		
		$tokeninfo = json_decode($tokeninfo, true);
		$userInfo = checkPassword($pdo, $tokeninfo['email']);

		if(count($userInfo) != 0 && $tokeninfo['aud'] == '640592571227-igj3s1lcf6v580op163fg4v26vins5ib.apps.googleusercontent.com'){
			session_name($config['_projectName']);
			session_start();
			$_SESSION['userName'] = $tokeninfo['email'];
			$_SESSION['type'] = $userInfo[2];

			exit(json_encode((object) ['success' => $userInfo[3].'?sidebar='.$_GET['sidebar']]));
		} else
			exit(json_encode((object) ['error' => $tokeninfo['email'].' not Authorized']));
	}
?>
<!DOCTYPE html>
<html lang="en">
<?php require $_SERVER["DOCUMENT_ROOT"].'/src/head.inc.php';?>
<body>
	<div class="sidebarWrapper_wrapper">
		<?php require $_SERVER["DOCUMENT_ROOT"].'/src/sidebar.inc.php'; ?>
		<div class="sidebarWrapper_page">
			<div class="footerDown_container">
				<div class="footerDown_body">
					<?php require $_SERVER["DOCUMENT_ROOT"].'/src/menu.inc.php'; ?>
					<div id = "container">
						<div id ="body">							
							<div class="container">
								<div class="row" style="padding-top: 3em;">
									<div class="col-12 bs-callout-left">
										<h2><?= $config['_show'] ?></h2>
									</div>
									<br><br><br>
								</div>
								<br>
								<div class=<?= '\'alert alert-danger \''?> <?= (!$incorrectPassword? ' hidden': '') ?> role="alert">
									<i class="fas fa-exclamation-circle"></i>
									Invalid Credentials
								</div>

								<div class="row">
									<div class="col-4 bs-callout-left offset-lg-4" style='background-color: #DDD'>
										<br/><br/>
										<form method="post" id='loginForm' name='loginForm' action=<?="'".basename($_SERVER['SCRIPT_NAME'])."?sidebar='"?>>
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
												<button style="width: 100%" type="button" class="btn btn-success" id='submitBtn' onclick="document.loginForm.action += $('.sidebarWrapper_sidebar').hasClass('active')?'':'1'; $('#loginForm').submit();">Sign In</button>
											</p>
											<div style="float: right;">
												<div id="my-signin2"></div>
											</div>
											<br>
											<br>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footerDown_footer">
					<?php require $_SERVER["DOCUMENT_ROOT"].'/src/foot.inc.php'; ?>
				</div>
			</div>
		</div>
	</div>
	<?php require $_SERVER["DOCUMENT_ROOT"].'/src/body_bottom.inc.php'; ?>
	<script src="/src/js/common.js"></script>
	<script src="/src/js/auth.js"></script>
	<script src="https://apis.google.com/js/platform.js?onload=init" async defer></script>
	<script type="text/javascript">
		window._projectName = <?= "'".$config['_projectName']."'" ?>;

		$(document).ready(function() {
			$('.form-control').keypress(function(event) {
				if (event.keyCode == 13 || event.which == 13) {
					document.loginForm.action += $('.sidebarWrapper_sidebar').hasClass('active')?'':'1';
					$('#loginForm').submit();
				}
			});
			$('.sidebar_trigger').on('click', function () {
				$('.sidebarWrapper_sidebar').toggleClass('active');
				$(this).toggleClass('active');
				if(!$('.sidebarWrapper_sidebar').hasClass('active'))
					$('.sidebarWrapper_sidebar ul .collapse').removeClass('show')
			});
			doSidebarProjects();
		});
	</script>
</body>
</html>