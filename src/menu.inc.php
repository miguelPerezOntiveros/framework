<nav class="navbar navbar-expand-lg navbar-dark">
	<a class="navbar-brand" href="#"><i style="color:white;" class="fas fa-angry"></i></a>
    <span style="color: white; padding-left: ">Welcome <?= $_SESSION['userName'] ?>! (<?= $_SESSION['type']?>)</span>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav ml-auto">
			<?php 
				$currentTable = isset($_GET['table'])? $_GET['table']:  key($config);
				// Iterate through tables
				foreach ($config as $table => $value) {
					if($table[0] == '_')
						continue;
					$tableNameToShow = (isset($config[$table]['displayName'])? 
						$config[$table]['displayName']: 
						ucwords(str_replace("_"," ", $table )));
					echo   '<li class="nav-item">
								<span onclick="loadSection(\''.$table.'\', \''.$tableNameToShow.'\');" class=\'tab nav-link\' id=\'menu_'.$table.'\'>'.$tableNameToShow.'</span>
							</li>';
				}
			?>
            <li class="nav-item">
                <a class="tab nav-link" href="login.php"><i class="fas fa-sign-out-alt"></i></a>
            </li>
		</ul>
	</div>
</nav>