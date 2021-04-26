<?php
	require_once("inc/settings.php");
	
	// if the user is logged in, unset the session
	if (isset($_SESSION['db_is_logged_in'])) {
	
		if(isset($_COOKIE["ReadingCraneInspection1"]) && isset($_COOKIE["ReadingCraneInspection2"])){
			setcookie("ReadingCraneInspection1", "", time()-60*60*24*100, "/");
			setcookie("ReadingCraneInspection2", "", time()-60*60*24*100, "/");
			//unset($_COOKIE["ReadingCraneInspection1"]);
			//unset($_COOKIE["ReadingCraneInspection2"]);
		}
		
		session_destroy();
	}
	
	// go to home page
	header('Location: index.php');
?>
