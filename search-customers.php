<?php

	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
		### REDIRECT USER
		header('Location:index.php');
	} else {
	

		
		$v_navigation = vertical_navigation();
		
		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>
