<?
	require_once("inc/settings.php");
	
	
	if(checkLogin() == 0){
		### REDIRECT USER
		header('Location:index.php');
	} else {

	
		require('inc/inspectionsList-0.0.2.class.php');

		$title = "Previous Inspections |";
		
		$myInspectionsList = new inspectionsList;

		$content = $myInspectionsList->display();

		$v_navigation = vertical_navigation();

		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>

