<?
	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
	
		### REDIRECT USER
		header('Location:index.php');
		
	} else {
		
		$title = 'View Inspection |';
				
		$v_navigation = vertical_navigation();

		if( isset($_POST['inspection_id'])) {
		
			$inspection_id = stripslashes($_POST['inspection_id']);
				
			require('inc/inspection-0.0.9.class.php');
		
			$myinspection = new inspection;
		
			$content = $myinspection->view_inspection($inspection_id);
			
		} elseif (isset($_GET['id'])){
		
			$inspection_id = stripslashes($_GET['id']);
				
			require('inc/inspection-0.1.0.class.php');
		
			$myinspection = new inspection;
		
			$content = $myinspection->view_inspection($inspection_id);
		} else {
			$content = 'Error';
		}
		
		
		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>

