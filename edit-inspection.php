<?
	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
	
		### REDIRECT USER
		header('Location:index.php');
		
	} else {
		$title = 'Edit Inspection |';
		$v_navigation = vertical_navigation();

		if( isset($_POST['edit-inspection']) || isset($_POST['inspection_id']) || isset($_GET['inspection_id'])) {
		
			if(isset($_POST['edit-inspection'])) {
				$inspection_id = stripslashes($_POST['edit-inspection']);
			} elseif(isset($_POST['inspection_id'])) {
				$inspection_id = stripslashes($_POST['inspection_id']);
			} else {
				$inspection_id = stripslashes($_GET['inspection_id']);
			}
				
			require('inc/inspection-0.1.0.class.php');
		
			$myinspection = new inspection;
			
				
			// $javascript = '<script language="javascript" >
			// <!---
			// function dismissSaving()(message, url){
			// if(confirm(message)) location.href = url;
			// }
			// --->
			// </SCRIPT>';
			
			//javascript:dismissSaving('If you leave this page now, any recent changes will be unsaved.', '/readingcrane/inspv3/edit-inspection.php?inspection_id=660&cat_num=13&inspection_type=crane')
		
			$content = $myinspection->edit_inspection($inspection_id);
			
			$v_navigation = $v_navigation . $myinspection->getNav();
			
		} else {
			$content = 'Error';
		}
		
		
		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>

