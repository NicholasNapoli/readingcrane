<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
		$title = 'Customer Management |';
		
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;	
		
		if (isset($_GET['redir'])) {
			$content .= '<div class="error">'.$myerror->output_error_code($_GET['redir']).'</div>';
		}
		

		
		$mycust = new customer;
		
		// List Customers
		$content .= $mycust->list_customers();
		
		unset($myrefer);
		unset($myerror);
		
		$v_navigation = vertical_navigation();
		
		/* Template File  */
		require_once("templates/standard.php");
	}
 
?>
