<?
	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
	
		### REDIRECT USER
		header('Location:index.php');
		
	} else {
		
		$v_navigation = vertical_navigation();

		if(isset($_GET['insp'])) {
		
			require('inc/inspection-0.0.9.class.php');
		
			$myinspection = new inspection;
		
			$content = $myinspection->view_form(stripslashes($_GET['insp']));
						
		} else {
			$content = 'Error';
		}
		
		
		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>

