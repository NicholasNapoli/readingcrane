<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
		$title = 'View Customer |';
				
		if (!isset($_GET['id'])) {
			header("Location:customer-management.php?redir=1");
		} else {
			$id = $_GET['id'];
		}
		
		if (isset($_GET['message'])) {
			if($_GET['message']=='update') {
				$content .= '<div id="message">Location Info Updated</div>';
			}
		}
		
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;	
		$mycust = new customer;
		$mylocation = new location;
		
		$mycust->getinfo($id);
		$content .= $mycust->breadcrumb($id);	
		
		
		// Customer Data
		$content .= '<div class="focus_view">'.$mycust->output_customer_data($id).'</div>';
		
		// List Locations
		//$content .= '<div class="list_view">'.
		//'<a href="add-location.php?id='.$mycust->customerid.'" class="add-button">Add Location To '.$mycust->name.' +</a><br />'.
		$content .= $mycust->list_customers_locations($mycust->customerid);//.'</div>';
		
		unset($myrefer);
		unset($myerror);
		
		$v_navigation = vertical_navigation();
		
		/* Template File  */
		require_once("templates/standard.php");
	}
	
?>
