<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
		$title = 'Edit Building |';
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;
		
		$content = '';
		
		if ((isset($_GET['id'])) || ($_GET['id']!='')) {
			$curcode = 1;
		} elseif (isset($_POST['code'])) {
			$curcode = $_POST["code"];
		} else {
			header("Location: customer-management.php");
		}
		
		$onload = '';
		
		$javascript = '
		<script language="JavaScript">
			function verify() {
				//First check to make sure fields are not null
				if (document.forms.frmbuilding.name.value == "") {
						alert ("Please make sure all required fields are filled in before proceeding");
						return false;
				}
		}
    
	</script>';

		
	switch ($curcode) {

		#we retrieved all necessary information, add them into the database now
		case 2:
			#SAVE CHANGES
			$mybuilding = new building;
			$mybuilding->buildingid = $_POST['buildingid'];
			$mybuilding->locationid = $_POST['locationid'];
			$mybuilding->name = $_POST["name"];
			
			if($mybuilding->save_building()){
				header("Location:view-building.php?id=".$_POST['buildingid']."&message=update");
			} else {
				$content .= 'Error: Problem saving building data';
			}
			#NOW WHERE DO WE GO ONCE THE USER IS ADDED TO THE TABLE?
			break;

		case 1:
		
			$mybuilding = new building;
			
			if((isset($_GET['id']))&&($_GET['id']!='')) {
				$_POST["buildingid"] = $_GET["id"];
			}
			
			$mybuilding->getinfo($_POST["buildingid"]);
			
			$content .= $mybuilding->breadcrumb($_POST["buildingid"]);
			
			if ($mybuilding->getinfo($_POST["buildingid"]) == FALSE){
				$content .= 'ERROR ' . $_POST["buildingid"];
				exit;
			}

			$_POST['name'] = $mybuilding->name;
			$_POST['locationid'] = $mybuilding->locationid;

			$content .= '<form name="frmbuilding" method="POST" action="edit-building.php">';
			$content .= '<input type="hidden" name="code" value="2">';
			$content .= '<input type="hidden" name="buildingid" value="' . $_POST["buildingid"] . '">';
			$content .= '<input type="hidden" name="locationid" value="' . $_POST['locationid'] . '">';
			$content .= '
			   <table class="data">
			   <tr class="dataheader"><td align="Center" colspan=2>Building Information</td></tr>
			   <tr><td><font class="required">*Building Name:</font> </td><td><input type="text" name="name" size="25" maxlength="30" value="'.$_POST['name'].'">
			   </table>
			   <input type="submit" class="mybutton" name="go" value="Save Building Information" onclick="javascript: verify();"><br><br>
			   <font class="required">* Indicates a required field</font>
			   </form>';
				
		   break;
		}
		
		unset($myrefer);
		unset($myerror);
		unset($mybuilding);
		$v_navigation = vertical_navigation();
		/* Template File  */
		require_once("templates/standard.php");
	}
 
?>
