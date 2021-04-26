<?
	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
	
		### REDIRECT USER
		header('Location:index.php');
		
	} else {
		
		$v_navigation = vertical_navigation();

		if(isset($_POST['unlock_inspection'])) {
		
			$inspection_id = $_POST['unlock_inspection'];
				
			$content = '
			<form action="unlock-inspection.php" method="post">
				<h1>
				CONFIRM INSPECTION UNLOCKING FOR <span style="color:red">'.$_POST['insp-name'].'</span>
				</h1>
				<p>Are you <b>SURE</b> you want to <b>UNLOCK</b> inspection '.$_POST['insp-name'].'?<br>
				<br>This will set this inspection\'s status to \'Unapproved\'.
				<br><br>
				
					<input type="hidden" name="confirm-unlock-inspection" value="'.$inspection_id.'">
					<input type="submit" value="Unlock Inspection" style="padding:10px;color:red;font-weight:bold;">
				
				</p>
				</form>
			';
			
		}
		
		if(isset($_POST['confirm-unlock-inspection'])) {
		
			$inspection_id = $_POST['confirm-unlock-inspection'];
				
			// Lock Inspection
			$update_qry = mysql_query("UPDATE `inspections_header` SET `lock` = NULL, `approved` = 0 WHERE `autoindex` = ".$inspection_id." ");
			
			$get_unit_q = mysql_query("select `unitid` from `inspections_header` WHERE `autoindex` = ".$inspection_id);
			$get_unit = mysql_fetch_row($get_unit_q);
			$unit_num = $get_unit[0];
			
			if ($update_qry) {
				if ($unit_num == 0) {
					$content =  "Inspection Unlocked [".$inspection_id."]";
				} else {
					header("Location:view-unit.php?id=".$unit_num." ");
				}
			}
			
		}
		
		
		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>

