<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {


		/* $content = '
		<h2>Crane Inspection</h2>'; */
		
		if($_SESSION['admin'] == 'Y') {			
			$content .= '';
		}
		
		
		$mycust = new customer;
		
		// List Customers
		$content .= $mycust->list_customers();
		
		//$content .= '<a href="customer-management.php" class="dashboard_btn" style="color:fff;">Search Customers</a>';
		
		// $content .= '<a href="customer-management.php" class="dashboard_btn" style="color:fff;">Search Locations</a>';
		// $content .= '<a href="customer-management.php" class="dashboard_btn" style="color:fff;">Search Buildings</a>';
		// $content .= '<a href="customer-management.php" class="dashboard_btn" style="color:fff;">Search Inspections</a>';
		
		$v_navigation = vertical_navigation();

		/* Template File  */
		require_once("templates/standard.php");
	}
 
?>
