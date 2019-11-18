<?php 
	if(isset($invoked_from_backend)){
		error_log('origin: backend');

		if(isset($GET_PARAMS))
			$_GET = $GET_PARAMS;
		if(isset($POST_PARAMS))
			$_POST = $POST_PARAMS;
	}
?>