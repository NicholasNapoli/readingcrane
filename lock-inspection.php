<?
	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
	
		### REDIRECT USER
		header('Location:index.php');
		
	} else {
		
		$v_navigation = vertical_navigation();

		if(isset($_POST['lock_inspection'])) {
		
			$inspection_id = $_POST['lock_inspection'];
				
			$content = '
			<form action="lock-inspection.php" method="post">
				<h1>
				CONFIRM INSPECTION LOCKDOWN FOR <span style="color:red">'.$_POST['insp-name'].'</span>
				</h1>
				<p>Are you <b>SURE</b> you want to <b>LOCK</b> inspection '.$_POST['insp-name'].'?<br>
				<br>This will set this inspection\'s status to \'Approved\'.
				<b>To unlock this inspection, please contact an Administrator</b><br><br>
				
					<input type="hidden" name="confirm-lock-inspection" value="'.$inspection_id.'">
					<input type="submit" value="Lock Inspection" style="padding:10px;color:red;font-weight:bold;">
				
				</p>
				</form>
			';
			
		}
		
		if(isset($_POST['confirm-lock-inspection'])) {
		
			$inspection_id = $_POST['confirm-lock-inspection'];
				
			// Lock Inspection
			$update_qry = mysql_query("UPDATE `inspections_header` SET `lock` = '1', `approved` = 1 WHERE `autoindex` = ".$inspection_id." ");
			
			$get_unit_q = mysql_query("select `unitid` from `inspections_header` WHERE `autoindex` = ".$inspection_id);
			$get_unit = mysql_fetch_row($get_unit_q);
			$unit_num = $get_unit[0];
			
			if ($update_qry) {
				if ($unit_num == 0) {
					$content =  "Inspection Locked [".$inspection_id."]";
				} else {
					header("Location:view-unit.php?id=".$unit_num." ");
				}
			}
			
		}
		
		
		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>

