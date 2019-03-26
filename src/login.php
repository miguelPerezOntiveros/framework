<?php
	require_once 'config.inc.php';
	require 'db_connection.inc.php';

	function checkPassword($pdo){
		$sql = 'select user, pass, name from user, user_type where type = user_type.id and user = \''.$_POST['userName'].'\'';
		error_log('INFO - sql:' .$sql);
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
		$userInfo = checkPassword($pdo);
		error_log($_POST['userName'].' is trying to log in');
		if(count($userInfo) == 0 || $userInfo[1] != $_POST['password']) 
			$incorrectPassword = true;
		else {
			session_name($config['_projectName']);
			session_start();
			$_SESSION['userName']= $_POST['userName'];		
			$_SESSION['type'] = $userInfo[2];

			header('Location: index.php?sidebar='.$_GET['sidebar']);

			exit('I should have redirected');
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<?php require 'head.inc.php';?>
<body>
	<div class="sidebarWrapper_wrapper">
		<?php require 'sidebar.inc.php'; ?>
		<div class="sidebarWrapper_page">
			<div class="footerDown_container">
				<div class="footerDown_body">
					<?php require 'menu.inc.php'; ?>
					<div id = "container">
						<div id ="body">
							<div class="container">
								<div class="row" style="padding-top: 3em;">
									<div class="col-12 bs-callout-left">
										<h2><?= $config['_projectName'] ?></h2>
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
												<button style="width: 100%" type="button" class="btn btn-success" id='submitBtn' onclick="document.loginForm.action += $('.sidebarWrapper_sidebar').hasClass('active')?'':'1'; $('#loginForm').submit();">Log In</button>
											</p>
											<br>





	<main class="container">
		<div class="valign-wrapper">
			<div class="row">
				<div class="col xl4 offset-xl4 l4 offset-l4">
					<div class="card-panel  center-align">

						<form method="POST" id='loginForm'>
							<div onclick="apiLoaded();" class="abcRioButton abcRioButtonBlue">
								<div class="abcRioButtonContentWrapper">
									<div class="abcRioButtonIcon" style="padding:15px">
										<div style="width:18px;height:18px;" class="abcRioButtonSvgImageWithFallback abcRioButtonIconImage abcRioButtonIconImage18">
											<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg">
												<g>
															<path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
											<path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
													<path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
													<path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
													<path fill="none" d="M0 0h48v48H0z"></path>
												</g>
											</svg>
										</div>
									</div>
									<span style="font-size:16px;line-height:48px;" class="abcRioButtonContents">
										<span id="not_signed_in652v7ngf849x">Sign in with Google</span>
										<span id="connected652v7ngf849x" style="display:none">Signed in with Google</span>
									</span>
								</div>
							</div>
						</form>

					</div>
				</div>
			</div>

		</div>
	</main>



										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footerDown_footer">
					<?php require 'foot.inc.php'; ?>
				</div>
			</div>
		</div>
	</div>
	<?php require 'body_bottom.inc.php'; ?>
	<script src="/src/js/common.js"></script>
	<script src="/src/js/auth.js"></script>
	<script src="https://apis.google.com/js/platform.js?onload=init" async defer></script>
	<script type="text/javascript">
		window._projectName = <?= "'".$config['_projectName']."'" ?>;

		$(document).ready(function() {
			$('.form-control').keypress(function(event) {
				if (event.keyCode == 13 || event.which == 13) {
					debugger;
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