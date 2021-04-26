<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
		
		$title = 'View Location |';
				
		if (!isset($_GET['id'])) {
			header("Location:customer-management.php?redir=1");
		} else {
			$id = $_GET['id'];
		}
		
		if (isset($_GET['message'])) {
			if($_GET['message']=='update') {
				$content .= '<div id="message">Update Successful</div>';
			}
		}
		
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;	
		$mylocale = new location;
		$mybuilding = new building;
		
		$mylocale->getinfo($id);
    $content .= $mylocale->breadcrumb($id);		
		
		// Customer Data
		$content .= '<div class="focus_view">'.$mylocale->output_location_data($id).'</div>';
		
		// List Locations
		$content .= $mylocale->list_locations_buildings($mylocale->locationid);
		
		unset($myrefer);
		unset($myerror);
		
		$v_navigation = vertical_navigation();
		
		/* Template File  */
		require_once("templates/standard.php");
	}
	
?>
