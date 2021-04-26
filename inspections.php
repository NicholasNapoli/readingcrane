<?
	require_once("inc/settings.php");

	if(checkLogin() == 0){
		### REDIRECT USER
		header('Location:index.php');
	} else {
		
		$v_navigation = vertical_navigation();
		$myinspection = new inspection;
		$content = $myinspection->display();
		$v_navigation = $v_navigation . $myinspection->getNav();
		
		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>

