<?
	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
	
		### REDIRECT USER
		header('Location:index.php');
		
	} else {
		
		$v_navigation = vertical_navigation();
		
		if (isset($_POST['unitid'])) {
		
			$insp_import_string = "UPDATE `inspections_header` SET `unitid` = ".$_POST['unitid'].", 
			`customer` = '',
			`crane_mfg` = '',
			`location` = '',
			`approx_span` = '',
			`power` = '',
			`serialnumber` = '',
			`capacity` = '',
			`type` = '',
			`hoist_mfg` = '',
			`hoist_serialnumber` = '',
			`model_number` = '',
			`approx_height` = '' WHERE `autoindex` = ".$_POST['inspection_index']." LIMIT 1";
			
			if (mysql_query($insp_import_string) or die(mysql_error())) {
				header("Location:view-unit.php?id=".$_POST['unitid']);
			}
		}
		
		if (!isset($_GET['id'])) {
			$content .= 'Sorry, you need to visit this page from a unit table. 
			Select your Customer / Location / Building / Unit, then click "Import Older Inspection"';
		} else {
		
			$unit = new unit;
			
			$content .= $unit->breadcrumb($_GET['id']);
			
			$content .= '<div style="padding:10px;font-weight:bold;">
				Please select the inspection to import into the new system.
				Once an inspection has been imported, it will only be accessable through the new interface.			
			</div>';
			
			$content .= '<form action="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'" method="post">
				<select name="inspection_index">';
			$all_inspection_query = mysql_query("select autoindex, jobnumber, customer from inspections_header where unitid = 0 order by customer");
			
			while ($row = mysql_fetch_array($all_inspection_query)) {
				if ($row['customer']=='') {
					$row['customer'] = 'NO CUSTOMER NAME';
				}
				
				if ($row['jobnumber']=='') {
					$row['jobnumber'] = 'NO JOB NUMBER';
				}
				
				$content .= '
				<option value="'.$row['autoindex'].'">'.$row['customer'].', '.$row['jobnumber'].'</option>
				';
			}
			
			$content .= '</select>
			<input type="hidden" name="unitid" value="'.$_GET['id'].'"> 
			<input type="submit" value="Import Inspection">';
		
		}

		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>

