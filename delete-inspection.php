<?
	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
	
		### REDIRECT USER
		header('Location:index.php');
		
	} else {
		
		$v_navigation = vertical_navigation();

		if(isset($_POST['delete-inspection'])) {
		
			$inspection_id = $_POST['delete-inspection'];
				
			$content = '
			<form action="delete-inspection.php" method="post">
				<h1>
				CONFIRM DELETION FOR <span style="color:red">'.$_POST['insp-name'].'</span>
				</h1>
				<p>Are you <b>SURE</b> you want to <b>DELETE</b> inspection '.$_POST['insp-name'].'?<br>
				<b>This can not be undone!</b><br><br>
				
					<input type="hidden" name="confirm-delete-inspection" value="'.$inspection_id.'">
					<input type="submit" value="Delete Inspection" This is not reversible!" style="padding:10px;color:red;font-weight:bold;">
				
				</p>
				</form>
			';
			
		}
		
		if(isset($_POST['confirm-delete-inspection'])) {
		
			$inspection_id = $_POST['confirm-delete-inspection'];
				
			require('inc/inspection-0.1.0.class.php');
		
			$myinspection = new inspection;
		
			$content = $myinspection->delete_inspection($inspection_id);
			
		}
		
		
		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>

