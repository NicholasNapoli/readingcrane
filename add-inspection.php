<?
	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
	
		### REDIRECT USER
		header('Location:index.php');
		
	} else {
		

		
		require('inc/inspection-0.1.0.class.php');
		


		$myinspection = new inspection;
		
		$content = $myinspection->new_inspection();

			

		
		
	}		
?>

