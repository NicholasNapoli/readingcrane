<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
	
		$title = 'View Unit | ';
		
		if (!isset($_GET['id'])) {
			header("Location:customer-management.php?redir=1");
		} else {
			$id = $_GET['id'];
		}
		
		if (isset($_GET['message'])) {
			if($_GET['message']=='update') {
				$content .= '<div id="message" style="float:right;">Update Successful</div>';
			}
		}
		
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;	
		$myunit = new unit;
		
		$myunit->getinfo($id);
		
		$content .= $myunit->breadcrumb($id);		
		// Customer Data
		$content .= '<div class="focus_view">'.$myunit->output_unit_data($id).'</div>';
		
		
		$content .= $myunit->list_units_inspections($id);
		
		$content .= $myunit->list_units_files($id);

		
		unset($myrefer);
		unset($myerror);
		
		$v_navigation = vertical_navigation();
		
		/* Template File  */
		require_once("templates/standard.php");
	}
	
?>
