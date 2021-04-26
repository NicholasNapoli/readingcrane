<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
	
		if ($_SESSION['userlevel']>3) {
			header("Location:dashboard.php");
		}
		
		$title = 'Approval Queue | ';
		
		if (isset($_GET['message'])) {
			if($_GET['message']=='update') {
				$content .= '<div id="message" style="float:right;">Update Successful</div>';
			}
		}
		
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;	
		$myqueue = new queue;
		
		// Show all unapproved inspections
		$content .= $myqueue->unapproved_inspections();

		unset($myrefer);
		unset($myerror);
		unset($myqueue);
		
		$v_navigation = vertical_navigation();
		
		/* Template File  */
		require_once("templates/standard.php");
	}
	
?>